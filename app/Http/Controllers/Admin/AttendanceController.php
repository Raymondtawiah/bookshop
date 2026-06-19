<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $today = Carbon::today()->toDateString();

        $todayRecord = Attendance::where('user_id', $user->id)
            ->where('date', $today)
            ->first();

        $recentRecords = Attendance::where('user_id', $user->id)
            ->orderBy('date', 'desc')
            ->limit(10)
            ->get();

        return view('admin.attendance.index', compact('todayRecord', 'recentRecords'));
    }

    public function requestAttendance(Request $request)
    {
        $request->validate([
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $user = Auth::user();
        $today = Carbon::now()->format('Y-m-d');

        $record = Attendance::where('user_id', $user->id)
            ->whereDate('date', $today)
            ->first();

        if ($record) {
            $record->update(['status' => 'pending']);
        } else {
            $record = Attendance::create([
                'user_id' => $user->id,
                'date' => $today,
                'status' => 'pending',
            ]);
        }

        if ($request->filled('notes')) {
            $record->update(['notes' => $request->notes]);
        }

        return redirect()->back()->with('success', 'Attendance request submitted successfully.');
    }

    public function approve($id)
    {
        $record = Attendance::findOrFail($id);
        $record->update(['status' => 'approved']);

        return redirect()->back()->with('success', 'Attendance request approved successfully.');
    }

    public function reject($id)
    {
        $record = Attendance::findOrFail($id);
        $record->update(['status' => 'rejected']);

        return redirect()->back()->with('success', 'Attendance request rejected successfully.');
    }
}
