<?php

namespace App\Http\Controllers;

use App\Models\JobPosting;
use App\Services\GeminiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\PdfToText\Pdf;
use Illuminate\Database\QueryException;

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

    try {
        $user = Auth::guard('applicant')->user();
        DB::transaction(function () use ($user, $parsedData) {
            $disabilityTypeFromAI = $parsedData['disability_type'] ?? null;
            
            if (empty(trim($disabilityTypeFromAI))) {
                $disabilityTypeToSave = 'Not Specified';
            } else {
                $disabilityTypeToSave = $disabilityTypeFromAI;
            }

            $resume = \App\Models\Resume::updateOrCreate(
                ['userID' => $user->id],
                [
                    'skills' => $parsedData['skills'] ?? '',
                    'experience' => $parsedData['experience_summary'] ?? null,
                    'typeOfDisability' => $disabilityTypeToSave, 
                    'firstName' => $user->firstName ?? 'N/A',
                    'lastName' => $user->lastName ?? 'N/A',
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
    } catch (QueryException $e) {
        if ($e->getCode() === '23514') { 
            \Illuminate\Support\Facades\Log::error("DATABASE_CHECK_VIOLATION: " . $e->getMessage());
            
            return back()->with('error', 'The resume could not be saved because a value (like Disability Type) provided by the AI is not allowed by the system. Please try again or use the Resume Builder.');
        }

        throw $e;
    }

    $potentialJobs = $this->getPotentialJobsFromData($parsedData);
    $rankedJobIds = [];
    if ($potentialJobs->isNotEmpty()) {
        $rankedJobIds = $this->geminiService->getAiJobMatches($parsedData, $potentialJobs);
    }

    // STORE IDS AND REDIRECT
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
                if (!empty($exp['jobTitle'])) {
                    $keywords[] = $exp['jobTitle'];
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

    public function show()
    {
        $user = Auth::guard('applicant')->user();
        $notifications = $user->notifications;
        $unreadNotifications = $user->unreadNotifications;

        return view('users.applicant.match_jobs', compact('user', 'notifications', 'unreadNotifications'));
    }
}
