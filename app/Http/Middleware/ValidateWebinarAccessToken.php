<?php

namespace App\Http\Middleware;

use App\Models\WebinarRegistration;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateWebinarAccessToken
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only apply to webinar join routes
        if (!$request->is('webinar/*/join')) {
            return $next($request);
        }

        $user = auth()->user();
        
        if (!$user) {
            abort(401, 'Authentication required.');
        }

        // Get access token from query parameter
        $token = $request->query('tk');
        
        if (!$token) {
            abort(403, 'Access token required. Please use your personal join link.');
        }

        // Find registration with this access token
        $registration = WebinarRegistration::where('access_token', $token)
            ->where('user_id', $user->id)
            ->first();

        if (!$registration) {
            abort(403, 'Invalid access token. This link has been revoked or is not valid for your account.');
        }

        // Check if token has expired
        if ($registration->access_token_expires_at && $registration->access_token_expires_at->isPast()) {
            abort(403, 'Access token has expired. Please generate a new join link.');
        }

        // Check if payment is completed
        if (!$registration->isPaid()) {
            abort(403, 'Payment required. Please complete your payment before joining.');
        }

        // Log access for security monitoring
        \Log::info('Webinar access granted', [
            'user_id' => $user->id,
            'webinar_id' => $registration->webinar_id,
            'registration_id' => $registration->id,
            'access_token' => $token,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return $next($request);
    }
}
