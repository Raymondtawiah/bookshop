<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Last-Minute Coaching Booking - {{ config('app.name', 'Nathaniel Gyarteng') }}</title>
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
        .hero-gradient {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 50%, #9333ea 100%);
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
        .animate-float-delayed {
            animation: float 6s ease-in-out infinite;
            animation-delay: 2s;
        }
    </style>
</head>
<body class="bg-gray-50 font-sans pt-16 m-0 p-0 box-border w-full min-w-0">
    <x-flash-message />
    
    @include('components.customer-navbar')
    
    @php
        $isActive = \App\Models\SiteSetting::get('coaching_booking_active', 'true') === 'true';
        $isAdmin = auth()->check() && auth()->user()->is_admin;
    @endphp

    @if(!$isActive && !$isAdmin)
    <div id="disabled-message" class="min-h-[70vh] flex items-center justify-center px-4">
        <div class="bg-white rounded-3xl shadow-xl shadow-gray-200/60 border border-gray-100 p-10 sm:p-14 max-w-lg text-center">
            <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                </svg>
            </div>
            <h2 class="text-3xl font-extrabold text-gray-900 mb-4">Booking Currently Unavailable</h2>
            <p class="text-gray-600 text-lg leading-relaxed">We're sorry, but the coaching booking service is temporarily paused.</p>
            <p class="text-gray-500 mt-4">Please check back later or reach out for assistance.</p>
        </div>
    </div>
    @endif

    @if($isActive || $isAdmin)

    <!-- Hero Section -->
    <section id="hero" class="relative overflow-hidden min-h-[90vh] flex items-center">
        <div class="absolute inset-0 hero-gradient" style="background-image: url('/coaching.png'); background-size: cover; background-position: center; background-attachment: fixed; background-blend-mode: overlay;"></div>
        <div class="absolute inset-0 bg-indigo-900/60"></div>
        
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 sm:py-28">
            <div class="grid lg:grid-cols-2 gap-12 lg:gap-8 items-center">
                <!-- Text Content -->
                <div class="space-y-8">
                    <div class="inline-flex items-center gap-2 px-4 py-2 bg-white/15 backdrop-blur-md rounded-full border border-white/20">
                        <span class="w-2 h-2 bg-green-400 rounded-full animate-ping"></span>
                        <span class="text-sm font-semibold text-white">Expert Coaching • Personalized Guidance</span>
                    </div>
                    
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white leading-tight tracking-tight">
                        Visa Interview
                        <span class="block text-indigo-200">Coaching</span>
                    </h1>
                    
                    <p class="text-lg sm:text-xl text-indigo-100 leading-relaxed max-w-lg">
                        I help people prepare for visa interviews so they can walk in feeling confident, clear, and ready. Many qualified applicants get rejected simply because they were not well prepared.
                    </p>
                    <p class="text-base text-indigo-200">I coach applicants for student, work, and travel visas (F-1, J-1, H-1B, B-1/B-2, and more).</p>
                    
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="#pricing" class="px-8 py-4 bg-white text-indigo-700 rounded-2xl font-bold text-lg hover:bg-indigo-50 transition-all duration-200 shadow-xl text-center">
                            Book Your Session Now
                        </a>
                    </div>
                    
                    <div class="grid grid-cols-3 gap-4 sm:gap-6">
                        <div class="bg-white/10 backdrop-blur-md rounded-2xl p-4 sm:p-5 border border-white/20">
                            <div class="text-2xl sm:text-3xl font-extrabold text-white">500+</div>
                            <div class="text-indigo-200 text-xs sm:text-sm font-medium">Sessions Done</div>
                        </div>
                        <div class="bg-white/10 backdrop-blur-md rounded-2xl p-4 sm:p-5 border border-white/20">
                            <div class="text-2xl sm:text-3xl font-extrabold text-white">95%</div>
                            <div class="text-indigo-200 text-xs sm:text-sm font-medium">Success Rate</div>
                        </div>
                        <div class="bg-white/10 backdrop-blur-md rounded-2xl p-4 sm:p-5 border border-white/20">
                            <div class="text-2xl sm:text-3xl font-extrabold text-white">5★</div>
                            <div class="text-indigo-200 text-xs sm:text-sm font-medium">Expert Rating</div>
                        </div>
                    </div>
                </div>

                <!-- Image / Visual -->
                <div class="hidden lg:block relative">
                    <div class="relative">
                        <div class="absolute -inset-4 bg-gradient-to-tr from-blue-400 to-purple-400 rounded-3xl blur-2xl opacity-40 animate-float"></div>
                        <img src="/coaching.png" alt="Visa Interview Coaching" 
                             class="relative rounded-3xl shadow-2xl w-full h-auto object-cover border border-white/20">
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Wave separator -->
        <div class="absolute bottom-0 left-0 right-0">
            <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0 120L48 105C96 90 192 60 288 45C384 30 480 30 576 37.5C672 45 768 60 864 67.5C960 75 1056 75 1152 67.5C1248 60 1344 45 1392 37.5L1440 30V120H1392C1344 120 1248 120 1152 120C1056 120 960 120 864 120C768 120 672 120 576 120C480 120 384 120 288 120C192 120 96 120 48 120H0Z" fill="white"/>
            </svg>
        </div>
    </section>

    <!-- Pricing Section -->
    <section id="pricing" class="py-16 sm:py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12 sm:mb-16">
                <span class="inline-block px-4 py-1.5 bg-indigo-50 text-indigo-700 rounded-full text-sm font-bold mb-4">Simple Pricing</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 mb-4">Choose Your Plan</h2>
                <p class="text-gray-600 text-lg max-w-2xl mx-auto">Select the coaching plan that fits your timeline and goals.</p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8 max-w-6xl mx-auto">
                <!-- Team Plan -->
                <div class="group relative bg-white rounded-3xl border-2 border-gray-200 p-6 sm:p-8 hover:border-indigo-300 hover:shadow-2xl hover:shadow-indigo-100/50 transition-all duration-300">
                    <div class="mb-6">
                        <div class="w-12 h-12 bg-indigo-50 rounded-2xl flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-1">Team Coaching Plan</h3>
                        <p class="text-gray-500 text-sm">Collaborative group sessions</p>
                    </div>
                    
                    <div class="mb-6">
                        <div class="flex items-baseline gap-2">
                            <span class="text-4xl font-extrabold text-gray-900">$49.99</span>
                            <span class="text-lg text-gray-400 line-through">$69.26</span>
                        </div>
                    </div>
                    
                    <p class="text-sm text-gray-600 mb-6 leading-relaxed">Perfect for collaborative learning with limited cohort size (max 5 people) for personalized attention.</p>
                    
                    <ul class="space-y-3 mb-8">
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-sm text-gray-600">2 Live Sessions (45 min each)</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-sm text-gray-600">Peer support and shared experiences</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-sm text-gray-600">Realistic mock interview practice</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-sm text-gray-600">Document review and feedback</span>
                        </li>
                    </ul>
                    
                    <button onclick="window.location.href='{{ route('coaching.booking.page', 'team') }}'" class="w-full py-3.5 rounded-2xl font-bold text-gray-700 border-2 border-gray-200 hover:border-indigo-300 hover:text-indigo-600 transition-all duration-200">
                        Book Team Plan
                    </button>
                </div>

                <!-- 1 Week Intensive -->
                <div class="group relative bg-white rounded-3xl border-2 border-gray-200 p-6 sm:p-8 hover:border-indigo-300 hover:shadow-2xl hover:shadow-indigo-100/50 transition-all duration-300">
                    <div class="mb-6">
                        <div class="w-12 h-12 bg-indigo-50 rounded-2xl flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-1">1 Week Interview Intensive</h3>
                        <p class="text-gray-500 text-sm">Fast-track preparation</p>
                    </div>
                    
                    <div class="mb-6">
                        <span class="text-4xl font-extrabold text-gray-900">$129.78</span>
                    </div>
                    
                    <p class="text-sm text-gray-600 mb-6 leading-relaxed">Perfect if your interview is coming up soon and you need fast, focused preparation.</p>
                    
                    <ul class="space-y-3 mb-8">
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-sm text-gray-600">Two high-intensity 60-min sessions</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-sm text-gray-600">Realistic mock interview practice</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-sm text-gray-600">Strong natural answers to tricky questions</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-sm text-gray-600">Document review and feedback</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-sm text-gray-600">Final strategy and confidence building</span>
                        </li>
                    </ul>
                    
                    <button onclick="window.location.href='{{ route('coaching.booking.page', 'single') }}'" class="w-full py-3.5 rounded-2xl font-bold text-gray-700 border-2 border-gray-200 hover:border-indigo-300 hover:text-indigo-600 transition-all duration-200">
                        Book 1 Week Intensive
                    </button>
                </div>

                <!-- Full Coaching (Featured) -->
                <div class="group relative bg-white rounded-3xl border-2 border-indigo-500 p-6 sm:p-8 shadow-xl shadow-indigo-100/50 md:-mt-4 md:mb-4 overflow-visible">
                    <div class="absolute -top-4 left-1/2 -translate-x-1/2 z-40">
                        <span class="bg-indigo-700 text-white px-6 py-2 rounded-full text-sm font-black shadow-xl whitespace-nowrap inline-block">Most Popular</span>
                    </div>
                    
                    <div class="mb-6">
                        <div class="w-12 h-12 bg-indigo-50 rounded-2xl flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-1">Full Coaching Program</h3>
                        <p class="text-gray-500 text-sm">Complete preparation package</p>
                    </div>
                    
                    <div class="mb-6">
                        <span class="text-4xl font-extrabold text-indigo-600">$216.36</span>
                    </div>
                    
                    <p class="text-sm text-gray-600 mb-6 leading-relaxed">For deeper preparation and lasting skills development.</p>
                    
                    <ul class="space-y-3 mb-8">
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-sm text-gray-600">Four high-intensity 60-minute sessions</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-sm text-gray-600">Complete interview strategy and prep</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-sm text-gray-600">Multiple mock interviews with feedback</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-sm text-gray-600">Document review and feedback</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-sm text-gray-600">Confidence and communication training</span>
                        </li>
                    </ul>
                    
                    <button onclick="window.location.href='{{ route('coaching.booking.page', 'premium') }}'" class="w-full py-3.5 rounded-2xl font-bold text-white plan-gradient hover:opacity-90 transition-all duration-200 shadow-lg shadow-indigo-200">
                        Book Full Coaching
                    </button>
                </div>
            </div>

            <div class="mt-16 text-center max-w-2xl mx-auto">
                <p class="text-xl sm:text-2xl text-gray-800 font-bold italic mb-3">"It is not about the hours we meet. It is about the intensity."</p>
                <p class="text-gray-500 leading-relaxed">Every session is highly focused and practical. We do not waste time. We drill what actually matters so you can handle the real interview with clarity and confidence.</p>
            </div>
            
            <p class="text-center text-gray-400 text-sm mt-8">Secure payment via Stripe</p>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-16 sm:py-24 bg-gray-50">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-10 sm:mb-12">
                <span class="inline-block px-4 py-1.5 bg-indigo-50 text-indigo-700 rounded-full text-sm font-bold mb-4">Got Questions?</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900">Frequently Asked Questions</h2>
            </div>
            
            <div class="space-y-4" x-data="{ selected: null }">
                <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden hover:border-indigo-200 transition-colors">
                    <button @click="selected = selected === 1 ? null : 1" class="w-full px-6 py-5 text-left flex items-center justify-between">
                        <span class="font-semibold text-gray-900 pr-4">Can I really prepare effectively in a last-minute session?</span>
                        <svg class="w-5 h-5 text-indigo-500 transition-transform duration-200 flex-shrink-0" :class="selected === 1 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="selected === 1" x-collapse class="px-6 pb-5 text-gray-600 leading-relaxed">
                        Absolutely! Even a single focused session can significantly boost your confidence and preparedness. We'll focus on the most critical areas and common questions to maximize your chances of success.
                    </div>
                </div>
                
                <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden hover:border-indigo-200 transition-colors">
                    <button @click="selected = selected === 2 ? null : 2" class="w-full px-6 py-5 text-left flex items-center justify-between">
                        <span class="font-semibold text-gray-900 pr-4">What if I need to reschedule?</span>
                        <svg class="w-5 h-5 text-indigo-500 transition-transform duration-200 flex-shrink-0" :class="selected === 2 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="selected === 2" x-collapse class="px-6 pb-5 text-gray-600 leading-relaxed">
                        We offer flexible rescheduling up to 24 hours before your session. Simply contact us and we'll find a new time that works for you.
                    </div>
                </div>
                
                <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden hover:border-indigo-200 transition-colors">
                    <button @click="selected = selected === 3 ? null : 3" class="w-full px-6 py-5 text-left flex items-center justify-between">
                        <span class="font-semibold text-gray-900 pr-4">What interview types do you cover?</span>
                        <svg class="w-5 h-5 text-indigo-500 transition-transform duration-200 flex-shrink-0" :class="selected === 3 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="selected === 3" x-collapse class="px-6 pb-5 text-gray-600 leading-relaxed">
                        We cover all types including visa interviews, job interviews, university admissions, immigration interviews, and more.
                    </div>
                </div>
                
                <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden hover:border-indigo-200 transition-colors">
                    <button @click="selected = selected === 4 ? null : 4" class="w-full px-6 py-5 text-left flex items-center justify-between">
                        <span class="font-semibold text-gray-900 pr-4">How does the payment work?</span>
                        <svg class="w-5 h-5 text-indigo-500 transition-transform duration-200 flex-shrink-0" :class="selected === 4 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="selected === 4" x-collapse class="px-6 pb-5 text-gray-600 leading-relaxed">
                        Payments are processed securely through Stripe. You'll receive payment details after booking confirmation.
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
                    const heroSection = document.querySelector('section');
                    const pricingSection = document.getElementById('pricing');
                    const faqSection = document.querySelectorAll('section')[1];
                    if (data.is_active === false) {
                        if (disabledMessage && disabledMessage.classList.contains('hidden')) {
                            disabledMessage.classList.remove('hidden');
                        }
                        if (heroSection) heroSection.style.display = 'none';
                        if (pricingSection) pricingSection.style.display = 'none';
                        if (faqSection) faqSection.style.display = 'none';
                    } else {
                        if (disabledMessage && !disabledMessage.classList.contains('hidden')) {
                            disabledMessage.classList.add('hidden');
                        }
                        if (heroSection) heroSection.style.display = 'block';
                        if (pricingSection) pricingSection.style.display = 'block';
                        if (faqSection) faqSection.style.display = 'block';
                    }
                })
                .catch(error => console.error('Error checking booking status:', error));
        }

        checkBookingStatus();
        setInterval(checkBookingStatus, 10000);
    </script>
</body>
</html>