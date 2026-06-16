<?php

namespace App\Services;

use App\Contracts\EmailSenderInterface;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class LaravelEmailSender implements EmailSenderInterface
{
    public function send(object $recipient, object $mail): bool
    {
        return $this->sendSync($recipient, $mail);
    }

    public function sendSync(object $recipient, object $mail): bool
    {
        try {
            if (! isset($recipient->email)) {
                Log::error('EmailSender: Recipient missing email address', [
                    'recipient' => is_array($recipient) ? $recipient : (array) $recipient,
                ]);

                return false;
            }

            Mail::to($recipient->email)->send($mail);

            Log::info('EmailSender: Email sent successfully', [
                'email' => $recipient->email,
                'mail_class' => get_class($mail),
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('EmailSender: Failed to send email', [
                'email' => $recipient->email ?? 'unknown',
                'mail_class' => get_class($mail),
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }
}
