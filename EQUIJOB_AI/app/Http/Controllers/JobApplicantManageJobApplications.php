<?php

namespace App\Http\Controllers;

use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobApplicantManageJobApplications extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::guard('applicant')->user();
        $search = request()->input('search');

        $applicationsQuery = JobApplication::with(['jobPosting', 'applicant'])
            ->where('applicantID', $user->id);

        if ($search) {
            $applicationsQuery->whereHas('jobPosting', function ($q) use ($search) {
                $q->where('position', 'like', "%{$search}%")
                    ->orWhere('companyName', 'like', "%{$search}%")
                    ->orWhere('sex', 'like', "%{$search}%")
                    ->orWhere('age', 'like', "%{$search}%")
                    ->orWhere('disability_type', 'like', "%{$search}%")
                    ->orWhere('educationalAttainment', 'like', "%{$search}%")
                    ->orWhere('jobPostingObjectives', 'like', "%{$search}%")
                    ->orWhere('requirements', 'like', "%{$search}%")
                    ->orWhere('experience', 'like', "%{$search}%")
                    ->orWhere('skills', 'like', "%{$search}%")
                    ->orWhere('contactPhone', 'like', "%{$search}%")
                    ->orWhere('contactEmail', 'like', "%{$search}%")
                    ->orWhere('remarks', 'like', "%{$search}%");
            });
            $applicationsQuery->orWhere('status', 'like', "%{$search}%");
        }

        $applications = $applicationsQuery->get();
        $notifications = $user->notifications ?? collect();
        $unreadNotifications = $user->unreadNotifications ?? collect();

        return response()
            ->view('users.applicant.manage_job_applications', compact('user', 'applications', 'notifications', 'unreadNotifications'))
            ->header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Fri, 01 Jan 1990 00:00:00 GMT');
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
