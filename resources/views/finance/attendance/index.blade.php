@extends('layouts.finance')

@section('title', 'Attendance')

@section('content')
    <div class="content">
        <div class="page-header">
            <div>
                <h1 class="page-title">Attendance</h1>
                <p class="page-subtitle">Submit and track your attendance</p>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl text-green-700 font-medium">
                {{ session('success') }}
            </div>
        @endif

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                        <line x1="16" y1="2" x2="16" y2="6"/>
                        <line x1="8" y1="2" x2="8" y2="6"/>
                        <line x1="3" y1="10" x2="21" y2="10"/>
                    </svg>
                </div>
                <div>
                    <p class="stat-label">Total Days</p>
                    <p class="stat-value stat-value--compact">{{ $totalDays }}</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="color: #16a34a;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                </div>
                <div>
                    <p class="stat-label">Present Days</p>
                    <p class="stat-value stat-value--compact" style="color: #16a34a;">{{ $presentDays }}</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="color: #4f46e5;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 12h-4l-3 9L9 3l-3 9H2"/>
                    </svg>
                </div>
                <div>
                    <p class="stat-label">Average Attendance</p>
                    <p class="stat-value stat-value--compact">{{ $avgAttendance }}%</p>
                </div>
            </div>
        </div>

        <div class="two-column">
            <div class="panel">
                <div class="panel-header">
                    <h2 class="panel-title">Today's Attendance</h2>
                </div>
                <div class="weekend-body">
                    @if($todayAttendance)
                        @php
                            $status = $todayAttendance->status;
                            $statusColors = ['pending' => 'bg-yellow-100 text-yellow-700', 'present' => 'bg-green-100 text-green-700', 'rejected' => 'bg-red-100 text-red-700'];
                            $statusColor = $statusColors[$status] ?? 'bg-gray-100 text-gray-700';
                        @endphp
                        <div class="event">
                            <div>
                                <div class="event-time">Today</div>
                                <div class="event-date">{{ $todayAttendance->attendance_date->format('M d, Y') }}</div>
                            </div>
                            <div class="event-name">
                                <span class="inline-flex px-3 py-1 rounded-full text-sm font-bold {{ $statusColor }}">
                                    {{ ucfirst($status) }}
                                </span>
                            </div>
                            <div class="event-right">
                                @if($todayAttendance->approver)
                                    <span class="text-sm text-gray-600">By {{ $todayAttendance->approver->name }}</span>
                                @else
                                    <span class="text-sm text-gray-500">Pending review</span>
                                @endif
                            </div>
                        </div>
                    @else
                        <form method="POST" action="{{ route('finance.attendance.store') }}" class="flex items-center gap-4">
                            @csrf
                            <button type="submit" class="add-book">Submit Attendance for Today</button>
                            <span class="text-sm text-gray-500">{{ now()->format('M d, Y') }}</span>
                        </form>
                    @endif
                </div>
            </div>

            <div class="panel">
                <div class="panel-header">
                    <h2 class="panel-title">Attendance Summary</h2>
                </div>
                <div class="weekend-body">
                    <div class="stats-grid" style="grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 10px;">
                        <div class="stat-card" style="min-height: auto; padding: 14px 16px;">
                            <p class="stat-label">Total</p>
                            <p class="stat-value stat-value--compact">{{ $totalDays }}</p>
                        </div>
                        <div class="stat-card" style="min-height: auto; padding: 14px 16px;">
                            <p class="stat-label">Present</p>
                            <p class="stat-value stat-value--compact" style="color: #16a34a;">{{ $presentDays }}</p>
                        </div>
                        <div class="stat-card" style="min-height: auto; padding: 14px 16px;">
                            <p class="stat-label">Avg</p>
                            <p class="stat-value stat-value--compact">{{ $avgAttendance }}%</p>
                        </div>
                    </div>
                    <div class="weekend-note">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <circle cx="12" cy="12" r="10"/>
                            <path d="M12 16v-4"/>
                            <path d="M12 8h.01"/>
                        </svg>
                        Attendance is reviewed by the admin team.
                    </div>
                </div>
            </div>
        </div>

        <div class="panel" style="margin-top: 24px;">
            <div class="panel-header">
                <h2 class="panel-title">Attendance History</h2>
            </div>
            <div class="weekend-body" style="padding: 0;">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-50 text-gray-600 font-semibold">
                            <tr>
                                <th class="px-6 py-4 font-semibold whitespace-nowrap">Date</th>
                                <th class="px-6 py-4 font-semibold whitespace-nowrap">Status</th>
                                <th class="px-6 py-4 font-semibold whitespace-nowrap">Approved By</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($attendances as $attendance)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 text-gray-900">{{ $attendance->attendance_date->format('M d, Y') }}</td>
                                    <td class="px-6 py-4">
                                        @php
                                            $status = $attendance->status;
                                            $statusColors = ['pending' => 'bg-yellow-100 text-yellow-700', 'present' => 'bg-green-100 text-green-700', 'rejected' => 'bg-red-100 text-red-700'];
                                            $statusColor = $statusColors[$status] ?? 'bg-gray-100 text-gray-700';
                                        @endphp
                                        <span class="inline-flex px-2 py-1 text-xs font-bold rounded-full {{ $statusColor }}">
                                            {{ ucfirst($status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-gray-600">{{ optional($attendance->approver)->name ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="p-4 border-t border-gray-100">
                    {{ $attendances->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
