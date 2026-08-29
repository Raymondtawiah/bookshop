@extends('layouts.finance')

@section('title', 'Attendance')

@section('content')
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Attendance</h1>
        <p class="text-gray-600">Submit and track your attendance</p>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <p class="text-sm font-medium text-gray-500 mb-1">Total Days</p>
            <p class="text-2xl font-bold text-gray-900">{{ $totalDays }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <p class="text-sm font-medium text-gray-500 mb-1">Present Days</p>
            <p class="text-2xl font-bold text-green-600">{{ $presentDays }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <p class="text-sm font-medium text-gray-500 mb-1">Average Attendance</p>
            <p class="text-2xl font-bold text-gray-900">{{ $avgAttendance }}%</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Today's Attendance</h2>
        @if($todayAttendance)
            <div class="flex items-center gap-3">
                @php
                    $status = $todayAttendance->status;
                    $statusColors = ['pending' => 'bg-yellow-100 text-yellow-700', 'present' => 'bg-green-100 text-green-700', 'rejected' => 'bg-red-100 text-red-700'];
                    $statusColor = $statusColors[$status] ?? 'bg-gray-100 text-gray-700';
                @endphp
                <span class="inline-flex px-3 py-1 rounded-full text-sm font-bold {{ $statusColor }}">
                    {{ ucfirst($status) }}
                </span>
                <span class="text-sm text-gray-500">{{ $todayAttendance->attendance_date->format('M d, Y') }}</span>
            </div>
        @else
            <form method="POST" action="{{ route('finance.attendance.store') }}" class="flex items-center gap-4">
                @csrf
                <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium">Submit Attendance for Today</button>
                <span class="text-sm text-gray-500">{{ now()->format('M d, Y') }}</span>
            </form>
        @endif
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="text-lg font-semibold text-gray-900">Attendance History</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Approved By</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($attendances as $attendance)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm text-gray-900">{{ $attendance->attendance_date->format('M d, Y') }}</td>
                            <td class="px-4 py-3">
                                @php
                                    $status = $attendance->status;
                                    $statusColors = ['pending' => 'bg-yellow-100 text-yellow-700', 'present' => 'bg-green-100 text-green-700', 'rejected' => 'bg-red-100 text-red-700'];
                                    $statusColor = $statusColors[$status] ?? 'bg-gray-100 text-gray-700';
                                @endphp
                                <span class="inline-flex px-2 py-1 text-xs font-bold rounded-full {{ $statusColor }}">
                                    {{ ucfirst($status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ optional($attendance->approver)->name ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-100">
            {{ $attendances->links() }}
        </div>
    </div>
@endsection
