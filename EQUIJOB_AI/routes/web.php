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
Route::get('/Coming-Soon', function () {
    return view('placeholders.coming_soon');
})->name('coming-soon');


Route::get('/Privacy-Policy', function () {
    return view('placeholders.privacy_policy');
})->name('privacy-policy');

//Landing Page 
Route::get('/', [LandingPageController::class, 'ViewLandingPage'])->name('landing-page');
Route::get('/About-us', [LandingPageController::class, 'ViewAboutUsPage'])->name('about-us');

//Contact Us 
Route::get('/Contact-Us', [LandingPageController::class, 'ViewContactUsPage'])->name('contact-us');
Route::post('/Contact-Us/Submit', [ContactUsController::class, 'store'])->name('contact-us-submit');

//Forgot Password 
Route::get('/Forgot-Password', [ForgotPasswordController::class, 'showForgotPasswordPage'])->name('forgot-password');
Route::get('/Forgot-Password/Validate-Email', [ForgotPasswordController::class, 'validateEmail'])->name('forgot-password.validate-email');
Route::get('/Forgot-Password/Reset-Password', [ForgotPasswordController::class, 'showUpdatePasswordPage'])->name('forgot-password.show-Update-Password-Page');
Route::put('/Forgot-Password/Reset-Password', [ForgotPasswordController::class, 'updatePassword'])->name('forgot-password.update-password');
//Sign in page
Route::get('/Sign-in', [LandingPageController::class, 'ViewSignInPage'])->name('sign-in');
Route::post('/Log-in', [SignInController::class, 'LoginUser'])->name('login');
Route::get('/Log-in', function () {
    return redirect()->route('sign-in');
});
//Sign Up
Route::get('/Sign-up-Applicant', [SignInController::class, 'ViewSignUpApplicantPage'])->name('sign-up-applicant');
Route::get('/Sign-up-JobProvider', [SignInController::class, 'ViewSignUpJobProviderPage'])->name('sign-up-job-provider');
Route::post('/Sign-up-Applicant/login', [SignInController::class, 'SignUpJobApplicant'])->name('sign-up-applicant-register');
Route::post('/Sign-up-JobProvider/login', [SignInController::class, 'SignUpJobProvider'])->name('sign-up-job-provider-register');
Route::get('/Email-Confirmation', [SignInController::class, 'ViewEmailConfirmationPage'])->name('email-confirmation');

// notification
Route::delete('/Notification/Delete/{id}', [NotificationController::class, 'destroy'])->name('notification-delete');


//Job Provider
Route::middleware('auth:job_provider')->group(function () {
    Route::get('/Job-Provider/Job-Provider-Dashboard', [JobProviderController::class, 'ViewJobProviderDashboard'])->name('job-provider-dashboard');
    Route::get('/Job-Provider/Job-Provider-Dashboard/Logout', [JobProviderController::class, 'LogoutJobProvider'])->name('job-provider-logout');
    Route::get('/Job-Provider/Job-Provider-Profile', [JobProviderProfileController::class, 'index'])->name('job-provider-profile');
    Route::get('/EQUIIJOB/Job-Provider/Job-Posting', [JobPostingController::class, 'index'])->name('job-provider-job-posting');
    Route::post('/Job-Provider/Job-Posting', [JobPostingController::class, 'store'])->name('job-provider-job-posting-store');
    Route::delete('/Job-Provider/Job-Posting/Delete/{id}', [JobPostingController::class, 'destroy'])->name('job-provider-job-posting-delete');
    Route::get('/Job-Provider/Job-Posting/{id}', [JobPostingController::class, 'show'])->name('job-provider-job-posting-show');
    Route::get('/Job-Provider/Job-Provider-Profile', [JobProviderProfileController::class, 'index'])->name('job-provider-profile');
    Route::put('/Job-Provider/Job-Provider-Profile/{id}', [JobProviderProfileController::class, 'update'])->name('job-provider-profile-update');
    Route::get('/Job-Provider/Manage-Job-Applications', [JobProviderManageJobApplications::class, 'index'])->name('job-provider-manage-job-applications');
    Route::post('/Job-Provider/Manage-Job-Applications/create-link', [JobProviderManageJobApplications::class, 'generateMeetLink'])->name('job-provider-manage-job-applications.google.meet.create_link');
    Route::get('/Job-Provider/Manage-Job-Applications/provider/google/redirect', [JobProviderManageJobApplications::class, 'redirectToGoogle'])->name('job-provider-manage-job-applications.provider.google.redirect');
    Route::get('/Job-Provider/Manage-Job-Applications/provider/google/callback', [JobProviderManageJobApplications::class, 'handleGoogleCallback'])->name('job-provider-manage-job-applications.provider.google.callback');
    Route::post('/Job-Provider/Manage-Job-Applications/{application}/schedule-interview', [JobProviderManageJobApplications::class, 'scheduleInterview'])->name('job-provider-manage-job-applications.scheduleinterview');
    Route::post('/job-provider/applications/generate-meet-link', [JobProviderManageJobApplications::class, 'generateMeetLink'])->name('job-provider.meet.create'); 
    Route::put('/Job-Provider/Manage-Job-Applications/update-to-offer/{application}', [JobProviderManageJobApplications::class, 'updateApplicationToOffer'])->name('job-provider-manage-job-applications.update-to-offer');
    Route::put('/Job-Provider/Manage-Job-Applications/reject/{id}', [JobProviderManageJobApplications::class, 'rejectApplication'])->name('job-provider-manage-job-applications.reject');
    Route::delete('/Job-Provider/Manage-Job-Applications/Delete/{id}', [JobProviderManageJobApplications::class, 'destroy'])->name('job-provider-manage-job-applications.delete');
    Route::get('/Job-Provider/Applicant-Feedback', [JobProviderJobRatingController::class, 'index'])->name('job-provider-applicant-feedback');
    Route::get('/Job-Provider/Generate-report', [JobProviderGenerateReports::class, 'index'])->name('job-provider-generate-report');
});

//Applicant 
Route::middleware(['auth:applicant'])->group(function () {
    Route::get('/Applicant/Applicant-Dashboard', [ApplicantController::class, 'ViewApplicantDashboard'])->name('applicant-dashboard');
    Route::get('/Applicant/Logout', [ApplicantController::class, 'LogOutUser'])->name('applicant-logout');
    Route::get('/Applicant/Applicant-Profile', [ApplicantProfileController::class, 'index'])->name('applicant-profile');
    Route::put('/Applicant/Applicant-Profile/{id}', [ApplicantProfileController::class, 'update'])->name('applicant-profile-update');
    Route::get('/Applicant/Resume-Builder', [ResumeController::class, 'index'])->name('applicant-resume-builder');
    Route::post('/Applicant/Resume-Builder', [ResumeController::class, 'store'])->name('applicant-resume-builder-store');
    Route::get('/Applicant/Resume-View-And-Download', [ResumeViewAndDownloadController::class, 'index'])->name('applicant-resume-view-and-download');
    Route::get('/Applicant/Resume-View-And-Download/Download', [ResumeViewAndDownloadController::class, 'download'])->name('applicant-resume-download');
    Route::get('/Applicant/Match-Jobs', [ApplicantMatchJobsController::class, 'showUploadForm'])->name('applicant-match-jobs');
    Route::post('/Applicant/Match-Jobs/Upload-Resume', [ApplicantMatchJobsController::class, 'matchWithPdf'])->name('applicant-match-jobs-upload-resume');
    Route::get('/Applicant/Match-Jobs/Recommended-Jobs', [ApplicantMatchJobsController::class, 'showRecommendations'])->name('applicant-match-jobs-recommended-jobs');
    Route::get('/Applicant/Manage-Job-Applications', [JobApplicantManageJobApplications::class, 'index'])->name('applicant-job-applications');
    Route::post('/Applicant/Job-Application', [ApplicantJobApplicationController::class, 'store'])->name('applicant-job-application-store');
    Route::put('/Applicant/Job-Application/Hired/{id}', [ApplicantJobApplicationController::class, 'hiredStatus'])->name('applicant-job-application-hired');
    Route::put('/Applicant/Manage-Job-Applications/withdraw/{id}', [ApplicantJobApplicationController::class, 'withdrawApplication'])->name('applicant-manage-job-applications.withdraw');
    Route::get('/Applicant/Application-Tracker', [ApplicationTrackerController::class, 'index'])->name('applicant-application-tracker');
    Route::get('/Applicant/Application-Tracker/status', [ApplicationTrackerController::class, 'show'])->name('applicant-application-tracker-show');
    Route::get('/Applicant/Applicant-Feedback', [ApplicantFeedbackController::class, 'index'])->name('applicant-feedback');
    Route::put('/Applicant/Applicant-Feedback/{feedback}', [ApplicantFeedbackController::class, 'update'])->name('applicant-feedback-update');
    Route::get('/Applicant/Job-Collections', [ApplicantJobCollectionsController::class, 'index'])->name('applicant-job-collections');
});

//admin
Route::middleware('auth:admin')->group(function () {
    Route::get('/Admin/Admin-Dashboard', [AdminController::class, 'ViewAdminDashboard'])->name('admin-dashboard');
    Route::get('/Admin/Logout', [AdminController::class, 'LogoutAdmin'])->name('admin-logout');
    Route::get('/Admin/Manage-User-Applicants', [AdminManageUsersController::class, 'index'])->name('admin-manage-user-applicants');
    Route::put('/Admin/Manage-User-Applicants/Accept/{id}', [AdminManageUsersController::class, 'update'])->name('admin-manage-user-applicants-accept');
    Route::put('/Admin/Manage-User-Job-Providers/Accept/{id}', [AdminManageUserJobProviderController::class, 'update'])->name('admin-manage-user-Job-Providers-accept');
    Route::get('/Admin/Manage-User-JobProviders', [AdminManageUserJobProviderController::class, 'index'])->name('admin-manage-user-job-providers');
    Route::delete('/Admin/Manage-User-JobProviders/Delete/{id}', [AdminManageUserJobProviderController::class, 'destroy'])->name('admin-manage-user-job-providers-delete');
    Route::delete('/Admin/Manage-User-Applicants/Delete/{id}', [AdminManageUsersController::class, 'destroy'])->name('admin-manage-user-applicants-delete');
    Route::get('/Admin/Manage-Job-Posting', [AdminManageJobPostingController::class, 'index'])->name('admin-manage-job-posting');
    Route::put('/Admin/Manage-Job-Posting/For-Posting/{id}', [AdminManageJobPostingController::class, 'updateForPosting'])->name('admin-manage-job-posting-for-posting');
    Route::put('/Admin/Manage-Job-Posting/Disapproved/{id}', [AdminManageJobPostingController::class, 'updateDisapproved'])->name('admin-manage-job-posting-disapproved');
    Route::get('/Admin/Manage-Job-Posting/{id}', [AdminManageJobPostingController::class, 'show'])->name('Admin-job-posting-show');
    Route::get('/Admin/Feedback-System-Review', [AdminContactUsController::class, 'index'])->name('admin-feedback-contact-us-system-review');
    Route::get('/Admin/Feedback-Job', [AdminJobRatingController::class, 'index'])->name('admin-feedback-job');
    Route::delete('/Admin/Feedback-System-Review/Delete/{feedback}', [AdminContactUsController::class, 'destroy'])->name('admin-feedback-system-review-delete');
    Route::delete('/Admin/Feedback-Job/Delete/{feedback}', [AdminContactUsController::class, 'destroy'])->name('admin-feedback-job-feedback-delete');
    Route::get('/Admin/Generate-Report', [AdminGenerateReportsController::class, 'index'])->name('admin-generate-report');
});
