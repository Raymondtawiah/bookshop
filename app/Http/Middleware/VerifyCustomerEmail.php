<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyCustomerEmail
{
    /**
     * Handle an incoming request.
     * Only customers need to verify their email, admins bypass this.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // If no user, let them through (they'll be redirected to login)
        if (!$user) {
            return $next($request);
        }

        // Admins don't need to verify their email
        if ($user->is_admin) {
            return $next($request);
        }

        // Customers must verify their email - redirect to custom verification page
        if (!$user->hasVerifiedEmail()) {
            // Store user ID in session for verification
            $request->session()->put('pending_login_user_id', $user->id);
            
            // Send verification code
            app(\App\Services\VerificationService::class)->sendCode($user, 'login');
            
            return redirect()->route('verification.login')
                ->with('warning', 'Please verify your email address with the 6-digit code sent to your email.');
        }

        return $next($request);
    }
}
