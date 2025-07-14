<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobProviderManageJobApplications extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::guard('job_provider')->user();
        $search = request()->input('search');

        $applicationsQuery = \App\Models\JobApplication::with(['jobPosting', 'applicant'])
            ->whereHas('jobPosting', function ($query) use ($user) {
                $query->whereRaw('"jobProviderID" = ?', [$user->id]);
            });

        if ($search) {
            // We put the full, correct logic here to ensure no other code interferes.
            $applicationsQuery->where(function ($q) use ($search) {
                $searchTerm = "%{$search}%";

                $q->where('jobApplicationNumber', 'like', $searchTerm)
                    ->orWhere('status', 'like', $searchTerm)
                    ->orWhereHas('jobPosting', function ($q2) use ($searchTerm) {
                        $q2->where('position', 'like', $searchTerm)
                            ->orWhereRaw('"companyName" LIKE ?', [$searchTerm])
                            ->orWhereRaw('"disabilityType" LIKE ?', [$searchTerm]);
                    })
                    ->orWhereHas('applicant', function ($q3) use ($searchTerm) {
                        $q3->where('first_name', 'like', $searchTerm)
                            ->orWhere('last_name', 'like', $searchTerm)
                            ->orWhere('phone_number', 'like', $searchTerm);
                    });
            });
        }

        $applications = $applicationsQuery->latest()->paginate(10);
        $notifications = $user->notifications ?? collect();
        $unreadNotifications = $user->unreadNotifications ?? collect();

        return response()
            ->view('users.job-provider.job_provider_job_applications', compact('user', 'applications', 'notifications', 'unreadNotifications'))
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
