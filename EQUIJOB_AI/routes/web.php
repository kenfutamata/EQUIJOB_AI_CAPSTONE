<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminManageJobPostingController;
use App\Http\Controllers\AdminManageUserJobProviderController;
use App\Http\Controllers\AdminManageUsersController;
use App\Http\Controllers\ApplicantController;
use App\Http\Controllers\ApplicantJobApplicationController;
use App\Http\Controllers\ApplicantMatchJobsController;
use App\Http\Controllers\ApplicantProfileController;
use App\Http\Controllers\JobApplicantManageJobApplications;
use App\Http\Controllers\JobPostingController;
use App\Http\Controllers\JobProviderController;
use App\Http\Controllers\JobProviderManageJobApplications;
use App\Http\Controllers\JobProviderProfileController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ResumeController;
use App\Http\Controllers\ResumeViewAndDownloadController;
use App\Http\Controllers\SignInController;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;
use PHPUnit\Event\Code\Test;
use Illuminate\Support\Facades\File;
use Gemini\Laravel\Facades\Gemini;

//Landing Page
Route::get('/', [LandingPageController::class, 'ViewLandingPage'])->name('landing-page');
Route::get('/EQUIJOB/Sign-in', [LandingPageController::class, 'ViewSignInPage'])->name('sign-in');
Route::post('/EQUIJOB/Log-in', [SignInController::class, 'LoginUser'])->name('login');
Route::get('/EQUIJOB/Log-in', function () {
    return redirect()->route('sign-in');
});

//Sign Up
Route::get('/EQUIJOB/Sign-up-Applicant', [SignInController::class, 'ViewSignUpApplicantPage'])->name('sign-up-applicant');
Route::get('/EQUIJOB/Sign-up-JobProvider', [SignInController::class, 'ViewSignUpJobProviderPage'])->name('sign-up-job-provider');
Route::post('/EQUIJOB/Sign-up-Applicant/login', [SignInController::class, 'SignUpJobApplicant'])->name('sign-up-applicant-register');
Route::post('/EQUIJOB/Sign-up-JobProvider/login', [SignInController::class, 'SignUpJobProvider'])->name('sign-up-job-provider-register');
Route::get('/EQUIJOB/Email-Confirmation', [SignInController::class, 'ViewEmailConfirmationPage'])->name('email-confirmation');

// notification
Route::delete('/EQUIJOB/Notification/Delete/{id}', [NotificationController::class, 'destroy'])->name('notification-delete');


//Job Provider
Route::middleware('auth:job_provider')->group(function () {
    Route::get('/EQUIJOB/Job-Provider/Job-Provider-Dashboard', [JobProviderController::class, 'ViewJobProviderDashboard'])->name('job-provider-dashboard');
    Route::get('/EQUIJOB/Job-Provider/Job-Provider-Dashboard/Logout', [JobProviderController::class, 'LogoutJobProvider'])->name('job-provider-logout');
    Route::get('/EQUIJOB/Job-Provider/Job-Provider-Profile', [JobProviderProfileController::class, 'index'])->name('job-provider-profile');
    Route::get('/EQUIIJOB/Job-Provider/Job-Posting', [JobPostingController::class, 'index'])->name('job-provider-job-posting');
    Route::post('/EQUIJOB/Job-Provider/Job-Posting', [JobPostingController::class, 'store'])->name('job-provider-job-posting-store');
    Route::delete('/EQUIJOB/Job-Provider/Job-Posting/Delete/{id}', [JobPostingController::class, 'destroy'])->name('job-provider-job-posting-delete');
    Route::get('/EQUIJOB/Job-Provider/Job-Posting/{id}', [JobPostingController::class, 'show'])->name('job-provider-job-posting-show');
    Route::get('/EQUIJOB/Job-Provider/Applicant-Profile', [JobProviderProfileController::class, 'index'])->name('job-provider-profile');
    Route::get('/EQUIJOB/Job-Provider/Manage-Job-Applications', [JobProviderManageJobApplications::class, 'index'])->name('job-provider-manage-job-applications');
});

//Applicant 

Route::middleware(['auth:applicant'])->group(function () {
    Route::get('/EQUIJOB/Applicant/Applicant-Dashboard', [ApplicantController::class, 'ViewApplicantDashboard'])->name('applicant-dashboard');
    Route::get('/EQUIJOB/Applicant/Logout', [ApplicantController::class, 'LogOutUser'])->name('applicant-logout');
    Route::get('/EQUIJOB/Applicant/Applicant-Profile', [ApplicantProfileController::class, 'index'])->name('applicant-profile');
    Route::put('/EQUIJOB/Applicant/Applicant-Profile/{id}', [ApplicantProfileController::class, 'update'])->name('applicant-profile-update');
    Route::get('/EQUIJOB/Applicant/Resume-Builder', [ResumeController::class, 'index'])->name('applicant-resume-builder');
    Route::post('/EQUIJOB/Applicant/Resume-Builder', [ResumeController::class, 'store'])->name('applicant-resume-builder-store');
    Route::get('/EQUIJOB/Applicant/Resume-View-And-Download', [ResumeViewAndDownloadController::class, 'index'])->name('applicant-resume-view-and-download');
    Route::get('/EQUIJOB/Applicant/Resume-View-And-Download/Download', [ResumeViewAndDownloadController::class, 'download'])->name('applicant-resume-download');
    Route::get('/EQUIJOB/Applicant/Match-Jobs', [ApplicantMatchJobsController::class, 'showUploadForm'])->name('applicant-match-jobs');
    Route::post('/EQUIJOB/Applicant/Match-Jobs/Upload-Resume', [ApplicantMatchJobsController::class, 'matchWithPdf'])->name('applicant-match-jobs-upload-resume');
    Route::get('/EQUIJOB/Applicant/Match-Jobs/Recommended-Jobs', [ApplicantMatchJobsController::class, 'showRecommendations'])->name('applicant-match-jobs-recommended-jobs');
    Route::get('/EQUIJOB/Applicant/Manage-Job-Applications', [JobApplicantManageJobApplications::class, 'index'])->name('applicant-job-applications');
    Route::post('/EQUIJOB/Applicant/Job-Application', [ApplicantJobApplicationController::class, 'store'])->name('applicant-job-application-store');
});

//admin
Route::middleware('auth:admin')->group(function () {
    Route::get('/EQUIJOB/Admin/Admin-Dashboard', [AdminController::class, 'ViewAdminDashboard'])->name('admin-dashboard');
    Route::get('/EQUIJOB/Admin/Logout', [AdminController::class, 'LogoutAdmin'])->name('admin-logout');
    Route::get('EQUIJOB/Admin/Manage-User-Applicants', [AdminManageUsersController::class, 'index'])->name('admin-manage-user-applicants');
    Route::put('EQUIJOB/Admin/Manage-User-Applicants/Accept/{id}', [AdminManageUsersController::class, 'update'])->name('admin-manage-user-applicants-accept');
    Route::put('EQUIJOB/Admin/Manage-User-Job-Providers/Accept/{id}', [AdminManageUserJobProviderController::class, 'update'])->name('admin-manage-user-Job-Providers-accept');
    Route::get('EQUIJOB/Admin/Manage-User-JobProviders', [AdminManageUserJobProviderController::class, 'index'])->name('admin-manage-user-job-providers');
    Route::delete('EQUIJOB/Admin/Manage-User-JobProviders/Delete/{id}', [AdminManageUserJobProviderController::class, 'destroy'])->name('admin-manage-user-job-providers-delete');
    Route::delete('EQUIJOB/Admin/Manage-User-Applicants/Delete/{id}', [AdminManageUsersController::class, 'destroy'])->name('admin-manage-user-applicants-delete');
    Route::get('EQUIJOB/Admin/Manage-Job-Posting', [AdminManageJobPostingController::class, 'index'])->name('admin-manage-job-posting');
    Route::put('EQUIJOB/Admin/Manage-Job-Posting/For-Posting/{id}', [AdminManageJobPostingController::class, 'updateForPosting'])->name('admin-manage-job-posting-for-posting');
    Route::put('EQUIJOB/Admin/Manage-Job-Posting/Disapproved/{id}', [AdminManageJobPostingController::class, 'updateDisapproved'])->name('admin-manage-job-posting-disapproved');
    Route::get('/EQUIJOB/Admin/Manage-Job-Posting/{id}', [AdminManageJobPostingController::class, 'show'])->name('Admin-job-posting-show');
});
