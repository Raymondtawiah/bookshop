<?php

namespace App\Actions\Fortify;

use App\Services\VerificationService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    /**
     * Create an authenticated user response.
     *
     * @param  Request  $request
     * @return Response
     */
    public function toResponse($request)
    {
        $user = $request->user();

        if ($user && $user->is_admin) {
            return redirect()->route('admin.dashboard');
        }

        // Check if customer's email is verified
        if ($user && ! $user->hasVerifiedEmail()) {
            // Store user ID in session for verification
            $request->session()->put('pending_login_user_id', $user->id);

            // Send verification code
            app(VerificationService::class)->sendCode($user, 'login');

            return redirect()->route('verification.login');
        }

        // Direct login - redirect to dashboard
        return redirect()->intended(route('dashboard'));
    }
}
