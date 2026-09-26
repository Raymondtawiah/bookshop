@extends('layouts.admin')

@section('title', 'Attendance History - ' . $user->name)

@section('content')
    <div class="content">
        <!-- PAGE HEADER -->
        <div class="page-header">
            <div>
                <h1 class="page-title">Attendance History</h1>
                <p class="page-subtitle">Attendance records for {{ $user->name }}.</p>
            </div>
            <a href="{{ route('admin.staff.index') }}" class="add-book">Back to Staff</a>
        </div>

        <div class="panel">
            <div class="panel-header" style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-indigo-600 flex items-center justify-center text-white font-bold shadow-md">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </div>
                    <div>
                        <div class="panel-title" style="margin-bottom: 2px;">{{ $user->name }}</div>
                        <p class="text-sm text-gray-500">{{ $user->email }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    @php
                        $filters = [
                            ['label' => 'All', 'value' => 'all'],
                            ['label' => 'Present', 'value' => 'present'],
                            ['label' => 'Pending', 'value' => 'pending'],
                            ['label' => 'Rejected', 'value' => 'rejected'],
                        ];
                    @endphp
                    @foreach($filters as $filter)
                        @php
                            $isActive = $currentFilter === $filter['value'];
                            $activeClass = $isActive
                                ? 'bg-indigo-600 text-white shadow-md'
                                : 'bg-white text-gray-600 border border-gray-300 hover:bg-gray-50';
                        @endphp
                        <a href="{{ route('admin.staff.attendance', $user->id) }}?status={{ $filter['value'] }}"
                           class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all {{ $activeClass }}">
                            {{ $filter['label'] }}
                        </a>
                    @endforeach
                </div>
            </div>
            <div class="weekend-body" style="padding: 0;">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-50 text-gray-600 font-semibold">
                            <tr>
                                <th class="px-6 py-4 font-semibold whitespace-nowrap">Date</th>
                                <th class="px-6 py-4 font-semibold whitespace-nowrap">Status</th>
                                <th class="px-6 py-4 font-semibold whitespace-nowrap">Rejection Reason</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($attendances as $attendance)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 text-gray-900 font-medium">
                                    {{ \Carbon\Carbon::parse($attendance->attendance_date)->format('M j, Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $status = $attendance->status;
                                        $statusColors = [
                                            'present' => 'emerald',
                                            'pending' => 'amber',
                                            'rejected' => 'red',
                                            'absent' => 'red'
                                        ];
                                        $color = $statusColors[$status] ?? 'gray';
                                    @endphp
                                    <span class="inline-flex px-3 py-1 rounded-full text-xs font-bold bg-{{ $color }}-50 text-{{ $color }}-700">
                                        {{ ucfirst($status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-gray-600">
                                    {{ $attendance->status === 'rejected' && $attendance->rejected_reason ? $attendance->rejected_reason : '—' }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-6 py-12 text-center text-gray-500">No attendance records found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($attendances->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $attendances->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
@endsection