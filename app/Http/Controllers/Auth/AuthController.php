<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\VerificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Handle the login request.
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');
        
        $user = User::where('email', $credentials['email'])->first();
        
        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            Log::warning('Login failed: invalid credentials', ['email' => $credentials['email']]);
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ])->onlyInput('email');
        }
        
        // Check if user is admin - redirect to admin dashboard
        if ($user->is_admin) {
            Auth::login($user, $remember);
            $request->session()->regenerate();
            Log::info('Admin logged in', ['user_id' => $user->id]);
            return redirect()->route('admin.dashboard');
        }
        
        // For customers, check if email is verified
        if (!$user->hasVerifiedEmail()) {
            // Store user ID in session for verification
            $request->session()->put('pending_login_user_id', $user->id);
            
            // Send verification code
            app(VerificationService::class)->sendCode($user, 'login');
            
            Log::info('Customer login: email not verified, redirecting to verification', ['user_id' => $user->id]);
            return redirect()->route('verification.login');
        }
        
        // Email is verified, log the user in
        Auth::login($user, $remember);
        $request->session()->regenerate();
        
        Log::info('Customer logged in successfully', ['user_id' => $user->id]);
        
        return redirect()->intended(route('dashboard'));
    }

    /**
     * Show the registration form.
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Handle the registration request.
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        
        // Store user ID in session for verification
        $request->session()->put('pending_login_user_id', $user->id);
        
        // Send verification code
        app(VerificationService::class)->sendCode($user, 'login');
        
        Log::info('New user registered', ['user_id' => $user->id, 'email' => $user->email]);
        
        return redirect()->route('verification.login');
    }

    /**
     * Logout the user.
     */
    public function logout(Request $request)
    {
        $user = $request->user();
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        Log::info('User logged out', ['user_id' => $user?->id]);
        
        return redirect()->route('login');
    }
}
