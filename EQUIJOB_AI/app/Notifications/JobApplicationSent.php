<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class JobApplicationSent extends Notification
{
    use Queueable;

    public $jobApplication;
    public $recipientType;

    public function __construct($jobApplication, $recipientType = 'job_provider')
    {
        $this->jobApplication = $jobApplication;
        $this->recipientType = $recipientType;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        $this->jobApplication->load('jobPosting');

        $message = 'A new application has been submitted for the position of ' . $this->jobApplication->jobPosting->position . '.';

        // Admin check logic can be added here if needed.

        return [
            'message' => $message,
            'job_application_id' => $this->jobApplication->id,
            'job_posting_position' => $this->jobApplication->jobPosting->position, 
            'url' => route('job-provider-manage-job-applications'),
        ];
    }

    public function toArray(object $notifiable): array
    {
        return [];
    }
}
