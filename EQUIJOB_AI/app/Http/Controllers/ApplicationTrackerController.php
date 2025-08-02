<?php

namespace App\Http\Controllers;

use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicationTrackerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::guard('applicant')->user();
        $notification = $user->notifications;
        $unreadNotifications = $user->unreadNotifications;
        $response = response()->view('users.applicant.applicant_tracker', compact('user', 'notification', 'unreadNotifications'));
        $response->header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate');
        $response->header('Pragma', 'no-cache');
        $response->header('Expires', 'Fri, 01 Jan 1990 00:00:00 GMT');
        return $response;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $user = Auth::guard('applicant')->user();
        $notification = $user->notifications;
        $unreadNotifications = $user->unreadNotifications;

        $request->validate([
            'jobApplicationNumber' => 'required|string|max:255',
        ]);

        try {
            $jobApplicationNumber = $request->input('jobApplicationNumber');
            $application = JobApplication::where('applicantID', $user->id)
                ->where('jobApplicationNumber', $jobApplicationNumber)
                ->first();

            if (!$application) {
                return back()->with('error', 'No application found with the provided ID.');
            }
            $applicationStatus = $application->status;
            return view('users.applicant.application_status', compact('user', 'application', 'applicationStatus', 'notification', 'unreadNotifications'));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("APPLICATION_TRACKER_ERROR: " . $e->getMessage());
            return back()->with('error', 'An unexpected error occurred while processing your request.');
        }
    
        catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("APPLICATION_TRACKER_ERROR: " . $e->getMessage());
            return back()->with('error', 'An unexpected error occurred while processing your request.');
        }
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
