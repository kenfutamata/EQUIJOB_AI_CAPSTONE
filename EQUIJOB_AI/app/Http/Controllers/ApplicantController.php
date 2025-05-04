<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApplicantController extends Controller
{
    public function ViewApplicantDashboard(){
        return view('user-dashboards.applicant.applicant_dashboard');
    }
}
