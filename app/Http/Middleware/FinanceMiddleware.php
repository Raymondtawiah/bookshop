<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class FinanceMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            Log::info('Finance middleware', ['result' => 'no user']);
            abort(401, 'Unauthorized.');
        }

        if ($user->is_admin) {
            Log::info('Finance middleware', ['result' => 'admin allowed', 'user_id' => $user->id]);
            return $next($request);
        }

        if ($user->is_staff) {
            Log::info('Finance middleware', ['result' => 'staff allowed', 'user_id' => $user->id]);
            return $next($request);
        }

        $role = strtolower(trim((string) ($user->role ?? '')));

        if ($role === 'finance admin' || $role === 'finance member') {
            Log::info('Finance middleware', ['result' => 'role allowed', 'user_id' => $user->id, 'role' => $role]);
            return $next($request);
        }

        Log::info('Finance middleware', ['result' => 'forbidden', 'user_id' => $user->id, 'role' => $role]);
        abort(403, 'Access denied. Finance team privileges required.');
    }
}