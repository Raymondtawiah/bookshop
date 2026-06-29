<?php

use App\Http\Controllers\Admin\AttendanceController;
use App\Http\Controllers\Admin\FinanceController;
use Illuminate\Support\Facades\Route;

Route::name('api.')->group(function () {
    Route::post('admin/staff', [AttendanceController::class, 'createStaff']);
    Route::get('admin/users', [AttendanceController::class, 'staffIndex']);
    Route::get('admin/attendance/pending', [AttendanceController::class, 'pending']);
    Route::put('admin/attendance/{id}/approve', [AttendanceController::class, 'approve']);
    Route::put('admin/attendance/{id}/reject', [AttendanceController::class, 'reject']);
    Route::get('admin/attendance/summary', [AttendanceController::class, 'staffSummary']);
    Route::get('admin/attendance/{userId}/summary', [AttendanceController::class, 'userSummary']);

    Route::post('staff/login', [AttendanceController::class, 'staffLogin']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('attendance/mark', [AttendanceController::class, 'markAttendance'])->name('attendance.mark');

        // Finance routes
        Route::get('finance/dashboard', [FinanceController::class, 'dashboard'])->name('finance.dashboard');
        Route::get('finance/incomes', [FinanceController::class, 'incomeIndex'])->name('finance.incomes');
        Route::get('finance/incomes/{id}', [FinanceController::class, 'incomeShow'])->name('finance.incomes.show');
        Route::post('finance/incomes', [FinanceController::class, 'incomeStore'])->name('finance.incomes.store');
        Route::put('finance/incomes/{id}', [FinanceController::class, 'incomeUpdate'])->name('finance.incomes.update');
        Route::delete('finance/incomes/{id}', [FinanceController::class, 'incomeDestroy'])->name('finance.incomes.destroy');

        Route::get('finance/expenses', [FinanceController::class, 'expenseIndex'])->name('finance.expenses');
        Route::get('finance/expenses/{id}', [FinanceController::class, 'expenseShow'])->name('finance.expenses.show');
        Route::post('finance/expenses', [FinanceController::class, 'expenseStore'])->name('finance.expenses.store');
        Route::put('finance/expenses/{id}', [FinanceController::class, 'expenseUpdate'])->name('finance.expenses.update');
        Route::delete('finance/expenses/{id}', [FinanceController::class, 'expenseDestroy'])->name('finance.expenses.destroy');

        Route::get('finance/payments', [FinanceController::class, 'paymentIndex'])->name('finance.payments');
        Route::get('finance/payments/{id}', [FinanceController::class, 'paymentShow'])->name('finance.payments.show');
        Route::post('finance/payments', [FinanceController::class, 'paymentStore'])->name('finance.payments.store');
        Route::put('finance/payments/{id}', [FinanceController::class, 'paymentUpdate'])->name('finance.payments.update');
        Route::delete('finance/payments/{id}', [FinanceController::class, 'paymentDestroy'])->name('finance.payments.destroy');

        Route::get('finance/reports', [FinanceController::class, 'reportIndex'])->name('finance.reports');

        // Finance Request routes
        Route::get('finance/my-requests', [FinanceController::class, 'myRequests'])->name('finance.my-requests');
        Route::post('finance/requests', [FinanceController::class, 'requestStore'])->name('finance.requests.store');
    });
});
