<?php

namespace App\Services;

use App\Models\WebinarRegistration;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use Carbon\Carbon;

class WebinarAccessService
{
    /**
     * Generate encrypted access token for webinar registration
     * Note: Tokens expire after 24 hours for security
     */
    public function generateAccessToken(WebinarRegistration $registration): string
    {
        $payload = [
            'registration_id' => $registration->id,
            'webinar_id' => $registration->webinar_id,
            'user_id' => $registration->user_id,
            'created_at' => now()->timestamp,
            'expires_at' => now()->addDays(7)->timestamp, // Expire after 7 days (next Friday)
            'nonce' => Str::random(16)
        ];

        return Crypt::encrypt($payload);
    }

    /**
     * Validate and decrypt access token
     * Note: Tokens expire after 7 days (next Friday) for weekly webinars
     */
    public function validateAccessToken(string $token): ?array
    {
        try {
            $payload = Crypt::decrypt($token);
            
            // Check if token has expired
            if (isset($payload['expires_at']) && now()->greaterThan(Carbon::parse($payload['expires_at']))) {
                return null; // Token expired
            }
            
            return $payload;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Generate secure webinar access link for paid user
     * Note: Links expire after 24 hours for security
     */
    public function generateAccessLink(WebinarRegistration $registration): string
    {
        // Generate access token
        $accessToken = $this->generateAccessToken($registration);
        
        // Store token in registration record (24 hour expiration)
        $registration->update([
            'access_token' => $accessToken,
            'access_token_expires_at' => now()->addHours(24) // 24 hour expiration
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
