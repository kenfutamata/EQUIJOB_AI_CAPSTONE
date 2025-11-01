<?php

namespace App\Http\Controllers;

use App\Exports\AdminExportJobApplicationsData;
use App\Models\JobApplication;
use Illuminate\Container\Attributes\Auth as AttributesAuth;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class AdminManageJobApplications extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $admin = Auth::guard('admin')->user();
        $search = $request->input('search');
        $jobApplicationTable = (new JobApplication())->getTable();
        $applicantTable = 'users';
        $jobPostingTable = 'jobPosting';
        $notifications = $admin->notifications ?? collect();
        $unreadNotifications = $admin->unreadNotifications ?? collect();
        $applicationsQuery = JobApplication::query()
            ->join($applicantTable, "{$jobApplicationTable}.applicantID", '=', "{$applicantTable}.id")
            ->join($jobPostingTable, "{$jobApplicationTable}.jobPostingID", '=', "{$jobPostingTable}.id")
            ->with(['applicant', 'jobPosting']);

        if ($search) {
            $searchTerm = '%' . $search . '%';
            $applicationsQuery->where(function ($q) use ($searchTerm, $jobApplicationTable, $applicantTable, $jobPostingTable) { // Added tables to use() for clarity
                $q->where("{$jobApplicationTable}.jobApplicationNumber", 'like', $searchTerm)
                    ->orWhere("{$jobApplicationTable}.status", 'like', $searchTerm)
                    ->orWhereHas('jobPosting', function ($q2) use ($searchTerm) {
                        $q2->where('position', 'like', 'searchTerm')
                            ->orWhere('companyName', 'like', $searchTerm)
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
            'emailAddress' => "{$applicantTable}.email", 
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

        return view('users.admin.admin_manage_job_applications', [
            'admin' => $admin,
            'applications' => $applications,
            'notifications' => $notifications,
            'unreadNotifications' => $unreadNotifications,
            'search' => $search
        ]);
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
        try {
            $application = JobApplication::findOrFail($id);
            $application->delete();
            return redirect()->back()->with('Success', 'Application Deleted Successfully');
        } catch (\Exception $e) {
            Log::error('Failed to delete application ' . $id . ': ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to Delete Application');
        }
    }

    public function export(Request $request){
        return Excel::download(
            new AdminExportJobApplicationsData(
                $request->search, 
                $request->sort, 
                $request->direction
            ), 
            "Admin Job Applications Data.xlsx"
        );
    }
}
