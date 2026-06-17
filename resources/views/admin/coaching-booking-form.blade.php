<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Book Your Coaching Session - {{ config('app.name', 'Nathaniel Gyarteng') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" href="/favicon.ico" sizes="any">
    <script src="https://cdn.tailwindcss.com"></script>
     <style>
        [x-cloak] { display: none !important; }
        .gradient-bg { background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); }
        .countdown-pulse { animation: pulse 2s infinite; }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
        html, body {
            max-width: 100% !important;
            overflow-x: hidden !important;
        }
    </style>
</head>
    <body class="bg-gray-50 font-sans pt-14 m-0 p-0 box-border w-full min-w-0">
        <x-flash-message />
        
        @include('components.customer-navbar')
        
        <div class="w-full overflow-x-hidden min-w-0">

        @php
            $isActive = \App\Models\SiteSetting::get('coaching_booking_active', 'true') === 'true';
            $isAdmin = auth()->check() && auth()->user()->is_admin;
        @endphp

        @if(!$isActive && !$isAdmin)
        <div id="disabled-message" class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-20 text-center">
            <div class="bg-white rounded-2xl shadow-sm p-12">
                <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                    </svg>
                </div>
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Booking Currently Unavailable</h2>
                <p class="text-gray-600 text-lg">We're sorry, but the coaching booking form is currently not available.</p>
                <p class="text-gray-500 mt-4">Please check back later or contact us for more information.</p>
            </div>
        </div>
        @endif

        @if($isActive || $isAdmin)

        <!-- Plan Selection Header -->
        <section class="py-8 bg-white">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold text-gray-900 mb-4">Complete Your Booking</h1>
                    <p class="text-lg text-gray-600">
                        @if($plan === 'team')
                            You've selected the <strong>Team Coaching Plan</strong> - $49.99
                        @elseif($plan === 'single')
                            You've selected the <strong>1 Week Interview Intensive</strong> - $129.78
                        @elseif($plan === 'premium')
                            You've selected the <strong>Full Coaching Program</strong> - $216.36
                        @endif
                    </p>
                    <a href="{{ route('coaching.booking') }}" class="text-indigo-600 hover:text-indigo-800 underline mt-4 inline-block">
                        ← Choose a different plan
                    </a>
                </div>
            </div>
        </section>

        <!-- Booking Form Section -->
        <section id="booking-form" class="py-8 bg-gray-50">
            <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-white rounded-2xl shadow-sm p-8">
                    <form method="POST" action="{{ route('coaching.store') }}" class="space-y-6">
                        @csrf
                        
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address *</label>
                                <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                                <input type="tel" name="phone" id="phone" value="{{ old('phone') }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                            
                            <div>
                                <label for="interview_type" class="block text-sm font-medium text-gray-700 mb-1">Interview Type *</label>
                                <select name="interview_type" id="interview_type" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="">Select type...</option>
                                    <option value="Visa Interview" {{ old('interview_type') == 'Visa Interview' ? 'selected' : '' }}>Visa Interview</option>
                                    <option value="Job Interview" {{ old('interview_type') == 'Job Interview' ? 'selected' : '' }}>Job Interview</option>
                                    <option value="University Interview" {{ old('interview_type') == 'University Interview' ? 'selected' : '' }}>University Interview</option>
                                    <option value="Immigration Interview" {{ old('interview_type') == 'Immigration Interview' ? 'selected' : '' }}>Immigration Interview</option>
                                    <option value="Other" {{ old('interview_type') == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('interview_type')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="interview_date" class="block text-sm font-medium text-gray-700 mb-1">Interview Date *</label>
                                <input type="date" name="interview_date" id="interview_date" value="{{ old('interview_date') }}" required
                                    min="{{ date('Y-m-d') }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                @error('interview_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="interview_time" class="block text-sm font-medium text-gray-700 mb-1">Preferred Time *</label>
                                <select name="interview_time" id="interview_time" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="">Select time...</option>
                                    <option value="09:00" {{ old('interview_time') == '09:00' ? 'selected' : '' }}>9:00 AM</option>
                                    <option value="10:00" {{ old('interview_time') == '10:00' ? 'selected' : '' }}>10:00 AM</option>
                                    <option value="11:00" {{ old('interview_time') == '11:00' ? 'selected' : '' }}>11:00 AM</option>
                                    <option value="12:00" {{ old('interview_time') == '12:00' ? 'selected' : '' }}>12:00 PM</option>
                                    <option value="13:00" {{ old('interview_time') == '13:00' ? 'selected' : '' }}>1:00 PM</option>
                                    <option value="14:00" {{ old('interview_time') == '14:00' ? 'selected' : '' }}>2:00 PM</option>
                                    <option value="15:00" {{ old('interview_time') == '15:00' ? 'selected' : '' }}>3:00 PM</option>
                                    <option value="16:00" {{ old('interview_time') == '16:00' ? 'selected' : '' }}>4:00 PM</option>
                                    <option value="17:00" {{ old('interview_time') == '17:00' ? 'selected' : '' }}>5:00 PM</option>
                                </select>
                                @error('interview_time')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Selected Plan *</label>
                            <div class="bg-indigo-50 border-2 border-indigo-200 rounded-xl p-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="font-semibold text-gray-900">
                                            @if($plan === 'team')
                                                Team Coaching Plan
                                            @elseif($plan === 'single')
                                                One Week Intensive
                                            @elseif($plan === 'premium')
                                                Full Coaching Program
                                            @endif
                                        </p>
                                        <p class="text-sm text-gray-500">
@if($plan === 'team')
                            $49.99
                        @elseif($plan === 'single')
                            $129.78
                        @elseif($plan === 'premium')
                            $216.36
                        @endif
                                        </p>
                                    </div>
                                    <div class="w-6 h-6 rounded-full border-2 border-indigo-500 bg-indigo-500 flex items-center justify-center">
                                        <div class="w-2 h-2 rounded-full bg-white"></div>
                                    </div>
                                </div>
                                <input type="hidden" name="package" value="{{ $plan }}" required>
                            </div>
                            @error('package')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Additional Notes (Optional)</label>
                            <textarea name="notes" id="notes" rows="3" placeholder="Tell us about your interview or any specific concerns..."
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">{{ old('notes') }}</textarea>
                        </div>
                        
                        <button type="submit" class="w-full gradient-bg text-white py-3 rounded-lg font-semibold hover:opacity-90 transition-opacity">
                            Confirm Booking
                        </button>
                    </form>
                </div>
            </div>
        </section>

        @endif

        @include('components.customer-footer')

        <script>
            function checkBookingStatus() {
                fetch('{{ route("coaching.status") }}')
                    .then(response => response.json())
                    .then(data => {
                        const disabledMessage = document.getElementById('disabled-message');
                        const formSection = document.getElementById('booking-form');
                        if (data.is_active === false) {
                            if (disabledMessage && disabledMessage.classList.contains('hidden')) {
                                disabledMessage.classList.remove('hidden');
                            }
                            if (formSection) {
                                formSection.style.display = 'none';
                            }
                        } else {
                            if (disabledMessage && !disabledMessage.classList.contains('hidden')) {
                                disabledMessage.classList.add('hidden');
                            }
                            if (formSection) {
                                formSection.style.display = 'block';
                            }
                        }
                    })
                    .catch(error => console.error('Error checking booking status:', error));
            }

            checkBookingStatus();
            setInterval(checkBookingStatus, 10000);
        </script>
        </div>
    </body>
</html>
