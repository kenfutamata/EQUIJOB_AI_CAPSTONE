<?php

namespace App\Http\Controllers;

use App\Models\JobApplication;
use Illuminate\Http\Request;
use App\Models\User;

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
        $validatedData = $request->validate([
            'uploadResume' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'uploadApplicationLetter' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);
        if ($request->hasFile('uploadResume')) {
            $file = $request->file('uploadResume');
            $filepath = $file->store('uploadResume', 'public');
            $validatedData['uploadResume'] = $filepath;
        }
        if ($request->hasFile('uploadApplicationLetter')) {
            $file = $request->file('uploadApplicationLetter');
            $filepath = $file->store('uploadApplicationLetter', 'public');
            $validatedData['uploadApplicationLetter'] = $filepath;
        }
        $validatedData['applicantID'] = auth('applicant')->id();
        $validatedData['jobApplicationNumber'] = $this->generateAlphaNumericId($validatedData['applicantID']);
        $validatedData['jobPostingID'] = $request->input('jobPostingID');
        $validatedData['status'] = 'Pending';

        try {
            JobApplication::create($validatedData);
            $jobProvider = User::find($request->input('jobProviderID'));
            if ($jobProvider) {
                $jobProvider->notify(new \App\Notifications\JobApplicationSent($validatedData, 'job_provider'));
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred Please Try Again ');
        }
        // Logic to store the job application in the database

        return redirect()->back()->with('Success', 'Application Submitted Successfully');
    }


    public function generateAlphaNumericId(string $applicantNumberID): string
    {
        $prefix = 'AN25';
        $last = JobApplication::whereNotNull('jobApplicationNumber')
            ->where('jobApplicationNumber', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        $lastID = $last?->jobApplicationNumber ?? $prefix . '0000';
        $number = (int) substr($lastID, strlen($prefix));
        $next = $number + 1;
        return $prefix . str_pad($next, 5, '0', STR_PAD_LEFT);
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
