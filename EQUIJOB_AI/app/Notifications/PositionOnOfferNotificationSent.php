<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PositionOnOfferNotificationSent extends Notification
{
    use Queueable;
    public $application; 
    public $recipientType;
    /**
     * Create a new notification instance.
     */
    public function __construct($application, $recipientType)
    {
        $this->application = $application;
        $this->recipientType = $recipientType;
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
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray($notifiable): array
    {
        $position = $this->application->jobPosting->position;
        $companyName = $this->application->jobPosting->companyName;
        $message = "The application for the position: {$position} for the company {$companyName} Has been On-Offer. Please check your email and your manage job application history.";

        return [
            'message' => $message,
            'application_id' => $this->application->id,
            'url' => route('applicant-job-applications'),
        ];
    }
}
