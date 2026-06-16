<?php

use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Admin\CoachingController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\Customer\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\VerificationController;
use App\Models\User;
use App\Services\VerificationService;
use Illuminate\Auth\Access\Authorization;
use Illuminate\Hashing\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;

// Chat routes (public - accessible to all)
Route::middleware(['web'])->group(function () {
    Route::post('chat', [ChatController::class, 'store'])->name('chat.store');
    Route::get('chat/messages', [ChatController::class, 'customerChats'])->name('chat.messages');
    Route::get('chat/unread', [ChatController::class, 'getUnreadCount'])->name('chat.unread');
    Route::post('chat/read', [ChatController::class, 'markAsRead'])->name('chat.read');
    Route::delete('chat/clear', [ChatController::class, 'clearAllChats'])->name('chat.clear');
    Route::delete('chat/{id}', [ChatController::class, 'clearChatMessage'])->name('chat.delete');
});

// Home and public routes - NO middleware needed
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('search', [HomeController::class, 'search'])->name('search');

// Customer dashboard (protected by auth middleware)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', [HomeController::class, 'dashboard'])->name('dashboard');
});

Route::get('visa-tip', function () {
    return view('visa-tip');
})->name('visa-tip');

// Google OAuth - needs web middleware for session
Route::middleware(['web'])->group(function () {
    Route::get('login/google', [GoogleController::class, 'redirectToGoogle'])->name('login.google');
    Route::get('login/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('login.google.callback');
});

// Login Routes - GET needs web middleware for session/CSRF, POST needs web
Route::middleware(['web'])->group(function () {
    Route::get('login', function () {
        return view('auth.login');
    })->middleware(['guest'])->name('login');

    Route::post('login', function (Request $request) {
        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        $user = User::where('email', $credentials['email'])->first();

        if (! $user || ! Illuminate\Support\Facades\Hash::check($credentials['password'], $user->password)) {
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ])->onlyInput('email');
        }

        if ($user->is_admin) {
            Auth::login($user, $remember);
            $request->session()->regenerate();

            return redirect()->route('admin.dashboard');
        }

        // For customers, check if email is verified (admin doesn't need verification)
        if (! $user->hasVerifiedEmail()) {
            // Only verify customers, not admins
            if (! $user->is_admin) {
                $request->session()->put('pending_login_user_id', $user->id);
                app(VerificationService::class)->sendCode($user, 'login');

                return redirect()->route('verification.login');
            }
        }

        Auth::login($user, $remember);
        $request->session()->regenerate();

        // Customers go to welcome page, not dashboard
        return redirect()->intended(route('home'));
    })->name('login.store');
});

// Register Routes - GET needs web middleware for session/CSRF, POST needs web
Route::middleware(['web'])->group(function () {
    Route::get('register', function () {
        return view('auth.register');
    })->middleware(['guest'])->name('register');

    Route::post('register', function (Request $request) {
        Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ])->validate();

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Illuminate\Support\Facades\Hash::make($request->password),
        ]);

        $request->session()->put('pending_login_user_id', $user->id);
        app(VerificationService::class)->sendCode($user, 'login');

        return redirect()->route('verification.login');
    })->name('register.store');

    // Logout Route - needs web for session
    Route::post('logout', function (Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    })->name('logout');

    // Verification Routes - POST needs web
    Route::post('verification/login/resend', [VerificationController::class, 'resendLoginCode'])->name('verification.login.resend');
    Route::post('verification/login/verify', [VerificationController::class, 'verifyLoginCode'])->name('verification.login.verify');

    // Password Reset with Verification Code - POST needs web
    Route::post('verification/password-reset/send', [VerificationController::class, 'sendPasswordResetCode'])->name('verification.password-reset.send');
    Route::post('verification/password-reset/resend', [VerificationController::class, 'resendPasswordResetCode'])->name('verification.password-reset.resend');
    Route::post('verification/password-reset/verify', [VerificationController::class, 'verifyPasswordResetCode'])->name('verification.password-reset.verify');
    Route::post('password/reset', [VerificationController::class, 'resetPassword'])->name('password.reset.update');
});

// Password reset routes - GET needs web middleware for session/CSRF, POST needs web
Route::middleware(['web'])->group(function () {
    // Guest-only password reset routes
    Route::get('forgot-password', function () {
        return view('auth.forgot-password');
    })->middleware(['guest'])->name('password.request');

    Route::post('forgot-password', function (Request $request) {
        $request->validate(['email' => 'required|email']);
        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withInput($request->only('email'))->withErrors(['email' => __($status)]);
    })->middleware(['guest'])->name('password.email');

    Route::get('reset-password/{token}', function ($token) {
        return view('auth.reset-password', ['token' => $token]);
    })->middleware(['guest'])->name('password.reset');

    Route::post('reset-password', function (Request $request) {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->password = Hash::make($request->password);
                $user->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withInput($request->only('email'))->withErrors(['email' => __($status)]);
    })->middleware(['guest'])->name('password.update');
});

// Verification GET routes (these are public, render views)
Route::get('verification/login', [VerificationController::class, 'showLoginVerification'])->name('verification.login');
Route::get('verification/password-reset', [VerificationController::class, 'showPasswordResetVerification'])->name('verification.password-reset');
Route::get('password/reset-form', [VerificationController::class, 'showPasswordResetForm'])->name('password.reset.form');

// Email Verification Routes (Laravel Fortify)
Route::get('email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('email/verify/{id}/{hash}', function (Request $request) {
    Authorization::authorizeResourceFor('App\Models\User', $request->route('id'));
    $request->user()->markEmailAsVerified();

    return redirect('/')->with('verified', true);
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('status', 'verification-link-sent');
})->middleware('auth')->name('verification.send');

Route::get('product/{id}', [ProductController::class, 'show'])->name('product.show');
Route::get('product/{id}/download', [ProductController::class, 'downloadPdf'])->name('product.download');

Route::middleware(['auth', 'verify.customer'])->group(function () {
    // Profile routes
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('password', [ProfileController::class, 'updatePassword'])->name('user.password.update');

    Route::get('cart', [CartController::class, 'viewCart'])->name('cart');
    Route::post('cart/add', [CartController::class, 'addToCart'])->name('cart.add');
    Route::put('cart/{id}', [CartController::class, 'updateQuantity'])->name('cart.update');
    Route::delete('cart/{id}', [CartController::class, 'removeFromCart'])->name('cart.remove');
    Route::get('checkout', [CartController::class, 'checkout'])->name('checkout');
    Route::post('checkout/process', [OrderController::class, 'processCheckout'])->name('checkout.process');
    Route::get('order/download/{order}', [OrderController::class, 'downloadPdf'])->name('order.download');
    Route::get('my-orders', [OrderController::class, 'myOrders'])->name('my-orders');
    Route::get('my-bookings', [CoachingController::class, 'myBookings'])->name('customer.my-bookings');

    // Payment routes
    Route::post('payment/initialize', [PaymentController::class, 'initializePayment'])->name('payment.initialize');
    Route::get('payment/callback', [PaymentController::class, 'callback'])->name('payment.callback');
    Route::get('payment/status', [PaymentController::class, 'checkPaymentStatus'])->name('payment.status');
    Route::get('payment/banks', [PaymentController::class, 'getBanks'])->name('payment.banks');
});

require __DIR__.'/settings.php';
require __DIR__.'/admin.php';
