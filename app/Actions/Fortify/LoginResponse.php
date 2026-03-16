<?php

namespace App\Actions\Fortify;

use App\Services\VerificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function __construct(
        protected VerificationService $verificationService
    ) {}

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
        
        // For regular users, send verification code before allowing login
        // Log the user out temporarily
        Auth::logout();
        
        // Store user ID in session for verification
        $request->session()->put('pending_login_user_id', $user->id);
        
        // Send verification code
        $this->verificationService->sendCode($user, 'login');
        
        // Redirect to verification page
        return redirect()->route('verification.login');
    }
}