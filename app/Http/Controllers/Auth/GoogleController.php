<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    /**
     * Redirect the user to Google's authentication page.
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google.
     */
    public function handleGoogleCallback()
    {
        try {

            $googleUser = Socialite::driver('google')->user();

            Log::info('Google OAuth callback received', [
                'email' => $googleUser->getEmail(),
                'name' => $googleUser->getName()
            ]);

            // Check if user already exists
            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user) {

                // Update Google info if missing
                if (!$user->google_id) {
                    $user->update([
                        'google_id' => $googleUser->getId(),
                        'avatar' => $googleUser->getAvatar(),
                    ]);
                }
                
                // ALWAYS mark email as verified since Google already verified it
                // This ensures existing users who haven't verified can still login via Google
                if (is_null($user->email_verified_at)) {
                    $user->email_verified_at = now();
                    $user->save();
                    Log::info('Google login: Email verified for existing user', ['user_id' => $user->id, 'email' => $user->email, 'verified_at' => $user->email_verified_at]);
                }
                
                // Refresh the user from database to ensure we have the latest data
                $user = $user->fresh();

            } else {

                // Create new user - Google already verified their email
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                    'password' => bcrypt(Str::random(16)),
                    'email_verified_at' => now(), // Google verified the email
                ]);

            }

            // Admins cannot login with Google - they must use password
            if ($user->is_admin) {
                return redirect()->route('login')
                    ->with('error', 'Admins must login with email and password.');
            }

            // Login the user
            Auth::login($user, true);
            
            // Regenerate session to prevent session fixation
            request()->session()->regenerate();

            // Debug: Check if user is actually logged in
            if (!Auth::check()) {
                Log::error('Google login: Auth::login() failed to log user in', [
                    'user_id' => $user->id,
                    'email' => $user->email
                ]);
                return redirect()->route('login')
                    ->with('error', 'Login failed. Please try again.');
            }
            
            // Debug: Log the login details
            Log::info('Google login successful - about to redirect', [
                'user_id' => $user->id,
                'email' => $user->email,
                'is_admin' => $user->is_admin,
                'google_id' => $user->google_id,
                'email_verified_at' => $user->email_verified_at,
                'auth_check' => Auth::check(),
                'session_id' => request()->session()->getId()
            ]);

            // Redirect based on user type
            if ($user->is_admin) {
                return redirect()->route('admin.dashboard');
            }
            
            // Use route() helper with proper parameters
            Log::info('Redirecting to home route');
            
            // Google login doesn't need email verification - Google already verified it
            return redirect()->route('home', [], 302, ['Cache-Control' => 'no-cache, no-store, must-revalidate', 'Pragma' => 'no-cache', 'Expires' => '0']);

        } catch (\Exception $e) {

            \Log::error('Google OAuth Error: '.$e->getMessage());
            \Log::error('Google OAuth Trace: '.$e->getTraceAsString());

            return redirect()->route('login')
                ->with('error', 'Unable to login with Google. Error: '.$e->getMessage());

        }
    }
}