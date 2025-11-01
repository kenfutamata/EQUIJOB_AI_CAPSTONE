<?php

namespace App\Console\Commands;

use App\Mail\SendInterviewDetailsJobApplicantMail;
use App\Mail\sendInterviewDetailsJobProviderMail;
use App\Models\JobApplication;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendInterviewDetailsNow extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-interview-details {application : The ID of the job application}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends the interview details for a specific job application to the applicant and provider immediately.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $applicationId = $this->argument('application');

        $this->info("Attempting to send interview details for Application ID: {$applicationId}");

        try {
            $application = JobApplication::with(['applicant', 'jobPosting.jobProvider'])->findOrFail($applicationId);

            if (is_null($application->interviewDate)) {
                $this->error("No interview date is set for Application #{$application->id}. Aborting.");
                return 1;
            }

            $applicant = $application->applicant;
            $jobProvider = $application->jobPosting?->jobProvider;

            $sentTo = [];

            if ($applicant && $applicant->email) {
                Mail::to($applicant)->send(new SendInterviewDetailsJobApplicantMail($application));
                $this->info("-> Details sent successfully to applicant: {$applicant->name} ({$applicant->email})");
                $sentTo[] = 'applicant';
            } else {
                $this->warn("-> Could not send to applicant. Applicant data or email missing.");
            }

            if ($jobProvider && $jobProvider->email) {
                Mail::to($jobProvider)->send(new SendInterviewDetailsJobProviderMail($application));
                $this->info("-> Details sent successfully to provider: {$jobProvider->name} ({$jobProvider->email})");
                $sentTo[] = 'provider';
            } else {
                $this->warn("-> Could not send to provider. Provider data or email missing.");
            }

            if (!empty($sentTo)) {
                $application->update(['reminderSentAt' => now()]);
                $this->info("Application #{$application->id} marked as sent.");
            } else {
                 $this->error("Failed to send any emails for Application #{$application->id}.");
                 return 1;
            }

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            $this->error("Job Application with ID #{$applicationId} not found.");
            return 1;
        } catch (\Exception $e) {
            $this->error("An unexpected error occurred for application #{$applicationId}: " . $e->getMessage());
            Log::error("Failed to send interview details for application #{$applicationId}: " . $e->getMessage(), ['exception' => $e]);
            return 1;
        }

        $this->info('✅  Finished sending interview details.');
        return 0; 
    }
}