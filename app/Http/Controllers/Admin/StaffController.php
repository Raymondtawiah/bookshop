<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class StaffController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);

        $staff = User::where('is_staff', true)
            ->withCount(['attendances as attendance_count' => function ($query) use ($month, $year) {
                $query->whereYear('date', $year)
                      ->whereMonth('date', $month)
                      ->where(function ($q) {
                          $q->where('status', 'approved')->orWhereNull('status');
                      });
            }])
            ->orderBy('created_at', 'desc')
            ->get();

        $pendingRequests = Attendance::with('user')
            ->where('status', 'pending')
            ->orderBy('date', 'desc')
            ->get();

        $months = [
            1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
            5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
            9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
        ];

        $years = range(now()->year - 2, now()->year + 1);

        return view('admin.staff.index', compact('staff', 'pendingRequests', 'months', 'years', 'month', 'year'));
    }

    public function create()
    {
        return view('admin.staff.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', Rule::in(['employee', 'finances', 'inventory'])],
        ]);

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
}
