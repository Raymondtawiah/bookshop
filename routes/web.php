<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Customer\ProfileController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\VerificationController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('visa-tip', function() {
    return view('visa-tip');
})->name('visa-tip');

// Authentication Routes - wrapped in web middleware for CSRF protection
Route::middleware(['web'])->group(function () {
    // Google Authentication Routes
    Route::get('login/google', [GoogleController::class, 'redirectToGoogle'])->name('login.google');
    Route::get('login/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('login.google.callback');

    // Login/Logout/Register Routes
    Route::get('login', [\App\Http\Controllers\Auth\AuthController::class, 'showLogin'])
        ->middleware(['guest'])
        ->name('login');

    Route::post('login', [\App\Http\Controllers\Auth\AuthController::class, 'login'])
        ->name('login.store');

    Route::get('register', [\App\Http\Controllers\Auth\AuthController::class, 'showRegister'])
        ->middleware(['guest'])
        ->name('register');

    Route::post('register', [\App\Http\Controllers\Auth\AuthController::class, 'register'])
        ->name('register.store');

    Route::post('logout', [\App\Http\Controllers\Auth\AuthController::class, 'logout'])
        ->name('logout');

    // Custom Verification Routes (6-digit code)
    Route::get('verification/login', [\App\Http\Controllers\VerificationController::class, 'showLoginVerification'])->name('verification.login');
    Route::post('verification/login/resend', [\App\Http\Controllers\VerificationController::class, 'resendLoginCode'])->name('verification.login.resend');
    Route::post('verification/login/verify', [\App\Http\Controllers\VerificationController::class, 'verifyLoginCode'])->name('verification.login.verify');
    
    // Password Reset with Verification Code
    Route::get('verification/password-reset', [\App\Http\Controllers\VerificationController::class, 'showPasswordResetVerification'])->name('verification.password-reset');
    Route::post('verification/password-reset/send', [\App\Http\Controllers\VerificationController::class, 'sendPasswordResetCode'])->name('verification.password-reset.send');
    Route::post('verification/password-reset/resend', [\App\Http\Controllers\VerificationController::class, 'resendPasswordResetCode'])->name('verification.password-reset.resend');
    Route::post('verification/password-reset/verify', [\App\Http\Controllers\VerificationController::class, 'verifyPasswordResetCode'])->name('verification.password-reset.verify');
    
    // Password Reset Form (after verification)
    Route::get('password/reset-form', [\App\Http\Controllers\VerificationController::class, 'showPasswordResetForm'])->name('password.reset.form');
    Route::post('password/reset', [\App\Http\Controllers\VerificationController::class, 'resetPassword'])->name('password.reset.update');
});

// Password Reset Routes
Route::get('forgot-password', function () {
    return view('auth.forgot-password');
})->middleware(['guest'])->name('password.request');

Route::post('forgot-password', function (\Illuminate\Http\Request $request) {
    $request->validate(['email' => 'required|email']);
    
    $status = \Illuminate\Support\Facades\Password::sendResetLink(
        $request->only('email')
    );
    
    return $status === \Illuminate\Support\Facades\Password::RESET_LINK_SENT
        ? back()->with('status', __($status))
        : back()->withInput($request->only('email'))->withErrors(['email' => __($status)]);
})->middleware(['guest'])->name('password.email');

Route::get('reset-password/{token}', function ($token) {
    return view('auth.reset-password', ['token' => $token]);
})->middleware(['guest'])->name('password.reset');

Route::post('reset-password', function (\Illuminate\Http\Request $request) {
    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|min:8|confirmed',
    ]);
    
    $status = \Illuminate\Support\Facades\Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user) use ($request) {
            $user->password = \Illuminate\Hashing\Hash::make($request->password);
            $user->save();
        }
    );
    
    return $status === \Illuminate\Support\Facades\Password::PASSWORD_RESET
        ? redirect()->route('login')->with('status', __($status))
        : back()->withInput($request->only('email'))->withErrors(['email' => __($status)]);
})->middleware(['guest'])->name('password.update');

// Email Verification Routes (Laravel Fortify)
Route::get('email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('email/verify/{id}/{hash}', function (\Illuminate\Http\Request $request) {
    \Illuminate\Auth\Access\Authorization::authorizeResourceFor('App\Models\User', $request->route('id'));
    $request->user()->markEmailAsVerified();
    return redirect('/dashboard')->with('verified', true);
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('email/verification-notification', function (\Illuminate\Http\Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('status', 'verification-link-sent');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::get('product/{id}', [ProductController::class, 'show'])->name('product.show');
Route::get('product/{id}/download', [ProductController::class, 'downloadPdf'])->name('product.download');

Route::middleware(['auth', 'verify.customer'])->group(function () {
    Route::get('dashboard', [HomeController::class, 'dashboard'])->name('dashboard');
    
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
    
    // Payment routes
    Route::post('payment/initialize', [PaymentController::class, 'initializePayment'])->name('payment.initialize');
    Route::get('payment/callback', [PaymentController::class, 'callback'])->name('payment.callback');
    Route::get('payment/status', [PaymentController::class, 'checkPaymentStatus'])->name('payment.status');
    Route::get('payment/banks', [PaymentController::class, 'getBanks'])->name('payment.banks');
});

require __DIR__.'/settings.php';
require __DIR__.'/admin.php';

// Debug routes - REMOVE IN PRODUCTION
require __DIR__.'/test_pdf.php';



