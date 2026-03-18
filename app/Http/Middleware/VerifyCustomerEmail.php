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

        // Customers must verify their email
        if (!$user->hasVerifiedEmail()) {
            return redirect()->route('verification.notice')
                ->with('warning', 'Please verify your email address before continuing.');
        }

        return $next($request);
    }
}
