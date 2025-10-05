<?php

namespace App\Http\Controllers;

use App\Exports\JobProviderJobPostingDataExport;
use App\Models\JobPosting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\JobPostingNotificationSent;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class JobPostingController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::guard('job_provider')->user();
        $search = $request->input('search');

        $postingsQuery = JobPosting::where('jobProviderID', $user->id);

        $postingsQuery->when($search, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('companyName', 'like', "%{$search}%")
                    ->orWhere('sex', 'like', "%{$search}%")
                    ->orWhere('age', 'like', "%{$search}%")
                    ->orWhere('disabilityType', 'like', "%{$search}%")
                    ->orWhere('educationalAttainment', 'like', "%{$search}%")
                    ->orWhere('jobPostingObjectives', 'like', "%{$search}%")
                    ->orWhere('requirements', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%")
                    ->orWhere('experience', 'like', "%{$search}%")
                    ->orWhere('skills', 'like', "%{$search}%")
                    ->orWhere('contactPhone', 'like', "%{$search}%")
                    ->orWhere('contactEmail', 'like', "%{$search}%")
                    ->orWhere('position', 'like', "%{$search}%")
                    ->orWhere('remarks', 'like', "%{$search}%");
            });
        });
        $sortable = ['position', 'companyName', 'sex', 'age', 'disabilityType', 'educationalAttainment', 'experience', 'skills', 'requirements', 'status'];
        $sort = in_array($request->sort, $sortable) ? $request->sort : 'created_at';
        $direction = $request->direction === 'desc' ? 'desc' : 'asc';

        $postings = $postingsQuery->orderBy($sort, $direction)->paginate(10)->withQueryString(); 

        $notifications = $user->notifications ?? collect();
        $unreadNotifications = $user->unreadNotifications ?? collect();

        return response()
            ->view('users.job-provider.job_posting', compact('user', 'postings', 'notifications', 'unreadNotifications'))
            ->header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Fri, 01 Jan 1990 00:00:00 GMT');
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $user = Auth::guard('job_provider')->user();

        $validatedData = $request->validate([
            'position' => 'required|string|max:100',
            'companyName' => 'required|string|max:100',
            'sex' => 'required|string|max:10',
            'age' => 'required|integer|min:18|max:65',
            'disabilityType' => 'required|string|max:100',
            'educationalAttainment' => 'nullable|string|max:255',
            'workEnvironment' => 'required|string|max:255',
            'salaryRange' => 'required|string|max:100',
            'jobPostingObjectives' => 'required|string|max:1000',
            'requirements' => 'required|string|max:1000',
            'description' => 'required|string|max:1000',
            'category'=>'required|string|max:100', 
            'experience' => 'nullable|string|max:255',
            'skills' => 'nullable|string|max:1000',
            'contactPhone' => 'required|string|max:15',
            'contactEmail' => 'nullable|email|max:255',
            'companyLogo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->has('job_description')) {
            $validatedData['description'] = $request->input('job_description');
        }
        $validatedData['companyLogo'] = $user->companyLogo;

        $validatedData['jobProviderID'] = Auth::guard('job_provider')->id();
        $validatedData['status'] = 'Pending';

        try {
            $jobPosting = JobPosting::create($validatedData);

            $admins = \App\Models\users::where('role', 'Admin')->get();
            foreach ($admins as $admin) {
                if (method_exists($admin, 'notify')) {
                    $admin->notify(new JobPostingNotificationSent($jobPosting, 'Admin'));
                }
            }

            return redirect()->route('job-provider-job-posting')->with('Success', 'Job posting created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while creating the job posting: ' . $e->getMessage());
            Log::error('Failed to Store Job Posting : ' . $e->getMessage());
        }
    }

    public function show(string $id)
    {
        $jobPosting = JobPosting::findOrFail($id);
        $user = Auth::guard('job_provider')->user();
        return view('users.job-provider.job_posting_show', compact('jobPosting', 'user'));
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        $jobPosting = JobPosting::findOrFail($id);
        $jobPosting->delete();
        return redirect()->back()->with('Delete_Success', 'Job posting deleted successfully');
    }

        public function export(Request $request)
    {
        $user = Auth::guard('job_provider')->user();

        return Excel::download(
            new JobProviderJobPostingDataExport(
                $request->search,
                $request->sort,
                $request->direction
            ),
            "Job Provider Job Postings {$user->companyName}.xlsx"
        );
    }
}
