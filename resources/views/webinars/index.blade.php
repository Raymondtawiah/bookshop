@extends('layouts.app')

@section('content')
    @if($registrationFormEnabled)
    <section id="register" class="relative overflow-hidden bg-white">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 py-20">
            <div class="bg-white border border-gray-200 rounded-[24px] p-10 sm:p-12 shadow-2xl relative overflow-hidden" style="box-shadow: 0 40px 80px -30px rgba(0,0,0,0.6);">
                <div class="absolute -top-20 -right-20 w-64 h-64 bg-gradient-to-br from-purple-500/20 to-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>

                @php
                    $webinar = $webinars->first();
                @endphp

                @if($webinar)
                    <div class="flex justify-center mb-5">
                        <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-indigo-50 border border-indigo-100 text-indigo-700 text-xs font-semibold uppercase tracking-widest">
                            <span class="relative flex h-2.5 w-2.5">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-green-500"></span>
                            </span>
                            Live Webinar
                        </span>
                    </div>

                    <div class="text-center mb-8">
                        <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-3 tracking-tight">{{ $webinar->title }}</h2>
                        <p class="text-base text-gray-600 leading-relaxed max-w-lg mx-auto">Practical guidance and live Q&amp;A with Nathaniel.</p>
                    </div>

                    <div class="flex items-center justify-center gap-3 mb-6">
                        <div class="flex gap-2 items-end" id="bars">
                            @php
                                $totalBars = 8;
                            @endphp
                            @for($i = 0; $i < $totalBars; $i++)
                                <span class="seat-color-step rounded bg-gray-900" style="width: {{ 16 + ($totalBars - $i) * 4 }}px; height: {{ 16 + ($totalBars - $i) * 4 }}px; animation-delay: {{ $i * 90 }}ms;"></span>
                            @endfor
                        </div>
                        <div class="text-xs text-gray-500">
                            <strong class="text-gray-900 font-semibold">Seats are filling up</strong>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center justify-center gap-3 mb-6 text-xs font-medium text-gray-600">
                        <span class="inline-flex items-center gap-1.5 bg-gray-50 border border-gray-200 rounded-full px-3 py-1.5">
                            <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            {{ $webinar->scheduled_at->format('M d, Y') }}
                            <span class="text-gray-400">•</span>
                            <span>{{ $webinar->scheduled_at->format('g:i A') }}</span>
                            @if($webinar->duration_minutes)
                                <span class="text-gray-400">•</span>
                                <span>{{ $webinar->duration_minutes }} min</span>
                            @endif
                        </span>

                        <span class="inline-flex items-center gap-1.5 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-full px-3 py-1.5">
                            @if(($webinar->payment_enabled ?? false) !== false && ($webinar->current_price ?? 0) > 0)
                                ${{ number_format($webinar->current_price, 2) }}
                            @else
                                FREE
                            @endif
                        </span>
                    </div>
                @endif

                <div class="h-px bg-gray-200 mb-6"></div>

                @php
                    $webinar = $webinars->first();
                @endphp

                @if($webinar)
                    <form id="registrationForm" method="POST" action="{{ route('webinars.register.store', $webinar) }}" novalidate>
                        @csrf
                        <input type="hidden" name="webinar_id" value="{{ $webinar->id ?? '' }}">

                        <div class="mb-4">
                            <label for="full_name" class="block text-sm font-semibold text-gray-900 mb-1.5">Full Name <span class="text-red-500">*</span></label>
                            <input type="text" name="full_name" id="full_name" required placeholder="e.g. Jane Doe" class="w-full px-3 py-2 border border-gray-200 rounded-md text-xs focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                        </div>

                        <div class="mb-4">
                            <label for="email" class="block text-sm font-semibold text-gray-900 mb-1.5">Email Address <span class="text-red-500">*</span></label>
                            <input type="email" name="email" id="email" required placeholder="you@example.com" class="w-full px-3 py-2 border border-gray-200 rounded-md text-xs focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                        </div>

                        <div class="mb-4">
                            <label for="phone" class="block text-sm font-semibold text-gray-900 mb-1.5">Phone Number <span class="text-red-500">*</span></label>
                            <input type="tel" name="phone" id="phone" required placeholder="+1xxxxxxxxxx" class="w-full px-3 py-2 border border-gray-200 rounded-md text-xs focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                        </div>

                        <div class="flex items-start gap-3 mb-4">
                            <input type="checkbox" name="terms" id="terms" required class="mt-0.5 w-3.5 h-3.5 accent-indigo-600">
                            <label for="terms" class="text-[11px] leading-relaxed text-gray-500">I agree to the <a href="#" class="text-indigo-600 font-semibold">terms and conditions</a> and understand that my registration will be confirmed upon payment.</label>
                        </div>

                        <button type="submit" class="w-full bg-indigo-600 text-white py-2.5 rounded-lg font-semibold text-xs hover:bg-indigo-700 transition-colors flex items-center justify-center gap-2">
                            Complete Registration
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                        </button>
                    </form>

                    <div id="registrationSuccess" class="hidden text-center py-10">
                        <div class="w-14 h-14 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto mb-4">
                            <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">You're almost in!</h3>
                        <p class="text-sm text-gray-500">This is a front-end demo — connect this form to your payment flow to confirm the registration.</p>
                    </div>

                    <p class="text-center text-xs text-gray-400 mt-6">Secure checkout &bull; Your info is never shared</p>
                @else
                    <div class="text-center py-8">
                        <div class="bg-gray-100 rounded-xl p-6 mb-4 border border-gray-200">
                            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <h3 class="text-xl font-semibold text-gray-700 mb-2">Webinar Not Found</h3>
                            <p class="text-gray-600">The webinar you are looking for does not exist.</p>
                        </div>
                        <a href="{{ route('webinars.index') }}" class="inline-block px-6 py-3 bg-indigo-600 text-white rounded-lg font-semibold hover:bg-indigo-700 transition-colors shadow-md hover:shadow-lg">
                            View All Webinars
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </section>
    @endif
@endsection

@include('chat-widget')
@include('components.cookie-consent')
