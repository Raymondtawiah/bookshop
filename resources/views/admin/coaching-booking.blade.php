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
        <section class="py-20 text-white max-w-full relative overflow-hidden min-h-screen flex items-center" 
                 style="background: linear-gradient(135deg, rgba(59, 130, 246, 0.85) 0%, rgba(99, 102, 241, 0.85) 50%, rgba(139, 92, 246, 0.85) 100%), url('/coaching.png'); background-size: cover; background-position: center; background-attachment: fixed;">
            
            <!-- Animated overlay pattern -->
            <div class="absolute inset-0 opacity-20">
                <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23ffffff" fill-opacity="0.3"%3E%3Cpath d="M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
            </div>
            
            <!-- Floating elements animation -->
            <div class="absolute inset-0 overflow-hidden">
                <div class="absolute top-10 left-10 w-20 h-20 bg-white/10 rounded-full animate-pulse"></div>
                <div class="absolute top-1/4 right-20 w-32 h-32 bg-blue-400/20 rounded-full animate-bounce" style="animation-delay: 0.5s;"></div>
                <div class="absolute bottom-20 left-1/4 w-16 h-16 bg-purple-400/20 rounded-full animate-pulse" style="animation-delay: 1s;"></div>
                <div class="absolute top-1/2 right-1/3 w-24 h-24 bg-indigo-400/20 rounded-full animate-bounce" style="animation-delay: 1.5s;"></div>
            </div>
            
            <div class="px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="grid md:grid-cols-2 gap-12 items-center max-w-7xl mx-auto">
                    <div class="space-y-8">
                        <!-- Badge with glow effect -->
                        <div class="inline-flex items-center px-6 py-3 bg-white/20 backdrop-blur-sm rounded-full border border-white/30 shadow-lg">
                            <span class="relative flex h-3 w-3 mr-3">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                            </span>
                            <span class="text-sm font-medium">Expert Coaching • Personalized Guidance</span>
                        </div>
                        
                        <!-- Main heading with solid text for better visibility -->
                        <h1 class="text-5xl md:text-6xl font-bold leading-tight text-white">
                            Visa Interview
                            <br>
                            Coaching
                        </h1>
                        
                        <!-- Description with better typography -->
                        <p class="text-xl md:text-2xl text-blue-100 leading-relaxed max-w-lg">
                            I help people prepare for visa interviews so they can walk in feeling confident, clear, and ready. Many qualified applicants get rejected simply because they were not well prepared for questions and pressure.
                        </p>
                        <p class="text-lg text-blue-200 mb-8">I coach applicants for student, work, and travel visas (F-1, J-1, H-1B, B-1/B-2, and more).</p>
                        
                        <!-- CTA button with enhanced styling -->
                        <div class="flex flex-col sm:flex-row gap-4">
                            <a href="#booking-form" class="px-8 py-4 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-xl font-semibold text-lg transition-all duration-300 transform hover:scale-105 shadow-xl hover:shadow-2xl">
                                Book Your Session Now
                            </a>
                        </div>
                        
                        <!-- Stats with enhanced cards -->
                        <div class="grid grid-cols-3 gap-6">
                            <div class="bg-white/20 backdrop-blur-sm rounded-xl p-4 border border-white/30 transform hover:scale-105 transition-transform duration-300">
                                <div class="text-3xl font-bold text-white">
                                    500+
                                </div>
                                <div class="text-white text-sm font-medium">Sessions</div>
                            </div>
                            <div class="bg-white/20 backdrop-blur-sm rounded-xl p-4 border border-white/30 transform hover:scale-105 transition-transform duration-300">
                                <div class="text-3xl font-bold text-white">
                                    95%
                                </div>
                                <div class="text-white text-sm font-medium">Success Rate</div>
                            </div>
                            <div class="bg-white/20 backdrop-blur-sm rounded-xl p-4 border border-white/30 transform hover:scale-105 transition-transform duration-300">
                                <div class="text-3xl font-bold text-white">
                                    5★
                                </div>
                                <div class="text-white text-sm font-medium">Expert Rating</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Enhanced image section with effects -->
                    <div class="hidden md:block relative">
                        <div class="relative group">
                            <!-- Glow effect behind image -->
                            <div class="absolute -inset-4 bg-gradient-to-r from-blue-600 to-purple-600 rounded-3xl blur-xl opacity-50 group-hover:opacity-75 transition-opacity duration-300"></div>
                            
                            <!-- Main image with enhanced styling -->
                            <img src="/coaching.png" alt="Visa Interview Coaching" 
                                 class="relative rounded-3xl shadow-2xl w-full h-auto object-cover transform group-hover:scale-105 transition-transform duration-500">
                            
                            <!-- Floating badge -->
                            <div class="absolute -top-4 -right-4 bg-gradient-to-r from-green-500 to-emerald-600 text-white px-4 py-2 rounded-full text-sm font-bold shadow-lg animate-bounce">
                                BOOK NOW
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Pricing Section -->
        <section class="py-16 bg-white">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-3xl font-bold text-center text-gray-900 mb-12">Choose Your Plan</h2>

                <div class="grid md:grid-cols-2 gap-8 max-w-3xl mx-auto">
                    <div class="border-2 border-gray-200 rounded-2xl p-8 hover:border-indigo-300 transition-colors">
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Team Coaching Plan</h3>
                        <div class="mb-4">
                            <p class="text-3xl font-bold text-indigo-600 mb-2">
                                ₵500 
                                <span class="text-lg text-gray-400 line-through">₵800 </span>
                            </p>
                            <p class="text-gray-500 mb-2">Perfect for collaborative learning with limited cohort size (max 5 people) for personalized Attention.</p>
                        </div>
                        <p class="font-medium text-gray-900 mb-3">What you get:</p>
                        <ul class="space-y-2 text-gray-600 mb-6">
                            <li class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-green-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                2 Live Sessions (45 minutes each) with group learning
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-green-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Learn together with peer support and shared experiences
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-green-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Practice together with realistic mock interviews
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-green-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Prepare together with document review and feedback
                            </li>
                        </ul>
                        <p class="text-sm text-gray-500 mb-6">Limited to 5 people per cohort for maximum personalized attention.</p>
                        <button onclick="window.location.href='{{ route('coaching.booking.page', 'team') }}'" class="w-full gradient-bg text-white py-3 rounded-lg font-semibold hover:opacity-90 transition-opacity">
                            Book Team Plan
                        </button>
                    </div>

                    <div class="border-2 border-gray-200 rounded-2xl p-8 hover:border-indigo-300 transition-colors">
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">1 Week Interview Intensive</h3>
                        <p class="text-3xl font-bold text-indigo-600 mb-4">₵1499</p>
                        <p class="text-gray-500 mb-4">Perfect if your interview is coming up soon and you need fast focused preparation.</p>
                        <p class="font-medium text-gray-900 mb-3">What you get:</p>
                        <ul class="space-y-2 text-gray-600 mb-6">
                            <li class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-green-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Two high-intensity 60-minute coaching sessions
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-green-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Realistic mock interview practice
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-green-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Strong natural answers to common and tricky questions
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-green-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Document review and feedback
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-green-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Final strategy and confidence building
                            </li>
                        </ul>
                        <p class="text-sm text-gray-500 mb-6">You will feel ready in one week.</p>
                        <button onclick="window.location.href='{{ route('coaching.booking.page', 'single') }}'" class="w-full gradient-bg text-white py-3 rounded-lg font-semibold hover:opacity-90 transition-opacity">
                            Book 1 Week Intensive
                        </button>
                    </div>
                    
                    <div class="border-2 border-indigo-500 rounded-2xl p-8 relative">
                        <div class="absolute -top-3 left-1/2 -translate-x-1/2 bg-indigo-600 text-white px-4 py-1 rounded-full text-sm font-medium">Most Popular</div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Full Coaching Program</h3>
                        <p class="text-3xl font-bold text-indigo-600 mb-4">₵2499</p>
                        <p class="text-gray-500 mb-4">For deeper preparation and lasting skills.</p>
                        <p class="font-medium text-gray-900 mb-3">What you get:</p>
                        <ul class="space-y-2 text-gray-600 mb-6">
                            <li class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-green-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Four high-intensity 60-minute coaching sessions
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-green-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Complete interview strategy and preparation
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-green-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Multiple mock interviews with detailed feedback
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-green-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Document review and feedback
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-green-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Confidence and communication training
                            </li>
                        </ul>
                        <button onclick="window.location.href='{{ route('coaching.booking.page', 'premium') }}'" class="w-full gradient-bg text-white py-3 rounded-lg font-semibold hover:opacity-90 transition-opacity">
                            Book Full Coaching
                        </button>
                    </div>
                </div>
                
                <div class="mt-12 text-center">
                    <p class="text-lg text-gray-700 font-medium italic">"It is not about the hours we meet. It is about the intensity."</p>
                    <p class="text-gray-500 mt-2">Every session is highly focused and practical. We do not waste time. We drill what actually matters so you can handle the real interview with clarity and confidence.</p>
                </div>
                
                <p class="text-center text-gray-500 mt-8">Secure payment via Paystack</p>
            </div>
        </section>

        <!-- Who This Is For Section -->
        <section class="py-16 bg-gray-50">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-3xl font-bold text-center text-gray-900 mb-8">Who This Is For</h2>
                <div class="bg-white rounded-2xl shadow-sm p-8 text-center">
                    <p class="text-lg text-gray-700">Anyone preparing for a U.S. or international visa interview. First-timers or those who want to improve after a previous challenge.</p>
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
        </div>
    </body>
</html>