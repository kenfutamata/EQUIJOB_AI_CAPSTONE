<?php

namespace App\Http\Controllers;

use App\Mail\jobApplicationEmailSent;
use App\Models\JobApplication;
use App\Models\JobPosting;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\users;
use App\Notifications\JobApplicationSent;
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
    public function store(Request $request)
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
            $applicationData = [
                'applicantID' => $applicant->id,
                'jobPostingID' => $posting->id,
                'jobApplicationNumber' => $this->generateAlphaNumericId(),
                'status' => 'Pending',
                'uploadResume' => $request->file('uploadResume')->store('uploadResume', 'public'),
                'uploadApplicationLetter' => $request->file('uploadApplicationLetter')->store('uploadApplicationLetter', 'public'),
            ];

            $newApplication = JobApplication::create($applicationData);

            $maildata = [
                'firstName' => $applicant->first_name,
                'lastName' => $applicant->last_name,
                'jobProvidersFirstName' => $jobProvider->first_name,
                'jobProvidersLastName' => $jobProvider->last_name,
                'position' => $posting->position,
                'companyName' => $posting->companyName,
                'applicationNumber' => $applicationData['jobApplicationNumber'],
            ];

            Mail::to($applicant->email)->send(new jobApplicationEmailSent($maildata));
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
            
            DB::transaction(function () use ($application){
            $application->status = 'Accepted';
            $application->save();

            JobApplication::where('applicantID', $application->applicantID)
                ->where('id', '!=', $application->id)
                ->whereIN('status', ['Pending', 'For Interview', 'On-Offer'])
                ->update(['status' => 'Occupied']);
            }); 


            $applicant = $application->applicant;
            $jobPosting = $application->jobPosting;

            $jobPosting->status = 'Occupied';
            $jobPosting->save();
            $jobProvider = Auth::guard('job_provider')->user();

            $maildata = [
                'firstName' => $applicant->first_name,
                'lastName' => $applicant->last_name,
                'companyName' => $jobPosting->companyName,
                'position' => $jobPosting->position,
                'jobProviderFirstName'=> $jobProvider->first_name,
                'jobProviderLastName'=> $jobProvider->last_name,
                'jobProviderEmail'=> $jobProvider->email,
                'jobProviderPhone'=> $jobProvider->phone,
            ];

            Mail::to($applicant->email)->send(new jobApplicationEmailSent($maildata));
            return redirect()->back()->with('Success', 'Application Updated to Hired');
        } catch (\Exception $e) {
            Log::error('Failed to update application ' . $id . ': ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to Update Application');
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
