<?php

use App\Http\Controllers\Api\AttendanceController;
use Illuminate\Support\Facades\Route;

Route::prefix('api')->group(function () {
    Route::post('admin/staff', [AttendanceController::class, 'createStaff']);
    Route::get('admin/users', [AttendanceController::class, 'staffIndex']);
    Route::get('admin/attendance/pending', [AttendanceController::class, 'pending']);
    Route::put('admin/attendance/{id}/approve', [AttendanceController::class, 'approve']);
    Route::put('admin/attendance/{id}/reject', [AttendanceController::class, 'reject']);
    Route::get('admin/attendance/summary', [AttendanceController::class, 'staffSummary']);
    Route::get('admin/attendance/{userId}/summary', [AttendanceController::class, 'userSummary']);

    Route::post('staff/login', [AttendanceController::class, 'staffLogin']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('attendance/mine', [AttendanceController::class, 'myAttendance']);
    });
});
