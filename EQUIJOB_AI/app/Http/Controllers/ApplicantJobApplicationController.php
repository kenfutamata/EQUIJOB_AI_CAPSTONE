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

        $validatedRequest = $request->validate([
            'uploadResume' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'uploadApplicationLetter' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'jobPostingID' => 'required|exists:jobPosting,id',
            'jobProviderID' => 'required|exists:users,id',
        ]);

        try {
            $applicant = auth('applicant')->user();
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
            $application = JobApplication::findOrFail($id);

            DB::transaction(function () use ($application) {
                $application->status = 'Hired';
                $application->hiredAt = now();
                $application->save();

                JobApplication::where('jobPostingID', $application->jobPostingID)
                    ->where('id', '!=', $application->id)
                    ->whereIN('status', ['Pending', 'For Interview', 'On-Offer'])
                    ->update(['status' => 'Not Available']);
            });


            $applicant = $application->applicant;
            $jobPosting = $application->jobPosting;

            $jobPosting->status = 'Occupied';
            $jobPosting->save();
            $jobProvider = Auth::guard('job_provider')->user();

            $feedback = Feedbacks::create([
                'jobApplicationID' => $application->id,
                'jobPostingID'     => $application->jobPostingID,
                'applicantID'      => $application->applicantID,
                'firstName'        => $applicant->firstName,
                'lastName'         => $applicant->lastName,
                'email'            => $applicant->email,
                'phoneNumber'      => $applicant->phoneNumber,
                'feedbackType'     => 'Job Rating',
                'status'           => 'Sent',
            ]);

            $maildata = [
                'firstName' => $applicant->firstName,
                'lastName' => $applicant->lastName,
                'companyName' => $jobPosting->companyName,
                'position' => $jobPosting->position,
                'jobProviderFirstName' => $jobProvider->firstName,
                'jobProviderLastName' => $jobProvider->lastName,
                'jobProviderEmail' => $jobProvider->email,
                'jobProviderPhone' => $jobProvider->phone,
            ];

            $maildataFeedback = [
                'firstName'        => $applicant->firstName,
                'lastName'         => $applicant->lastName,
                'email'            => $applicant->email,
                'jobProviderFirstName' => $application->jobPosting->jobProvider->firstName,
                'jobProviderLastName' => $application->jobPosting->jobProvider->lastName,
                'companyName'      => $application->jobPosting->jobProvider->companyName,
                'position'         => $application->jobPosting->position,
            ];
            Mail::to($applicant)->send(new HiredStatusSent($maildata));
            Mail::to($applicant)->send(new NotifyApplicantFeedbackSent($maildataFeedback));
            return redirect()->back()->with('Success', 'Application Updated to Hired');
        } catch (\Exception $e) {
            Log::error('Failed to update application ' . $id . ': ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to Update Application');
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
