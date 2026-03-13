<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BookController;
use App\Http\Controllers\Admin\CustomerController;
use Illuminate\Support\Facades\Route;

// Admin authentication routes - requires login via web guard with is_admin=true
Route::prefix('admin')->name('admin.')->middleware('auth:web')->group(function () {
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
    
    Route::get('orders', function () {
        return view('admin.orders.index');
    })->name('orders');
    
    Route::get('settings', function () {
        return view('admin.settings.index');
    })->name('settings');
});