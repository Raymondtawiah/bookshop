<?php

namespace App\Mail;

use App\Models\WebinarSession;
use App\Models\WebinarNotification;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WebinarNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public WebinarSession $webinar,
        public WebinarNotification $notification,
        public ?User $user = null
    ) {}

    public function envelope(): Envelope
    {
        $subject = $this->notification->title . ' - ' . $this->webinar->title;
        
        return new Envelope(
            subject: $subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.webinar-notification',
            with: [
                'webinar' => $this->webinar,
                'notification' => $this->notification,
                'user' => $this->user,
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
