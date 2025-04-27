<?php

use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\SignUpController;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;
use PHPUnit\Event\Code\Test;

//Landing Page
Route::get('/',[LandingPageController::class, 'ViewLandingPage'])->name('landing-page');
Route::get('/EQUIJOB/Sign-in',[LandingPageController::class, 'ViewSignInPage'])->name('sign-in');

//Sign Up
Route::get('/EQUIJOB/Sign-up-Applicant',[SignUpController::class, 'ViewSignUpApplicantPage'])->name('sign-up-applicant');
Route::get('/EQUIJOB/Sign-up-JobProvider',[SignUpController::class, 'ViewSignUpJobProviderPage'])->name('sign-up-job-provider');
Route::post('/EQUIJOB/Sign-up-Applicant/login',[SignUpController::class, 'SignUpJobApplicant'])->name('sign-up-applicant-register');
Route::post('/EQUIJOB/Sign-up-JobProvider/login',[SignUpController::class, 'SignUpJobProvider'])->name('sign-up-job-provider-register');
Route::get('/EQUIJOB/Email-Confirmation',[SignUpController::class, 'ViewEmailConfirmationPage'])->name('email-confirmation');


