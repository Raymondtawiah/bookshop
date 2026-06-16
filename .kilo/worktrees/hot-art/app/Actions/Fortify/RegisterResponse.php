<?php

namespace App\Actions\Fortify;

use App\Services\VerificationService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;

class RegisterResponse implements RegisterResponseContract
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

        // Customer registration - redirect to email verification using 6-digit code
        // Store user ID in session for verification
        $request->session()->put('pending_login_user_id', $user->id);

        // Send verification code
        app(VerificationService::class)->sendCode($user, 'login');

        return redirect()->route('verification.login');
    }
}
