<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\VerificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;

class VerificationController extends Controller
{
    public function __construct(
        protected VerificationService $verificationService
    ) {}

    /**
     * Show the verification page for login
     */
    public function showLoginVerification()
    {
        $userId = Session::get('pending_login_user_id');
        
        if (!$userId) {
            return redirect()->route('login');
        }

        $user = User::find($userId);
        
        if (!$user) {
            return redirect()->route('login');
        }

        return view('auth.verify-login', [
            'user' => $user,
            'type' => 'login'
        ]);
    }

    /**
     * Resend the verification code for login
     */
    public function resendLoginCode(Request $request)
    {
        $userId = Session::get('pending_login_user_id');
        
        if (!$userId) {
            return response()->json(['error' => 'Session expired. Please login again.'], 422);
        }

        $user = User::find($userId);
        
        if (!$user) {
            return response()->json(['error' => 'User not found.'], 422);
        }

        // Rate limiting - only allow 3 resend requests per minute
        $key = 'resend-login-code:' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            return response()->json(['error' => "Please wait $seconds seconds before requesting another code."], 429);
        }

        RateLimiter::hit($key, 60);

        $this->verificationService->resendCode($user, 'login');

        return response()->json(['message' => 'Verification code sent successfully!']);
    }

    /**
     * Verify the login code
     */
    public function verifyLoginCode(Request $request)
    {
        $request->validate([
            'code' => 'required|digits:6',
        ]);

        $userId = Session::get('pending_login_user_id');
        
        if (!$userId) {
            throw ValidationException::withMessages([
                'code' => 'Session expired. Please login again.',
            ]);
        }

        $user = User::find($userId);
        
        if (!$user) {
            throw ValidationException::withMessages([
                'code' => 'User not found.',
            ]);
        }

        // Rate limiting
        $key = 'verify-login-code:' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            throw ValidationException::withMessages([
                'code' => "Too many attempts. Please wait $seconds seconds.",
            ]);
        }

        if (!$this->verificationService->verifyCode($user, $request->code, 'login')) {
            RateLimiter::hit($key, 60);
            throw ValidationException::withMessages([
                'code' => 'Invalid or expired verification code.',
            ]);
        }

        // Clear the pending login session
        Session::forget('pending_login_user_id');

        // Log the user in
        Auth::login($user);

        // Clear rate limiter
        RateLimiter::clear($key);

        return redirect()->intended(route('dashboard'))->with('success', 'Login successful!');
    }

    /**
     * Show the verification page for password reset
     */
    public function showPasswordResetVerification(Request $request)
    {
        $email = $request->session()->get('password_reset_email');
        
        if (!$email) {
            return redirect()->route('password.request');
        }

        $user = User::where('email', $email)->first();
        
        if (!$user) {
            return redirect()->route('password.request');
        }

        return view('auth.verify-password-reset', [
            'user' => $user,
            'type' => 'password_reset'
        ]);
    }

    /**
     * Send the verification code for password reset
     */
    public function sendPasswordResetCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();

        // Rate limiting
        $key = 'password-reset-code:' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            return back()->with('error', "Please wait $seconds seconds before requesting another code.");
        }

        RateLimiter::hit($key, 60);

        $this->verificationService->sendCode($user, 'password_reset');

        // Store email in session for verification
        $request->session()->put('password_reset_email', $user->email);

        // Redirect to the verification page instead of returning JSON
        return redirect()->route('verification.password-reset')->with('message', 'Verification code sent successfully!');
    }

    /**
     * Resend the verification code for password reset
     */
    public function resendPasswordResetCode(Request $request)
    {
        $email = $request->session()->get('password_reset_email');
        
        if (!$email) {
            return response()->json(['error' => 'Session expired. Please try again.'], 422);
        }

        $user = User::where('email', $email)->first();
        
        if (!$user) {
            return response()->json(['error' => 'User not found.'], 422);
        }

        // Rate limiting
        $key = 'resend-password-reset-code:' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            return response()->json(['error' => "Please wait $seconds seconds before requesting another code."], 429);
        }

        RateLimiter::hit($key, 60);

        $this->verificationService->resendCode($user, 'password_reset');

        return response()->json(['message' => 'Verification code sent successfully!']);
    }

    /**
     * Verify the password reset code
     */
    public function verifyPasswordResetCode(Request $request)
    {
        $request->validate([
            'code' => 'required|digits:6',
        ]);

        $email = $request->session()->get('password_reset_email');
        
        if (!$email) {
            throw ValidationException::withMessages([
                'code' => 'Session expired. Please try again.',
            ]);
        }

        $user = User::where('email', $email)->first();
        
        if (!$user) {
            throw ValidationException::withMessages([
                'code' => 'User not found.',
            ]);
        }

        // Rate limiting
        $key = 'verify-password-reset-code:' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            throw ValidationException::withMessages([
                'code' => "Too many attempts. Please wait $seconds seconds.",
            ]);
        }

        if (!$this->verificationService->verifyCode($user, $request->code, 'password_reset')) {
            RateLimiter::hit($key, 60);
            throw ValidationException::withMessages([
                'code' => 'Invalid or expired verification code.',
            ]);
        }

        // Store the user ID in session for password reset
        $request->session()->put('password_reset_user_id', $user->id);

        // Clear rate limiter
        RateLimiter::clear($key);

        return response()->json(['message' => 'Code verified successfully!', 'redirect' => route('password.reset.form')]);
    }

    /**
     * Show the password reset form after verification
     */
    public function showPasswordResetForm(Request $request)
    {
        $userId = $request->session()->get('password_reset_user_id');
        
        if (!$userId) {
            return redirect()->route('password.request');
        }

        $user = User::find($userId);
        
        if (!$user) {
            return redirect()->route('password.request');
        }

        return view('auth.reset-password', [
            'email' => $user->email,
            'token' => $user->id // Using user ID as token for simplicity
        ]);
    }

    /**
     * Reset the user's password after verification
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::where('email', $request->email)->first();
        
        if (!$user) {
            throw ValidationException::withMessages([
                'email' => 'User not found.',
            ]);
        }

        // Verify that the user went through the verification process
        $userId = $request->session()->get('password_reset_user_id');
        if (!$userId || $userId != $user->id) {
            throw ValidationException::withMessages([
                'email' => 'Please complete the verification process first.',
            ]);
        }

        // Update the password
        $user->password = Hash::make($request->password);
        $user->save();

        // Clear session
        $request->session()->forget('password_reset_email');
        $request->session()->forget('password_reset_user_id');

        return redirect()->route('login')->with('success', 'Password reset successfully! Please login with your new password.');
    }
}
