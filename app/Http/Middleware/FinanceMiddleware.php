<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FinanceMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(401, 'Unauthorized.');
        }

        // Allow admins to access finance pages
        if ($user->is_admin) {
            return $next($request);
        }

        // Allow finance team roles
        if ($user->is_staff) {
            return $next($request);
        }

        abort(403, 'Access denied. Finance team privileges required.');
    }
}