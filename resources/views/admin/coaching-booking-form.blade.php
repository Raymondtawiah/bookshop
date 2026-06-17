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
        html, body {
            max-width: 100% !important;
            overflow-x: hidden !important;
        }
        .plan-gradient {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        }
        .field-focus:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.12);
        }
        .input-group:focus-within label {
            color: #4f46e5;
        }
        .input-group:focus-within .input-icon {
            color: #4f46e5;
        }
    </style>
</head>
<body class="bg-gray-50 font-sans m-0 p-0">
    <x-flash-message />
    
    @include('components.customer-navbar')
    
    @php
        $planDetails = [
            'team' => ['name' => 'Team Coaching Plan', 'price' => 49.99, 'badge' => 'Group Learning', 'features' => ['2 Live Sessions (45 min each)', 'Collaborative learning', 'Peer support', 'Max 5 people per cohort']],
            'single' => ['name' => '1 Week Interview Intensive', 'price' => 129.78, 'badge' => 'Fast Track', 'features' => ['Two 60-min sessions', 'Mock interview practice', 'Strategy building', 'Fast preparation']],
            'premium' => ['name' => 'Full Coaching Program', 'price' => 216.36, 'badge' => 'Best Value', 'features' => ['Four 60-min sessions', 'Complete preparation', 'Multiple mock interviews', 'Confidence training']],
        ];
        $planInfo = $planDetails[$plan] ?? $planDetails['single'];
    @endphp

    <main class="min-h-screen pt-20 pb-16 px-4 sm:px-6 lg:px-8">
        <div class="max-w-6xl mx-auto">
            
            <!-- Header -->
            <div class="text-center mb-10">
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-50 text-indigo-700 rounded-full text-sm font-semibold mb-4">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ $planInfo['badge'] }}
                </div>
                <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 tracking-tight mb-2">Complete Your Booking</h1>
                <p class="text-gray-600 text-lg">You've selected: <span class="font-bold text-indigo-600">{{ $planInfo['name'] }}</span></p>
                <a href="{{ route('coaching.booking') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-indigo-600 mt-3 font-medium transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Choose a different plan
                </a>
            </div>

            <div class="grid lg:grid-cols-12 gap-8">
                
                <!-- Plan Summary Sidebar -->
                <div class="lg:col-span-4">
                    <div class="bg-white rounded-3xl shadow-xl shadow-indigo-100/60 border border-gray-100 p-8 lg:sticky lg:top-6">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-lg font-bold text-gray-900">Plan Summary</h2>
                            <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center">
                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                        </div>

                        <div class="flex items-baseline gap-2 mb-6">
                            <span class="text-4xl font-extrabold text-gray-900">${{ number_format($planInfo['price'], 2) }}</span>
                            <span class="text-sm font-medium text-gray-500">USD</span>
                        </div>

                        <div class="space-y-3 mb-8">
                            @foreach($planInfo['features'] as $feature)
                            <div class="flex items-start gap-3">
                                <div class="w-5 h-5 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <svg class="w-3 h-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <span class="text-sm text-gray-600 leading-relaxed">{{ $feature }}</span>
                            </div>
                            @endforeach
                        </div>

                        <div class="border-t border-gray-100 pt-6">
                            <div class="flex items-center gap-3 text-sm text-gray-600">
                                <svg class="w-5 h-5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                                <span>Secure checkout with Stripe</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Booking Form -->
                <div class="lg:col-span-8">
                    <div class="bg-white rounded-3xl shadow-xl shadow-gray-200/60 border border-gray-100 p-6 sm:p-10">
                        <form method="POST" action="{{ route('coaching.store') }}" class="space-y-8" id="bookingForm">
                            @csrf
                            <input type="hidden" name="package" value="{{ $plan }}">

                            <!-- Personal Information -->
                            <div>
                                <div class="flex items-center gap-2 mb-5">
                                    <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                    </div>
                                    <h3 class="text-base font-bold text-gray-900">Personal Information</h3>
                                </div>
                                <div class="grid sm:grid-cols-2 gap-5">
                                    <div class="sm:col-span-2 input-group">
                                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-1.5">Full Name <span class="text-red-500">*</span></label>
                                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                            placeholder="e.g. Jane Smith"
                                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl field-focus transition-all duration-200 placeholder:text-gray-400">
                                        @error('name')
                                            <p class="mt-1.5 text-sm text-red-600 font-medium">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <div class="input-group">
                                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-1.5">Email Address <span class="text-red-500">*</span></label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                                <svg class="w-4 h-4 text-gray-400 input-icon transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                                </svg>
                                            </div>
                                            <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                                placeholder="you@example.com"
                                                class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl field-focus transition-all duration-200 placeholder:text-gray-400">
                                        </div>
                                        @error('email')
                                            <p class="mt-1.5 text-sm text-red-600 font-medium">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <div class="input-group">
                                        <label for="phone" class="block text-sm font-semibold text-gray-700 mb-1.5">Phone Number</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                                <svg class="w-4 h-4 text-gray-400 input-icon transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                                </svg>
                                            </div>
                                            <input type="tel" name="phone" id="phone" value="{{ old('phone') }}"
                                                placeholder="+1 (555) 123-4567"
                                                class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl field-focus transition-all duration-200 placeholder:text-gray-400">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Interview Details -->
                            <div>
                                <div class="flex items-center gap-2 mb-5">
                                    <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <h3 class="text-base font-bold text-gray-900">Interview Details</h3>
                                </div>
                                <div class="grid sm:grid-cols-2 gap-5">
                                    <div class="input-group">
                                        <label for="interview_type" class="block text-sm font-semibold text-gray-700 mb-1.5">Interview Type <span class="text-red-500">*</span></label>
                                        <div class="relative">
                                            <select name="interview_type" id="interview_type" required
                                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl field-focus transition-all duration-200 appearance-none pr-10 cursor-pointer">
                                                <option value="">Select type...</option>
                                                <option value="Visa Interview" {{ old('interview_type') == 'Visa Interview' ? 'selected' : '' }}>Visa Interview</option>
                                                <option value="Job Interview" {{ old('interview_type') == 'Job Interview' ? 'selected' : '' }}>Job Interview</option>
                                                <option value="University Interview" {{ old('interview_type') == 'University Interview' ? 'selected' : '' }}>University Interview</option>
                                                <option value="Immigration Interview" {{ old('interview_type') == 'Immigration Interview' ? 'selected' : '' }}>Immigration Interview</option>
                                            </select>
                                            <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                                </svg>
                                            </div>
                                        </div>
                                        @error('interview_type')
                                            <p class="mt-1.5 text-sm text-red-600 font-medium">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <div class="input-group">
                                        <label for="interview_date" class="block text-sm font-semibold text-gray-700 mb-1.5">Interview Date <span class="text-red-500">*</span></label>
                                        <input type="date" name="interview_date" id="interview_date" value="{{ old('interview_date') }}" required
                                            min="{{ date('Y-m-d') }}"
                                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl field-focus transition-all duration-200">
                                        @error('interview_date')
                                            <p class="mt-1.5 text-sm text-red-600 font-medium">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <div class="input-group sm:col-span-2">
                                        <label for="interview_time" class="block text-sm font-semibold text-gray-700 mb-1.5">Preferred Time <span class="text-red-500">*</span></label>
                                        <div class="relative">
                                            <select name="interview_time" id="interview_time" required
                                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl field-focus transition-all duration-200 appearance-none pr-10 cursor-pointer">
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
                                            <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                            </div>
                                        </div>
                                        @error('interview_time')
                                            <p class="mt-1.5 text-sm text-red-600 font-medium">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Additional Notes -->
                            <div>
                                <div class="flex items-center gap-2 mb-5">
                                    <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </div>
                                    <h3 class="text-base font-bold text-gray-900">Additional Notes</h3>
                                    <span class="text-xs font-medium text-gray-400 bg-gray-100 px-2.5 py-1 rounded-full">Optional</span>
                                </div>
                                <textarea name="notes" id="notes" rows="4" 
                                    placeholder="Tell us about your interview or any specific concerns..."
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl field-focus transition-all duration-200 resize-none placeholder:text-gray-400">{{ old('notes') }}</textarea>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="w-full plan-gradient text-white py-4 rounded-2xl font-bold text-lg hover:opacity-90 transition-all duration-200 shadow-lg shadow-indigo-200 flex items-center justify-center gap-3 group">
                                <span>Confirm Booking</span>
                                <span class="font-extrabold">${{ number_format($planInfo['price'], 2) }}</span>
                                <svg class="w-5 h-5 transition-transform duration-200 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    @include('components.customer-footer')
</body>
</html>