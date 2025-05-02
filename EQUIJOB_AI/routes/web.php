<?php

use App\Http\Controllers\JobProviderController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\SignInController;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;
use PHPUnit\Event\Code\Test;

//Landing Page
Route::get('/',[LandingPageController::class, 'ViewLandingPage'])->name('landing-page');
Route::get('/EQUIJOB/Sign-in',[LandingPageController::class, 'ViewSignInPage'])->name('sign-in');
Route::post('/EQUIJOB/Log-in',[SignInController::class, 'LoginUser'])->name('login');
Route::get('/EQUIJOB/Log-in', function () {
    return redirect()->route('sign-in');
});

//Sign Up
Route::get('/EQUIJOB/Sign-up-Applicant',[SignInController::class, 'ViewSignUpApplicantPage'])->name('sign-up-applicant');
Route::get('/EQUIJOB/Sign-up-JobProvider',[SignInController::class, 'ViewSignUpJobProviderPage'])->name('sign-up-job-provider');
Route::post('/EQUIJOB/Sign-up-Applicant/login',[SignInController::class, 'SignUpJobApplicant'])->name('sign-up-applicant-register');
Route::post('/EQUIJOB/Sign-up-JobProvider/login',[SignInController::class, 'SignUpJobProvider'])->name('sign-up-job-provider-register');
Route::get('/EQUIJOB/Email-Confirmation',[SignInController::class, 'ViewEmailConfirmationPage'])->name('email-confirmation');


//Job Provider
Route::middleware('auth:job_provider')->group(function () {
    Route::get('/EQUIJOB/Job-Provider-Dashboard',[JobProviderController::class, 'ViewJobProviderDashboard'])->name('job-provider-dashboard');
});