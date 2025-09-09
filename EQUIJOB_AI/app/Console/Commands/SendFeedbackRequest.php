<?php

namespace App\Console\Commands;

use App\Models\Feedbacks;
use App\Models\JobApplication;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SendFeedbackRequest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'feedback:send-requests';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scan for hired applicants and send them a feedback request email after one month';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Scanning for all "Hired" applications...');

        try {

            $hiredApplications = JobApplication::where('status', 'Hired')->get();

            if ($hiredApplications->isEmpty()) {
                $this->info('No applications with "Hired" status found.');
                return;
            }

            $this->info("Found {$hiredApplications->count()} total 'Hired' application(s). Now checking dates...");
            $oneMonthAgo = today()->subMonth();
            $processedCount = 0;

            foreach ($hiredApplications as $application) {

                if (!$application->updated_at || !($application->updated_at instanceof Carbon)) {
                    $this->warn("-> Skipping application #{$application->id}: 'updated_at' is missing or not a valid date.");
                    continue;
                }

                if ($application->updated_at->startOfDay()->lte($oneMonthAgo)) {

                    $feedbackExists = Feedbacks::where('jobApplicationID', $application->id)->exists();
                    $applicant = $application->applicant;

                    if (!$feedbackExists && $applicant) {
                        $this->info("--> Processing application #{$application->id} for applicant: {$applicant->email} (Hired on: " . $application->updated_at->toDateString() . ")");

                        $feedback = Feedbacks::create([
                            'jobPostingID'     => $application->jobPostingID,
                            'applicantID'      => $application->applicantID,
                            'firstName'        => $applicant->first_name,
                            'lastName'         => $applicant->last_name,
                            'email'            => $applicant->email,
                            'phoneNumber'      => $applicant->phone_number,
                            'feedbackType'     => 'Job Rating',
                            'status'           => 'Sent',
                        ]);
                        $maildata = [
                            'firstName'        => $applicant->first_name,
                            'lastName'         => $applicant->last_name,
                            'email'            => $applicant->email,
                            'jobProviderFirstName' => $application->jobPosting->jobProvider->first_name,
                            'jobProviderLastName' => $application->jobPosting->jobProvider->last_name,
                            'companyName'      => $application->jobPosting->jobProvider->company_name,
                            'position'         => $application->jobPosting->position,
                        ];
                        Mail::to($applicant->email)->send(new \App\Mail\NotifyApplicantFeedbackSent($maildata));

                        $this->info("---> Email sent and pending feedback #{$feedback->id} created.");
                        $processedCount++;
                    } else if ($feedbackExists) {
                        $this->warn("-> Skipping application #{$application->id}: Feedback already exists.");
                    }
                }
            }

            if ($processedCount > 0) {
                $this->info("Successfully processed {$processedCount} feedback request(s).");
            } else {
                $this->info("No applications were old enough to require a feedback request today.");
            }
        } catch (\Exception $e) {
            Log::error('Error in feedback:send-request command: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            $this->error("An error occurred. Check the log for details.");
        }
    }
}
