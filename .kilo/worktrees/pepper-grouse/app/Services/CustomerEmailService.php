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
     */
    public function sendEmailWithAttachment(
        Order $order,
        string $pdfPath,
        string $subject,
        string $template = 'emails.order-confirmation'
    ): bool {
        try {
            // Get full path to the PDF
            $fullPath = Storage::disk('public')->path($pdfPath);

            if (! file_exists($fullPath)) {
                Log::error("PDF file not found: {$fullPath}");

                return false;
            }

            // Get cart items for the order
            $cartItems = Cart::where('user_id', $order->user_id)->get();

            // Get admin name
            try {
                $adminName = auth()->check() ? auth()->user()->name : 'Admin';
            } catch (\Exception $e) {
                $adminName = 'Admin';
            }

            // Extract filename from path
            $filename = basename($pdfPath);

            // Send email with PDF attachment
            Mail::send(
                $template,
                [
                    'order' => $order,
                    'user' => $order->user,
                    'cartItems' => $cartItems,
                    'adminName' => $adminName,
                ],
                function ($message) use ($order, $fullPath, $filename, $subject) {
                    $message->to($order->email, $order->customer_name)
                        ->subject($subject)
                        ->attach($fullPath, [
                            'as' => $filename,
                            'mime' => 'application/pdf',
                        ]);
                }
            );

            Log::info("Email sent successfully to: {$order->email}");

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
