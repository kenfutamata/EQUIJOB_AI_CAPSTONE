<?php

namespace App\Console\Commands;

use App\Models\Feedbacks;
use App\Models\JobApplication;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendFeedbackDetailsNow extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'feedback:send-now {application : The ID of the job application to process}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Instantly sends a feedback request for a specific hired application, bypassing the one-month delay.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $applicationId = $this->argument('application');

        try {
            $application = JobApplication::findOrFail($applicationId);


            if ($application->status !== 'Hired') {
                $this->error("Error: Application #{$applicationId} has a status of '{$application->status}', not 'Hired'.");
                return 1;
            }

            $feedbackExists = Feedbacks::where('applicantID', $application->id)->exists();
            if ($feedbackExists) {
                $this->warn("Warning: Feedback already exists for Application #{$applicationId}. No action taken.");
                return 1;
            }

            $applicant = $application->applicant;
            if (!$applicant) {
                $this->error("Error: Could not find the applicant associated with Application #{$applicationId}.");
                return 1;
            }


            $this->info("Processing Application #{$applicationId} for applicant: {$applicant->email}...");

            $feedback = Feedbacks::create([
                'jobApplicationID' => $application->id,
                'jobPostingID'     => $application->jobPostingID,
                'applicantID'      => $application->applicantID,
                'firstName'        => $applicant->firstName,
                'lastName'         => $applicant->lastName,
                'email'            => $applicant->email,
                'phoneNumber'      => $applicant->phoneNumber,
                'feedbackType'     => 'Job Rating',
                'status'           => 'Sent',
            ]);

            $maildata = [
                'firstName'        => $applicant->firstName,
                'lastName'         => $applicant->lastName,
                'email'            => $applicant->email,
                'jobProviderFirstName' => $application->jobPosting->jobProvider->firstName,
                'jobProviderLastName' => $application->jobPosting->jobProvider->lastName,
                'companyName'      => $application->jobPosting->jobProvider->companyName,
                'position'         => $application->jobPosting->position,
            ];

            Mail::to($applicant->email)->send(new \App\Mail\NotifyApplicantFeedbackSent($maildata));

            $this->info("-> Email sent successfully.");
            $this->info("-> Pending feedback record #{$feedback->id} created in the database.");
            $this->info("Done.");

            return 0;
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            $this->error("Error: Job Application with ID [{$applicationId}] not found.");
            return 1;
        } catch (\Exception $e) {
            Log::error('Error in feedback:send-now command: ' . $e->getMessage());
            $this->error("An unexpected error occurred. Check the log for details.");
            return 1;
        }
    }
}
