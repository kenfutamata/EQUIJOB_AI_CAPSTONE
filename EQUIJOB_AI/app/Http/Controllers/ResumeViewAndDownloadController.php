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
        $resume = Resume::with(['experiences', 'educations'])->where('user_id', $user->id)->first();
        $generatedSummary = $resume->summary ?? "No AI summary generated yet.";

        $skillsList = [];

        if (!empty($resume->skills)) {
            // Always treat as comma-separated string
            $skillsList = array_filter(array_map('trim', explode(',', $resume->skills)));
        }
        $notifications = $user->notifications;
        $unreadNotifications = $user->unreadNotifications;
        $response = response()->view('users.applicant.resume-view', [
            'user' => $user,
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

    public function download()
    {
    $user = Auth::guard('applicant')->user(); 
    $resume = Resume::with(['experiences', 'educations'])->where('user_id', $user->id)->first();
    $generatedSummary = $resume->summary ?? "No AI summary generated yet.";
    $skillsList = [];
    if (!empty($resume->skills)) {
        $skillsList = array_filter(array_map('trim', explode(',', $resume->skills)));
    }

    // Pass all the data AND our new flag to the view
    $data = [
        'user' => $user,
        'resume' => $resume,
        'generatedSummary' => $generatedSummary,
        'skillsList' => $skillsList,
        'is_pdf' => true, // <-- This flag controls the layout in the view
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
