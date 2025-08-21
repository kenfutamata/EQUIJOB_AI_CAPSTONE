<?php

namespace App\Http\Controllers;

use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class JobApplicantManageJobApplications extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::guard('applicant')->user();
        $search = $request->input('search');

        $applicationsQuery = JobApplication::with(['jobPosting', 'applicant'])
            ->where('applicantID', $user->id)
            ->join(DB::raw('"jobPosting"'), DB::raw('"jobApplications"."jobPostingID"'), '=', DB::raw('"jobPosting"."id"'))
            ->select(
                DB::raw('"jobApplications".*'),
                DB::raw('"jobPosting"."position"'),
                DB::raw('"jobPosting"."companyName"')
            );

        if ($search) {
            $applicationsQuery->where(function ($q) use ($search) {
                $q->where('"jobApplications"."jobApplicationNumber"', 'like', "%{$search}%")
                    ->orWhere('"jobPosting"."position"', 'like', "%{$search}%")
                    ->orWhere('"jobPosting"."companyName"', 'like', "%{$search}%")
                    ->orWhere('"jobApplications"."status"', 'like', "%{$search}%");
            });
        }

        $sortable = ['jobApplicationNumber', 'position', 'companyName', 'status'];
        $sort = in_array($request->sort, $sortable) ? $request->sort : 'jobApplicationNumber';
        $direction = $request->direction === 'desc' ? 'desc' : 'asc';

        $applications = $applicationsQuery
            ->orderBy($sort, $direction)
            ->paginate(10);

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
