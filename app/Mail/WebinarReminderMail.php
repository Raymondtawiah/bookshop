<?php

namespace App\Mail;

use App\Models\WebinarSession;
use App\Models\WebinarRegistration;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WebinarReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public WebinarSession $webinar,
        public WebinarRegistration $registration,
        public string $reminderType
    ) {}

    public function envelope(): Envelope
    {
        $subject = match($this->reminderType) {
            '24_hours' => 'Reminder: Webinar Tomorrow - ' . $this->webinar->title,
            '1_hour' => 'Starting Soon: Webinar in 1 Hour - ' . $this->webinar->title,
            '15_minutes' => 'URGENT: Webinar Starting in 15 Minutes - ' . $this->webinar->title,
            'post_webinar' => 'Thank You for Attending - ' . $this->webinar->title,
            default => 'Webinar Reminder - ' . $this->webinar->title
        };
        
        return new Envelope(
            subject: $subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.webinar-reminder',
            with: [
                'webinar' => $this->webinar,
                'registration' => $this->registration,
                'reminderType' => $this->reminderType,
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
