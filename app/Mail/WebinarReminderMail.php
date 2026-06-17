<?php

namespace App\Mail;

use App\Models\WebinarRegistration;
use App\Models\WebinarSession;
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
        public string $reminderType,
        public ?string $accessLink = null,
        public ?string $customMessage = null,
        public ?string $reminderDateTime = null
    ) {}

    public function envelope(): Envelope
    {
        $webinarDate = $this->webinar->scheduled_at ? $this->webinar->scheduled_at->format('M j') : null;

        $subject = match ($this->reminderType) {
            '24_hours' => $webinarDate
                ? ($this->webinar->scheduled_at->isToday()
                    ? 'Reminder: Webinar Today - '.$this->webinar->title
                    : 'Reminder: Webinar Tomorrow - '.$this->webinar->title)
                : 'Reminder: Webinar - '.$this->webinar->title,
            '1_hour' => 'Starting Soon: Webinar in 1 Hour - '.$this->webinar->title,
            '15_minutes' => 'URGENT: Webinar Starting in 15 Minutes - '.$this->webinar->title,
            'post_webinar' => 'Thank You for Attending - '.$this->webinar->title,
            default => 'Webinar Reminder - '.$this->webinar->title
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
                'accessLink' => $this->accessLink,
                'customMessage' => $this->customMessage,
                'reminderDateTime' => $this->reminderDateTime,
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
