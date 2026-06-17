@extends('layouts.admin')

@section('title', 'Free Book Leads')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Free Book Leads</h1>
        <p class="text-gray-600">Manage and track lead captures for free book downloads</p>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 sm:gap-4 mb-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Total Leads</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalLeads }}</p>
                </div>
                <div class="bg-blue-50 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Downloaded</p>
                    <p class="text-2xl font-bold text-green-600">{{ $totalDownloaded }}</p>
                </div>
                <div class="bg-green-50 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Emails Sent</p>
                    <p class="text-2xl font-bold text-purple-600">{{ $totalNotified }}</p>
                </div>
                <div class="bg-purple-50 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-xl shadow-lg border border-indigo-100 p-4 sm:p-6 mb-8">
        <form method="GET" action="{{ route('admin.free-books') }}" class="space-y-4 sm:space-y-6">
            <div>
                <label for="search" class="block text-xs sm:text-sm font-semibold text-gray-700 mb-2">Search Leads</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text" name="search" id="search" placeholder="Search by name, email, or book title..."
                        class="w-full pl-10 pr-4 py-2 sm:py-3 text-sm border border-indigo-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white shadow-sm transition-all"
                        value="{{ request()->get('search') }}">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4">
                <div>
                    <label for="book_id" class="block text-xs sm:text-sm font-semibold text-gray-700 mb-2">Book</label>
                    <select name="book_id" id="book_id" class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm border border-indigo-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white shadow-sm transition-all">
                        <option value="">All Free Books</option>
                        @foreach($totalBooks as $bookOption)
                            <option value="{{ $bookOption->id }}" {{ request()->get('book_id') == $bookOption->id ? 'selected' : '' }}>
                                {{ $bookOption->title }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="downloaded" class="block text-xs sm:text-sm font-semibold text-gray-700 mb-2">Download Status</label>
                    <select name="downloaded" id="downloaded" class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm border border-indigo-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white shadow-sm transition-all">
                        <option value="">All</option>
                        <option value="yes" {{ request()->get('downloaded') === 'yes' ? 'selected' : '' }}>Downloaded</option>
                        <option value="no" {{ request()->get('downloaded') === 'no' ? 'selected' : '' }}>Not Downloaded</option>
                    </select>
                </div>
                <div>
                    <label for="notified" class="block text-xs sm:text-sm font-semibold text-gray-700 mb-2">Email Sent</label>
                    <select name="notified" id="notified" class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm border border-indigo-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white shadow-sm transition-all">
                        <option value="">All</option>
                        <option value="yes" {{ request()->get('notified') === 'yes' ? 'selected' : '' }}>Sent</option>
                        <option value="no" {{ request()->get('notified') === 'no' ? 'selected' : '' }}>Pending</option>
                    </select>
                </div>
                <div>
                    <label for="date_range" class="block text-xs sm:text-sm font-semibold text-gray-700 mb-2">Date Range</label>
                    <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2">
                        <input type="date" name="start_date" id="start_date" class="flex-1 px-3 py-2 text-sm border border-indigo-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white shadow-sm transition-all" value="{{ request()->get('start_date') }}">
                        <input type="date" name="end_date" id="end_date" class="flex-1 px-3 py-2 text-sm border border-indigo-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white shadow-sm transition-all" value="{{ request()->get('end_date') }}">
                    </div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-2 sm:gap-3 sm:justify-end">
                <button type="submit" class="w-full sm:w-auto px-6 sm:px-8 py-2 sm:py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg hover:from-indigo-700 hover:to-purple-700 transition-all font-semibold shadow-md text-sm">
                    Apply Filters
                </button>
                <a href="{{ route('admin.free-books') }}" class="w-full sm:w-auto px-6 sm:px-8 py-2 sm:py-3 text-gray-600 hover:text-gray-900 hover:bg-white rounded-lg transition-all font-semibold text-center shadow-sm text-sm">
                    Reset
                </a>
                <button type="submit" formaction="{{ route('admin.free-books.download-all') }}" class="w-full sm:w-auto px-6 sm:px-8 py-2 sm:py-3 bg-gradient-to-r from-green-600 to-teal-600 text-white rounded-lg hover:from-green-700 hover:to-teal-700 transition-all font-semibold shadow-md text-sm inline-flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Export CSV
                </button>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-lg border border-gray-100">
        <div class="bg-gradient-to-r from-green-600 to-teal-600 px-4 sm:px-6 py-3 sm:py-4">
            <h2 class="text-base sm:text-lg font-semibold text-white">Lead List</h2>
            <p class="text-green-100 text-xs sm:text-sm">All free book download leads</p>
        </div>
        @if($leads->isNotEmpty())
            <div class="overflow-x-auto">
                <table class="w-full table-fixed">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-2 py-2 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Lead #</th>
                            <th class="px-2 py-2 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Name</th>
                            <th class="px-2 py-2 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Email</th>
                            <th class="px-2 py-2 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Book</th>
                            <th class="px-2 py-2 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Downloaded</th>
                            <th class="px-2 py-2 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Email Sent</th>
                            <th class="px-2 py-2 text-left text-xs font-bold text-gray-700 uppercase tracking-wider w-24">Date</th>
                            <th class="px-2 py-2 text-left text-xs font-bold text-gray-700 uppercase tracking-wider w-20">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($leads as $lead)
                        <tr class="hover:bg-indigo-50 transition-colors">
                            <td class="px-2 py-3">
                                <span class="font-semibold text-gray-900 text-xs">#{{ $lead->id }}</span>
                            </td>
                            <td class="px-2 py-3 text-xs text-gray-600 truncate">
                                {{ $lead->full_name }}
                            </td>
                            <td class="px-2 py-3 text-xs text-gray-600 truncate">
                                <a href="mailto:{{ $lead->email }}" class="text-indigo-600 hover:text-indigo-800">{{ $lead->email }}</a>
                            </td>
                            <td class="px-2 py-3 text-xs text-gray-600 truncate">
                                {{ $lead->book_title }}
                            </td>
                            <td class="px-2 py-3 whitespace-nowrap">
                                @if($lead->downloaded_at)
                                    <span class="inline-flex px-1 py-0.5 text-xs font-bold rounded-full bg-green-100 text-green-700">Yes</span>
                                    <div class="text-xs text-gray-500 mt-1">{{ $lead->downloaded_at->format('M d, Y H:i') }}</div>
                                @else
                                    <span class="inline-flex px-1 py-0.5 text-xs font-bold rounded-full bg-gray-100 text-gray-600">No</span>
                                @endif
                            </td>
                            <td class="px-2 py-3 whitespace-nowrap">
                                @if($lead->notified_at)
                                    <span class="inline-flex px-1 py-0.5 text-xs font-bold rounded-full bg-green-100 text-green-700">Sent</span>
                                @else
                                    <span class="inline-flex px-1 py-0.5 text-xs font-bold rounded-full bg-yellow-100 text-yellow-700">Pending</span>
                                @endif
                            </td>
                            <td class="px-2 py-3 text-xs text-gray-600">
                                {{ $lead->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-2 py-3 whitespace-nowrap">
                                <a href="{{ route('admin.free-books.download', $lead) }}" class="text-indigo-600 hover:text-indigo-800" title="Download PDF">
                                    <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                    </svg>
                                </a>
                                <a href="mailto:{{ $lead->email }}" class="text-indigo-600 hover:text-indigo-800 ml-2" title="Email Lead">
                                    <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-16">
                <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <p class="text-gray-500 font-semibold">No leads found</p>
                <p class="text-gray-400 text-sm mt-1">Leads will appear when visitors request free book downloads</p>
            </div>
        @endif
    </div>

    <div class="mt-6">
        {{ $leads->appends(request()->query())->links() }}
    </div>
</div>
@endsection
