<?php

use App\Http\Controllers\AdminContactUsController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminGenerateReportsController;
use App\Http\Controllers\AdminJobRatingController;
use App\Http\Controllers\AdminManageJobPostingController;
use App\Http\Controllers\AdminManageUserJobProviderController;
use App\Http\Controllers\AdminManageUsersController;
use App\Http\Controllers\ApplicantController;
use App\Http\Controllers\ApplicantFeedbackController;
use App\Http\Controllers\ApplicantJobApplicationController;
use App\Http\Controllers\ApplicantJobCollectionsController;
use App\Http\Controllers\ApplicantMatchJobsController;
use App\Http\Controllers\ApplicantProfileController;
use App\Http\Controllers\ApplicationTrackerController;
use App\Http\Controllers\ContactUsController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\JobApplicantManageJobApplications;
use App\Http\Controllers\JobPostingController;
use App\Http\Controllers\JobProviderController;
use App\Http\Controllers\JobProviderGenerateReports;
use App\Http\Controllers\JobProviderJobRatingController;
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
use Illuminate\Support\Facades\DB;

//coming Soon 
Route::get('/coming-soon', function () {
    return view('placeholders.coming_soon');
})->name('coming-soon');


Route::get('/privacy-policy', function () {
    return view('placeholders.privacy_policy');
})->name('privacy-policy');

//Landing Page 
Route::get('/', [LandingPageController::class, 'ViewLandingPage'])->name('landing-page');
Route::get('/about-us', [LandingPageController::class, 'ViewAboutUsPage'])->name('about-us');

//Contact Us 
Route::get('/contact-ss', [LandingPageController::class, 'ViewContactUsPage'])->name('contact-us');
Route::post('/contact-us/submit', [ContactUsController::class, 'store'])->name('contact-us-submit');

//Forgot Password 
Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotPasswordPage'])->name('forgot-password');
Route::get('/forgot-password/validate-email', [ForgotPasswordController::class, 'validateEmail'])->name('forgot-password.validate-email');
Route::get('/forgot-password/reset-password', [ForgotPasswordController::class, 'showUpdatePasswordPage'])->name('forgot-password.show-Update-Password-Page');
Route::put('/forgot-password/reset-password', [ForgotPasswordController::class, 'updatePassword'])->name('forgot-password.update-password');
//Sign in page
Route::get('/sign-in', [LandingPageController::class, 'ViewSignInPage'])->name('sign-in');
Route::post('/log-in', [SignInController::class, 'LoginUser'])->name('login');
Route::get('/log-in', function () {
    return redirect()->route('sign-in');
});
//Sign Up
Route::get('/sign-up-applicant', [SignInController::class, 'ViewSignUpApplicantPage'])->name('sign-up-applicant');
Route::get('/sign-up-jobprovider', [SignInController::class, 'ViewSignUpJobProviderPage'])->name('sign-up-job-provider');
Route::post('/sign-up-applicant/login', [SignInController::class, 'SignUpJobApplicant'])->name('sign-up-applicant-register');
Route::post('/sign-up-jobprovider/login', [SignInController::class, 'SignUpJobProvider'])->name('sign-up-job-provider-register');
Route::get('/email-confirmation', [SignInController::class, 'ViewEmailConfirmationPage'])->name('email-confirmation');

//Jobs Page
Route::get('/jobs', [LandingPageController::class, 'ViewJobsPage'])->name('jobs');
// notification
Route::delete('/notification/delete/{id}', [NotificationController::class, 'destroy'])->name('notification-delete');


//Job Provider
Route::middleware('auth:job_provider')->group(function () {
    Route::get('/job-provider/job-provider-dashboard', [JobProviderController::class, 'ViewJobProviderDashboard'])->name('job-provider-dashboard');
    Route::get('/job-provider/job-provider-dashboard/logout', [JobProviderController::class, 'LogoutJobProvider'])->name('job-provider-logout');
    Route::get('/job-provider/job-provider-profile', [JobProviderProfileController::class, 'index'])->name('job-provider-profile');
    Route::get('/job-provider/job-posting/export', [JobPostingController::class, 'export'])->name('job-provider-job-posting-export');
    Route::get('/job-provider/job-posting', [JobPostingController::class, 'index'])->name('job-provider-job-posting');
    Route::post('/job-provider/job-posting', [JobPostingController::class, 'store'])->name('job-provider-job-posting-store');
    Route::delete('/job-provider/job-posting/delete/{id}', [JobPostingController::class, 'destroy'])->name('job-provider-job-posting-delete');
    Route::get('/job-provider/job-posting/{id}', [JobPostingController::class, 'show'])->name('job-provider-job-posting-show');
    Route::get('/job-provider/job-provider-profile', [JobProviderProfileController::class, 'index'])->name('job-provider-profile');
    Route::put('/job-provider/job-provider-profile', [JobProviderProfileController::class, 'update'])->name('job-provider-profile-update');
    Route::get('/job-provider/manage-job-applications', [JobProviderManageJobApplications::class, 'index'])->name('job-provider-manage-job-applications');
    Route::get('/job-provider/manage-job-applications/export', [JobProviderManageJobApplications::class, 'export'])->name('job-provider-job-applications-export');
    Route::post('/job-provider/manage-job-applications/create-link', [JobProviderManageJobApplications::class, 'generateMeetLink'])->name('job-provider-manage-job-applications.google.meet.create_link');
    Route::get('/job-provider/manage-job-applications/provider/google/redirect', [JobProviderManageJobApplications::class, 'redirectToGoogle'])->name('job-provider-manage-job-applications.provider.google.redirect');
    Route::get('/job-provider/manage-job-applications/provider/google/callback', [JobProviderManageJobApplications::class, 'handleGoogleCallback'])->name('job-provider-manage-job-applications.provider.google.callback');
    Route::post('/job-provider/manage-job-applications/{application}/schedule-interview', [JobProviderManageJobApplications::class, 'scheduleInterview'])->name('job-provider-manage-job-applications.scheduleinterview');
    Route::post('/job-provider/applications/generate-meet-link', [JobProviderManageJobApplications::class, 'generateMeetLink'])->name('job-provider.meet.create');
    Route::put('/job-provider/manage-job-applications/update-to-offer/{application}', [JobProviderManageJobApplications::class, 'updateApplicationToOffer'])->name('job-provider-manage-job-applications.update-to-offer');
    Route::put('/job-provider/manage-job-applications/reject/{application}', [JobProviderManageJobApplications::class, 'rejectApplication'])->name('job-provider-manage-job-applications.reject');
    Route::delete('/job-provider/manage-job-applications/Delete/{id}', [JobProviderManageJobApplications::class, 'destroy'])->name('job-provider-manage-job-applications.delete');
    Route::get('/job-provider/applicant-feedback', [JobProviderJobRatingController::class, 'index'])->name('job-provider-applicant-feedback');
    Route::get('/job-provider/applicant-feedback/export', [JobProviderJobRatingController::class, 'export'])->name('job-provider-applicant-feedback-export');
    Route::get('/job-provider/generate-report', [JobProviderGenerateReports::class, 'index'])->name('job-provider-generate-report');
});

//Applicant 
Route::middleware(['auth:applicant'])->group(function () {
    Route::get('/applicant/applicant-dashboard', [ApplicantController::class, 'ViewApplicantDashboard'])->name('applicant-dashboard');
    Route::get('/applicant/logout', [ApplicantController::class, 'LogOutUser'])->name('applicant-logout');
    Route::get('/applicant/applicant-profile', [ApplicantProfileController::class, 'index'])->name('applicant-profile');
    Route::put('/applicant/applicant-profile/{id}', [ApplicantProfileController::class, 'update'])->name('applicant-profile-update');
    Route::get('/applicant/resume-builder', [ResumeController::class, 'index'])->name('applicant-resume-builder');
    Route::post('/applicant/resume-builder', [ResumeController::class, 'store'])->name('applicant-resume-builder-store');
    Route::get('/applicant/resume-view-and-download', [ResumeViewAndDownloadController::class, 'index'])->name('applicant-resume-view-and-download');
    Route::get('/applicant/resume-view-and-download/download', [ResumeViewAndDownloadController::class, 'download'])->name('applicant-resume-download');
    Route::get('/applicant/match-jobs', [ApplicantMatchJobsController::class, 'showUploadForm'])->name('applicant-match-jobs');
    Route::post('/applicant/match-jobs/upload-resume', [ApplicantMatchJobsController::class, 'matchWithPdf'])->name('applicant-match-jobs-upload-resume');
    Route::get('/applicant/match-jobs/recommended-jobs', [ApplicantMatchJobsController::class, 'showRecommendations'])->name('applicant-match-jobs-recommended-jobs');
    Route::get('/applicant/manage-job-applications', [JobApplicantManageJobApplications::class, 'index'])->name('applicant-job-applications');
    Route::post('/applicant/job-application', [ApplicantJobApplicationController::class, 'store'])->name('applicant-job-application-store');
    Route::put('/applicant/job-application/Hired/{id}', [ApplicantJobApplicationController::class, 'hiredStatus'])->name('applicant-job-application-hired');
    Route::put('/applicant/manage-job-applications/withdraw/{id}', [ApplicantJobApplicationController::class, 'withdrawApplication'])->name('applicant-manage-job-applications.withdraw');
    Route::get('/applicant/application-tracker', [ApplicationTrackerController::class, 'index'])->name('applicant-application-tracker');
    Route::get('/applicant/application-tracker/status', [ApplicationTrackerController::class, 'show'])->name('applicant-application-tracker-show');
    Route::get('/applicant/applicant-feedback', [ApplicantFeedbackController::class, 'index'])->name('applicant-feedback');
    Route::put('/applicant/applicant-feedback/{feedback}', [ApplicantFeedbackController::class, 'update'])->name('applicant-feedback-update');
    Route::get('/applicant/job-collections', [ApplicantJobCollectionsController::class, 'index'])->name('applicant-job-collections');
});

//admin
Route::middleware('auth:admin')->group(function () {
    Route::get('/admin/admin-dashboard', [AdminController::class, 'ViewAdminDashboard'])->name('admin-dashboard');
    Route::get('/admin/logout', [AdminController::class, 'LogoutAdmin'])->name('admin-logout');
    Route::get('/admin/manage-user-applicants', [AdminManageUsersController::class, 'index'])->name('admin-manage-user-applicants');
    Route::put('/admin/manage-user-applicants/Accept/{id}', [AdminManageUsersController::class, 'update'])->name('admin-manage-user-applicants-accept');
    Route::put('/admin/manage-user-job-providers/Accept/{id}', [AdminManageUserJobProviderController::class, 'update'])->name('admin-manage-user-Job-Providers-accept');
    Route::get('/admin/manage-user-job-providers', [AdminManageUserJobProviderController::class, 'index'])->name('admin-manage-user-job-providers');
    Route::delete('/admin/manage-user-job-providers/Delete/{id}', [AdminManageUserJobProviderController::class, 'destroy'])->name('admin-manage-user-job-providers-delete');
    Route::delete('/admin/manage-user-applicants/Delete/{id}', [AdminManageUsersController::class, 'destroy'])->name('admin-manage-user-applicants-delete');
    Route::get('admin/manage-job-posting/Export', [AdminManageJobPostingController::class, 'export'])->name('admin-manage-job-posting-export');
    Route::get('/admin/manage-job-posting', [AdminManageJobPostingController::class, 'index'])->name('admin-manage-job-posting');
    Route::put('/admin/manage-job-posting/for-posting/{id}', [AdminManageJobPostingController::class, 'updateForPosting'])->name('admin-manage-job-posting-for-posting');
    Route::put('/admin/manage-job-posting/disapproved/{id}', [AdminManageJobPostingController::class, 'updateDisapproved'])->name('admin-manage-job-posting-disapproved');
    Route::get('/admin/manage-job-posting/{id}', [AdminManageJobPostingController::class, 'show'])->name('admin-job-posting-show');
    Route::get('/admin/feedback-system-review', [AdminContactUsController::class, 'index'])->name('admin-feedback-contact-us-system-review');
    Route::get('/admin/feedback-job', [AdminJobRatingController::class, 'index'])->name('admin-feedback-job');
    Route::delete('/admin/feedback-system-review/delete/{feedback}', [AdminContactUsController::class, 'destroy'])->name('admin-feedback-system-review-delete');
    Route::delete('/admin/feedback-job/delete/{feedback}', [AdminContactUsController::class, 'destroy'])->name('admin-feedback-job-feedback-delete');
    Route::get('/admin/feedback-system-review/export', [AdminContactUsController::class, 'export'])->name('admin-feedback-system-review-export');
    Route::get('/admin/feedback-job/export', [AdminJobRatingController::class, 'export'])->name('admin-feedback-job-export');
    Route::get('/admin/generate-report', [AdminGenerateReportsController::class, 'index'])->name('admin-generate-report');
    Route::get('admin/manage-user-applicants/Export', [AdminManageUsersController::class, 'export'])->name('admin-manage-user-applicants-export');
    Route::get('admin/manage-user-job-Providers/export', [AdminManageUserJobProviderController::class, 'export'])->name('admin-manage-user-jobproviders-export');
});
