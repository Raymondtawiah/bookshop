<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BookController;
use App\Http\Controllers\Admin\CoachingController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\FreeBookLeadsController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\WebinarController;
use Illuminate\Support\Facades\Route;

// Admin login routes - accessible without authentication
Route::prefix('admin')->name('admin.')->middleware(['web'])->group(function () {
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.post');
});

// Public coaching booking page (no login required) - outside admin prefix
Route::get('coaching-booking', [CoachingController::class, 'index'])->name('coaching.booking');
Route::get('coaching-booking/{plan}', [CoachingController::class, 'bookingPage'])->name('coaching.booking.page');
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
    Route::get('books/create-pdf', [BookController::class, 'createPdf'])->name('books.createPdf');
    Route::post('books', [BookController::class, 'store'])->name('books.store');
    Route::get('books/{book}/edit', [BookController::class, 'edit'])->name('books.edit');
    Route::put('books/{book}', [BookController::class, 'update'])->name('books.update');
    Route::delete('books/{book}', [BookController::class, 'destroy'])->name('books.destroy');

    // Free Book Leads
    Route::get('free-books', [FreeBookLeadsController::class, 'index'])->name('free-books');
    Route::get('free-books/{lead}/download', [FreeBookLeadsController::class, 'download'])->name('free-books.download');
    Route::get('free-books/download-all', [FreeBookLeadsController::class, 'downloadAll'])->name('free-books.download-all');

    // Customers
    Route::get('customers', [CustomerController::class, 'index'])->name('customers');

    Route::get('orders', [OrderController::class, 'index'])->name('orders');
    Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::put('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.status');
    Route::post('orders/{order}/send-book-pdf', [OrderController::class, 'sendBookPdf'])->name('orders.sendBookPdf');
    Route::post('orders/{order}/send-book-offer', [OrderController::class, 'sendBookOffer'])->name('orders.sendBookOffer');

    Route::get('settings', function () {
        return view('admin.settings.index');
    })->name('settings');

    // Passage preview API route
    Route::get('passages/preview', [OrderController::class, 'previewPassage'])->name('passages.preview');

    // Coaching bookings
    Route::get('coachings', [CoachingController::class, 'adminIndex'])->name('coachings.index');
    Route::get('coachings/{booking}', [CoachingController::class, 'adminShow'])->name('coachings.show');
    Route::post('coachings/{booking}/send-payment-reminder', [CoachingController::class, 'sendPaymentReminder'])->name('coachings.sendPaymentReminder');
    Route::get('coachings/upcoming', [CoachingController::class, 'getUpcomingMeetings'])->name('coachings.upcoming');
    Route::put('coachings/{booking}/status', [CoachingController::class, 'updateStatus'])->name('coachings.status');
    Route::post('coachings/{booking}/send-link', [CoachingController::class, 'sendMeetingLink'])->name('coachings.sendLink');
    Route::post('coachings/{booking}/send-reminder', [CoachingController::class, 'sendReminder'])->name('coachings.sendReminder');
    Route::delete('coachings/{booking}', [CoachingController::class, 'destroy'])->name('coachings.destroy');

    // Notifications
    Route::get('notifications', [NotificationController::class, 'index'])->name('notifications');
    Route::post('notifications/mark-read', [NotificationController::class, 'markAsRead'])->name('notifications.markRead');
    Route::post('notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllRead');

    Route::get('notifications/unread-count', [NotificationController::class, 'unreadCount'])->name('notifications.unreadCount');

    // Webinars
    Route::get('webinars', [WebinarController::class, 'index'])->name('webinars.index');
    Route::get('webinars/create', [WebinarController::class, 'create'])->name('webinars.create');
    Route::post('webinars', [WebinarController::class, 'store'])->name('webinars.store');
    Route::get('webinars/{webinar}/edit', [WebinarController::class, 'edit'])->name('webinars.edit')->where('webinar', '[0-9]+');
    Route::put('webinars/{webinar}', [WebinarController::class, 'update'])->name('webinars.update')->where('webinar', '[0-9]+');
    Route::delete('webinars/{webinar}', [WebinarController::class, 'destroy'])->name('webinars.destroy')->where('webinar', '[0-9]+');
    Route::post('webinars/{webinar}/registrations/{registration}/send-payment-reminder', [WebinarController::class, 'sendPaymentReminder'])->name('webinars.sendPaymentReminder')->where('webinar', '[0-9]+')->where('registration', '[0-9]+');
    Route::get('webinars/{webinar}/send-reminder/{registration?}', [WebinarController::class, 'showSendReminder'])->name('webinars.sendReminder.form')->where('webinar', '[0-9]+');
    Route::post('webinars/{webinar}/send-reminder/{registration?}', [WebinarController::class, 'sendWebinarReminder'])->name('webinars.sendWebinarReminder')->where('webinar', '[0-9]+');
    Route::post('webinars/{webinar}/registrations/{registration}/toggle-finished', [WebinarController::class, 'toggleRegistrationFinished'])->name('webinars.toggleRegistrationFinished')->where('webinar', '[0-9]+')->where('registration', '[0-9]+');
    Route::post('webinars/{webinar}/registrations/{registration}/toggle-attended', [WebinarController::class, 'toggleAttended'])->name('webinars.toggleAttended')->where('webinar', '[0-9]+')->where('registration', '[0-9]+');
    Route::get('webinars/{webinar}/notifications/create', [WebinarController::class, 'createNotification'])->name('webinars.notifications.create')->where('webinar', '[0-9]+');
    Route::post('webinars/{webinar}/notifications', [WebinarController::class, 'storeNotification'])->name('webinars.notifications.store')->where('webinar', '[0-9]+');
    Route::post('webinars/{webinar}/notifications/{notification}/send-to-users', [WebinarController::class, 'sendNotificationToUsers'])->name('webinars.notifications.sendToUsers')->where('webinar', '[0-9]+');

    // Webinar Registrations (Admin)
    Route::post('webinars/{webinar}/registrations/{registration}/resend-email', [WebinarController::class, 'resendEmail'])->name('webinars.resendEmail')->where('webinar', '[0-9]+')->where('registration', '[0-9]+');
    Route::delete('webinars/{webinar}/registrations/{registration}', [WebinarController::class, 'destroyRegistration'])->name('webinars.registrations.destroy')->where('webinar', '[0-9]+')->where('registration', '[0-9]+');
    Route::post('webinars/{webinar}/toggle-registration', [WebinarController::class, 'toggleRegistration'])->name('webinars.toggleRegistration')->where('webinar', '[0-9]+');
    Route::post('webinars/{webinar}/toggle-visibility', [WebinarController::class, 'toggleVisibility'])->name('webinars.toggleVisibility')->where('webinar', '[0-9]+');
    Route::post('webinars/toggle-registration-visibility', [WebinarController::class, 'toggleRegistrationVisibility'])->name('webinars.toggleRegistrationVisibility');
    Route::post('webinars/toggle-registration-form', [WebinarController::class, 'toggleRegistrationForm'])->name('webinars.toggleRegistrationForm');

    // Staff Management
    Route::get('staff', [StaffController::class, 'index'])->name('staff.index');
    Route::get('staff/create', [StaffController::class, 'create'])->name('staff.create');
    Route::post('staff', [StaffController::class, 'store'])->name('staff.store');
});
