<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Request Attendance - {{ config('app.name', 'Bookshop') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" href="/favicon.ico" sizes="any">
</head>
<body class="bg-gray-50 font-sans pt-20">
<x-admin-navbar />

<main class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex items-center gap-4 mb-8">
        <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
        </div>
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900">Request Attendance</h1>
            <p class="text-gray-500 font-medium">{{ now()->format('l, F j, Y') }}</p>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-2xl font-medium">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-3xl shadow-xl shadow-gray-200/60 border border-gray-100 p-8">
        @if($todayRecord && $todayRecord->status === 'pending')
            <div class="text-center mb-8">
                <div class="w-20 h-20 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Attendance Pending</h2>
                <p class="text-gray-600">Your attendance request for today is awaiting administrator approval.</p>
                @if($todayRecord->notes)
                    <p class="text-gray-500 mt-2 text-sm italic">"{{ $todayRecord->notes }}"</p>
                @endif
            </div>
        @elseif($todayRecord && $todayRecord->status === 'approved')
            <div class="text-center mb-8">
                <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Attendance Approved</h2>
                <p class="text-gray-600">Your attendance for today has been approved.</p>
                @if($todayRecord->clock_in && $todayRecord->clock_out)
                    <div class="mt-4 space-y-2">
                        <p class="text-gray-600">Clock in: <strong>{{ \Carbon\Carbon::parse($todayRecord->clock_in)->format('g:i A') }}</strong></p>
                        <p class="text-gray-600">Clock out: <strong>{{ \Carbon\Carbon::parse($todayRecord->clock_out)->format('g:i A') }}</strong></p>
                    </div>
                @elseif($todayRecord->clock_in && ! $todayRecord->clock_out)
                    <div class="mt-4 space-y-2">
                        <p class="text-gray-600">Clocked in at: <strong>{{ \Carbon\Carbon::parse($todayRecord->clock_in)->format('g:i A') }}</strong></p>
                        <p class="text-gray-500 text-sm">Admin will update clock-out time after end of day.</p>
                    </div>
                @endif
            </div>
        @elseif($todayRecord && $todayRecord->status === 'rejected')
            <div class="text-center mb-4">
                <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Request Rejected</h2>
                <p class="text-gray-600">Your attendance request was not approved.</p>
            </div>
            <form method="POST" action="{{ route('admin.attendance.request') }}" class="space-y-6">
                @csrf
                <div>
                    <label for="notes" class="block text-sm font-bold text-gray-700 mb-2">Notes (optional)</label>
                    <textarea name="notes" id="notes" rows="3"
                        class="input-field resize-none" placeholder="Any notes for today...">{{ old('notes') }}</textarea>
                </div>
                <button type="submit" class="w-full btn-primary flex items-center justify-center gap-2">
                    Request Again
                </button>
            </form>
        @else
            <div class="text-center mb-8">
                <div class="w-20 h-20 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Request Attendance</h2>
                <p class="text-gray-600">Submit a request to mark your attendance for today.</p>
            </div>

            <form method="POST" action="{{ route('admin.attendance.request') }}" class="space-y-6">
                @csrf
                <div>
                    <label for="notes" class="block text-sm font-bold text-gray-700 mb-2">Notes (optional)</label>
                    <textarea name="notes" id="notes" rows="3"
                        class="input-field resize-none" placeholder="Any notes for today...">{{ old('notes') }}</textarea>
                </div>
                <button type="submit" class="w-full btn-primary flex items-center justify-center gap-2">
                    Submit Request
                </button>
            </form>
        @endif
    </div>

    @if($recentRecords->count() > 0)
    <div class="mt-8 bg-white rounded-3xl shadow-xl shadow-gray-200/60 border border-gray-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-900">Recent Records</h2>
            <p class="text-sm text-gray-500 mt-1">Your recent attendance history</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 uppercase tracking-wider">Clock In</th>
                        <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($recentRecords as $record)
                        <tr class="hover:bg-indigo-50/50 transition-colors">
                            <td class="px-6 py-4 text-gray-900 font-semibold">{{ \Carbon\Carbon::parse($record->date)->format('M j, Y') }}</td>
                            <td class="px-6 py-4 text-gray-600 font-medium">{{ $record->clock_in ? \Carbon\Carbon::parse($record->clock_in)->format('g:i A') : '—' }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold
                                    {{ $record->status === 'approved' ? 'bg-green-100 text-green-700' : '' }}
                                    {{ $record->status === 'pending' ? 'bg-amber-100 text-amber-700' : '' }}
                                    {{ $record->status === 'rejected' ? 'bg-red-100 text-red-700' : '' }}">
                                    {{ ucfirst($record->status) }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</main>

</body>
</html>
