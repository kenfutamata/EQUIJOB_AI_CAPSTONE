<?php

namespace App\Http\Controllers;

use App\Models\JobApplication;
use App\Models\JobPosting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobProviderController extends Controller
{
    public function ViewJobProviderDashboard()
    {
        $user = Auth::guard('job_provider')->user();
        $notifications = $user->notifications;
        $unreadNotifications = $user->unreadNotifications;
        $jobPostingCount = JobPosting::where('jobProviderID', $user->id)->count();
        $jobApplicationCount = JobApplication::whereHas('jobPosting', function ($query) use ($user) {
            $query->where('jobProviderID', $user->id);
        })->count();
        $jobApplicantInterviewCount = JobApplication::whereHas('jobPosting', function ($query) use ($user) {
            $query->where('jobProviderID', $user->id);
        })->where('status', 'Interview')->count();
        $jobApplicantHiredCount = JobApplication::whereHas('jobPosting', function($q) use ($user){
            $q->where('jobProviderID', $user->id);
        })->where('status', 'Hired')
        ->count();
        $response = response()->view('users.job-provider.job_provider_dashboard', compact('user', 'notifications', 'unreadNotifications', 'jobPostingCount', 'jobApplicationCount', 'jobApplicantInterviewCount', 'jobApplicantHiredCount'));
        $response->header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate');
        $response->header('Pragma', 'no-cache');
        $response->header('Expires', 'Fri, 01 Jan 1990 00:00:00 GMT');

        return $response;
    }
    public function LogoutJobProvider(Request $request)
    {
        Auth::guard('job_provider')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('landing-page')->with('success', 'You have been logged out.');
    }
}
