<?php

namespace App\Contracts;

use App\Models\Order;

/**
 * Interface for Email Services
 *
 * This follows the Dependency Inversion Principle (DIP) -
 * high-level modules should not depend on low-level modules.
 * Both should depend on abstractions.
 */
interface EmailServiceInterface
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
    ): bool;

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
    ): bool;
}
