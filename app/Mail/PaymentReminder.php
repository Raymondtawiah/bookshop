<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentReminder extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    public $paymentLink;

    public $customMessage;

    public function __construct(Order $order, string $paymentLink, ?string $customMessage = null)
    {
        $this->order = $order;
        $this->paymentLink = $paymentLink;
        $this->customMessage = $customMessage;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Payment Reminder - Complete Your Order',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.payment-reminder',
            with: [
                'order' => $this->order,
                'paymentLink' => $this->paymentLink,
                'customMessage' => $this->customMessage,
            ],
        );
    }
}
