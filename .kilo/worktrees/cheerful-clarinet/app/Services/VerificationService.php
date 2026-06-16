<?php

namespace App\Services;

use App\Mail\VerificationCodeMail;
use App\Models\User;
use App\Models\VerificationCode;
use Illuminate\Support\Facades\Mail;

class VerificationService
{
    /**
     * Send a verification code to a user
     */
    public function sendCode(User $user, string $type): VerificationCode
    {
        $verificationCode = VerificationCode::createForUser($user, $type);

        Mail::to($user->email)->send(new VerificationCodeMail($verificationCode));

        return $verificationCode;
    }

    /**
     * Verify a code for a user
     */
    public function verifyCode(User $user, string $code, string $type): bool
    {
        return VerificationCode::verifyForUser($user, $code, $type);
    }

    /**
     * Resend a verification code
     */
    public function resendCode(User $user, string $type): VerificationCode
    {
        return $this->sendCode($user, $type);
    }
}
