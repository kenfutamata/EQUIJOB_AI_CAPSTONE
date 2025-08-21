<?php

namespace App\Console\Commands;

use App\Mail\SendInterviewDetailsJobApplicantMail;
use App\Mail\sendInterviewDetailsJobProviderMail;
use App\Models\JobApplication;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendInterviewReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-interview-reminders';
    protected $description = 'Find interviews for tomorrow and semd email reminders to job applicants and job providers.';

    /**
     * The console command description.
     *
     * @var string
     */

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting to send interview reminders...');

        $applications = JobApplication::query()
            ->with(['applicant', 'jobPosting.jobProvider']) // Eager load all needed relationships
            ->whereNotNull('interviewDate')
            ->whereNull('reminderSentAt')
            ->whereDate('interviewDate', now()->addDay()->toDateString())
            ->get();

        if ($applications->isEmpty()) {
            $this->info('No interviews schedules for tomorrow. Done');
            return 0;
        }

        $this->info("Found {$applications->count()} application(s) with interviews tomorrow");

        foreach ($applications as $application) {
            try {
                $applicantEmail = $application->applicant?->email;

                $jobProviderEmail = $application->jobPosting?->jobProvider?->email;

                if ($applicantEmail) {
                    Mail::to($applicantEmail)->send(new SendInterviewDetailsJobApplicantMail($application));
                    $this->info("Reminder sent to applicant: {$applicantEmail}");
                }

                if ($jobProviderEmail) {
                    // CORRECTED: Class name should be PascalCase
                    Mail::to($jobProviderEmail)->send(new SendInterviewDetailsJobProviderMail($application));
                    $this->info("Reminder sent to provider: {$jobProviderEmail}");
                }

                $application->update(['reminderSentAt' => now()]);
            } catch (\Exception $e) {
                $this->error("Interview Reminder failed for application #{$application->id}: " . $e->getMessage());
                Log::error("Interview Reminder failed for application #{$application->id}: " . $e->getMessage());
            }
        }
        $this->info('Finished Sending Interview Reminders');
        return 0;
    }
}
