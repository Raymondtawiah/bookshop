<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckWebinarAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        $webinar = $request->route('webinar');

        // Check if user is registered for this webinar
        $registration = $webinar->registrations()->where('user_id', $user->id)->first();

        if (! $registration) {
            return redirect()->route('webinars.show', $webinar)
                ->with('error', 'You must register for this webinar to access it.');
        }

        // Check if user has paid
        if (! $registration->isPaid()) {
            return redirect()->route('webinars.payment', [$webinar, $registration])
                ->with('error', 'You must complete payment to access this webinar.');
        }

        return $next($request);
    }
}
