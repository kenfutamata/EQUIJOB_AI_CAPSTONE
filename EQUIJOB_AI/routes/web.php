<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminManageUserJobProviderController;
use App\Http\Controllers\AdminManageUsersController;
use App\Http\Controllers\ApplicantController;
use App\Http\Controllers\ApplicantProfileController;
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
    Route::get('/EQUIJOB/Job-Provider-Dashboard/Logout',[JobProviderController::class, 'LogoutJobProvider'])->name('job-provider-logout');
});

//Applicant 

Route::middleware(['auth:applicant'])->group(function () {
    Route::get('/EQUIJOB/Applicant-Dashboard',[ApplicantController::class, 'ViewApplicantDashboard'])->name('applicant-dashboard');
    Route::get('/EQUIJOB/Applicant-Dashboard/Logout',[ApplicantController::class, 'LogOutUser'])->name('applicant-logout');
    Route::get('/EQUIJOB/Applicant-Dashboard/Applicant-Profile',[ApplicantController::class, 'ShowProfile'])->name('applicant-profile');
    Route::get('/EQUIJOB/Applicant-Dashboard/Applicant-Profile',[ApplicantController::class, 'EditProfile'])->name('applicant-profile-edit');
    Route::get('/EQUIJOB/Applicant-Dashboard/Applicant-Profile',[ApplicantProfileController::class, 'index'])->name('applicant-profile');
    Route::put('/EQUIJOB/Applicant-Dashboard/Applicant-Profile/{id}',[ApplicantProfileController::class, 'update'])->name('applicant-profile-update');

});

//admin
Route::middleware('auth:admin')->group(function () {
    Route::get('/EQUIJOB/Admin-Dashboard',[AdminController::class, 'ViewAdminDashboard'])->name('admin-dashboard');
    Route::get('/EQUIJOB/Admin/Logout',[AdminController::class, 'LogoutAdmin'])->name('admin-logout');
    Route::get('EQUIJOB/Admin/Manage-User-Applicants',[AdminManageUsersController::class, 'index'])->name('admin-manage-user-applicants');
    Route::put('EQUIJOB/Admin/Manage-User-Applicants/Accept/{id}',[AdminManageUsersController::class, 'update'])->name('admin-manage-user-applicants-accept'); 
    Route::put('EQUIJOB/Admin/Manage-User-Job-Providers/Accept/{id}',[AdminManageUserJobProviderController::class, 'update'])->name('admin-manage-user-Job-Providers-accept'); 
    Route::get('EQUIJOB/Admin/Manage-User-JobProviders',[AdminManageUserJobProviderController::class, 'index'])->name('admin-manage-user-job-providers');
    Route::delete('EQUIJOB/Admin/Manage-User-JobProviders/Delete/{id}',[AdminManageUserJobProviderController::class, 'destroy'])->name('admin-manage-user-job-providers-delete');
    Route::delete('EQUIJOB/Admin/Manage-User-Applicants/Delete/{id}',[AdminManageUsersController::class, 'destroy'])->name('admin-manage-user-applicants-delete');
});