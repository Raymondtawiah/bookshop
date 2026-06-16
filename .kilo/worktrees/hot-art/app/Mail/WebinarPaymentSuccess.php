<?php

namespace App\Mail;

use App\Models\WebinarRegistration;
use App\Models\WebinarSession;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WebinarPaymentSuccess extends Mailable
{
    use Queueable, SerializesModels;

    public $registration;

    public $webinar;

    public $webinarLink;

    public $accessLink;

    public function __construct(WebinarRegistration $registration, WebinarSession $webinar, ?string $webinarLink = null, ?string $accessLink = null)
    {
        $this->registration = $registration;
        $this->webinar = $webinar;
        $this->webinarLink = $webinarLink;
        $this->accessLink = $accessLink;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Webinar Registration Confirmed - '.$this->webinar->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.webinar-payment-success',
            with: [
                'registration' => $this->registration,
                'webinar' => $this->webinar,
                'webinarLink' => $this->webinarLink,
                'accessLink' => $this->accessLink,
            ],
        );
    }
}
