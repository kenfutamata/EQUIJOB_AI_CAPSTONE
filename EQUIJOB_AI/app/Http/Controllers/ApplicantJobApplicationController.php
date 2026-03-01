<?php

namespace App\Http\Controllers;

use App\Mail\HiredStatusSent;
use App\Mail\jobApplicationEmailSent;
use App\Mail\NotifyApplicantFeedbackSent;
use App\Models\Feedbacks;
use App\Models\JobApplication;
use App\Models\JobPosting;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\users;
use App\Notifications\JobApplicationSent;
use App\Notifications\JobApplicationWithdrawnSent;
use App\Services\SupabaseStorageService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;


class ApplicantJobApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, SupabaseStorageService $supabase)
    {

        $applicant = auth('applicant')->user();
        $esists = JobApplication::where('applicantID', $applicant->id)
            ->where('jobPostingID', $request->input('jobPostingID'))
            ->exists();
        if ($esists) {
            return redirect()->back()->with('error', 'You have already applied to this job posting.');
        }

        $validatedRequest = $request->validate([
            'uploadResume' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'uploadApplicationLetter' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'jobPostingID' => 'required|exists:jobPosting,id',
            'jobProviderID' => 'required|exists:users,id',
        ]);

        try {
            $posting = JobPosting::find($validatedRequest['jobPostingID']);
            $jobProvider = User::where('id', $validatedRequest['jobProviderID'])
                ->where('role', 'Job Provider')
                ->first();

            if (!$jobProvider) {
                throw new \Exception('The specified job provider could not be found or does not have the correct role.');
            }

            $resumeUrl = $supabase->upload($request->file('uploadResume'), 'uploadResume');
            $applicationLetterUrl = $supabase->upload($request->file('uploadApplicationLetter'), 'uploadApplicationLetter');
            $applicationData = [
                'applicantID' => $applicant->id,
                'jobPostingID' => $posting->id,
                'jobApplicationNumber' => $this->generateAlphaNumericId(),
                'status' => 'Pending',
                'uploadResume' => $resumeUrl,
                'uploadApplicationLetter' => $applicationLetterUrl,
            ];

            $newApplication = JobApplication::create($applicationData);

            $maildata = [
                'firstName' => $applicant->firstName,
                'lastName' => $applicant->lastName,
                'email' => $applicant->email,
                'jobProvidersFirstName' => $jobProvider->firstName,
                'jobProvidersLastName' => $jobProvider->lastName,
                'position' => $posting->position,
                'companyName' => $posting->companyName,
                'applicationNumber' => $applicationData['jobApplicationNumber'],
            ];

            Mail::to($applicant)->send(new jobApplicationEmailSent($maildata));
            $jobProvider->notify(new JobApplicationSent($newApplication, 'job_provider'));
        } catch (\Exception $e) {
            Log::error('Job application failed: ' . $e->getMessage() . ' on line ' . $e->getLine() . ' in ' . $e->getFile());
            return redirect()->back()->with('error', 'An error occurred. Please try again.');
        }
        return redirect()->back()->with('Success', 'Application Submitted Successfully');
    }


    public function generateAlphaNumericId(): string
    {
        $prefix = 'AN25';
        $last = JobApplication::where('jobApplicationNumber', 'like', $prefix . '%')
            ->latest('id')
            ->first();

        $lastID = $last?->jobApplicationNumber ?? $prefix . '0000';
        $number = (int) substr($lastID, strlen($prefix));
        $next = $number + 1;

        return $prefix . str_pad($next, 5, '0', STR_PAD_LEFT);
    }

    public function hiredStatus(string $id)
    {
        try {
            // 1. Eager load everything: the applicant, the posting, and the person who posted the job
            // This assumes JobPosting has a 'jobProvider' relationship (User model)
            $application = JobApplication::with(['applicant', 'jobPosting.jobProvider'])->findOrFail($id);

            $applicant = $application->applicant;
            $jobPosting = $application->jobPosting;

            // 2. Fetch the Job Provider from the database instead of the session
            // Based on your other methods, $jobPosting->jobProvider should work.
            // If that relationship isn't set, use: User::find($jobPosting->jobProviderID);
            $jobProvider = $jobPosting->jobProvider ?? User::find($jobPosting->jobProviderID);

            // --- SAFETY CHECKS ---
            if (!$applicant) {
                throw new \Exception("Applicant data not found.");
            }

            if (!$jobProvider) {
                throw new \Exception("The Job Provider associated with this posting no longer exists.");
            }

            // 3. Perform the database update
            DB::transaction(function () use ($application, $jobPosting) {
                $application->status = 'Hired';
                $application->hiredAt = now();
                $application->feedbackSent = false;
                $application->expectedFeedback = now()->addDays(30);
                $application->save();

                // Mark other applications for the same job as "Not Available"
                JobApplication::where('jobPostingID', $application->jobPostingID)
                    ->where('id', '!=', $application->id)
                    ->whereIn('status', ['Pending', 'For Interview', 'On-Offer'])
                    ->update(['status' => 'Not Available']);

                // Set the job itself to Occupied
                if ($jobPosting) {
                    $jobPosting->status = 'Occupied';
                    $jobPosting->save();
                }
            });

            // 4. Prepare data for the email (Using the Provider we found in the DB)
            $maildata = [
                'firstName' => $applicant->firstName,
                'lastName' => $applicant->lastName,
                'companyName' => $jobPosting->companyName ?? 'EQUIJOB Partner',
                'position' => $jobPosting->position ?? 'Position',
                'jobProviderFirstName' => $jobProvider->firstName,
                'jobProviderLastName' => $jobProvider->lastName,
                'jobProviderEmail' => $jobProvider->email,
                'jobProviderPhone' => $jobProvider->phone ?? 'N/A',
            ];

            // 5. Send the email to the applicant
            Mail::to($applicant->email, $applicant->firstName . ' ' . $applicant->lastName)->send(new HiredStatusSent($maildata));

            return redirect()->back()->with('Success', 'Congratulations! You have accepted the offer.');
        } catch (\Exception $e) {
            Log::error('Hired Status Error for App ' . $id . ': ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
    public function withdrawApplication(string $id)
    {
        try {
            $application = JobApplication::findOrFail($id);
            $application->status = 'Withdrawn';
            $application->save();
            $jobProvider = $application->jobPosting->jobProvider;
            $jobProvider->notify(new JobApplicationWithdrawnSent($application, 'job_provider'));
            return redirect()->back()->with('Success', 'Application Withdrawn Successfully');
        } catch (\Exception $e) {
            Log::error('Failed to withdraw application ' . $id . ': ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to Withdraw Application');
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id) {}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
