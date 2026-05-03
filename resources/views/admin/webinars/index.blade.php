@extends('layouts.admin')

@section('title', 'Webinars Management')

@section('content')
            <!-- Header Section -->
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-gray-900">Webinars</h1>
                <p class="text-gray-500">View all webinars and manage attendees</p>
            </div>

            <!-- Search Bar -->
            <div class="mb-8">
                <form action="{{ route('admin.webinars.index') }}" method="GET" class="flex gap-2 max-w-xl">
                    <div class="relative flex-1">
                        <input 
                            type="text" 
                            name="q"
                            value="{{ request('q') }}"
                            placeholder="Search by title or description..."
                            class="w-full px-5 py-3 pl-12 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all"
                        >
                        <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <button type="submit" class="px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                        Search
                    </button>
                    @if(request('q'))
                    <a href="{{ route('admin.webinars.index') }}" class="px-4 py-3 text-gray-600 hover:text-gray-900 font-medium">
                        Clear
                    </a>
                    @endif
                </form>
            </div>

            <!-- Webinars Table -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                @if($webinars->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b border-gray-100">
                                <tr>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Title</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Scheduled</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Price</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Registrations</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Paid</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Status</th>
                                    <th class="px-6 py-4 text-right text-sm font-semibold text-gray-900">Attendees</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($webinars as $webinar)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="font-medium text-gray-900 max-w-xs">{{ $webinar->title }}</div>
                                            <div class="text-sm text-gray-500 mt-1 max-w-xs truncate">{{ $webinar->description ?? 'No description' }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-sm text-gray-600">
                                                @if($webinar->scheduled_at)
                                                    {{ $webinar->scheduled_at->format('M d, Y h:i A') }}
                                                @else
                                                    <span class="text-gray-400">TBA</span>
                                                @endif
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="font-medium text-gray-900">₵{{ number_format($webinar->price, 2) }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-sm text-gray-600">{{ $webinar->total_registrations }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-sm text-gray-600">{{ $webinar->total_paid_registrations }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($webinar->status === 'active')
                                                <span class="px-2 py-0.5 text-xs bg-green-100 text-green-700 rounded">Active</span>
                                            @elseif($webinar->status === 'scheduled')
                                                <span class="px-2 py-0.5 text-xs bg-blue-100 text-blue-700 rounded">Scheduled</span>
                                            @elseif($webinar->status === 'completed')
                                                <span class="px-2 py-0.5 text-xs bg-gray-100 text-gray-700 rounded">Completed</span>
                                            @else
                                                <span class="px-2 py-0.5 text-xs bg-red-100 text-red-700 rounded">Inactive</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <a href="{{ route('admin.webinars.admin.show', $webinar->id) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors text-sm font-medium">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                                View Attendees
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="px-6 py-4 border-t border-gray-100">
                        {{ $webinars->links() }}
                    </div>
                @else
                    <div class="p-12 text-center">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <p class="text-gray-500">No webinars available at the moment.</p>
                    </div>
                @endif
            </div>
@endsection
