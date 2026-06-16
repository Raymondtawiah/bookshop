<?php

namespace App\Services;

use App\Contracts\EmailServiceInterface;
use App\Models\Cart;
use App\Models\Order;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

/**
 * Customer Email Service
 *
 * This follows the Single Responsibility Principle (SRP) -
 * This class is only responsible for sending emails to customers.
 */
class CustomerEmailService implements EmailServiceInterface
{
    /**
     * Send an email with PDF attachment
     *
     * @param  Order  $order  The order containing customer details
     * @param  string  $pdfPath  Path to the PDF file to attach
     * @param  string  $subject  Email subject
     * @param  string  $template  Blade template for email content
     * @param  string|null  $overrideEmail  Optional email override (e.g. admin-specified recipient)
     */
    public function sendEmailWithAttachment(
        Order $order,
        string $pdfPath,
        string $subject,
        string $template = 'emails.order-confirmation',
        ?string $overrideEmail = null
    ): bool {
        try {
            $fullPath = Storage::disk('public')->path($pdfPath);

            if (! file_exists($fullPath)) {
                Log::error("PDF file not found: {$fullPath}");

                return false;
            }

            $cartItems = Cart::where('user_id', $order->user_id)->get();

            try {
                $adminName = auth()->check() ? auth()->user()->name : 'Admin';
            } catch (\Exception $e) {
                $adminName = 'Admin';
            }

            $filename = basename($pdfPath);
            $recipientEmail = $overrideEmail ?? $order->email;
            $recipientName = $overrideEmail ? null : $order->customer_name;

            Mail::send(
                $template,
                [
                    'order' => $order,
                    'user' => $order->user,
                    'cartItems' => $cartItems,
                    'adminName' => $adminName,
                ],
                function ($message) use ($fullPath, $filename, $subject, $recipientEmail, $recipientName) {
                    $message->to($recipientEmail, $recipientName)
                        ->subject($subject)
                        ->attach($fullPath, [
                            'as' => $filename,
                            'mime' => 'application/pdf',
                        ]);
                }
            );

            Log::info("Email sent successfully to: {$recipientEmail}");

            return true;

        } catch (\Exception $e) {
            Log::error('Failed to send email: '.$e->getMessage());

            return false;
        }
    }

    /**
     * Send a simple email without attachment
     *
     * @param  string  $to  Recipient email
     * @param  string  $name  Recipient name
     * @param  string  $subject  Email subject
     * @param  string  $body  Email body content
     */
    public function sendSimpleEmail(
        string $to,
        string $name,
        string $subject,
        string $body
    ): bool {
        try {
            Mail::raw($body, function ($message) use ($to, $name, $subject) {
                $message->to($to, $name)
                    ->subject($subject);
            });

            Log::info("Simple email sent to: {$to}");

            return true;

        } catch (\Exception $e) {
            Log::error('Failed to send simple email: '.$e->getMessage());

            return false;
        }
    }

    /**
     * Send a custom email with view template
     *
     * @param  string  $to  Recipient email
     * @param  string  $name  Recipient name
     * @param  string  $subject  Email subject
     * @param  string  $template  Blade template
     * @param  array  $data  Data to pass to the view
     */
    public function sendTemplateEmail(
        string $to,
        string $name,
        string $subject,
        string $template,
        array $data = []
    ): bool {
        try {
            Mail::send(
                $template,
                $data,
                function ($message) use ($to, $name, $subject) {
                    $message->to($to, $name)
                        ->subject($subject);
                }
            );

            Log::info("Template email sent to: {$to}");

            return true;

        } catch (\Exception $e) {
            Log::error('Failed to send template email: '.$e->getMessage());

            return false;
        }
    }
}
