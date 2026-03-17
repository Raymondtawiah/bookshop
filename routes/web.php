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

// Debug routes - remove after testing
Route::get('/test', function() {
    return 'Routes are working! ' . date('Y-m-d H:i:s');
});

Route::get('/test-session', function() {
    if (!session()->isStarted()) {
        session_start();
    }
    $_SESSION['test'] = 'Session is working!';
    return 'Session test: ' . ($_SESSION['test'] ?? 'failed') . ' | Session ID: ' . session()->getId();
});

Route::get('visa-tip', function() {
    return view('visa-tip');
})->name('visa-tip');

// Google Authentication Routes
Route::get('login/google', [GoogleController::class, 'redirectToGoogle'])->name('login.google');
Route::get('login/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('login.google.callback');

Route::get('product/{id}', [ProductController::class, 'show'])->name('product.show');
Route::get('product/{id}/download', [ProductController::class, 'downloadPdf'])->name('product.download');

Route::middleware(['auth'])->group(function () {
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


