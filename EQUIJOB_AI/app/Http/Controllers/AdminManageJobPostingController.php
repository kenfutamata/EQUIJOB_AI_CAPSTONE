<?php

namespace App\Http\Controllers;

use App\Exports\JobPostingsDataExport;
use App\Models\JobPosting;
use App\Notifications\ApprovedJobPostingNotification;
use App\Notifications\DisapprovedJobPostingSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\JobPostingStatusUpdated;
use Maatwebsite\Excel\Facades\Excel;

class AdminManageJobPostingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        $search = $request->input('search');

        $postingsQuery = JobPosting::query()
            ->whereHas('jobProvider', function ($query) {
                $query->where('role', 'Job Provider');
            })
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('companyName', 'like', "%{$search}%")
                        ->orWhere('position', 'like', "%{$search}%")
                        ->orWhere('disabilityType', 'like', "%{$search}%")
                        ->orWhere('educationalAttainment', 'like', "%{$search}%")
                        ->orWhere('workEnvironment', 'like', "%{$search}%")
                        ->orWhere('experience', 'like', "%{$search}%")
                        ->orWhere('category', 'like', "%{$search}%")
                        ->orWhere('skills', 'like', "%{$search}%")
                        ->orWhere('status', 'like', "%{$search}%");              
                    });
            });

        $sortable = ['position', 'companyName', 'disabilityType', 'educationalAttainment', 'workEnvironment', 'experience', 'skills', 'category', 'status'];
        $sort = in_array($request->input('sort'), $sortable) ? $request->input('sort') : 'created_at';
        $direction = $request->input('direction') === 'asc' ? 'asc' : 'desc';

        $postings = $postingsQuery
            ->orderBy($sort, $direction)
            ->paginate(10)
            ->withQueryString();

        $notifications = $admin->notifications ?? collect();
        $unreadNotifications = $admin->unreadNotifications ?? collect();

        return response()
            ->view('users.admin.admin_manage_job_posting', compact(
                'admin',
                'postings',
                'notifications',
                'unreadNotifications',
                'search'
            ))
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

    public function updateForPosting(string $id)
    {
        $JobPosting = JobPosting::findOrFail($id);
        $JobPosting->status = 'For Posting';
        $JobPosting->save();
        if ($JobPosting->jobProvider) {
            $JobPosting->jobProvider->notify(new ApprovedJobPostingNotification($JobPosting));
        }
        return redirect()->back()->with('Success', 'Job posting status updated successfully');
    }

    public function updateDisapproved(string $id)
    {
        $validateInformation = request()->validate([
            'remarks' => 'required|string|max:1000',
        ]);
        $JobPosting = JobPosting::findOrFail($id);

        $JobPosting->status = 'Disapproved';
        $JobPosting->remarks = $validateInformation['remarks'];
        $JobPosting->save();
        if ($JobPosting->jobProvider) {
            $JobPosting->jobProvider->notify(new DisapprovedJobPostingSent($JobPosting, 'Disapproved', $validateInformation['remarks']));
        }
        return redirect()->back()->with('Success', 'Job posting status Disapproved');
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $jobPosting = JobPosting::findOrFail($id);
        $user = Auth::guard('admin')->user();
        return view('users.admin.job_posting_show', compact('jobPosting', 'user'));
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

    public function export(Request $request)
    {
        return Excel::download(new JobPostingsDataExport(
            $request->search, 
            $request->sort, 
            $request->direction
        ),
        'Job Postings.xlsx' 
    );
    }
}
