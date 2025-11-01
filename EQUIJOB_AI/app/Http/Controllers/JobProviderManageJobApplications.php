<?php

namespace App\Http\Controllers;

// Use specific Form Requests for validation

use App\Exports\JobProviderJobApplicationsDataExport;
use App\Exports\JobProviderJobPostingDataExport;
use App\Http\Requests\RejectApplicationRequest;
use App\Http\Requests\ScheduleInterviewRequest;
// Consolidate Mail and Notification usage
use App\Mail\disapprovalDetailssent;
use App\Mail\InterviewDetailsSent;
use App\Mail\SendOnOfferDetails;
use App\Models\JobApplication;
use App\Notifications\JobInterviewDetailsSent;
use App\Notifications\PositionOnOfferNotificationSent;
use App\Services\GoogleMeetService; // Example of a dedicated service
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Maatwebsite\Excel\Facades\Excel;

class JobProviderManageJobApplications extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $user = Auth::guard('job_provider')->user();
        $search = $request->input('search');
        $jobApplicationTable = (new JobApplication())->getTable();
        $applicantTable = 'users';
        $jobPostingTable = 'jobPosting';

        $applicationsQuery = JobApplication::query()
            ->join($applicantTable, "{$jobApplicationTable}.applicantID", '=', "{$applicantTable}.id")
            ->join($jobPostingTable, "{$jobApplicationTable}.jobPostingID", '=', "{$jobPostingTable}.id")
            ->where("{$jobPostingTable}.jobProviderID", $user->id)
            ->with(['applicant', 'jobPosting']);

        if ($search) {
            $searchTerm = '%' . $search . '%';
            $applicationsQuery->where(function ($q) use ($searchTerm, $jobApplicationTable) {
                $q->where("{$jobApplicationTable}.jobApplicationNumber", 'like', $searchTerm)
                    ->orWhere("{$jobApplicationTable}.status", 'like', $searchTerm)
                    ->orWhereHas('jobPosting', function ($q2) use ($searchTerm) {
                        $q2->where('position', 'like', $searchTerm)
                            ->orWhere('companyName', 'like', $searchTerm)
                            ->orWhere('disabilityType', 'like', $searchTerm);
                    })
                    ->orWhereHas('applicant', function ($q3) use ($searchTerm) {
                        $q3->where('firstName', 'like', $searchTerm)
                            ->orWhere('lastName', 'like', $searchTerm)
                            ->orWhere('phoneNumber', 'like', $searchTerm)
                            ->orWhere('gender', 'like', $searchTerm)
                            ->orWhere('address', 'like', $searchTerm)
                            ->orWhere('disabilityType', 'like', $searchTerm);
                    });
            });
        }

        $sortable = [
            'jobApplicationNumber' => "{$jobApplicationTable}.jobApplicationNumber",
            'firstName' => "{$applicantTable}.firstName",
            'lastName' => "{$applicantTable}.lastName",
            'phoneNumber' => "{$applicantTable}.phoneNumber",
            'gender' => "{$applicantTable}.sex",
            'address' => "{$applicantTable}.address",
            'emailAddress' => "{$applicantTable}.emailAddress",
            'disabilityType' => "{$applicantTable}.disabilityType",
            'position' => "{$jobPostingTable}.position",
            'companyName' => "{$jobPostingTable}.companyName",
            'status' => "{$jobApplicationTable}.status"
        ];

        if ($request->has('sort')) {
            $sort = $sortable[$request->input('sort')] ?? "{$jobApplicationTable}.created_at";
            $direction = $request->direction === 'desc' ? 'desc' : 'asc';
        } else {
            $sort = "{$jobApplicationTable}.created_at";
            $direction = 'desc';
        }

        $applications = $applicationsQuery
            ->select("{$jobApplicationTable}.*")
            ->orderBy($sort, $direction)
            ->paginate(10);

        return view('users.job-provider.job_provider_job_applications', [
            'user' => $user,
            'applications' => $applications,
            'notifications' => $user->notifications ?? collect(),
            'unreadNotifications' => $user->unreadNotifications ?? collect(),
            'search' => $search
        ]);
    }

    /**
     * Generate a Google Meet link using a dedicated service.
     */
    public function generateMeetLink(GoogleMeetService $meetService): JsonResponse
    {
        try {
            $meetLink = $meetService->createMeeting();
            return response()->json(['meetLink' => $meetLink]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create meeting link: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Schedule an interview for a job application.
     */
    public function scheduleInterview(ScheduleInterviewRequest $request, JobApplication $application): RedirectResponse
    {
        $validated = $request->validated();
        $jobProvider = Auth::guard('job_provider')->user();

        try {
            $duplicate = JobApplication::where('id', '!=', $application->id)
                ->where('applicantID', $application->applicantID)
                ->where('interviewDate', $validated['interviewDate'])
                ->where('interviewTime', $validated['interviewTime'])
                ->first();

            if ($duplicate) {
                return redirect()->back()->with('error', 'The Date and time has been occupied by another existing interview. please try again.');
            }
            $application->update([
                'status' => 'For Interview',
                'interviewDate' => $validated['interviewDate'],
                'interviewTime' => $validated['interviewTime'],
                'interviewLink' => $validated['interviewLink'],
            ]);

            $applicant = $application->applicant;
            $jobPosting = $application->jobPosting;
            $mailData = [
                'firstName' => $applicant->firstName,
                'lastName' => $applicant->lastName,
                'position' => $jobPosting->position,
                'companyName' => $jobPosting->companyName,
                'interviewDate' => \Carbon\Carbon::parse($validated['interviewDate'])->format('F j, Y'),
                'interviewTime' => \Carbon\Carbon::parse($validated['interviewTime'])->format('g:i A'),
                'interviewLink' => $validated['interviewLink'],
                'jobProviderFirstName' => $jobProvider->firstName,
                'jobProviderLastName' => $jobProvider->lastName,
            ];
            Mail::to($applicant)->send(new InterviewDetailsSent($mailData, 'applicant'));
            $applicant->notify(new JobInterviewDetailsSent($application, 'applicant'));

            return redirect()->route('job-provider-manage-job-applications')->with('Success', 'Successfully Scheduled Interview');
        } catch (\Exception $e) {
            Log::error('Failed to schedule interview for application ' . $application->id . ': ' . $e->getMessage());
            return redirect()->route('job-provider-manage-job-applications')->with('error', 'Failed to Schedule Interview');
        }
    }

    /**
     * Update an application status to 'On-Offer'.
     */
    public function updateApplicationToOffer(JobApplication $application): RedirectResponse
    {
        try {
            $application->update(['status' => 'On-Offer']);

            $applicant = $application->applicant;
            $jobPosting = $application->jobPosting;
            $jobProvider = Auth::guard('job_provider')->user();

            $mailData = [
                'firstName' => $applicant->firstName,
                'lastName' => $applicant->lastName,
                'companyName' => $jobPosting->companyName,
                'position' => $jobPosting->position,
                'jobProviderFirstName' => $jobProvider->firstName,
                'jobProviderLastName' => $jobProvider->lastName,
            ];

            Mail::to($applicant)->send(new SendOnOfferDetails($mailData));
            $applicant->notify(new PositionOnOfferNotificationSent($application, 'applicant'));

            return redirect()->route('job-provider-manage-job-applications')->with('Success', 'Application and Position is on-offer');
        } catch (\Exception $e) {
            Log::error('Failed to update application ' . $application->id . ': ' . $e->getMessage());
            return redirect()->route('job-provider-manage-job-applications')->with('error', 'Failed to Update Application');
        }
    }

    /**
     * Reject a job application.
     */
    public function rejectApplication(Request $request, string $id)
    {
        $request->validate([
            'remarks' => 'required|string|max:255',
        ]);

        try {
            $application = JobApplication::findOrFail($id);
            $application->status = 'Rejected';
            $application->remarks = $request->input('remarks');
            $application->save();
            $applicant = $application->applicant;
            $jobPosting = $application->jobPosting;
            $jobProvider = Auth::guard('job_provider')->user();
            $maildata = [
                'firstName' => $applicant->firstName,
                'lastName' => $applicant->lastName,
                'position' => $jobPosting->position,
                'companyName' => $jobPosting->companyName,
                'jobProviderFirstName' => $jobProvider->firstName,
                'jobProviderLastName' => $jobProvider->lastName,
                'remarks' => $application->remarks,
            ];
            Mail::to($applicant)->send(new disapprovalDetailssent($maildata));
            return redirect()->route('job-provider-manage-job-applications')->with('Success', 'Application Rejected Successfully');
        } catch (\Exception $e) {
            Log::error('Failed to reject application ' . $id . ': ' . $e->getMessage());
            return redirect()->route('job-provider-manage-job-applications')->with('error', 'Failed to Reject Application');
        }
    }

    /**
     * Remove the specified resource from storage.
     */

    public function export(Request $request)
    {
        $user = Auth::guard('job_provider')->user();

        return Excel::download(
            new JobProviderJobApplicationsDataExport(
                $request->search,
                $request->sort,
                $request->direction
            ),
            "Job Provider Job Applications {$user->companyName}.xlsx"
        );
    }
}
