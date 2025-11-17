<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class JobApplicationWithdrawnSent extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */

    public $jobApplication;
    public $receipientType;
    public function __construct($jobApplication, $receipientType = 'job_provider')
    {
        $this->jobApplication = $jobApplication;
        $this->receipientType = $receipientType;
    }


    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */

    public function toDatabase($notifiable): array{

        $this->jobApplication->load('jobPosting');
        
        $message = 'The application for the position of ' . $this->jobApplication->jobPosting->position . ' has been withdrawn by the applicant.';

        return [
            'message' => $message,
            'job_application_id' => $this->jobApplication->id,
            'job_posting_position' => $this->jobApplication->jobPosting->position, 
            'url' => route('job-provider-manage-job-applications'),
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
