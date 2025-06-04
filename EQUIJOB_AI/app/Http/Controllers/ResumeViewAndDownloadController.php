<?php

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

        $response = response()->view('users.applicant.resume-view', [
            'user' => $user,
            'resume' => $resume,
            'generatedSummary' => $generatedSummary,
            'skillsList' => $skillsList,
        ]);

        $response->header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate');
        $response->header('Pragma', 'no-cache');
        $response->header('Expires', 'Fri, 01 Jan 1990 00:00:00 GMT');

        return $response;
    }

    public function download(){
        $user = Auth::guard('applicant')->user(); 
        $resume = Resume::with(['experiences', 'educations'])->where('user_id', $user->id)->first();
        $generatedSummary = $resume->summary ?? "No AI summary generated yet.";
        $skillsList = [];
        if (!empty($resume->skills)) {
            // Always treat as comma-separated string
            $skillsList = array_filter(array_map('trim', explode(',', $resume->skills)));
        $pdf = Pdf::loadView('users.applicant.resume-view', [
            'user' => $user,
            'resume' => $resume,
            'generatedSummary' => $generatedSummary,
            'skillsList' => $skillsList,
        ]);
        return $pdf->download('resume.pdf');
        }
    }
    
    
    public function create() { }

    public function store(Request $request) { }

    public function show(string $id) { }

    public function edit(string $id) { }

    public function update(Request $request, string $id) { }

    public function destroy(string $id) { }
}
