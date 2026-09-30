@extends('layouts.admin')

@section('title', 'Webinars - Visa with Nathaniel')

@section('content')
    <div class="content">
        <!-- PAGE HEADER -->
        <div class="page-header">
            <div>
                <h1 class="page-title">Webinars</h1>
                <p class="page-subtitle">Manage webinars, registrations and reminders.</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.webinars.index') }}" class="p-2.5 bg-white text-indigo-600 border border-gray-200 rounded-xl hover:bg-indigo-50 transition-colors shadow-sm" title="Back">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <a href="{{ route('admin.webinars.create') }}" class="p-2.5 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 transition-colors shadow-sm" title="Create Webinar">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                </a>
                <a href="{{ route('admin.webinars.groups') }}" class="p-2.5 bg-white text-indigo-600 border border-gray-200 rounded-xl hover:bg-indigo-50 transition-colors shadow-sm" title="Groups">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </a>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-6 mb-6">
            <form method="POST" action="{{ route('admin.webinars.toggleRegistrationForm') }}" class="flex items-center gap-3">
                @csrf
                <span class="text-sm font-medium text-gray-700">Registration form</span>
                <label class="toggle" title="Registration form">
                    <input type="checkbox" name="is_enabled" value="1" class="sr-only" {{ $registrationFormEnabled ? 'checked' : '' }}>
                    <span class="toggle-circle"></span>
                </label>
            </form>
        </div>

        <!-- WEBINARS LIST -->
        <div class="panel">
            <div class="panel-header">
                <h2 class="panel-title">All Webinars</h2>
            </div>
            <div class="panel-body">
                <div class="space-y-3">
                    @foreach($webinars as $webinar)
                        <div class="flex items-center gap-3">
                            <span class="text-sm font-medium text-gray-700">{{ $webinar->title }}</span>
                            @if($webinar->isExpired())
                                <span class="inline-flex items-center gap-1.5 bg-red-50 border border-red-100 text-red-700 rounded-full px-2.5 py-1 text-xs font-medium">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Expired
                                </span>
                            @endif
                            <div class="flex items-center gap-2">
                                <form method="POST" action="{{ route('admin.webinars.togglePayment', $webinar->id) }}" class="flex items-center gap-1">
                                    @csrf
                                    <label class="toggle" title="{{ $webinar->title }} payment">
                                        <input type="checkbox" name="payment_enabled" value="1" class="sr-only" {{ $webinar->payment_enabled ? 'checked' : '' }}>
                                        <span class="toggle-circle"></span>
                                    </label>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.toggle input[type="checkbox"]').forEach(function(input) {
                const toggle = input.closest('.toggle');
                const circle = toggle ? toggle.querySelector('.toggle-circle') : null;
                if (!circle) return;

                function updatePosition() {
                    if (input.checked) {
                        circle.style.transform = 'translateX(-22px)';
                    } else {
                        circle.style.transform = 'translateX(0)';
                    }
                }

                updatePosition();
                input.addEventListener('change', function() {
                    updatePosition();
                    setTimeout(function() {
                        input.closest('form').submit();
                    }, 300);
                });
            });
        });
    </script>
@endsection
