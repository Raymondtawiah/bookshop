<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Last-Minute Coaching Booking - {{ config('app.name', 'Nathaniel Gyarteng') }}</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <link rel="icon" href="/favicon.ico" sizes="any">
        <style>
            [x-cloak] { display: none !important; }
            .gradient-bg { background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); }
            .countdown-pulse { animation: pulse 2s infinite; }
            @keyframes pulse {
                0%, 100% { opacity: 1; }
                50% { opacity: 0.7; }
            }
        </style>
    </head>
    <body class="bg-gray-50 font-sans pt-14">
        <x-flash-message />
        
        @include('components.customer-navbar')

        @php
            $isActive = \App\Models\SiteSetting::get('coaching_booking_active', 'true') === 'true';
            $isAdmin = auth()->check() && auth()->user()->is_admin;
        @endphp

        @if($isAdmin)
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <form method="POST" action="{{ route('admin.coachings.toggleActive') }}" class="flex flex-wrap items-center gap-4">
                @csrf
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" class="sr-only peer" {{ $isActive ? 'checked' : '' }} onchange="this.form.submit()">
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                    <span class="ml-3 text-sm font-medium text-gray-900">Booking {{ $isActive ? 'Enabled' : 'Disabled' }}</span>
                </label>
            </form>
        </div>
        @endif

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

        <!-- Hero Section -->
        <section class="gradient-bg text-white py-20">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <div x-data="{ countdown: 60, active: true }" x-init="setInterval(() => { if (countdown > 0) countdown--; else active = false; }, 60000)" class="inline-flex items-center gap-2 bg-white/20 px-4 py-2 rounded-full mb-6 countdown-pulse">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-sm font-medium" x-text="active ? `Session expires in ${countdown} min` : 'Session expired'"></span>
                </div>
                
                <h1 class="text-4xl md:text-5xl font-bold mb-4">One Last Session to Prepare for Your Interview</h1>
                <p class="text-xl text-indigo-100 mb-8">Book a personalized one-on-one coaching session within a week of your interview</p>
                <a href="#booking-form" class="inline-flex items-center gap-2 bg-white text-indigo-600 px-8 py-3 rounded-lg font-semibold hover:bg-indigo-50 transition-colors">
                    Book Your Session Now
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
                
                <div class="mt-12 flex justify-center">
                    <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=400&h=300&fit=crop" alt="Confidence and readiness" class="rounded-xl shadow-2xl max-w-md">
                </div>
            </div>
        </section>

        <!-- How It Works Section -->
        <section class="py-16 bg-white">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-3xl font-bold text-center text-gray-900 mb-12">How It Works</h2>
                
                <div class="grid md:grid-cols-3 gap-8">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">1. Choose Date & Time</h3>
                        <p class="text-gray-600">Select your preferred date and time based on your interview schedule</p>
                    </div>
                    
                    <div class="text-center">
                        <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">2. Secure Your Spot</h3>
                        <p class="text-gray-600">Make a safe and seamless online payment through Paystack</p>
                    </div>
                    
                    <div class="text-center">
                        <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">3. Attend Your Session</h3>
                        <p class="text-gray-600">Join a one-on-one coaching session to refine your responses and build confidence</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Pricing Section -->
        <section class="py-16 bg-white">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-3xl font-bold text-center text-gray-900 mb-12">Choose Your Package</h2>
                
                <div class="grid md:grid-cols-2 gap-8 max-w-3xl mx-auto">
                    <div class="border-2 border-gray-200 rounded-2xl p-8 hover:border-indigo-300 transition-colors">
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Single Session</h3>
                        <p class="text-3xl font-bold text-indigo-600 mb-4">₵300</p>
                        <ul class="space-y-3 text-gray-600">
                            <li class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                One 45-minute session
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Interview Q&A practice
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Confidence building tips
                            </li>
                        </ul>
                    </div>
                    
                    <div class="border-2 border-indigo-500 rounded-2xl p-8 relative">
                        <div class="absolute -top-3 left-1/2 -translate-x-1/2 bg-indigo-600 text-white px-4 py-1 rounded-full text-sm font-medium">Most Popular</div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Premium</h3>
                        <p class="text-3xl font-bold text-indigo-600 mb-4">₵500</p>
                        <ul class="space-y-3 text-gray-600">
                            <li class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                One 45-minute session
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Interview Q&A practice
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Confidence building tips
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Follow-up notes & action plan
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                24/7 support until interview
                            </li>
                        </ul>
                    </div>
                </div>
                
                <p class="text-center text-gray-500 mt-4">Secure payment via Paystack</p>
            </div>
        </section>

        <!-- Booking Form Section -->
        <section id="booking-form" class="py-16 bg-gray-50">
            <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-3xl font-bold text-center text-gray-900 mb-4">Book Your Session</h2>
                <p class="text-center text-gray-600 mb-8">Book in minutes!</p>
                
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
                            <label class="block text-sm font-medium text-gray-700 mb-2">Select Package *</label>
                            <div class="grid md:grid-cols-2 gap-4">
                                <label class="relative border-2 border-gray-200 rounded-xl p-4 cursor-pointer hover:border-indigo-300 transition-colors has-[:checked]:border-indigo-500 has-[:checked]:bg-indigo-50">
                                    <input type="radio" name="package" value="single" class="sr-only" {{ old('package') == 'single' ? 'checked' : '' }}>
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="font-semibold text-gray-900">Single Session</p>
                                            <p class="text-sm text-gray-500">₵300</p>
                                        </div>
                                        <div class="w-6 h-6 rounded-full border-2 border-gray-300 flex items-center justify-center has-[:checked]:border-indigo-500 has-[:checked]:bg-indigo-500">
                                            <div class="w-2 h-2 rounded-full bg-white hidden has-[:checked]:block"></div>
                                        </div>
                                    </div>
                                </label>
                                <label class="relative border-2 border-gray-200 rounded-xl p-4 cursor-pointer hover:border-indigo-300 transition-colors has-[:checked]:border-indigo-500 has-[:checked]:bg-indigo-50">
                                    <input type="radio" name="package" value="premium" class="sr-only" {{ old('package', 'premium') == 'premium' ? 'checked' : '' }}>
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="font-semibold text-gray-900">Premium</p>
                                            <p class="text-sm text-gray-500">₵500</p>
                                        </div>
                                        <div class="w-6 h-6 rounded-full border-2 border-gray-300 flex items-center justify-center has-[:checked]:border-indigo-500 has-[:checked]:bg-indigo-500">
                                            <div class="w-2 h-2 rounded-full bg-white hidden has-[:checked]:block"></div>
                                        </div>
                                    </div>
                                </label>
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

        <!-- FAQ Section -->
        <section class="py-16 bg-white">
            <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-3xl font-bold text-center text-gray-900 mb-12">Frequently Asked Questions</h2>
                
                <div class="space-y-4" x-data="{ selected: null }">
                    <div class="border border-gray-200 rounded-lg overflow-hidden">
                        <button @click="selected = selected === 1 ? null : 1" class="w-full px-6 py-4 text-left flex items-center justify-between hover:bg-gray-50">
                            <span class="font-medium text-gray-900">Can I really prepare effectively in a last-minute session?</span>
                            <svg class="w-5 h-5 text-gray-500 transition-transform" :class="selected === 1 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="selected === 1" class="px-6 py-4 bg-gray-50 text-gray-600">
                            Absolutely! Even a single focused session can significantly boost your confidence and preparedness. We'll focus on the most critical areas and common questions to maximize your chances of success.
                        </div>
                    </div>
                    
                    <div class="border border-gray-200 rounded-lg overflow-hidden">
                        <button @click="selected = selected === 2 ? null : 2" class="w-full px-6 py-4 text-left flex items-center justify-between hover:bg-gray-50">
                            <span class="font-medium text-gray-900">What if I need to reschedule?</span>
                            <svg class="w-5 h-5 text-gray-500 transition-transform" :class="selected === 2 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="selected === 2" class="px-6 py-4 bg-gray-50 text-gray-600">
                            We offer flexible rescheduling up to 24 hours before your session. Simply contact us and we'll find a new time that works for you.
                        </div>
                    </div>
                    
                    <div class="border border-gray-200 rounded-lg overflow-hidden">
                        <button @click="selected = selected === 3 ? null : 3" class="w-full px-6 py-4 text-left flex items-center justify-between hover:bg-gray-50">
                            <span class="font-medium text-gray-900">What interview types do you cover?</span>
                            <svg class="w-5 h-5 text-gray-500 transition-transform" :class="selected === 3 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="selected === 3" class="px-6 py-4 bg-gray-50 text-gray-600">
                            We cover all types including visa interviews, job interviews, university admissions, immigration interviews, and more.
                        </div>
                    </div>
                    
                    <div class="border border-gray-200 rounded-lg overflow-hidden">
                        <button @click="selected = selected === 4 ? null : 4" class="w-full px-6 py-4 text-left flex items-center justify-between hover:bg-gray-50">
                            <span class="font-medium text-gray-900">How does the payment work?</span>
                            <svg class="w-5 h-5 text-gray-500 transition-transform" :class="selected === 4 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="selected === 4" class="px-6 py-4 bg-gray-50 text-gray-600">
                            Payments are processed securely through Paystack. You'll receive payment details after booking confirmation.
                        </div>
                    </div>
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
    </body>
</html>