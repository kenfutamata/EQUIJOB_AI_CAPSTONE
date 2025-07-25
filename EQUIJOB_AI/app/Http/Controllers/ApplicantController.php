<?php

namespace App\Http\Controllers;

use App\Models\JobApplication;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ApplicantController extends Controller
{
    public function ViewApplicantDashboard()
    {
        $user = Auth::guard('applicant')->user();
        $notification = $user->notifications;
        $unreadNotifications = $user->unreadNotifications;
        $applicationCount = JobApplication::where('applicantID', $user->id)->count();
        $forInterviewCount = JobApplication::where('applicantID', $user->id)->where('status', 'For Interview')->count();
        $onOfferCount = JobApplication::where('applicantID', $user->id)->where('status', 'On-Offer')->count();
        $response = response()->view('users.applicant.applicant_dashboard', compact('user', 'notification', 'unreadNotifications', 'applicationCount', 'forInterviewCount', 'onOfferCount'));
        $response->header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate');
        $response->header('Pragma', 'no-cache');
        $response->header('Expires', 'Fri, 01 Jan 1990 00:00:00 GMT');
        return $response;
    }

    public function LogOutUser(Request $request)
    {
        Auth::guard('applicant')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('landing-page')->with('success', 'You have been logged out.');
    }
}
