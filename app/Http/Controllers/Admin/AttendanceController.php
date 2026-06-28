<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreStaffRequest;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AttendanceController extends Controller
{
    // Web routes
    public function index(Request $request)
    {
        $staff = User::where('is_staff', true)
            ->orderBy('created_at', 'desc')
            ->get();

        $totalStaff = $staff->count();
        
        $pendingStaff = $staff->filter(fn($user) => 
            optional($user->attendances()->latest()->first())->status === 'pending' || 
            ($user->attendances()->count() === 0)
        )->count();
        
        $approvedStaff = $staff->filter(fn($user) => 
            optional($user->attendances()->latest()->first())->status === 'present'
        )->count();
        
        $rejectedStaff = $staff->filter(fn($user) => 
            optional($user->attendances()->latest()->first())->status === 'rejected'
        )->count();

        return view('admin.staff.index', compact('staff', 'totalStaff', 'pendingStaff', 'approvedStaff', 'rejectedStaff'));
    }

    public function create()
    {
        return view('admin.staff.create');
    }

    public function store(StoreStaffRequest $request)
    {
        $validated = $request->validated();

        User::create([
            'name' => $validated['name'] ?? explode('@', $validated['email'])[0],
            'email' => $validated['email'],
            'phone_number' => $validated['phone_number'] ?? null,
            'password' => $validated['password'],
            'role' => $validated['role'],
            'is_staff' => true,
        ]);

        return redirect()->route('admin.staff.index')
            ->with('success', 'Staff member created successfully.');
    }

    // Web route - shows attendance history page for a staff user
    public function history(Request $request, User $user)
    {
        $attendances = $user->attendances()
            ->orderByDesc('date')
            ->paginate(20);

        $totalDays = $attendances->total();
        $presentDays = $user->attendances()->where('status', 'present')->count();
        $avgAttendance = $totalDays > 0 ? round(($presentDays / $totalDays) * 100) : 0;

        return view('admin.staff.attendance-history', compact('user', 'attendances', 'totalDays', 'presentDays', 'avgAttendance'));
    }

    // API endpoint - create staff
    public function createStaff(Request $request)
    {
        $user = $request->user();

        if (! $user->is_admin) {
            abort(403, 'Unauthorized.');
        }

        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'email' => 'required|email|max:255',
            'phone_number' => 'nullable|string|max:20',
            'password' => 'required|string|min:8',
            'role' => 'required|string',
        ]);

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

    // API endpoint - list staff
    public function staffIndex(Request $request)
    {
        $user = $request->user();

        if (! $user->is_admin) {
            abort(403, 'Unauthorized.');
        }

        $staff = User::where('is_staff', true)
            ->orderBy('created_at', 'desc')
            ->get(['id', 'name', 'email', 'role', 'phone_number', 'created_at']);

        return response()->json([
            'success' => true,
            'data' => $staff,
        ]);
    }

    // API endpoint - pending attendance
    public function pending(Request $request)
    {
        $user = $request->user();

        if (! $user->is_admin) {
            abort(403, 'Unauthorized.');
        }

        $records = Attendance::with('user')
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

    public function markAttendance(Request $request)
    {
        $user = $request->user();

        if (! $user->is_staff) {
            abort(403, 'Unauthorized.');
        }

        Attendance::updateOrCreate(
            ['user_id' => $user->id, 'date' => now()->toDateString()],
            ['status' => 'pending']
        );

        return response()->json([
            'success' => true,
            'message' => 'Attendance marked successfully.',
        ]);
    }

    // API endpoint - my attendance
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
}