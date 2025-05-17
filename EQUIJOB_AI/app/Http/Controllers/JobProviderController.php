<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobProviderController extends Controller
{
    public function ViewJobProviderDashboard(){
    $user = Auth::guard('job_provider')->user(); 
    $response = response()->view('users.job-provider.job_provider_dashboard', compact('user'));

    $response->header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate');
    $response->header('Pragma', 'no-cache');
    $response->header('Expires', 'Fri, 01 Jan 1990 00:00:00 GMT');

    return $response;
    }
    public function LogoutJobProvider(Request $request){
        Auth::guard('job_provider')->logout();
    
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('landing-page')->with('success', 'You have been logged out.');
 
    }
}
