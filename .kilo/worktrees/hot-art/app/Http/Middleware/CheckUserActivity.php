<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserActivity
{
    /**
     * Handle an incoming request.
     * Log out user after inactivity and redirect to home page.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $lastActivity = $request->session()->get('last_activity');
            $sessionLifetime = config('session.lifetime', 480) * 60; // convert to seconds

            if ($lastActivity && (time() - $lastActivity > $sessionLifetime)) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect('/')->with('info', 'You have been logged out due to inactivity.');
            }

            $request->session()->put('last_activity', time());
        }

        return $next($request);
    }
}
