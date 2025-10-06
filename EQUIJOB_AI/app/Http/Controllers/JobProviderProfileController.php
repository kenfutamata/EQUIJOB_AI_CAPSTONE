<?php

namespace App\Http\Controllers;

use App\Models\JobPosting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobProviderProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::guard('job_provider')->user();
        $notification = $user->notifications;
        $unreadNotifications = $user->unreadNotifications;
        $response = response()->view('users.job-provider.job_provider_profile', compact('user', 'notification', 'unreadNotifications'));
        $response->header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate');
        $response->header('Pragma', 'no-cache');
        $response->header('Expires', 'Fri, 01 Jan 1990 00:00:00 GMT');
        return $response;
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


    /**
     * Display the specified resource.
     */
   public function store(Request $request)
    {
        // 1. Validate the input for the job posting itself
        $validatedData = $request->validate([
            'position' => 'required|string|max:255',
            'sex' => 'required|string',
            'age' => 'required|integer',
            'disabilityType' => 'required|string',
            'educationalAttainment' => 'required|string',
            'salaryRange' => 'required|string',
            'workEnvironment' => 'required|string',
            'category' => 'required|string',
            'description' => 'required|string',
            'experience' => 'required|string',
            'skills' => 'required|string',
            'requirements' => 'required|string',
            'contactPhone' => 'required|string',
            'contactEmail' => 'required|email',
            // Do NOT validate companyName or companyLogo here, we get it from the user
        ]);

        // 2. Get the currently authenticated job provider
        $provider = Auth::guard('job_provider')->user();

        // 3. Create a new JobPosting instance
        $jobPosting = new JobPosting($validatedData);

        // 4. THIS IS THE CRITICAL FIX:
        //    Copy the correct, verified information from the provider's profile
        //    to the new job posting record. This avoids data duplication and typos.
        $jobPosting->jobProviderID = $provider->id;
        $jobPosting->companyName = $provider->companyName;
        $jobPosting->companyLogo = $provider->companyLogo; // <-- This copies the correct path

        // 5. Save the new job posting to the database
        $jobPosting->save();

        // 6. Redirect back with a success message
        //    (Change 'job-provider-dashboard' to your actual route name)
        return redirect()->route('job-provider-dashboard')->with('success', 'Job has been posted successfully!');
    }

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
    public function update(Request $request)
    {
        $validateInformation = $request->validate([
            'firstName' => 'string|max:100|regex:/^[A-Za-z\s]+$/',
            'lastName' => 'string|max:100|regex:/^[A-Za-z\s]+$/',
            'email' => 'string|email|max:255',
            'businessPermit' => 'file|mimes:jpg,jpeg,png,pdf|max:2048',
            'companyName' => 'string|max:100|regex:/^[A-Za-z\s]+$/',
            'companyLogo'=> 'file|mimes:jpg,jpeg,png|max:2048',
            'profilePicture' => 'file|mimes:jpg,jpeg,png|max:2048',
        ]);
        if($request->hasFile('companyLogo')){
            $file = $request->file('companyLogo'); 
            $filepath = $file->store('companyLogo', 'public'); 
            $validateInformation['companyLogo'] = $filepath;
        }
        if ($request->hasFile('businessPermit')) {
            $file = $request->file('businessPermit');
            $filepath = $file->store('businessPermit', 'public');
            $validateInformation['businessPermit'] = $filepath;
        }
        if ($request->hasFile('profilePicture')) {
            $file = $request->file('profilePicture');
            $filepath = $file->store('profilePicture', 'public');
            $validateInformation['profilePicture'] = $filepath;
        }

        try {
            $user = Auth::guard('job_provider')->user();
            if (!$user instanceof \App\Models\User) {
                $user = \App\Models\User::find($user->id);
            }
            foreach ($validateInformation as $key => $value) {
                $user->$key = $value;
            }
            $user->save();
            return redirect()->back()->with('success', 'Profile updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while updating the profile: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
