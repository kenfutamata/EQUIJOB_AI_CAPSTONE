<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class JobPostingNotificationSent extends Notification
{
    use Queueable;

    public $jobPosting;
    public $recipientType;

    public function __construct($jobPosting, $recipientType = 'job_provider')
    {
        $this->jobPosting = $jobPosting;
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
                'message' => 'A new job posting for ' . $this->jobPosting->position . ' has been submitted.',
                'job_posting_id' => $this->jobPosting->id,
                'job_posting_position' => $this->jobPosting->position,
                'url' => url('job-provider-job-posting-show' . $this->jobPosting->id),
            ];
        }
        return [
            'message' => 'A new job posting has been submitted.',
            'job_posting_id' => $this->jobPosting->id,
            'job_posting_position' => $this->jobPosting->position,
            'url' => url('job-provider-job-posting-show' . $this->jobPosting->id),
        ];
    }

    public function toArray(object $notifiable): array
    {
        return [];
    }
}
