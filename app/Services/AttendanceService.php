<?php

namespace App\Services;

use App\Mail\AttendanceNotificationMail;
use App\Models\Attendance;
use App\Models\User;
use App\Notifications\AttendanceSubmitted;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class AttendanceService
{
    public function markAttendance(User $user): Attendance
    {
        $now = now();

        $attendance = Attendance::updateOrCreate(
            [
                'user_id' => $user->id,
                'attendance_date' => $now->toDateString(),
            ],
            [
                'status' => 'pending',
            ]
        );

        $admins = User::where('is_admin', true)->get();

        foreach ($admins as $admin) {
            $admin->notify(new AttendanceSubmitted($user, $attendance));
        }

        return $attendance;
    }

    public function clockOut(User $user, ?Attendance $attendance = null): Attendance
    {
        throw new \InvalidArgumentException('Clock out is not supported.');
    }

    public function approveByAdmin(int $attendanceId, User $admin): Attendance
    {
        return DB::transaction(function () use ($attendanceId, $admin) {
            $attendance = Attendance::where('id', $attendanceId)
                ->where('status', 'pending')
                ->firstOrFail();

            $attendance->update([
                'status' => 'present',
                'approved_by' => $admin->id,
                'approved_at' => now(),
            ]);

            $user = $attendance->user;
            $user->notify(new \App\Notifications\AttendanceApproved($attendance));

            return $attendance->fresh();
        });
    }

    public function rejectByAdmin(int $attendanceId, User $admin, string $reason): Attendance
    {
        return DB::transaction(function () use ($attendanceId, $admin, $reason) {
            $attendance = Attendance::where('id', $attendanceId)
                ->where('status', 'pending')
                ->firstOrFail();

            $attendance->update([
                'status' => 'rejected',
                'approved_by' => $admin->id,
                'approved_at' => now(),
                'rejected_reason' => $reason,
            ]);

            $user = $attendance->user;
            $user->notify(new \App\Notifications\AttendanceRejected($attendance));

            return $attendance->fresh();
        });
    }

    public function getPendingAttendances()
    {
        return Attendance::with('user')
            ->where('status', 'pending')
            ->orderByDesc('attendance_date')
            ->get();
    }

    public function getUserHistory(User $user)
    {
        return $user->attendances()
            ->orderByDesc('attendance_date')
            ->get()
            ->map(function ($attendance) {
                return [
                    'id' => $attendance->id,
                    'date' => $attendance->attendance_date,
                    'status' => $attendance->status,
                    'approved_at' => $attendance->approved_at?->format('M d, Y H:i'),
                    'rejected_reason' => $attendance->rejected_reason,
                ];
            });
    }
}
