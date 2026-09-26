@extends('layouts.admin')

@section('title', 'Webinar Groups - Visa with Nathaniel')

@section('content')
    <div class="content">
        <div class="page-header">
            <div>
                <h1 class="page-title">Webinar Groups</h1>
                <p class="page-subtitle">Grouped by webinar title with attendee history.</p>
            </div>
            <a href="{{ route('admin.webinars.index') }}" class="p-2.5 bg-white text-indigo-600 border border-gray-200 rounded-xl hover:bg-indigo-50 transition-colors shadow-sm" title="Back to webinars">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
        </div>

        @if($groups->isEmpty())
            <div class="bg-white rounded-xl border border-gray-200 p-10 text-center text-gray-500">
                No webinar groups found.
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
            @foreach($groups as $title => $group)
                <a href="{{ route('admin.webinars.groups.show', $title) }}" class="block bg-white rounded-xl border border-gray-200 p-5 hover:border-indigo-300 hover:shadow-md transition-all">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <h2 class="text-sm font-semibold text-gray-900 mb-1">{{ $group['title'] }}</h2>
                            <p class="text-xs text-gray-500 line-clamp-2">{{ $group['description'] ?: 'No description provided.' }}</p>
                        </div>
                        <span class="shrink-0 text-[11px] font-medium text-gray-600 bg-gray-100 border border-gray-200 rounded-full px-2 py-1">
                            {{ $group['count'] }} {{ $group['count'] === 1 ? 'session' : 'sessions' }}
                        </span>
                    </div>

                    <div class="mt-4 flex items-center gap-4 text-xs text-gray-600">
                        <span class="inline-flex items-center gap-1.5 bg-gray-50 border border-gray-200 rounded-full px-2.5 py-1">
                            <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            {{ $group['total_registrations'] }} registrations
                        </span>
                        <span class="inline-flex items-center gap-1.5 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-full px-2.5 py-1">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $group['total_paid'] }} paid
                        </span>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
@endsection
