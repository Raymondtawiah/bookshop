<?php

use App\Http\Controllers\Admin\FinanceController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::prefix('finance')->name('finance.')->middleware(['auth:web', 'finance'])->group(function () {
    Route::get('dashboard', [FinanceController::class, 'dashboardWeb'])->name('dashboard');
    Route::get('attendance', [FinanceController::class, 'attendanceWeb'])->name('attendance');
    Route::get('attendance-data', [FinanceController::class, 'attendanceData'])->name('attendance-data');
    Route::get('dashboard-data', [FinanceController::class, 'dashboard'])->name('dashboard-data');
    Route::get('incomes', [FinanceController::class, 'incomesWeb'])->name('incomes');
    Route::get('incomes-data', [FinanceController::class, 'incomeIndex'])->name('incomes-data');
    Route::post('incomes', [FinanceController::class, 'incomeStore'])->name('incomes-store');
    Route::get('incomes/{id}', [FinanceController::class, 'incomeShow'])->name('incomes-show');
    Route::put('incomes/{id}', [FinanceController::class, 'incomeUpdate'])->name('incomes-update');
    Route::delete('incomes/{id}', [FinanceController::class, 'incomeDestroy'])->name('incomes-destroy');
    Route::get('expenses', [FinanceController::class, 'expensesWeb'])->name('expenses');
    Route::get('expenses-data', [FinanceController::class, 'expenseIndex'])->name('expenses-data');
    Route::get('payments', [FinanceController::class, 'paymentsWeb'])->name('payments');
    Route::get('payments-data', [FinanceController::class, 'paymentIndex'])->name('payments-data');
    Route::get('reports', [FinanceController::class, 'reportsWeb'])->name('reports');
    Route::get('settings', [FinanceController::class, 'settingsWeb'])->name('settings');
    Route::post('logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route('login');
    })->name('logout');
    Route::post('requests', [FinanceController::class, 'requestStore'])->name('requests.store');
});