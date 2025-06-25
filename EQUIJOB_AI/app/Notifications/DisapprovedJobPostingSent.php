<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DisapprovedJobPostingSent extends Notification
{
    use Queueable;
    private $jobPosting;
    private $recipientType;
    
    /**
     * Create a new notification instance.
     */
    public function __construct($jobPosting, $recipientType = 'job_provider')
    {
        $this->jobPosting = $jobPosting;
        $this->recipientType = $recipientType;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable): array
    {
        return ['Database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toDatabase($notifiable)
    {
          return [
            'message'=> 'Your job posting for ' . $this->jobPosting->position . ' has been Disapproved. Please Refer to the Remarks for more details.',
            'job_posting_id' => $this->jobPosting->id,
            'job_posting_position' => $this->jobPosting->position,
            'url' => route('Admin-job-posting-show', $this->jobPosting->id),
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
