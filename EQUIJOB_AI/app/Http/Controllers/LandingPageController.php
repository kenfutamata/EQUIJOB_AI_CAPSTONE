<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    public function ViewLandingPage(){
        return view ('landing_page'); 
    }

    public function ViewSignInPage(){
        return view ('sign-in-page.sign_in'); 
    }

    public function ViewContactUsPage(){
        return view('contact_us'); 
    }
}
