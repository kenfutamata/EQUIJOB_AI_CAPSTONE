<?php

namespace App\Http\Controllers;

use App\Models\JobApplication;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class JobApplicantManageJobApplications extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        $user = Auth::guard('applicant')->user();
        $search = $request->input('search');
        $jobApplicationTable = (new JobApplication())->getTable();
        $applicantTable = 'users';
        $jobPostingTable = 'jobPosting';

        $applicationsQuery = JobApplication::query()
            ->join($applicantTable, "{$jobApplicationTable}.applicantID", '=', "{$applicantTable}.id")
            ->join($jobPostingTable, "{$jobApplicationTable}.jobPostingID", '=', "{$jobPostingTable}.id")
            ->where("{$applicantTable}.id", $user->id)
            ->with(['applicant', 'jobPosting']);

        if ($search) {
            $searchTerm = '%' . $search . '%';

            $applicationsQuery->where(function ($q) use ($searchTerm, $jobApplicationTable) {
                $q->where("{$jobApplicationTable}.jobApplicationNumber", 'like', $searchTerm)
                    ->orWhere("{$jobApplicationTable}.status", 'like', $searchTerm)
                    ->orWhereHas('jobPosting', function ($q2) use ($searchTerm) {
                        $q2->where('position', 'like', $searchTerm)
                            ->orWhere('companyName', 'like', 'searchTerm')
                            ->orWhere('disabilityType', 'like', $searchTerm);
                    })
                    ->orWhereHas('applicant', function ($q3) use ($searchTerm) {
                        $q3->where('firstName', 'like', $searchTerm)
                            ->orWhere('lastName', 'like', $searchTerm)
                            ->orWhere('phoneNumber', 'like', $searchTerm)
                            ->orWhere('gender', 'like', $searchTerm)
                            ->orWhere('address', 'like', $searchTerm)
                            ->orWhere('disabilityType', 'like', $searchTerm);
                    });
            });
        }

        $sortable = [
            'jobApplicationNumber' => "{$jobApplicationTable}.jobApplicationNumber",
            'firstName' => "{$applicantTable}.firstName",
            'lastName' => "{$applicantTable}.lastName",
            'phoneNumber' => "{$applicantTable}.phoneNumber",
            'gender' => "{$applicantTable}.sex",
            'address' => "{$applicantTable}.address",
            'emailAddress' => "{$applicantTable}.emailAddress",
            'disabilityType' => "{$applicantTable}.disabilityType",
            'position' => "{$jobPostingTable}.position",
            'companyName' => "{$jobPostingTable}.companyName",
            'status' => "{$jobApplicationTable}.status"
        ];
        
        if ($request->has('sort') && isset($sortable[$request->input('sort')])) {
            $sort = $sortable[$request->input('sort')];
            $direction = $request->direction === 'desc' ? 'desc' : 'asc';
        } else {
            $sort = "{$jobApplicationTable}.created_at";
            $direction = 'desc';
        }

        $applications = $applicationsQuery
            ->select("{$jobApplicationTable}.*")
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