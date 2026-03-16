<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
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
            $user = User::where('email', $googleUser->email)->first();

            if ($user) {
                // Update existing user with Google info if not already set
                if (!$user->google_id) {
                    $user->update([
                        'google_id' => $googleUser->id,
                        'avatar' => $googleUser->avatar,
                    ]);
                }
            } else {
                // Create new user
                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'avatar' => $googleUser->avatar,
                    'password' => bcrypt(str_random(16)), // Random password for OAuth users
                    'email_verified_at' => now(), // Email is verified by Google
                ]);
            }

            // Log the user in
            Auth::login($user);

            // Redirect to dashboard
            return redirect()->intended(route('dashboard'))->with('success', 'Welcome back!');

        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Google OAuth Error: ' . $e->getMessage());
            
            return redirect()->route('login')
                ->with('error', 'Unable to login with Google. Please try again.');
        }
    }
}
