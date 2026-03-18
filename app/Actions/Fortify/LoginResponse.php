<?php

namespace App\Actions\Fortify;

use Illuminate\Http\Request;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    /**
     * Create an authenticated user response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function toResponse($request)
    {
        $user = $request->user();
        
        if ($user && $user->is_admin) {
            return redirect()->route('admin.dashboard');
        }
        
        // Check if customer's email is verified
        if ($user && !$user->hasVerifiedEmail()) {
            // Store user ID in session for verification
            $request->session()->put('pending_login_user_id', $user->id);
            
            // Send verification code
            app(\App\Services\VerificationService::class)->sendCode($user, 'login');
            
            return redirect()->route('verification.login');
        }
        
        // Direct login - redirect to home/welcome page
        return redirect()->intended(route('home'));
    }
}
