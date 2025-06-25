<?php

namespace App\Http\Controllers;

use App\Models\users;
use App\Notifications\TestNotification;
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

        // Fetch notifications correctly
        $notifications = $user?->notifications ?? collect();
        $unreadNotifications = $user?->unreadNotifications ?? collect();

        return response()->view('users.admin.admin_dashboard', compact(
            'user',
            'userCount',
            'jobProviderCount',
            'applicantCount',
            'notifications',
            'unreadNotifications'
        ))->header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate')
          ->header('Pragma', 'no-cache')
          ->header('Expires', 'Fri, 01 Jan 1990 00:00:00 GMT');
    }

    public function LogoutAdmin(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('landing-page')->with('success', 'You have been logged out.');
    }

    public function SendTestNotification()
    {
        $admin = users::where('role', 'Admin')->first();
        if ($admin) {
            $admin->notify(new TestNotification());
            return 'Test notification sent to admin.';
        }
        return 'No admin user found.';
    }
}
