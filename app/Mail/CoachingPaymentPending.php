<?php

namespace App\Mail;

use App\Models\CoachingBooking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CoachingPaymentPending extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;

    public $paymentLink;

    public $deadlineDatetime;

    public function __construct(CoachingBooking $booking, string $paymentLink, $deadlineDatetime = null)
    {
        $this->booking = $booking;
        $this->paymentLink = $paymentLink;
        $this->deadlineDatetime = $deadlineDatetime;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Payment Pending - Complete Your Coaching Session Booking',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.coaching-payment-pending',
            with: [
                'booking' => $this->booking,
                'paymentLink' => $this->paymentLink,
                'deadlineDatetime' => $this->deadlineDatetime,
            ],
        );
    }
}
