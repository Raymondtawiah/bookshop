<?php

namespace App\Mail;

use App\Models\CoachingBooking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CoachingPaymentReceived extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;

    public function __construct(CoachingBooking $booking)
    {
        $this->booking = $booking;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Payment Confirmed - Your Coaching Session is Booked!',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.coaching-payment-received',
            with: [
                'booking' => $this->booking,
            ],
        );
    }
}
