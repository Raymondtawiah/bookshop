<?php

namespace App\Http\Middleware;

use App\Models\Webinar;
use App\Models\WebinarRegistration;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProtectWebinarLink
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only apply to webinar join routes
        if (!$request->is('webinar/*/verified/*')) {
            return $next($request);
        }

        $user = auth()->user();
        
        if (!$user) {
            return redirect()->route('login')
                ->with('error', 'Please login to access the webinar.');
        }

        // Get webinar and registration from route parameters
        $webinar = $request->route('webinar');
        $registration = $request->route('registration');

        // Verify this registration belongs to the authenticated user
        if ($registration->user_id !== $user->id) {
            abort(403, 'Unauthorized access. This webinar access is not for your account.');
        }

        // Verify payment is completed
        if (!$registration->isPaid()) {
            return redirect()->route('webinars.payment', [$webinar, $registration])
                ->with('error', 'Please complete payment before joining the webinar.');
        }

        return $next($request);
    }
}
