<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CreateStaffRequest;
use App\Http\Requests\Api\StaffLoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AttendanceController extends Controller
{
    public function clockIn(Request $request)
    {
        $user = $request->user();
        $today = now()->toDateString();

        $attendance = $user->attendances()->updateOrCreate(
            ['date' => $today],
            ['status' => 'pending']
        );

        return response()->json([
            'success' => true,
            'message' => 'Attendance request sent for approval.',
            'data' => $attendance,
        ]);
    }

    public function clockOut(Request $request)
    {
        $user = $request->user();
        $today = now()->toDateString();

        $attendance = $user->attendances()->whereDate('date', $today)->first();

        if (! $attendance) {
            return response()->json([
                'success' => false,
                'message' => 'No attendance request found for today.',
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => 'Attendance updated.',
            'data' => $attendance,
        ]);
    }

    public function myAttendance(Request $request)
    {
        $user = $request->user();

        $records = $user->attendances()
            ->orderByDesc('date')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'date' => $item->date,
                    'status' => $item->status,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $records,
        ]);
    }

    public function pending(Request $request)
    {
        $records = \App\Models\Attendance::with('user')
            ->where('status', 'pending')
            ->orderByDesc('date')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'date' => $item->date,
                    'status' => $item->status,
                    'user' => [
                        'id' => $item->user->id,
                        'name' => $item->user->name,
                        'email' => $item->user->email,
                        'role' => $item->user->role,
                    ],
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $records,
        ]);
    }

    public function approve(Request $request, $id)
    {
        $record = \App\Models\Attendance::findOrFail($id);
        $record->update(['status' => 'approved']);

        return response()->json([
            'success' => true,
            'message' => 'Attendance approved.',
            'data' => $record,
        ]);
    }

    public function reject(Request $request, $id)
    {
        $record = \App\Models\Attendance::findOrFail($id);
        $record->update(['status' => 'rejected']);

        return response()->json([
            'success' => true,
            'message' => 'Attendance rejected.',
            'data' => $record,
        ]);
    }

    public function staffLogin(StaffLoginRequest $request)
    {
        $validated = $request->validated();

        $user = User::where('email', $validated['email'])->first();

        if (! $user || ! Hash::check($validated['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        if (! $user->is_staff) {
            throw ValidationException::withMessages([
                'email' => ['You are not authorized to access staff features.'],
            ]);
        }

        $token = $user->createToken('staff-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'is_staff' => $user->is_staff,
            ],
        ]);
    }

    public function createStaff(CreateStaffRequest $request)
    {
        $validated = $request->validated();

        $user = User::create([
            'name' => $validated['name'] ?? explode('@', $validated['email'])[0],
            'email' => $validated['email'],
            'phone_number' => $validated['phone_number'] ?? null,
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'is_staff' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Staff member created successfully.',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'is_staff' => $user->is_staff,
            ],
        ], 201);
    }

    public function staffIndex(Request $request)
    {
        $staff = User::where('is_staff', true)
            ->orderBy('created_at', 'desc')
            ->get(['id', 'name', 'email', 'role', 'phone_number', 'created_at']);

        return response()->json([
            'success' => true,
            'data' => $staff,
        ]);
    }

    public function staffSummary(StaffSummaryRequest $request)
    {
        $statusFilter = $request->query('status', 'approved');

        $admin = $request->user();

        if (! $admin || ! $admin->is_admin) {
            abort(403, 'Unauthorized.');
        }

        $staff = User::where('is_staff', true)->get(['id', 'name', 'email', 'role']);

        $data = $staff->map(function ($user) use ($statusFilter) {
            $query = $user->attendances();
            if ($statusFilter !== 'all') {
                $query->where('status', $statusFilter);
            }

            $base = clone $query;

            return [
                'user_id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'weekly' => (clone $base)->whereBetween('date', [now()->startOfWeek()->toDateString(), now()->endOfWeek()->toDateString()])->count(),
                'monthly' => (clone $base)->whereBetween('date', [now()->startOfMonth()->toDateString(), now()->endOfMonth()->toDateString()])->count(),
                'yearly' => (clone $base)->whereBetween('date', [now()->startOfYear()->toDateString(), now()->endOfYear()->toDateString()])->count(),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    public function userSummary(StaffSummaryRequest $request, $userId)
    {
        $statusFilter = $request->query('status', 'approved');

        $admin = $request->user();

        if (! $admin || ! $admin->is_admin) {
            abort(403, 'Unauthorized.');
        }

        $staffUser = User::where('is_staff', true)->findOrFail($userId);

        $query = $staffUser->attendances();
        if ($statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'user_id' => $staffUser->id,
                'name' => $staffUser->name,
                'email' => $staffUser->email,
                'role' => $staffUser->role,
                'weekly' => (clone $query)->whereBetween('date', [now()->startOfWeek()->toDateString(), now()->endOfWeek()->toDateString()])->count(),
                'monthly' => (clone $query)->whereBetween('date', [now()->startOfMonth()->toDateString(), now()->endOfMonth()->toDateString()])->count(),
                'yearly' => (clone $query)->whereBetween('date', [now()->startOfYear()->toDateString(), now()->endOfYear()->toDateString()])->count(),
            ],
        ]);
    }
}

        $statusFilter = $request->query('status', 'approved');

        $staff = User::where('is_staff', true)->get(['id', 'name', 'email', 'role']);

        $data = $staff->map(function ($user) use ($statusFilter) {
            $query = $user->attendances();
            if ($statusFilter !== 'all') {
                $query->where('status', $statusFilter);
            }

            $base = clone $query;

            return [
                'user_id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'weekly' => (clone $base)->whereBetween('date', [now()->startOfWeek()->toDateString(), now()->endOfWeek()->toDateString()])->count(),
                'monthly' => (clone $base)->whereBetween('date', [now()->startOfMonth()->toDateString(), now()->endOfMonth()->toDateString()])->count(),
                'yearly' => (clone $base)->whereBetween('date', [now()->startOfYear()->toDateString(), now()->endOfYear()->toDateString()])->count(),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    public function userSummary(Request $request, $userId)
    {
        $request->validate([
            'status' => ['nullable', 'in:all,approved,pending,rejected'],
        ]);

        $admin = $request->user();

        if (! $admin || ! $admin->is_admin) {
            abort(403, 'Unauthorized.');
        }

        $staffUser = User::where('is_staff', true)->findOrFail($userId);
        $statusFilter = $request->query('status', 'approved');

        $query = $staffUser->attendances();
        if ($statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'user_id' => $staffUser->id,
                'name' => $staffUser->name,
                'email' => $staffUser->email,
                'role' => $staffUser->role,
                'weekly' => (clone $query)->whereBetween('date', [now()->startOfWeek()->toDateString(), now()->endOfWeek()->toDateString()])->count(),
                'monthly' => (clone $query)->whereBetween('date', [now()->startOfMonth()->toDateString(), now()->endOfMonth()->toDateString()])->count(),
                'yearly' => (clone $query)->whereBetween('date', [now()->startOfYear()->toDateString(), now()->endOfYear()->toDateString()])->count(),
            ],
        ]);
    }
}
