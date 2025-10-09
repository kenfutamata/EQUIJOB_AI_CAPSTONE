<?php
//
namespace App\Http\Controllers;

use App\Models\Resume;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class ResumeViewAndDownloadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::guard('applicant')->user();

        // Eager load the resume directly onto the user object.
        $user->load('resume.experiences', 'resume.educations');

        // Now, get the resume FROM the user object.
        $resume = $user->resume;

        // If the user has no resume, redirect them.
        if (!$resume) {
            return redirect()->route('applicant.resume.builder')->with('error', 'No resume data found. Please build your resume first.');
        }

        $generatedSummary = $resume->summary ?? "No AI summary generated yet.";
        $skillsList = [];
        if (!empty($resume->skills)) {
            $skillsList = array_filter(array_map('trim', explode(',', $resume->skills)));
        }

        $notifications = $user->notifications;
        $unreadNotifications = $user->unreadNotifications;

        $response = response()->view('users.applicant.resume-view', [
            'user' => $user, // Now the user object has the resume relationship loaded
            'resume' => $resume,
            'generatedSummary' => $generatedSummary,
            'skillsList' => $skillsList,
            'notifications' => $notifications,
            'unreadNotifications' => $unreadNotifications,
        ]);

        $response->header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate');
        $response->header('Pragma', 'no-cache');
        $response->header('Expires', 'Fri, 01 Jan 1990 00:00:00 GMT');

        return $response;
    }
    // In app/Http/Controllers/ResumeViewAndDownloadController.php

    public function download()
    {
        $user = Auth::guard('applicant')->user();

        // Eager load the resume directly onto the user object. THIS IS THE FIX.
        $user->load('resume.experiences', 'resume.educations');

        $resume = $user->resume;

        // Add a safety check in case there is no resume
        if (!$resume) {
            // You can't redirect from a download, so it's better to show an error or an empty PDF.
            // For now, we'll abort, but you could create a specific error view.
            abort(404, 'Resume not found.');
        }

        $generatedSummary = $resume->summary ?? "No AI summary generated yet.";
        $skillsList = [];
        if (!empty($resume->skills)) {
            $skillsList = array_filter(array_map('trim', explode(',', $resume->skills)));
        }

        // Pass all the data AND our new flag to the view
        $data = [
            'user' => $user, // The user object NOW has the resume relationship loaded.
            'resume' => $resume,
            'generatedSummary' => $generatedSummary,
            'skillsList' => $skillsList,
            'is_pdf' => true, // This flag correctly controls the layout in the view
        ];

        $pdf = Pdf::loadView('users.applicant.resume-view', $data);
        return $pdf->download('resume.pdf');
    }


    public function create() {}

    public function store(Request $request) {}

    public function show(string $id) {}

    public function edit(string $id) {}

    public function update(Request $request, string $id) {}

    public function destroy(string $id) {}
}
