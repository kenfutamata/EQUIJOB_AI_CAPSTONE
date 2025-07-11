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


    public function showUploadForm()
    {
        $user = Auth::guard('applicant')->user();

        $notifications = $user->notifications;
        $unreadNotifications = $user->unreadNotifications;
        return view('users.applicant.match_jobs', compact('user', 'notifications', 'unreadNotifications'));
    }


  public function matchWithPdf(Request $request)
{
    $request->validate([
        'resume' => 'required|file|mimes:pdf|max:5120',
    ]);

    // This block is now much simpler.
    try {
        $file = $request->file('resume');
        $filePath = $file->getRealPath();
        $mimeType = $file->getMimeType();

        $parsedData = $this->geminiService->extractInformationFromResumeFile($filePath, $mimeType);

        if (!$parsedData) {
            return back()->with('error', 'The AI could not understand the resume content. Please try a different file.');
        }

    } catch (\Exception $e) {
        \Illuminate\Support\Facades\Log::error("AI_PROCESSING_FAILED: " . $e->getMessage());
        return back()->with('error', 'An unexpected error occurred while processing the resume.');
    }

    // 2. *** SAVE PARSED DATA TO DATABASE *** (This part remains the same)
    // I've also corrected the $users -> $user typo for you.
    $user = Auth::guard('applicant')->user();
    DB::transaction(function () use ($user, $parsedData) {
        $resume = \App\Models\Resume::updateOrCreate(
            ['user_id' => $user->id],
            [
                'skills' => $parsedData['skills'] ?? '',
                'experience' => $parsedData['experience_summary'] ?? null,
                'type_of_disability' => $parsedData['disability_type'] ?? 'Not Specified',
                'first_name' => $user->first_name ?? 'N/A',
                'last_name' => $user->last_name ?? 'N/A',
                'email' => $user->email,
            ]
        );

        $resume->experiences()->delete();
        if (!empty($parsedData['experience_details'])) {
            $resume->experiences()->createMany($parsedData['experience_details']);
        }

        $resume->educations()->delete();
        if (!empty($parsedData['education_details'])) {
            $resume->educations()->createMany($parsedData['education_details']);
        }
    });

    // 3. GET AI-RANKED RECOMMENDATIONS (This part remains the same)
    $potentialJobs = $this->getPotentialJobsFromData($parsedData);
    $rankedJobIds = [];
    if ($potentialJobs->isNotEmpty()) {
        $rankedJobIds = $this->geminiService->getAiJobMatches($parsedData, $potentialJobs);
    }

    // 4. STORE IDS IN SESSION AND REDIRECT (This part remains the same)
    session(['recommended_job_ids' => $rankedJobIds]);
    return redirect()->route('applicant-match-jobs-recommended-jobs');
}

    public function showRecommendations()
    {
        $jobIds = session('recommended_job_ids', []);

        $recommendedJobs = collect();
        if (!empty($jobIds)) {

            $jobIdOrder = implode(',', $jobIds);


            $orderClause = "CASE id ";
            foreach ($jobIds as $index => $id) {

                $position = $index + 1;
                $orderClause .= "WHEN {$id} THEN {$position} ";
            }
            $orderClause .= "END";


            $recommendedJobs = JobPosting::whereIn('id', $jobIds)
                ->orderByRaw($orderClause)
                ->get();
        }

        $user = Auth::guard('applicant')->user();
        $notifications = $user->notifications;
        $unreadNotifications = $user->unreadNotifications;

        return view('users.applicant.job_recommendations', compact('recommendedJobs', 'user', 'notifications', 'unreadNotifications'));
    }


    private function getPotentialJobsFromData(array $resumeData): \Illuminate\Support\Collection
    {
        $keywords = array_map('trim', explode(',', $resumeData['skills'] ?? ''));


        if (!empty($resumeData['experience_details'])) {
            foreach ($resumeData['experience_details'] as $exp) {
                if (!empty($exp['job_title'])) {
                    $keywords[] = $exp['job_title'];
                }
            }
        }


        if (!empty($resumeData['education_details'][0]['degree'])) {
            $keywords[] = $resumeData['education_details'][0]['degree'];
        }

        $keywords = array_unique(array_filter($keywords)); 

        if (empty($keywords)) {
            return collect();
        }


        $query = JobPosting::query()->where('status', 'For Posting');

        $query->where(function ($q) use ($keywords) {
            foreach ($keywords as $keyword) {
                $q->orWhere('skills', 'LIKE', "%{$keyword}%")
                    ->orWhere('position', 'LIKE', "%{$keyword}%")
                    ->orWhere('description', 'LIKE', "%{$keyword}%")
                    ->orWhere('requirements', 'LIKE', "%{$keyword}%")
                    ->orWhere('experience', 'LIKE', "%{$keyword}%") 
                    ->orWhere('educationalAttainment', 'LIKE', "%{$keyword}%"); 
            }
        });

        return $query->limit(20)->get(); 
    }
}
