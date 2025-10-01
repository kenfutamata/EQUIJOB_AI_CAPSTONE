<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TestBrevoMail extends Mailable
{
    use Queueable, SerializesModels;

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Test Email from Brevo API',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        // Specify both the HTML view and the plain-text view
        return new Content(
            view: 'users.test',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}