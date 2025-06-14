<?php

namespace App\Http\Controllers;

use App\Models\users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function ViewAdminDashboard()
    {
        $user = Auth::guard('admin')->user(); 
        $userCount = users::where('status', 'Active')->count();
        $applicantCount  = users::where('role', 'Applicant')->where('status', 'Active')->count();
        $jobProviderCount = users::where('role', 'Job Provider')->where('status', 'Active')->count();
        $response = response()->view('users.admin.admin_dashboard', compact('user', 'userCount', 'jobProviderCount', 'applicantCount'));
        $response->header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate');
        $response->header('Pragma', 'no-cache');
        $response->header('Expires', 'Fri, 01 Jan 1990 00:00:00 GMT');
        return $response;
    }

    public function LogoutAdmin(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('landing-page')->with('success', 'You have been logged out.');
    }
}
