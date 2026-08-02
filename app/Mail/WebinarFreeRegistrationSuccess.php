<?php

namespace App\Mail;

use App\Models\WebinarRegistration;
use App\Models\WebinarSession;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WebinarFreeRegistrationSuccess extends Mailable
{
    use Queueable, SerializesModels;

    public $registration;

    public $webinar;

    public $webinarLink;

    public $accessLink;

    public $customMessage;

    public function __construct(WebinarRegistration $registration, WebinarSession $webinar, ?string $webinarLink = null, ?string $accessLink = null)
    {
        $this->registration = $registration;
        $this->webinar = $webinar;
        $this->webinarLink = $webinarLink ?? $webinar->webinar_link;
        $this->accessLink = $accessLink;
        $this->customMessage = $webinar->custom_email_message;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'You\'re Registered - '.$this->webinar->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.webinar-free-registration-success',
            with: [
                'registration' => $this->registration,
                'webinar' => $this->webinar,
                'webinarLink' => $this->webinarLink,
                'accessLink' => $this->accessLink,
                'customMessage' => $this->customMessage,
            ],
        );
    }
}
