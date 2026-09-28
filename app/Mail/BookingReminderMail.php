<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public Order $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Booking Reminder: '.($this->order->customer_name ?? 'Your booking'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.booking-reminder',
            with: ['order' => $this->order],
        );
    }
}
