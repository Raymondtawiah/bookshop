<?php

use App\Http\Controllers\Admin\AttendanceController;
use App\Http\Controllers\Admin\ExpenseController;
use App\Http\Controllers\Admin\FinanceController;
use App\Http\Controllers\Admin\IncomeController;
use App\Http\Controllers\Admin\PaymentController;
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

        Route::apiResource('finance/incomes', IncomeController::class);
        Route::apiResource('finance/expenses', ExpenseController::class);
        Route::apiResource('finance/payments', PaymentController::class);

        Route::get('finance/dashboard', [FinanceController::class, 'dashboard'])->name('finance.dashboard');
        Route::get('finance/reports', [FinanceController::class, 'reportIndex'])->name('finance.reports');
        Route::get('finance/my-requests', [FinanceController::class, 'myRequests'])->name('finance.my-requests');
        Route::post('finance/requests', [FinanceController::class, 'requestStore'])->name('finance.requests.store');
    });
});
