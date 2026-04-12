<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BookController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\CoachingController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\ChatController;
use Illuminate\Support\Facades\Route;

// Admin login routes - accessible without authentication
Route::prefix('admin')->name('admin.')->middleware(['web'])->group(function () {
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.post');
});

// Public coaching booking page (no login required) - outside admin prefix
Route::get('coaching-booking', [CoachingController::class, 'index'])->name('coaching.booking');
Route::post('coaching-booking', [CoachingController::class, 'store'])->name('coaching.store');
Route::get('coaching/callback', [CoachingController::class, 'callback'])->name('coaching.callback');
Route::get('coaching/status', [CoachingController::class, 'getBookingStatus'])->name('coaching.status');

// Admin routes - requires login via web guard with is_admin=true
Route::prefix('admin')->name('admin.')->middleware(['auth:web', 'admin'])->group(function () {
    Route::get('dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    // Books resource routes
    Route::get('books', [BookController::class, 'index'])->name('books');
    Route::get('books/create', [BookController::class, 'create'])->name('books.create');
    Route::post('books', [BookController::class, 'store'])->name('books.store');
    Route::get('books/{book}/edit', [BookController::class, 'edit'])->name('books.edit');
    Route::put('books/{book}', [BookController::class, 'update'])->name('books.update');
    Route::delete('books/{book}', [BookController::class, 'destroy'])->name('books.destroy');

    // Customers
    Route::get('customers', [CustomerController::class, 'index'])->name('customers');

    Route::get('orders', [OrderController::class, 'index'])->name('orders');
    Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::put('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.status');
    Route::post('orders/{order}/generate-pdf', [OrderController::class, 'generateAndSendPdf'])->name('orders.generatePdf');
    Route::post('orders/{order}/generate-text-pdf', [OrderController::class, 'generateFromText'])->name('orders.generateTextPdf');
    Route::post('orders/{order}/send-pdf', [OrderController::class, 'sendPdf'])->name('orders.sendPdf');
    Route::post('orders/{order}/upload-word-pdf', [OrderController::class, 'uploadWordPdf'])->name('orders.uploadWordPdf');
    Route::post('orders/{order}/upload-pdf', [OrderController::class, 'uploadPdf'])->name('orders.uploadPdf');
    Route::delete('orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');

    Route::get('settings', function () {
        return view('admin.settings.index');
    })->name('settings');

    // Passage preview API route
    Route::get('passages/preview', [OrderController::class, 'previewPassage'])->name('passages.preview');

    // Coaching bookings
    Route::get('coachings', [CoachingController::class, 'adminIndex'])->name('coachings.index');
    Route::get('coachings/upcoming', [CoachingController::class, 'getUpcomingMeetings'])->name('coachings.upcoming');
    Route::put('coachings/{booking}/status', [CoachingController::class, 'updateStatus'])->name('coachings.status');
    Route::post('coachings/{booking}/send-link', [CoachingController::class, 'sendMeetingLink'])->name('coachings.sendLink');
    Route::post('coachings/{booking}/send-reminder', [CoachingController::class, 'sendReminder'])->name('coachings.sendReminder');
    Route::post('coachings/toggle-active', [CoachingController::class, 'toggleActive'])->name('coachings.toggleActive');
    Route::delete('coachings/{booking}', [CoachingController::class, 'destroy'])->name('coachings.destroy');

    // Notifications
    Route::get('notifications', [NotificationController::class, 'index'])->name('notifications');
    Route::post('notifications/mark-read', [NotificationController::class, 'markAsRead'])->name('notifications.markRead');
    Route::post('notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllRead');
    Route::get('notifications/unread-count', [NotificationController::class, 'unreadCount'])->name('notifications.unreadCount');
});
