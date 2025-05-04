<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class JobProviderController extends Controller
{
    public function ViewJobProviderDashboard(){
        return view('user-dashboards.job-provider.job_provider_dashboard');
    }
    public function LogoutJobProvider(Request $request){
        auth()->guard('job_provider')->logout();
        return redirect()->route('landing-page');
    }
}
