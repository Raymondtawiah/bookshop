<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ApproveAttendanceRequest;
use App\Http\Requests\Admin\MarkAttendanceRequest;
use App\Http\Requests\Admin\RejectAttendanceRequest;
use App\Http\Requests\Admin\StoreStaffRequest;
use App\Models\Attendance;
use App\Models\User;
use App\Services\AttendanceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AttendanceController extends Controller
{
    public function __construct(private AttendanceService $attendanceService) {}

    // Web routes
    public function index(Request $request)
    {
        $staff = User::where('is_staff', true)
            ->with('latestAttendance')
            ->orderBy('created_at', 'desc')
            ->get();

        $totalStaff = $staff->count();

        $pendingStaff = $staff->filter(fn($user) =>
            optional($user->latestAttendance)->status === 'pending' ||
            $user->attendances()->count() === 0
        )->count();

        $approvedStaff = $staff->filter(fn($user) =>
            optional($user->latestAttendance)->status === 'present'
        )->count();

        $rejectedStaff = $staff->filter(fn($user) =>
            optional($user->latestAttendance)->status === 'rejected'
        )->count();

        $attendances = Attendance::with('user', 'approver')
            ->orderByDesc('attendance_date')
            ->get();

        $monthlyTrend = [];
        for ($i = 6; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthLabel = $month->format('M Y');
            $monthStart = $month->copy()->startOfMonth();
            $monthEnd = $month->copy()->endOfMonth();

            $totalInMonth = Attendance::whereBetween('attendance_date', [$monthStart, $monthEnd])->count();
            $presentInMonth = Attendance::whereBetween('attendance_date', [$monthStart, $monthEnd])
                ->where('status', 'present')
                ->count();

            $percentage = $totalInMonth > 0 ? round(($presentInMonth / $totalInMonth) * 100, 1) : 0;

            $monthlyTrend[] = [
                'month' => $monthLabel,
                'total' => $totalInMonth,
                'present' => $presentInMonth,
                'percentage' => $percentage,
            ];
        }

        return view('admin.staff.index', compact('staff', 'totalStaff', 'pendingStaff', 'approvedStaff', 'rejectedStaff', 'attendances', 'monthlyTrend'));
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
        $query = $user->attendances()->orderByDesc('attendance_date');

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $attendances = $query->paginate(20);

        $totalDays = $attendances->total();
        $presentDays = $user->attendances()->where('status', 'present')->count();
        $avgAttendance = $totalDays > 0 ? round(($presentDays / $totalDays) * 100) : 0;

        $currentFilter = $request->input('status', 'all');

        return view('admin.staff.attendance-history', compact('user', 'attendances', 'totalDays', 'presentDays', 'avgAttendance', 'currentFilter'));
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

        $records = Attendance::with('user', 'approver')
            ->where('status', 'pending')
            ->orderByDesc('attendance_date')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'date' => $item->attendance_date,
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

    public function markAttendance(MarkAttendanceRequest $request)
    {
        $attendance = $this->attendanceService->markAttendance($request->user());

        return response()->json([
            'success' => true,
            'message' => 'Attendance submitted successfully.',
            'data' => $attendance,
        ], 201);
    }

    public function approveAttendance(ApproveAttendanceRequest $request, $attendanceId)
    {
        $attendance = $this->attendanceService->approveByAdmin(
            (int) $attendanceId,
            $request->user()
        );

        return redirect()->route('admin.staff.index')
            ->with('success', 'Attendance approved successfully.');
    }

    public function rejectAttendance(RejectAttendanceRequest $request, $attendanceId)
    {
        $reason = $request->validated('rejected_reason') ?: 'Rejected via admin panel';

        $attendance = $this->attendanceService->rejectByAdmin(
            (int) $attendanceId,
            $request->user(),
            $reason
        );

        return redirect()->route('admin.staff.index')
            ->with('success', 'Attendance rejected successfully.');
    }

    // API endpoint - approve by URL ID parameter
    public function approve($attendanceId)
    {
        $attendance = $this->attendanceService->approveByAdmin((int) $attendanceId, request()->user());

        return response()->json([
            'success' => true,
            'message' => 'Attendance approved.',
            'data' => $attendance,
        ]);
    }

    public function reject($attendanceId)
    {
        $reason = request()->input('rejected_reason', 'No reason provided');
        $attendance = $this->attendanceService->rejectByAdmin((int) $attendanceId, request()->user(), $reason);

        return response()->json([
            'success' => true,
            'message' => 'Attendance rejected.',
            'data' => $attendance,
        ]);
    }
}
