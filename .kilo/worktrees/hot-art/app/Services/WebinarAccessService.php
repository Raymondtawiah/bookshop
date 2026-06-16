<?php

namespace App\Services;

use App\Models\WebinarRegistration;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

class WebinarAccessService
{
    /**
     * Generate encrypted access token for webinar registration
     */
    public function generateAccessToken(WebinarRegistration $registration): string
    {
        $webinar = $registration->webinar;
        $expiration = $this->getWebinarExpiration($registration);

        $payload = [
            'registration_id' => $registration->id,
            'webinar_id' => $webinar->id,
            'user_id' => $registration->user_id,
            'created_at' => now()->timestamp,
            'expires_at' => $expiration->timestamp,
            'nonce' => Str::random(16),
        ];

        return Crypt::encrypt($payload);
    }

    /**
     * Validate and decrypt access token
     */
    public function validateAccessToken(string $token): ?array
    {
        try {
            $payload = Crypt::decrypt($token);

            if (isset($payload['expires_at']) && now()->greaterThan(Carbon::parse($payload['expires_at']))) {
                return null;
            }

            return $payload;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Generate secure webinar access link for paid user
     */
    public function generateAccessLink(WebinarRegistration $registration): string
    {
        // Return existing token if available
        if ($registration->access_token) {
            return route('webinars.access', [
                'webinar' => $registration->webinar_id,
                'token' => $registration->access_token,
            ]);
        }

        // Generate access token
        $accessToken = $this->generateAccessToken($registration);

        // Try to store in database if columns exist
        try {
            $this->storeAccessToken($registration, $accessToken);
        } catch (\Exception $e) {
            // Columns don't exist, continue without storing
        }

        // Generate secure URL
        return route('webinars.access', [
            'webinar' => $registration->webinar_id,
            'token' => $accessToken,
        ]);
    }

    /**
     * Store access token in registration (if columns exist)
     */
    protected function storeAccessToken(WebinarRegistration $registration, string $accessToken): void
    {
        // Check if columns exist before attempting update
        if (! $this->hasAccessTokenColumns()) {
            return;
        }

        try {
            $expiration = $this->getWebinarExpiration($registration);
            $registration->update([
                'access_token' => $accessToken,
                'access_token_expires_at' => $expiration,
            ]);
        } catch (QueryException $e) {
            // Columns don't exist in database, continue without storing
            // This is expected for older databases without the migration
        }
    }

    /**
     * Check if access_token columns exist in the database
     */
    protected function hasAccessTokenColumns(): bool
    {
        try {
            $columns = \Schema::getColumnListing('webinar_registrations');

            return in_array('access_token', $columns) && in_array('access_token_expires_at', $columns);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Refresh access token if needed
     */
    public function refreshTokenIfNeeded(WebinarRegistration $registration): string
    {
        if ($registration->access_token) {
            return $registration->access_token;
        }

        return $this->generateAccessToken($registration);
    }

    /**
     * Revoke access token
     */
    public function revokeAccessToken(WebinarRegistration $registration): void
    {
        if (! $this->hasAccessTokenColumns()) {
            return;
        }

        if ($registration->isFillable('access_token')) {
            $registration->update([
                'access_token' => null,
                'access_token_expires_at' => null,
            ]);
        }
    }

    /**
     * Calculate access expiration based on webinar scheduled date.
     * If webinar is scheduled for today, expire at end of day.
     * If webinar is in the future, expire after the webinar date.
     * If no scheduled date, default to 7 days from now.
     */
    private function getWebinarExpiration(WebinarRegistration $registration): CarbonInterface
    {
        $webinar = $registration->webinar;

        if ($webinar->scheduled_at) {
            $scheduledDate = $webinar->scheduled_at->copy();

            // If scheduled for today, expire end of day
            if ($scheduledDate->isToday()) {
                return $scheduledDate->setTime(23, 59, 59);
            }

            // If scheduled in the future, expire after the webinar
            if ($scheduledDate->isFuture()) {
                return $scheduledDate->addDays(1)->setTime(23, 59, 59);
            }
        }

        // No scheduled date or webinar already passed - default to 7 days
        return now()->addDays(7)->setTime(23, 59, 59);
    }

    /**
     * Check if user can access webinar via token
     */
    public function canAccessWebinar(string $token, $webinarId): ?WebinarRegistration
    {
        $payload = $this->validateAccessToken($token);

        if (! $payload || $payload['webinar_id'] != $webinarId) {
            return null;
        }

        $registration = WebinarRegistration::find($payload['registration_id']);

        if (! $registration ||
            $registration->webinar_id != $webinarId ||
            ! $registration->isPaid()) {
            return null;
        }

        if (isset($payload['expires_at']) && now()->greaterThan(Carbon::parse($payload['expires_at']))) {
            return null;
        }

        return $registration;
    }
}
