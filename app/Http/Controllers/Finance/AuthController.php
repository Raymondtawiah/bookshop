<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\VerificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function __construct(private VerificationService $verificationService)
    {
    }

    public function showLoginForm()
    {
        return view('finance.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        if (! $user->is_staff) {
            throw ValidationException::withMessages([
                'email' => ['You are not authorized to access the finance area.'],
            ]);
        }

        $role = strtolower(trim($user->role));

        if ($role === 'finance admin') {
            Auth::login($user, $request->boolean('remember'));

            $request->session()->regenerate();

            return redirect()->route('finance.dashboard');
        }

        Session::put('pending_finance_login_user_id', $user->id);

        $this->verificationService->sendCode($user, 'login');

        return redirect()->route('finance.login.verify');
    }

    public function showVerifyForm(Request $request)
    {
        $userId = Session::get('pending_finance_login_user_id');

        if (! $userId) {
            return redirect()->route('finance.login');
        }

        $user = User::find($userId);

        if (! $user) {
            return redirect()->route('finance.login');
        }

        return view('finance.auth.verify-login', [
            'user' => $user,
            'type' => 'login',
        ]);
    }

    public function verify(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string', 'size:6'],
        ]);

        $userId = Session::get('pending_finance_login_user_id');

        if (! $userId) {
            return redirect()->route('finance.login')->with('error', 'Session expired. Please login again.');
        }

        $user = User::find($userId);

        if (! $user) {
            return redirect()->route('finance.login')->with('error', 'User not found.');
        }

        if (! $this->verificationService->verifyCode($user, $request->code, 'login')) {
            return back()->withErrors(['code' => 'Invalid or expired verification code.'])->onlyInput('code');
        }

        Session::forget('pending_finance_login_user_id');

        Auth::login($user, $request->boolean('remember'));

        $request->session()->regenerate();

        return redirect()->route('finance.dashboard');
    }

    public function resend(Request $request)
    {
        $userId = Session::get('pending_finance_login_user_id');

        if (! $userId) {
            return redirect()->route('finance.login')->with('error', 'Session expired. Please login again.');
        }

        $user = User::find($userId);

        if (! $user) {
            return redirect()->route('finance.login')->with('error', 'User not found.');
        }

        $this->verificationService->resendCode($user, 'login');

        return back()->with('success', 'Verification code sent successfully!');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
