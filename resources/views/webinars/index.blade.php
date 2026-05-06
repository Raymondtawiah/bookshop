@extends('layouts.app')

@section('content')
    <!-- Hero Section -->
    <section class="py-20 text-white max-w-full relative overflow-hidden min-h-screen flex items-center" 
             style="background: linear-gradient(135deg, rgba(59, 130, 246, 0.85) 0%, rgba(99, 102, 241, 0.85) 50%, rgba(139, 92, 246, 0.85) 100%), url('/webinar.png'); background-size: cover; background-position: center; background-attachment: fixed;">
        
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
                        <a href="#register" class="group relative px-8 py-4 bg-white text-blue-600 rounded-xl font-semibold text-lg transition-all duration-300 transform hover:scale-105 shadow-xl hover:shadow-2xl">
                            <span class="relative z-10">Registration</span>
                        </a>
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

    <!-- Registration Form -->
    <section id="register" class="py-20 bg-gradient-to-r from-blue-600 to-indigo-700">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl p-10 shadow-2xl">
                <div class="text-center mb-8">
                    <h2 class="text-4xl font-bold mb-4">Register Now</h2>
                    <p class="text-xl text-slate-600 mb-4">
                        Secure your spot today
                    </p>
                    
                    @php
                        $webinar = $webinars->first();
                        $paymentsOpen = $webinar ? $webinar->arePaymentsOpen() : true;
                        $paymentMessage = $webinar ? $webinar->getPaymentStatusMessage() : '';
                    @endphp
                    
                    @if($paymentsOpen)
                        <div class="bg-blue-50 p-4 rounded-lg mb-6">
                            <div class="flex items-center justify-center gap-2">
                                <span class="text-sm text-slate-600">Price:</span>
                                <span class="text-3xl font-bold text-blue-600" id="registrationPrice">₵30.00</span>
                                <span class="text-sm text-blue-600 font-medium" id="priceTier">Early Registration</span>
                            </div>
                            <p class="text-xs text-slate-500 mt-2">Price automatically updates based on registration timing</p>
                        </div>
                    @else
                        <div class="bg-red-50 border-2 border-red-200 p-4 rounded-lg mb-6">
                            <div class="flex items-center justify-center gap-2 mb-2">
                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span class="text-lg font-semibold text-red-700">Registration Closed</span>
                            </div>
                            <p class="text-sm text-red-600">{{ $paymentMessage }}</p>
                            <p class="text-xs text-red-500 mt-2">Webinars are held every Friday. Registration reopens on Sunday.</p>
                        </div>
                    @endif
                </div>
                
                @if($paymentsOpen)
                    <form id="registrationForm" method="POST" action="#" class="space-y-6" data-first-webinar-id="{{ $webinars->first()->id ?? '' }}">
                        <input type="hidden" name="webinar_id" id="webinarId">
                        
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Name</label>
                            <input type="text" name="full_name" required
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Email Address</label>
                            <input type="email" name="email" required
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        
                        
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Phone</label>
                            <input type="tel" name="phone" required
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        
                        <div class="flex items-start gap-3">
                            <input type="checkbox" name="terms" required class="mt-1 rounded">
                            <label class="text-sm text-slate-600">
                                I agree to the terms and conditions
                            </label>
                        </div>
                        
                        <button type="submit" class="w-full py-4 bg-blue-600 text-white rounded-lg font-semibold text-lg hover:bg-blue-700 transition-colors">
                            Complete Registration
                        </button>
                    </form>
                @else
                    <div class="text-center py-8">
                        <div class="bg-gray-100 rounded-lg p-6 mb-4">
                            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <h3 class="text-xl font-semibold text-gray-700 mb-2">Next Registration Opens Sunday</h3>
                            <p class="text-gray-600">Our weekly webinar is held every Friday. Registration opens every Sunday and closes every Thursday.</p>
                        </div>
                        <a href="{{ route('home') }}" class="inline-block px-6 py-3 bg-gray-600 text-white rounded-lg font-semibold hover:bg-gray-700 transition-colors">
                            Back to Home
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!-- Registration Modal -->
    <div id="registrationModal" class="fixed inset-0 bg-black/50 z-50 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-bold">Register for Webinar</h3>
                    <button onclick="closeRegistrationModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <div class="bg-blue-50 p-4 rounded-lg mb-6">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-slate-600">Price:</span>
                        <span class="text-2xl font-bold text-blue-600" id="modalPrice">₵0.00</span>
                    </div>
                </div>
                <p class="text-center text-slate-600">Complete the registration form below to secure your spot.</p>
            </div>
        </div>
    </div>

    <script>
        // Dynamic pricing update
        function updatePricing() {
            const now = new Date();
            const dayOfWeek = now.getDay(); // 0 = Sunday, 1 = Monday, ..., 6 = Saturday
            const priceElement = document.getElementById('registrationPrice');
            const tierElement = document.getElementById('priceTier');
            
            let price, tier;
            
            // Sunday (0) to Tuesday (2) = Early Registration
            if (dayOfWeek >= 0 && dayOfWeek <= 2) {
                price = 30.00;
                tier = 'Early Registration';
            } else {
                // Wednesday (3) onwards = Late Registration
                price = 50.00;
                tier = 'Late Registration';
            }
            
            priceElement.textContent = '₵' + price.toFixed(2);
            tierElement.textContent = tier;
        }
        
        // Update pricing every minute
        setInterval(updatePricing, 60000);
        
        document.addEventListener('DOMContentLoaded', function() {
            updatePricing();
            
            document.getElementById('registrationForm').addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                const form = document.getElementById('registrationForm');
                const firstWebinarId = form.dataset.firstWebinarId;
                const webinarId = formData.get('webinar_id') || firstWebinarId; // Use first available webinar
                
                // Validate webinar ID exists
                if (!webinarId) {
                    alert('No webinars are currently available for registration. Please check back later.');
                    return;
                }
                
                // Show loading state
                const submitBtn = this.querySelector('button[type="submit"]');
                const originalText = submitBtn.textContent;
                submitBtn.textContent = 'Processing...';
                submitBtn.disabled = true;
                
                fetch(`/webinar/${webinarId}/register`, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        if (data.redirect_url) {
                            window.location.href = data.redirect_url;
                        } else {
                            alert('Registration successful! Check your email for details.');
                            this.reset();
                        }
                    } else {
                        alert(data.message || 'Registration failed. Please try again.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                })
                .finally(() => {
                    submitBtn.textContent = originalText;
                    submitBtn.disabled = false;
                });
            });
        });
    </script>
@endsection