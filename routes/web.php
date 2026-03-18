<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Customer\ProfileController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Auth\GoogleController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('visa-tip', function() {
    return view('visa-tip');
})->name('visa-tip');

// Google Authentication Routes
Route::get('login/google', [GoogleController::class, 'redirectToGoogle'])->name('login.google');
Route::get('login/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('login.google.callback');

// Email Verification Routes
Route::get('email/verify', \Laravel\Fortify\Http\Controllers\EmailVerificationNotificationController::class, 'show')
    ->middleware(['auth'])
    ->name('verification.notice');

Route::post('email/verification-notification', \Laravel\Fortify\Http\Controllers\EmailVerificationNotificationController::class, 'store')
    ->middleware(['auth', 'throttle:6,1'])
    ->name('verification.send');

Route::get('email/verify/{id}/{hash}', \Laravel\Fortify\Http\Controllers\VerifyEmailController::class, '__invoke')
    ->middleware(['auth', 'signed'])
    ->name('verification.verify');

// Password Reset Routes
Route::get('forgot-password', \Laravel\Fortify\Http\Controllers\PasswordResetLinkController::class, 'create')
    ->middleware(['guest'])
    ->name('password.request');

Route::post('forgot-password', \Laravel\Fortify\Http\Controllers\PasswordResetLinkController::class, 'store')
    ->middleware(['guest'])
    ->name('password.email');

Route::get('reset-password/{token}', \Laravel\Fortify\Http\Controllers\NewPasswordController::class, 'create')
    ->middleware(['guest'])
    ->name('password.reset');

Route::post('reset-password', \Laravel\Fortify\Http\Controllers\NewPasswordController::class, 'store')
    ->middleware(['guest'])
    ->name('password.update');

Route::get('product/{id}', [ProductController::class, 'show'])->name('product.show');
Route::get('product/{id}/download', [ProductController::class, 'downloadPdf'])->name('product.download');

Route::middleware(['auth', 'verify.customer'])->group(function () {
    Route::get('dashboard', [HomeController::class, 'index'])->name('dashboard');
    
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



