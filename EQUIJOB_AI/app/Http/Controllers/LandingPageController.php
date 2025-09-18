<?php

namespace App\Http\Controllers;

use App\Models\JobPosting;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    public function ViewLandingPage(){
        return view ('landing_page'); 
    }

    public function ViewSignInPage(){
        return view ('sign-in-page.sign_in'); 
    }

    public function ViewAboutUsPage(){
        return view('about_us'); 
    }
    public function ViewContactUsPage(){
        return view('contact_us'); 
    }

    public function ViewJobsPage(){
        $query = JobPosting::where('status', 'For Posting');
        if(request()->has('category') && request()->category != ''){
            $query->where('category', request()->category);
        }
        $collections = $query->paginate(10);
        $response = response()->view('jobs', compact('collections'));
        $response->header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate');
        $response->header('Pragma', 'no-cache');
        $response->header('Expires', 'Fri, 01 Jan 1990 00:00:00 GMT');
        return $response;
    }
}
