@extends('layouts.admin')

@section('title', 'Attendance History - ' . $user->name)

@section('content')
<div class="space-y-8">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-zinc-900 dark:text-zinc-100">Attendance History</h1>
        <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Attendance records for {{ $user->name }}.</p>
    </div>

    <div class="bg-white dark:bg-zinc-800 rounded-3xl border border-zinc-200 dark:border-zinc-700 shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-zinc-200 dark:border-zinc-700">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-400 to-indigo-600 flex items-center justify-center text-white font-bold shadow-md">
                    {{ strtoupper(substr($user->name, 0, 2)) }}
                </div>
                <div>
                    <h2 class="text-xl font-bold text-zinc-900 dark:text-zinc-100">{{ $user->name }}</h2>
                    <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ $user->email }}</p>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-zinc-50 dark:bg-zinc-900/50 text-zinc-600 dark:text-zinc-400 font-semibold">
                    <tr>
                        <th class="px-6 py-4 font-semibold">Date</th>
                        <th class="px-6 py-4 font-semibold">Status</th>
                        <th class="px-6 py-4 font-semibold">Notes</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100 dark:divide-zinc-700">
                    @forelse($attendances as $attendance)
                    <tr class="card-hover">
                        <td class="px-6 py-4 text-zinc-900 dark:text-zinc-100 font-medium">
                            {{ \Carbon\Carbon::parse($attendance->date)->format('M j, Y') }}
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
                                $color = $statusColors[$status] ?? 'zinc';
                            @endphp
                            <span class="inline-flex px-3 py-1 rounded-full text-xs font-bold bg-{{ $color }}-50 text-{{ $color }}-700 ring-1 ring-{{ $color }}-200">
                                {{ ucfirst($status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-zinc-600 dark:text-zinc-400">{{ $attendance->notes ?: '—' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-6 py-12 text-center text-zinc-500">No attendance records found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($attendances->hasPages())
        <div class="px-6 py-4 border-t border-zinc-200 dark:border-zinc-700">
            {{ $attendances->links() }}
        </div>
        @endif
    </div>

    <div class="flex items-center justify-between">
        <a href="{{ route('admin.staff.index') }}" class="px-4 py-2 rounded-xl border border-zinc-300 dark:border-zinc-600 text-sm font-semibold text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-700 transition-colors">
            Back to Staff
        </a>
        <div class="text-sm text-zinc-500 dark:text-zinc-400">
            Total: <span class="font-semibold text-zinc-900 dark:text-zinc-100">{{ $attendances->total() }}</span> records
        </div>
    </div>
</div>
@endsection