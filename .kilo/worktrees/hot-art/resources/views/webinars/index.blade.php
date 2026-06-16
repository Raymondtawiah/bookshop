@extends('layouts.app')

@section('content')
    <!-- Hero Section -->
    <section class="py-20 text-white max-w-full relative overflow-hidden min-h-screen flex items-center" 
             style="background: linear-gradient(135deg, rgba(59, 130, 246, 0.85) 0%, rgba(99, 102, 241, 0.85) 50%, rgba(139, 92, 246, 0.85) 100%), url('/webinar.png'); background-size: cover; background-position: center; background-attachment: fixed;">
        
        <!-- Animated overlay pattern -->
        <div class="absolute inset-0 opacity-20">
            <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=&quot;60&quot; height=&quot;60&quot; viewBox=&quot;0 0 60 60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cg fill=&quot;none&quot; fill-rule=&quot;evenodd&quot;%3E%3Cg fill=&quot;%23ffffff&quot; fill-opacity=&quot;0.3&quot;%3E%3Cpath d=&quot;M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z&quot;/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
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
                        <span class="text-sm font-medium">Upcoming Sessions • Expert Led</span>
                    </div>
                    
                    <!-- Main heading with solid text for better visibility -->
                    <h1 class="text-5xl md:text-6xl font-bold leading-tight text-white">
                        Visa Interview
                        <br>
                        Success Webinar
                    </h1>
                    
                    <!-- Description with better typography -->
                    <p class="text-xl md:text-2xl text-blue-100 leading-relaxed max-w-lg">
                        Master your visa interview with expert guidance. Learn proven strategies, common questions, and how to answer confidently to get your visa approved.
                    </p>
                    
                    <!-- CTA buttons with enhanced styling -->
                    <div class="flex flex-col sm:flex-row gap-4">
                        @if($registrationFormEnabled && $webinars->isNotEmpty())
                            <a href="#register" class="group relative px-8 py-4 bg-white text-blue-600 rounded-xl font-semibold text-lg transition-all duration-300 transform hover:scale-105 shadow-xl hover:shadow-2xl">
                                <span class="relative z-10">Register Now</span>
                            </a>
                        @else
                            <a href="#register" class="group relative px-8 py-4 bg-white text-blue-600 rounded-xl font-semibold text-lg transition-all duration-300 transform hover:scale-105 shadow-xl hover:shadow-2xl">
                                <span class="relative z-10">Registration</span>
                            </a>
                        @endif
                        <a href="#about" class="px-8 py-4 bg-white/10 backdrop-blur-sm border-2 border-white/30 text-white rounded-xl font-semibold text-lg hover:bg-white/20 transition-all duration-300 transform hover:scale-105">
                            Learn More
                        </a>
                    </div>
                    
                    <!-- Stats with enhanced cards -->
                    <div class="grid grid-cols-3 gap-6">
                        <div class="bg-white/20 backdrop-blur-sm rounded-xl p-4 border border-white/30 transform hover:scale-105 transition-transform duration-300">
                            <div class="text-3xl font-bold text-white">
                                {{ $webinars->count() ?? 0 }}+
                            </div>
                            <div class="text-white text-sm font-medium">Available Sessions</div>
                        </div>
                        <div class="bg-white/20 backdrop-blur-sm rounded-xl p-4 border border-white/30 transform hover:scale-105 transition-transform duration-300">
                            <div class="text-3xl font-bold text-white">
                                5+
                            </div>
                            <div class="text-white text-sm font-medium">Expert Speakers</div>
                        </div>
                        <div class="bg-white/20 backdrop-blur-sm rounded-xl p-4 border border-white/30 transform hover:scale-105 transition-transform duration-300">
                            <div class="text-3xl font-bold text-white">
                                60min
                            </div>
                            <div class="text-white text-sm font-medium">Each Session</div>
                        </div>
                    </div>
                </div>
                
                <!-- Enhanced image section with effects -->
                <div class="hidden md:block relative">
                    <div class="relative group">
                        <!-- Glow effect behind image -->
                        <div class="absolute -inset-4 bg-gradient-to-r from-blue-600 to-purple-600 rounded-3xl blur-xl opacity-50 group-hover:opacity-75 transition-opacity duration-300"></div>
                        
                        <!-- Main image with enhanced styling -->
                        <img src="/webinar.png" alt="Professional Webinars" 
                             class="relative rounded-3xl shadow-2xl w-full h-auto object-cover transform group-hover:scale-105 transition-transform duration-500">
                        
                        <!-- Floating badge -->
                        <div class="absolute -top-4 -right-4 bg-gradient-to-r from-green-500 to-emerald-600 text-white px-4 py-2 rounded-full text-sm font-bold shadow-lg animate-bounce">
                            LIVE NOW
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- What You'll Learn -->
    <section id="about" class="py-20 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold mb-4">What You'll Learn</h2>
                <p class="text-xl text-slate-600">
                    Master your visa interview with proven strategies
                </p>
            </div>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="p-6 rounded-xl bg-white border border-slate-200 hover:shadow-lg transition-shadow">
                    <div class="mb-4">
                        <svg class="w-12 h-12 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Common Interview Questions</h3>
                    <p class="text-slate-600">Learn the most frequently asked visa interview questions and how to answer them confidently.</p>
                </div>
                <div class="p-6 rounded-xl bg-white border border-slate-200 hover:shadow-lg transition-shadow">
                    <div class="mb-4">
                        <svg class="w-12 h-12 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Document Preparation</h3>
                    <p class="text-slate-600">Understand exactly what documents you need and how to organize them for a successful interview.</p>
                </div>
                <div class="p-6 rounded-xl bg-white border border-slate-200 hover:shadow-lg transition-shadow">
                    <div class="mb-4">
                        <svg class="w-12 h-12 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Body Language & Confidence</h3>
                    <p class="text-slate-600">Master the art of confident communication and positive body language during your interview.</p>
                </div>
                <div class="p-6 rounded-xl bg-white border border-slate-200 hover:shadow-lg transition-shadow">
                    <div class="mb-4">
                        <svg class="w-12 h-12 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Red Flags to Avoid</h3>
                    <p class="text-slate-600">Learn the common mistakes that lead to visa denials and how to avoid them completely.</p>
                </div>
                <div class="p-6 rounded-xl bg-white border border-slate-200 hover:shadow-lg transition-shadow">
                    <div class="mb-4">
                        <svg class="w-12 h-12 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Mock Interview Practice</h3>
                    <p class="text-slate-600">Participate in live mock interviews and get feedback on your performance from experts.</p>
                </div>
                <div class="p-6 rounded-xl bg-white border border-slate-200 hover:shadow-lg transition-shadow">
                    <div class="mb-4">
                        <svg class="w-12 h-12 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Success Stories</h3>
                    <p class="text-slate-600">Hear real success stories and learn from others who have successfully obtained their visas.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Registration Form Section -->
    @if($registrationFormEnabled)
    <section id="register" class="py-20 relative overflow-hidden bg-gradient-to-br from-blue-600 via-indigo-700 to-purple-800">
        
        <!-- Animated gradient background -->
        <div class="absolute inset-0">
            <div class="absolute inset-0 bg-gradient-to-r from-blue-500/40 via-indigo-500/40 to-purple-500/40 animate-gradient-shift"></div>
            <div class="absolute inset-0 bg-gradient-to-l from-purple-500/30 via-blue-500/30 to-indigo-500/30 animate-gradient-shift-reverse" style="animation-delay: 2s;"></div>
        </div>
        
        <!-- Animated wave patterns -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <svg class="absolute -top-10 -left-10 w-40 h-40 text-white/5 animate-spin" style="animation-duration: 30s;" viewBox="0 0 100 100">
                <path d="M10,50 Q30,30 50,50 T90,50" stroke="currentColor" fill="none" stroke-width="2"/>
            </svg>
            <svg class="absolute -bottom-10 -right-10 w-40 h-40 text-white/5 animate-spin" style="animation-duration: 40s; animation-delay: 5s;" viewBox="0 0 100 100">
                <path d="M10,50 Q30,70 50,50 T90,50" stroke="currentColor" fill="none" stroke-width="2"/>
            </svg>
            <svg class="absolute top-1/2 left-1/4 w-32 h-32 text-blue-200/10 animate-spin" style="animation-duration: 35s;" viewBox="0 0 100 100">
                <circle cx="50" cy="50" r="40" stroke="currentColor" fill="none" stroke-width="2"/>
            </svg>
        </div>
        
        <!-- Floating particles with varied animations -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute top-10 left-10 w-3 h-3 bg-white/30 rounded-full animate-float" style="animation-duration: 6s;"></div>
            <div class="absolute top-20 right-20 w-2 h-2 bg-blue-200/40 rounded-full animate-float" style="animation-duration: 8s; animation-delay: 1s;"></div>
            <div class="absolute top-1/3 left-1/4 w-4 h-4 bg-purple-200/30 rounded-full animate-float" style="animation-duration: 7s; animation-delay: 2s;"></div>
            <div class="absolute bottom-20 right-1/3 w-2 h-2 bg-indigo-200/35 rounded-full animate-float" style="animation-duration: 9s; animation-delay: 0.5s;"></div>
            <div class="absolute top-2/3 right-1/4 w-3 h-3 bg-white/25 rounded-full animate-float" style="animation-duration: 5s; animation-delay: 1.5s;"></div>
            <div class="absolute bottom-1/3 left-2/3 w-2 h-2 bg-blue-300/30 rounded-full animate-float" style="animation-duration: 10s; animation-delay: 2.5s;"></div>
            <div class="absolute top-1/4 left-3/4 w-1 h-1 bg-purple-300/40 rounded-full animate-float" style="animation-duration: 4s; animation-delay: 3s;"></div>
            <div class="absolute bottom-1/4 left-1/3 w-3 h-3 bg-white/20 rounded-full animate-float" style="animation-duration: 11s; animation-delay: 0.8s;"></div>
        </div>
        
        <!-- Animated grid pattern -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute inset-0" style="background-image: radial-gradient(circle at 1px 1px, rgba(255,255,255,0.3) 1px, transparent 0); background-size: 40px 40px;"></div>
        </div>
        
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="bg-white/95 backdrop-blur-sm rounded-2xl p-10 shadow-2xl text-center transform transition-all duration-500 hover:scale-[1.02]">
                <div class="inline-flex items-center px-4 py-2 bg-blue-50 rounded-full mb-4 animate-pulse">
                    <span class="relative flex h-2 w-2 mr-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-500 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-600"></span>
                    </span>
                    <span class="text-sm font-medium text-gray-800">Limited Spots Available</span>
                </div>
                
                <h2 class="text-4xl font-bold mb-4 text-gray-800 bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-indigo-600">Register for Webinar</h2>
                <p class="text-xl text-gray-600 mb-8">
                    Secure your spot for the upcoming session
                </p>
                
                @php
                    $webinar = $webinars->first();
                @endphp

                @if($webinar)
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-6 rounded-xl mb-6 border border-blue-100">
                        <div class="flex items-center justify-center gap-3">
                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center animate-pulse">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .896-3 2s1.343 2 3 2 3-.896 3-2-1.343-2-3-2zM12 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 16c-2.76 0-5-2.24-5-5v-2c0-2.76 2.24-5 5-5s5 2.24 5 5v2c0 2.76-2.24 5-5 5z"/>
                                </svg>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600 block">Price:</span>
                                <span class="text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">${{ number_format($webinar->current_price, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('webinars.register.page', $webinar->id) }}" target="_self" class="inline-block w-full py-4 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg font-semibold text-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-300 shadow-lg hover:shadow-2xl transform hover:scale-[1.02] relative overflow-hidden group">
                        <span class="relative z-10">Proceed to Registration</span>
                        <div class="absolute inset-0 bg-gradient-to-r from-white/0 via-white/20 to-white/0 transform translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-700"></div>
                    </a>
                @else
                    <div class="bg-gray-100 rounded-xl p-6 mb-6">
                        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <h3 class="text-xl font-semibold text-gray-700 mb-2">No Webinars Available</h3>
                        <p class="text-gray-600">There are currently no webinars available for registration.</p>
                    </div>
                @endif
            </div>
        </div>
    </section>
    @endif
@endsection