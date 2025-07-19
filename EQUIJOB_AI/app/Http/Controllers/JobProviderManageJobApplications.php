<?php

namespace App\Http\Controllers;

use App\Mail\InterviewDetailsSent;
use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Google_Client;
use Carbon\Carbon;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;
use Google_Service_Calendar_ConferenceData;
use Google_Service_Calendar_CreateConferenceRequest;
use Google_Service_Meet;
use Google_Service_Meet_Space;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class JobProviderManageJobApplications extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::guard('job_provider')->user();
        $search = $request->input('search');

        $applicationsQuery = JobApplication::with(['jobPosting', 'applicant'])
            ->whereHas('jobPosting', function ($query) use ($user) {
                $query->where('jobProviderID', $user->id);
            });

        if ($search) {
            $searchTerm = '%' . $search . '%';

            $applicationsQuery->where(function ($q) use ($searchTerm) {
                $q->where('jobApplicationNumber', 'like', $searchTerm)
                    ->orWhere('status', 'like', $searchTerm)
                    ->orWhereHas('jobPosting', function ($q2) use ($searchTerm) {
                        $q2->where('position', 'like', $searchTerm)
                            ->orWhere('companyName', 'like', $searchTerm)
                            ->orWhere('disabilityType', 'like', $searchTerm);
                    })
                    ->orWhereHas('applicant', function ($q3) use ($searchTerm) {
                        $q3->where('first_name', 'like', $searchTerm)
                            ->orWhere('last_name', 'like', $searchTerm)
                            ->orWhere('phone_number', 'like', $searchTerm);
                    });
            });
        }

        $applications = $applicationsQuery->latest()->paginate(10)->withQueryString();
        $notifications = $user->notifications ?? collect();
        $unreadNotifications = $user->unreadNotifications ?? collect();

        return response()
            ->view('users.job-provider.job_provider_job_applications', compact(
                'user',
                'applications',
                'notifications',
                'unreadNotifications',
                'search'
            ))
            ->header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Fri, 01 Jan 1990 00:00:00 GMT');
    }

    public function generateMeetLink(Request $request): JsonResponse
    {
        $masterRefreshToken = env('GOOGLE_MASTER_REFRESH_TOKEN');

        if (!$masterRefreshToken) {
            return response()->json(['error' => 'Application is not configured for meeting creation.'], 500);
        }

        $client = new Google_Client();
        $client->setClientId(env('GOOGLE_CLIENT_ID'));
        $client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
        $client->setAccessType('offline');

        try {
            $accessToken = $client->fetchAccessTokenWithRefreshToken($masterRefreshToken);

            if (isset($accessToken['error'])) {
                return response()->json(['error' => 'Authentication service failed.'], 500);
            }

            // Set the newly fetched access token on the client.
            $client->setAccessToken($accessToken);

            // Make the API call to create the meeting.
            $meetService = new Google_Service_Meet($client);
            $space = new Google_Service_Meet_Space();
            $createdSpace = $meetService->spaces->create($space);

            // Success: Return the link.
            return response()->json(['meetLink' => $createdSpace->getMeetingUri()]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create meeting link: ' . $e->getMessage()], 500);
        }
    }

    public function scheduleInterview(Request $request, JobApplication $application)
    {
        $validated = $request->validate([
            'interviewDate' => 'required|date',
            'interviewTime' => 'required',
            'interviewLink' => 'required|url',
        ]);

        try {
            $applicant = $application->applicant;
            $jobPosting = $application->jobPosting;
            $application->status = 'For Interview';
            $application->interviewDate = $validated['interviewDate'];
            $application->interviewTime = $validated['interviewTime'];
            $application->interviewLink = $validated['interviewLink'];
            $application->save();
            $maildata = [
                'firstName' => $applicant->first_name,
                'lastName' => $applicant->last_name,
                'position' => $jobPosting->position,
                'companyName' => $jobPosting->companyName,
                'interviewDate' => $application->interviewDate->foramt('F j, y'),
                'interviewTime' => $application->interviewTime->foramt('g:i A'),
                'interviewLink' => $application->interviewLink,
            ];

            Mail::to($applicant->email)->send(new InterviewDetailsSent($maildata, 'applicant'));

            return redirect()->route('job-provider-manage-job-applications')->with('Success', 'Successfully Scheduled Interview');
        } catch (\Exception $e) {
            Log::error('Failed to schedule interview for application ' . $application->id . ': ' . $e->getMessage());
            return redirect()->route('job-provider-manage-job-applications')->with('error', 'Failed to Schedule Interview');
        }
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
    public function show(string $id)
    {
        //
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
