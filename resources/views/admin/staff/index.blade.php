<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Staff Management - {{ config('app.name', 'Bookshop') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" href="/favicon.ico" sizes="any">
</head>
<body class="bg-gray-50 font-sans pt-20">
<x-admin-navbar />

<main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex items-center justify-between mb-8">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-extrabold text-gray-900">Staff Management</h1>
                <p class="text-gray-500 font-medium">Manage your team members</p>
            </div>
        </div>
        <a href="{{ route('admin.staff.create') }}" class="px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl font-semibold hover:from-indigo-700 hover:to-purple-700 transition-all duration-200 shadow-lg shadow-indigo-200 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Add Staff
        </a>
    </div>

    @if($pendingRequests->count() > 0)
    <div class="bg-white rounded-3xl shadow-xl shadow-gray-200/60 border border-gray-100 overflow-hidden mb-6">
        <div class="px-6 py-5 border-b border-gray-200 bg-gradient-to-r from-amber-50 to-orange-50">
            <h2 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                Pending Attendance Requests
            </h2>
            <p class="text-sm text-gray-600 mt-1">Review and approve or reject attendance requests from staff</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 uppercase tracking-wider">Staff Member</th>
                        <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 uppercase tracking-wider">Notes</th>
                        <th class="px-6 py-4 text-center text-sm font-bold text-gray-700 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-center text-sm font-bold text-gray-700 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($pendingRequests as $request)
                    <tr class="hover:bg-amber-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-sm">
                                    {{ strtoupper(substr($request->user->name ?? $request->user->email, 0, 1)) }}
                                </div>
                                <span class="font-semibold text-gray-900">{{ $request->user->name ?? '—' }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-gray-600 font-medium">{{ \Carbon\Carbon::parse($request->date)->format('M j, Y') }}</td>
                        <td class="px-6 py-4 text-gray-600 font-medium">{{ $request->notes ?: 'No notes' }}</td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center justify-center px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-700">
                                Pending
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <form method="POST" action="{{ route('admin.attendance.approve', $request) }}" class="inline-block mr-2">
                                @csrf
                                <button type="submit" class="px-3 py-1.5 bg-green-600 text-white text-sm font-semibold rounded-lg hover:bg-green-700 transition-colors">
                                    Approve
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.attendance.reject', $request) }}" class="inline-block">
                                @csrf
                                <button type="submit" class="px-3 py-1.5 bg-red-600 text-white text-sm font-semibold rounded-lg hover:bg-red-700 transition-colors">
                                    Reject
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- Attendance Filter -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4 mb-6">
        <form method="GET" action="{{ route('admin.staff.index') }}" class="flex flex-wrap items-end gap-4">
            <div>
                <label for="month" class="block text-sm font-bold text-gray-700 mb-1">Month</label>
                <select name="month" id="month" class="px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none">
                    @foreach($months as $m => $name)
                        <option value="{{ $m }}" {{ (int)$month === $m ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="year" class="block text-sm font-bold text-gray-700 mb-1">Year</label>
                <select name="year" id="year" class="px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none">
                    @foreach($years as $y)
                        <option value="{{ $y }}" {{ (int)$year === $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="px-6 py-2 bg-indigo-600 text-white font-semibold rounded-xl hover:bg-indigo-700 transition-colors shadow-sm">
                Filter
            </button>
        </form>
    </div>

    <div class="bg-white rounded-3xl shadow-xl shadow-gray-200/60 border border-gray-100 overflow-hidden">
        @if($staff->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gradient-to-r from-gray-50 to-gray-100/80 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 uppercase tracking-wider">Phone</th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 uppercase tracking-wider">Role</th>
                            <th class="px-6 py-4 text-center text-sm font-bold text-gray-700 uppercase tracking-wider">Days Present</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($staff as $member)
                            <tr class="hover:bg-indigo-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-sm">
                                            {{ strtoupper(substr($member->name ?? $member->email, 0, 1)) }}
                                        </div>
                                        <span class="font-semibold text-gray-900">{{ $member->name ?? '—' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-gray-600 font-medium">{{ $member->email }}</td>
                                <td class="px-6 py-4 text-gray-600 font-medium">{{ $member->phone_number ?? '—' }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 text-xs font-bold rounded-full
                                        {{ $member->role === 'employee' ? 'bg-blue-100 text-blue-700' : '' }}
                                        {{ $member->role === 'finances' ? 'bg-green-100 text-green-700' : '' }}
                                        {{ $member->role === 'inventory' ? 'bg-amber-100 text-amber-700' : '' }}
                                        {{ ! $member->role ? 'bg-gray-100 text-gray-500' : '' }}">
                                        {{ ucfirst($member->role ?? 'N/A') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center justify-center w-10 h-10 rounded-full font-bold text-sm
                                        {{ $member->attendance_count > 0 ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                        {{ $member->attendance_count }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="p-16 text-center">
                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <p class="text-gray-500 mb-4 text-lg font-medium">No staff members yet.</p>
                <a href="{{ route('admin.staff.create') }}" class="inline-block px-5 py-2.5 text-indigo-600 hover:text-indigo-700 font-semibold hover:bg-indigo-50 rounded-lg transition-colors">
                    Add Your First Staff Member →
                </a>
            </div>
        @endif
    </div>
</main>

</body>
</html>
