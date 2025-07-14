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
        if ($this->recipientType === 'Admin') {
            return [
                'message' => 'A new job application for ' . $this->jobApplication->position . ' has been submitted.',
                'job_posting_id' => $this->jobApplication->id,
                'job_posting_position' => $this->jobApplication->position,
                'url' => url('job-provider-job-posting-show' . $this->jobApplication->id),
            ];
        }
        return [
            'message' => 'A new job posting has been submitted.',
            'job_posting_id' => $this->jobApplication->id,
            'job_posting_position' => $this->jobApplication->position,
            'url' => url('job-provider-job-posting-show' . $this->jobApplication->id),
        ];
    }

    public function toArray(object $notifiable): array
    {
        return [];
    }
}
