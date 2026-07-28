@extends('layouts.app')

@section('title', 'Register for Webinar')

@section('content')
    <div class="min-h-screen relative overflow-hidden py-12 px-4 sm:px-6 lg:px-8" 
         style="background: linear-gradient(135deg, rgba(59, 130, 246, 0.1) 0%, rgba(99, 102, 241, 0.1) 50%, rgba(139, 92, 246, 0.1) 100%);">
        
        <!-- Animated overlay pattern -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=&quot;60&quot; height=&quot;60&quot; viewBox=&quot;0 0 60 60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cg fill=&quot;none&quot; fill-rule=&quot;evenodd&quot;%3E%3Cg fill=&quot;%234F46E5&quot; fill-opacity=&quot;0.3&quot;%3E%3Cpath d=&quot;M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z&quot;/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
        </div>
        
        <!-- Floating elements animation -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute top-20 left-10 w-20 h-20 bg-blue-300/20 rounded-full animate-pulse"></div>
            <div class="absolute top-1/4 right-20 w-28 h-28 bg-indigo-300/20 rounded-full animate-bounce" style="animation-delay: 0.5s;"></div>
            <div class="absolute bottom-20 left-1/4 w-16 h-16 bg-purple-300/20 rounded-full animate-pulse" style="animation-delay: 1s;"></div>
            <div class="absolute top-1/2 right-1/3 w-24 h-24 bg-pink-300/20 rounded-full animate-bounce" style="animation-delay: 1.5s;"></div>
        </div>
        
        <div class="max-w-2xl mx-auto relative z-10">
            <!-- Flash Messages -->
            <div id="flash-container" class="mb-6 hidden">
                <div id="flash-message" class="rounded-xl p-4 shadow-lg transform transition-all duration-500">
                    <div class="flex items-center gap-3">
                        <div id="flash-icon"></div>
                        <div>
             
                        <p id="flash-title" class="font-bold text-sm"></p>
                            <p id="flash-text" class="text-sm opacity-90"></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Back Button -->
            <a href="{{ route('webinars.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 mb-8 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Webinars
            </a>

            <!-- Registration Card -->
            <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-2xl overflow-hidden transform hover:scale-105 transition-transform duration-500">
                <!-- Header -->
                <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-8 py-6">
                    <h1 class="text-3xl font-bold text-white">Register for Webinar</h1>
                    <p class="text-blue-100 mt-2">Secure your spot for the upcoming session</p>
                </div>

                <!-- Content -->
                <div class="p-8">
                    @php
                        $webinar = $webinar ?? null;
                    @endphp

                    @if($webinar)
                        <!-- Price -->
                        <div class="flex items-center justify-between mb-6">
                            <span class="text-gray-600 font-medium">Price</span>
                            <span class="text-3xl font-bold text-gray-900">${{ number_format($webinar->current_price, 2) }}</span>
                        </div>

                        <!-- Registration Form -->
                        <form id="registrationForm" method="POST" action="#" class="space-y-6">
                            @csrf
                            <input type="hidden" name="webinar_id" value="{{ $webinar->id ?? '' }}">

                            <div class="transform transition-all duration-300 hover:translate-x-1">
                                <label for="full_name" class="block text-sm font-semibold text-gray-700 mb-2">Full Name *</label>
                                <input type="text" name="full_name" id="full_name" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 hover:shadow-md text-gray-800 placeholder-gray-500"
                                    placeholder="Enter your full name">
                            </div>

                            <div class="transform transition-all duration-300 hover:translate-x-1">
                                <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email Address *</label>
                                <input type="email" name="email" id="email" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 hover:shadow-md text-gray-800 placeholder-gray-500"
                                    placeholder="Enter your email address">
                            </div>

                            <div class="transform transition-all duration-300 hover:translate-x-1">
                                <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">Phone Number *</label>
                                <input type="tel" name="phone" id="phone" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 hover:shadow-md text-gray-800 placeholder-gray-500"
                                    placeholder="Enter your phone number">
                            </div>

                            <div class="flex items-start gap-3 transform transition-all duration-300 hover:translate-x-1">
                                <input type="checkbox" name="terms" id="terms" required class="mt-1 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <label for="terms" class="text-sm text-gray-600">
                                    I agree to the terms and conditions and understand that my registration will be confirmed upon payment.
                                </label>
                            </div>

                            <button type="submit" class="w-full py-4 bg-gradient-to-r from-blue-600 to-indigo-700 text-white rounded-lg font-semibold text-lg hover:from-blue-700 hover:to-indigo-800 transition-all duration-300 shadow-lg hover:shadow-2xl transform hover:-translate-y-1">
                                Proceed to Payment
                            </button>
                        </form>
                    @else
                        <!-- No Webinar -->
                        <div class="text-center py-8">
                            <div class="bg-gray-100/80 rounded-xl p-6 mb-4 border border-gray-200 transform hover:scale-105 transition-transform duration-300">
                                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <h3 class="text-xl font-semibold text-gray-700 mb-2">Webinar Not Found</h3>
                                <p class="text-gray-600">The webinar you are looking for does not exist.</p>
                            </div>
                            <a href="{{ route('webinars.index') }}" class="inline-block px-6 py-3 bg-gradient-to-r from-gray-600 to-gray-700 text-white rounded-lg font-semibold hover:from-gray-700 hover:to-gray-800 transition-all duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-1">
                                View All Webinars
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <style>
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .animate-fade-in-up {
            animation: fadeInUp 0.8s ease-out forwards;
        }

        /* Flash messages */
        #flash-container.success #flash-message {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            border: 1px solid #34d399;
        }
        #flash-container.error #flash-message {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
            border: 1px solid #f87171;
        }
        #flash-container.hidden {
            display: none;
        }
    </style>
    
    <script>
        function showFlash(type, message) {
            const container = document.getElementById('flash-container');
            const flash = document.getElementById('flash-message');
            const title = document.getElementById('flash-title');
            const text = document.getElementById('flash-text');
            const icon = document.getElementById('flash-icon');

            container.classList.remove('hidden', 'success', 'error');
            container.classList.add(type);

            if (type === 'success') {
                title.textContent = 'Success!';
                icon.innerHTML = '<svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
            } else {
                title.textContent = 'Error';
                icon.innerHTML = '<svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
            }

            text.textContent = message;

            setTimeout(() => {
                container.classList.add('hidden');
            }, 4000);
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Add fade-in animation to the registration card
            const card = document.querySelector('.bg-white\\/95');
            if (card) {
                card.classList.add('animate-fade-in-up');
            }
            
            const form = document.getElementById('registrationForm');
            if (form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const formData = new FormData(this);
                    const webinarId = formData.get('webinar_id');
                    
                    // Validate webinar ID exists
                    if (!webinarId) {
                        alert('Invalid webinar. Please try again.');
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
                                showFlash('success', data.message || 'Registration successful! Check your email for details.');
                                setTimeout(() => {
                                    window.location.href = '{{ route('webinars.index') }}';
                                }, 4500);
                            }
                        } else {
                            showFlash('error', data.message || 'Registration failed. Please try again.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showFlash('error', 'An error occurred. Please try again.');
                    })
                    .finally(() => {
                        submitBtn.textContent = originalText;
                        submitBtn.disabled = false;
                    });
                });
            }
        });
    </script>
@endsection
