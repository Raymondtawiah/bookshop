<?php

namespace App\Mail;

use App\Models\WebinarRegistration;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WebinarPaymentPending extends Mailable
{
    use Queueable, SerializesModels;

    public $registration;

    public $paymentLink;

    public $reminderDate;

    public function __construct(WebinarRegistration $registration, string $paymentLink, ?string $reminderDate = null)
    {
        $this->registration = $registration;
        $this->paymentLink = $paymentLink;
        $this->reminderDate = $reminderDate;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Payment Pending - Complete Your Webinar Registration',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.webinar-payment-pending',
            with: [
                'registration' => $this->registration,
                'paymentLink' => $this->paymentLink,
                'reminderDate' => $this->reminderDate,
            ],
        );
    }
}
