<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
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
                
                // Mark email as verified since Google verified it
                // Force verify even if somehow not set
                if (is_null($user->email_verified_at)) {
                    $user->forceFill(['email_verified_at' => now()])->save();
                }

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

            return redirect()->intended(route('dashboard'))
                ->with('success', 'Welcome back!');

        } catch (\Exception $e) {

            \Log::error('Google OAuth Error: '.$e->getMessage());
            \Log::error('Google OAuth Trace: '.$e->getTraceAsString());

            return redirect()->route('login')
                ->with('error', 'Unable to login with Google. Error: '.$e->getMessage());

        }
    }
}