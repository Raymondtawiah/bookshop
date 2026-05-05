@extends('layouts.app')

@section('content')
    <!-- Hero Section -->
    <section class="py-20 bg-gradient-to-br from-blue-600 via-indigo-600 to-purple-700 text-white max-w-full">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 gap-12 items-center max-w-7xl mx-auto">
                <div>
                    <div class="inline-block px-4 py-2 bg-white/20 rounded-full mb-4">
                        <span class="text-sm">Upcoming Sessions • Expert Led</span>
                    </div>
                    <h1 class="text-5xl font-bold mb-6">
                        Visa Interview Success Webinar
                    </h1>
                    <p class="text-xl mb-8 text-blue-100">
                        Master your visa interview with expert guidance. Learn proven strategies, common questions, and how to answer confidently to get your visa approved.
                    </p>
                    <div class="flex gap-4">
                        <a href="#register" class="px-8 py-4 bg-white text-blue-600 rounded-lg font-semibold text-lg hover:bg-gray-100 transition-colors">
                            Registration
                        </a>
                    </div>
                    <div class="flex gap-8 mt-8">
                        <div>
                            <div class="text-3xl font-bold">{{ $webinars->count() ?? 0 }}+</div>
                            <div class="text-blue-200">Available Sessions</div>
                        </div>
                        <div>
                            <div class="text-3xl font-bold">5+</div>
                            <div class="text-blue-200">Expert Speakers</div>
                        </div>
                        <div>
                            <div class="text-3xl font-bold">60min</div>
                            <div class="text-blue-200">Each Session</div>
                        </div>
                    </div>
                </div>
                <div class="hidden md:block">
                    <img src="/webinar.png" alt="Professional Webinars" class="rounded-2xl shadow-2xl w-full h-auto object-cover">
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
                    <form id="registrationForm" class="space-y-6">
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
                const webinarId = formData.get('webinar_id') || 1; // Default to first webinar
                
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