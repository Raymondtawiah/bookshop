<?php

namespace App\Services;

use App\Models\WebinarRegistration;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

class WebinarAccessService
{
    /**
     * Generate encrypted access token for webinar registration
     * Note: Tokens are permanent (no expiration) so users can access anytime
     */
    public function generateAccessToken(WebinarRegistration $registration): string
    {
        $payload = [
            'registration_id' => $registration->id,
            'webinar_id' => $registration->webinar_id,
            'user_id' => $registration->user_id,
            'created_at' => now()->timestamp,
            'nonce' => Str::random(16)
        ];

        return Crypt::encrypt($payload);
    }

    /**
     * Validate and decrypt access token
     * Note: Tokens do not expire - they are permanent
     */
    public function validateAccessToken(string $token): ?array
    {
        try {
            $payload = Crypt::decrypt($token);
            
            // Tokens are permanent - no expiration check
            return $payload;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Generate secure webinar access link for paid user
     * Note: Links are permanent - no expiration
     */
    public function generateAccessLink(WebinarRegistration $registration): string
    {
        // Generate access token
        $accessToken = $this->generateAccessToken($registration);
        
        // Store token in registration record (no expiration)
        $registration->update([
            'access_token' => $accessToken,
            'access_token_expires_at' => null // Permanent access
        ]);

        // Generate secure URL
        return route('webinars.access', [
            'webinar' => $registration->webinar_id,
            'token' => $accessToken
        ]);
    }

    /**
     * Refresh access token if needed
     * Note: Tokens don't expire, so only regenerate if missing
     */
    public function refreshTokenIfNeeded(WebinarRegistration $registration): string
    {
        // Return existing token if available (tokens are permanent)
        if ($registration->access_token) {
            return $registration->access_token;
        }

        // Generate new token only if missing
        return $this->generateAccessToken($registration);
    }

    /**
     * Revoke access token
     */
    public function revokeAccessToken(WebinarRegistration $registration): void
    {
        $registration->update([
            'access_token' => null,
            'access_token_expires_at' => null
        ]);
    }

    /**
     * Check if user can access webinar via token
     */
    public function canAccessWebinar(string $token, $webinarId): ?WebinarRegistration
    {
        $payload = $this->validateAccessToken($token);
        
        if (!$payload || $payload['webinar_id'] != $webinarId) {
            return null;
        }

        $registration = WebinarRegistration::find($payload['registration_id']);
        
        if (!$registration || 
            $registration->webinar_id != $webinarId || 
            !$registration->isPaid()) {
            return null;
        }

        return $registration;
    }
}
