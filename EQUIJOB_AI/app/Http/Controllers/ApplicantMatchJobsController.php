<?php

namespace App\Http\Controllers;

use App\Models\JobPosting;
use App\Services\GeminiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\PdfToText\Pdf;

class ApplicantMatchJobsController extends Controller
{
    protected $geminiService;

    public function __construct(GeminiService $geminiService)
    {
        $this->geminiService = $geminiService;
    }

    /**
     * Method 1: Displays the simple PDF upload form.
     */
    public function showUploadForm()
    {
        $user = Auth::guard('applicant')->user();
        // Passing these variables so your topbar component doesn't break
        $notifications = $user->notifications;
        $unreadNotifications = $user->unreadNotifications;
        return view('users.applicant.match_jobs', compact('user', 'notifications', 'unreadNotifications'));
    }

    /**
     * Method 2: Handles the PDF upload, gets matches, and redirects.
     */
    public function matchWithPdf(Request $request)
    {
        // 1. VALIDATE & EXTRACT TEXT (This part is already good)
        $request->validate([
            'resume' => 'required|file|mimes:pdf|max:5120',
        ]);

        try {
            $path = $request->file('resume')->getRealPath();
            $binaryPath = config('services.spatie.pdf_to_text_binary_path');
            $resumeText = (new Pdf($binaryPath))->setPdf($path)->text();
            if (empty(trim($resumeText))) {
                return back()->with('error', 'Could not read text from the PDF. It might be image-based.');
            }
        } catch (\Exception $e) {
            // Log the full, detailed error for your own records.
            \Illuminate\Support\Facades\Log::error("PDF_PROCESSING_FAILED: " . $e->getMessage());

            // Create a helpful error message for the user.
            $errorMessage = 'Failed to process the PDF file. Please ensure it is a text-based PDF and not a scanned image.';

            // **THE CRITICAL PART**: If your app is in debug mode, append the real technical error.
            if (config('app.debug')) {
                $errorMessage .= ' <br><br><strong>Technical Info:</strong> ' . $e->getMessage();
            }

            // Redirect back with the new, detailed error message.
            return back()->with('error', $errorMessage);
        }

        // 2. PARSE WITH OUR NEW GEMINI PROMPT
        $parsedData = $this->geminiService->extractInformationResume($resumeText);
        if (!$parsedData) {
            return back()->with('error', 'The AI could not understand the resume content.');
        }

        // 3. *** SAVE PARSED DATA TO DATABASE ***
        $users = Auth::guard('applicant')->user();
        DB::transaction(function () use ($users, $parsedData) {
            // A. Update or create the main Resume record
            $resume = \App\Models\Resume::updateOrCreate( // Using the fully qualified namespace
                ['user_id' => $users->id], // The attributes to find the record by.
                [                        // The values to update or create with.
                    'skills' => $parsedData['skills'] ?? '',
                    'experience' => $parsedData['experience_summary'] ?? null,
                    'type_of_disability' => $parsedData['disability_type'] ?? 'Not Specified',
                    'first_name' => $users->first_name ?? 'N/A',
                    'last_name' => $users->last_name ?? 'N/A',
                    'email' => $users->email,
                ]
            );

            // B. Clear old details and add the new ones from the AI
            $resume->experiences()->delete();
            if (!empty($parsedData['experience_details'])) {
                $resume->experiences()->createMany($parsedData['experience_details']);
            }

            $resume->educations()->delete();
            if (!empty($parsedData['education_details'])) {
                $resume->educations()->createMany($parsedData['education_details']);
            }
        });

        // 4. GET AI-RANKED RECOMMENDATIONS (This part stays the same)
        $potentialJobs = $this->getPotentialJobsFromData($parsedData);
        $rankedJobIds = [];
        if ($potentialJobs->isNotEmpty()) {
            $rankedJobIds = $this->geminiService->getAiJobMatches($parsedData, $potentialJobs);
        }

        // 5. STORE IDS IN SESSION AND REDIRECT
        session(['recommended_job_ids' => $rankedJobIds]);
        return redirect()->route('applicant-match-jobs-recommended-jobs'); // Use the correct route name
    }

    /**
     * Method 3: Displays the final recommendations page.
     */
    public function showRecommendations()
    {
        $jobIds = session('recommended_job_ids', []);

        $recommendedJobs = collect();
        if (!empty($jobIds)) {
            // --- THIS IS THE POSTGRESQL-COMPATIBLE FIX ---

            // 1. Create a string of the ordered IDs for the SQL query.
            $jobIdOrder = implode(',', $jobIds);

            // 2. Build the CASE statement for PostgreSQL ordering.
            $orderClause = "CASE id ";
            foreach ($jobIds as $index => $id) {
                // The position in the array determines the order.
                $position = $index + 1;
                $orderClause .= "WHEN {$id} THEN {$position} ";
            }
            $orderClause .= "END";

            // 3. Execute the query using the new ordering logic.
            $recommendedJobs = JobPosting::whereIn('id', $jobIds)
                ->orderByRaw($orderClause)
                ->get();
        }

        $user = Auth::guard('applicant')->user();
        $notifications = $user->notifications;
        $unreadNotifications = $user->unreadNotifications;

        return view('users.applicant.job_recommendations', compact('recommendedJobs', 'user', 'notifications', 'unreadNotifications'));
    }

    /**
     * THE FIX: This method now contains the necessary logic to find potential jobs.
     * It pre-filters jobs from the database to avoid sending too many to the AI.
     */
    private function getPotentialJobsFromData(array $resumeData): \Illuminate\Support\Collection
    {
        $keywords = array_map('trim', explode(',', $resumeData['skills'] ?? ''));

        // Add job titles from experience to the keywords list
        if (!empty($resumeData['experience_details'])) {
            foreach ($resumeData['experience_details'] as $exp) {
                if (!empty($exp['job_title'])) {
                    $keywords[] = $exp['job_title'];
                }
            }
        }

        // Add degree to the keywords list
        if (!empty($resumeData['education_details'][0]['degree'])) {
            $keywords[] = $resumeData['education_details'][0]['degree'];
        }

        $keywords = array_unique(array_filter($keywords)); // Remove duplicates and empty values

        if (empty($keywords)) {
            return collect();
        }

        // Build a query that checks all relevant fields against our keywords
        $query = JobPosting::query()->where('status', 'For Posting');

        $query->where(function ($q) use ($keywords) {
            foreach ($keywords as $keyword) {
                $q->orWhere('skills', 'LIKE', "%{$keyword}%")
                    ->orWhere('position', 'LIKE', "%{$keyword}%")
                    ->orWhere('description', 'LIKE', "%{$keyword}%")
                    ->orWhere('requirements', 'LIKE', "%{$keyword}%")
                    ->orWhere('experience', 'LIKE', "%{$keyword}%") // Also check job's experience field
                    ->orWhere('educational_attainment', 'LIKE', "%{$keyword}%"); // And education field
            }
        });

        return $query->limit(20)->get(); // Limit to 20 to keep AI costs down
    }
}
