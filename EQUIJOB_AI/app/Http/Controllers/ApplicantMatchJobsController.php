<?php

namespace App\Http\Controllers;

use App\Models\JobApplication;
use App\Models\JobPosting;
use App\Services\GeminiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Support\Str; // Import Str for string comparison helpers

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

            // 1. Extract Data from AI
            $parsedData = $this->geminiService->extractInformationFromResumeFile($filePath, $mimeType);

            // 2. Check if content exists
            $isResumeContentEmpty = !$parsedData || (
                empty(trim($parsedData['skills'] ?? '')) &&
                empty($parsedData['experience_details'] ?? []) &&
                empty($parsedData['education_details'] ?? [])
            );

            if ($isResumeContentEmpty) {
                return back()->with('error', 'The AI could not identify any resume content. Please ensure you are uploading a valid resume.');
            }

            // 3. SECURITY CHECK: Verify Resume Ownership
            $user = Auth::guard('applicant')->user();
            
            if (!$this->validateResumeOwner($user, $parsedData)) {
                return back()->with('error', 'The name on the uploaded resume does not match your profile name. Please upload your own resume.');
            }

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("AI_PROCESSING_FAILED: " . $e->getMessage());
            return back()->with('error', 'An unexpected error occurred while processing the resume.');
        }

        // 4. Save to Database
        try {
            DB::transaction(function () use ($user, $parsedData) {
                $disabilityTypeFromAI = $parsedData['disability_type'] ?? null;
                $disabilityTypeToSave = empty(trim($disabilityTypeFromAI)) ? 'Not Specified' : $disabilityTypeFromAI;

                $resume = \App\Models\Resume::updateOrCreate(
                    ['userID' => $user->id],
                    [
                        'skills' => $parsedData['skills'] ?? '',
                        'experience' => $parsedData['experience_summary'] ?? null,
                        'type_of_disability' => $disabilityTypeToSave,
                        'first_name' => $user->firstName,
                        'last_name' => $user->lastName,
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
                return back()->with('error', 'The resume contains data (like Disability Type) not allowed by the system. Please use the Resume Builder.');
            }
            throw $e;
        }

        $potentialJobs = $this->getPotentialJobsFromData($parsedData);
        $rankedJobIds = [];
        if ($potentialJobs->isNotEmpty()) {
            $rankedJobIds = $this->geminiService->getAiJobMatches($parsedData, $potentialJobs);
        }

        session(['recommended_job_ids' => $rankedJobIds]);
        return redirect()->route('applicant-match-jobs-recommended-jobs');
    }

    /**
     * Helper function to validate if the resume name matches the logged-in user.
     */
    private function validateResumeOwner($user, $parsedData): bool
    {
        $extractedInfo = $parsedData['candidate_info'] ?? [];
        $extractedFirst = strtolower(trim($extractedInfo['first_name'] ?? ''));
        $extractedLast = strtolower(trim($extractedInfo['last_name'] ?? ''));


        if (empty($extractedFirst) && empty($extractedLast)) {
             return false; 
        }

        $userFirst = strtolower(trim($user->firstName));
        $userLast = strtolower(trim($user->lastName));

        $lastNameMatch = str_contains($extractedLast, $userLast) || str_contains($userLast, $extractedLast);
        $firstNameMatch = str_contains($extractedFirst, $userFirst) || str_contains($userFirst, $extractedFirst);

        if ($lastNameMatch && $firstNameMatch) {
            return true;
        }


        $distFirst = levenshtein($userFirst, $extractedFirst);
        $distLast = levenshtein($userLast, $extractedLast);

        if ($distFirst <= 2 && $distLast <= 2) {
            return true;
        }

        return false;
    }

    public function showRecommendations()
    {
        $jobIds = session('recommended_job_ids', []);

        $recommendedJobs = collect();
        if (!empty($jobIds)) {
            $orderClause = "CASE id ";
            foreach ($jobIds as $index => $id) {
                $position = $index + 1;
                $orderClause .= "WHEN {$id} THEN {$position} ";
            }
            $orderClause .= "END";

            $recommendedJobs = JobPosting::withCount([
                'jobApplications',
                'jobApplications as interviews_count' => function ($query) {         
                    $query->where('status', 'For Interview');
                }
            ])
            ->whereIn('id', $jobIds)
            ->orderByRaw($orderClause)
            ->get();
        }

        $user = Auth::guard('applicant')->user();
        $notifications = $user->notifications;
        $unreadNotifications = $user->unreadNotifications;

        $appliedJobIds = JobApplication::where('applicantID', $user->id)
            ->pluck('jobPostingID')
            ->toArray();

        $numberOfAppliedJobs = count($appliedJobIds);
        $numberOfInterviews = JobApplication::where('applicantID', $user->id)
            ->where('status', 'For Interview')
            ->count();
            
        return view('users.applicant.job_recommendations', compact('recommendedJobs', 'user', 'notifications', 'unreadNotifications', 'appliedJobIds', 'numberOfAppliedJobs', 'numberOfInterviews'));
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

        $lowerCaseKeywords = array_map('strtolower', array_unique(array_filter($keywords)));

        if (empty($lowerCaseKeywords)) {
            return collect();
        }

        $query = JobPosting::query()->where('status', 'For Posting');

        $query->where(function ($q) use ($lowerCaseKeywords) {
            foreach ($lowerCaseKeywords as $keyword) {
                $q->orWhere(DB::raw('LOWER("skills")'), 'LIKE', "%{$keyword}%")
                    ->orWhere(DB::raw('LOWER("position")'), 'LIKE', "%{$keyword}%")
                    ->orWhere(DB::raw('LOWER("description")'), 'LIKE', "%{$keyword}%")
                    ->orWhere(DB::raw('LOWER("requirements")'), 'LIKE', "%{$keyword}%")
                    ->orWhere(DB::raw('LOWER("experience")'), 'LIKE', "%{$keyword}%")
                    ->orWhere(DB::raw('LOWER("educationalAttainment")'), 'LIKE', "%{$keyword}%");
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