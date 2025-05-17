<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ApplicantController extends Controller
{
    public function ViewApplicantDashboard(){
    $user = Auth::guard('applicant')->user(); 
    $response = response()->view('users.applicant.applicant_dashboard', compact('user'));

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
