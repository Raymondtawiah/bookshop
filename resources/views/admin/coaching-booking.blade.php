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
    <section id="hero" class="relative overflow-hidden bg-gray-100">
        <div style="width:100%;max-width:1200px;height:500px;margin:0 auto;position:relative;background:linear-gradient(135deg, rgba(59, 130, 246, 0.85) 0%, rgba(99, 102, 241, 0.85) 50%, rgba(139, 92, 246, 0.85) 100%);">
            <div id="star-overlay" style="position:absolute;inset:0;background:rgba(59, 130, 246, 0.2);"></div>
            <div class="relative max-w-7xl mx-auto px-6 py-40 sm:py-56 flex items-center justify-center h-full">
                <div class="text-center max-w-3xl mx-auto space-y-5">
                    <div class="inline-flex items-center gap-2 px-4 py-2 bg-white/15 backdrop-blur-md rounded-full border border-white/20">
                        <span class="relative flex h-2.5 w-2.5 mr-2.5">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-green-500"></span>
                        </span>
                        <span class="text-sm font-medium">Expert Coaching • Personalized Guidance</span>
                    </div>
                    
                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-white leading-tight tracking-tight">
                        Visa Interview
                        <span class="block text-transparent bg-clip-text bg-gradient-to-r from-yellow-300 to-orange-300">Coaching</span>
                    </h1>
                    
                    <p class="text-base sm:text-lg text-white/90 leading-relaxed max-w-2xl mx-auto">
                        I help people prepare for visa interviews so they can walk in feeling confident, clear, and ready. Many qualified applicants get rejected simply because they were not well prepared.
                    </p>
                </div>
            </div>
        </div>
        <div class="relative max-w-7xl mx-auto px-6 py-2 flex items-center justify-center">
        </div>
    </section>

    <style>
      @keyframes twinkle {
          0%, 100% { opacity: 0.3; transform: scale(1); }
          50% { opacity: 1; transform: scale(1.2); }
      }
      .star {
          position: absolute;
          background: white;
          border-radius: 50%;
          animation: twinkle 2s infinite ease-in-out;
      }
    </style>

    <script>
      (function() {
        const container = document.getElementById('star-overlay');
        if (!container) return;
        const count = 60;
        for (let i = 0; i < count; i++) {
            const star = document.createElement('div');
            star.style.position = 'absolute';
            star.style.top = Math.random() * 100 + '%';
            star.style.left = Math.random() * 100 + '%';
            const size = Math.random() * 3 + 1;
            star.style.width = size + 'px';
            star.style.height = size + 'px';
            star.style.background = 'white';
            star.style.borderRadius = '50%';
            star.style.opacity = Math.random() * 0.6 + 0.2;
            star.style.animation = 'twinkle ' + (Math.random() * 2 + 1) + 's infinite ease-in-out';
            star.style.animationDelay = Math.random() * 2 + 's';
            container.appendChild(star);
        }
      })();
    </script>

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
                @if(false)
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
                    
                    <button onclick="window.location.href='{{ route('coaching.booking.page', 'team') }}'" class="w-full py-3.5 rounded-3xl font-bold text-gray-700 border-2 border-gray-200 hover:border-indigo-300 hover:text-indigo-600 transition-all duration-200">
                        Book Team Plan
                    </button>
                </div>
                @endif

                <!-- 1 Week Intensive -->
                @if(false)
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
                    
                    <button onclick="window.location.href='{{ route('coaching.booking.page', 'single') }}'" class="w-full py-3.5 rounded-3xl font-bold text-gray-700 border-2 border-gray-200 hover:border-indigo-300 hover:text-indigo-600 transition-all duration-200 ">
                        Book 1 Week Intensive
                    </button>
                </div>
                @endif

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
                         <span class="text-4xl font-extrabold text-indigo-600">$10</span>
                    </div>
                    
                    <p class="text-sm text-gray-600 mb-6 leading-relaxed">For deeper preparation and lasting skills development.</p>
                    
                    <ul class="space-y-3 mb-8">
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                             <span class="text-sm text-gray-600">One focused 20-minute session</span>
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
                             <span class="text-sm text-gray-600">Targeted 20-minute interview prep</span>
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
                    
                    <button onclick="window.location.href='https://calendly.com/nathanielgyarteng/new-meeting-1?primary_color=6004d4'" class="w-full py-3.5 rounded-3xl font-bold text-white plan-gradient hover:opacity-90 transition-all duration-200 shadow-lg shadow-indigo-200">
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
            
             <div class="grid grid-cols-1 md:grid-cols-2 gap-6" x-data="{ selected: null }">
                 <div class="group bg-white border border-gray-200 rounded-3xl p-6 sm:p-8 hover:border-indigo-300 hover:shadow-2xl hover:shadow-indigo-100/50 transition-all duration-300">
                     <div class="flex items-start gap-4">
                         <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center flex-shrink-0">
                             <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8-1.06 0-2.077-.16-3.02-.454L3 21l1.5-4.5C3.55 15.16 3 13.63 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                         </div>
                         <div class="flex-1">
                             <button @click="selected = selected === 1 ? null : 1" class="w-full text-left font-semibold text-gray-900 mb-2">Can I really prepare effectively in a last-minute session?</button>
                             <div x-show="selected === 1" x-collapse class="text-gray-600 leading-relaxed">
                                 Absolutely! Even a single focused session can significantly boost your confidence and preparedness. We'll focus on the most critical areas and common questions to maximize your chances of success.
                             </div>
                         </div>
                     </div>
                 </div>
                 
                 <div class="group bg-white border border-gray-200 rounded-3xl p-6 sm:p-8 hover:border-indigo-300 hover:shadow-2xl hover:shadow-indigo-100/50 transition-all duration-300">
                     <div class="flex items-start gap-4">
                         <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center flex-shrink-0">
                             <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                         </div>
                         <div class="flex-1">
                             <button @click="selected = selected === 2 ? null : 2" class="w-full text-left font-semibold text-gray-900 mb-2">What if I need to reschedule?</button>
                             <div x-show="selected === 2" x-collapse class="text-gray-600 leading-relaxed">
                                 We offer flexible rescheduling up to 24 hours before your session. Simply contact us and we'll find a new time that works for you.
                             </div>
                         </div>
                     </div>
                 </div>
                 
                 <div class="group bg-white border border-gray-200 rounded-3xl p-6 sm:p-8 hover:border-indigo-300 hover:shadow-2xl hover:shadow-indigo-100/50 transition-all duration-300">
                     <div class="flex items-start gap-4">
                         <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center flex-shrink-0">
                             <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 21c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M12 18h.01M7 21h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                         </div>
                         <div class="flex-1">
                             <button @click="selected = selected === 3 ? null : 3" class="w-full text-left font-semibold text-gray-900 mb-2">What interview types do you cover?</button>
                             <div x-show="selected === 3" x-collapse class="text-gray-600 leading-relaxed">
                                 We cover all types including visa interviews, job interviews, university admissions, immigration interviews, and more.
                             </div>
                         </div>
                     </div>
                 </div>
                 
                 <div class="group bg-white border border-gray-200 rounded-3xl p-6 sm:p-8 hover:border-indigo-300 hover:shadow-2xl hover:shadow-indigo-100/50 transition-all duration-300">
                     <div class="flex items-start gap-4">
                         <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center flex-shrink-0">
                             <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                         </div>
                         <div class="flex-1">
                             <button @click="selected = selected === 4 ? null : 4" class="w-full text-left font-semibold text-gray-900 mb-2">How does the payment work?</button>
                             <div x-show="selected === 4" x-collapse class="text-gray-600 leading-relaxed">
                                 Payments are processed securely through Stripe. You'll receive payment details after booking confirmation.
                             </div>
                         </div>
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