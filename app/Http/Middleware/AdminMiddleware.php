<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        
        // Debug logging
        Log::info('AdminMiddleware check', [
            'user_id' => $user?->id,
            'user_email' => $user?->email,
            'is_admin' => $user?->is_admin,
            'is_admin_type' => gettype($user?->is_admin),
        ]);
        
        // Check if user is authenticated via web guard and has is_admin flag
        if (!$user || !$user->is_admin) {
            abort(403, 'Access denied. Admin privileges required.');
        }

        return $next($request);
    }
}