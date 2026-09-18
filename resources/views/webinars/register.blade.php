@extends('layouts.app')

@section('title', 'Register for Webinar')

@section('content')
    <!-- Hero Section -->
    <section id="home" class="relative overflow-hidden bg-gray-100">
        <div style="width:100%;max-width:1200px;height:600px;margin:0 auto;position:relative;background:linear-gradient(135deg, rgba(59, 130, 246, 0.85) 0%, rgba(99, 102, 241, 0.85) 50%, rgba(139, 92, 246, 0.85) 100%);">
            <div id="star-overlay" style="position:absolute;inset:0;background:rgba(59, 130, 246, 0.2);"></div>
            <div class="relative max-w-7xl mx-auto px-6 py-40 sm:py-56 flex items-center justify-center h-full">
                <div class="text-center max-w-3xl mx-auto space-y-5">
                    <div class="inline-flex items-center gap-2 px-4 py-2 bg-white/15 backdrop-blur-md rounded-full border border-white/20">
                        <span class="relative flex h-2.5 w-2.5 mr-2.5">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-green-500"></span>
                        </span>
                        <span class="text-sm font-medium">Upcoming Sessions • Expert Led</span>
                    </div>
                    
                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-white leading-tight tracking-tight">
                        Visa Interview
                        <span class="block text-transparent bg-clip-text bg-gradient-to-r from-yellow-300 to-orange-300">Success Webinar</span>
                    </h1>
                    
                    <p class="text-base sm:text-lg text-white/90 leading-relaxed max-w-2xl mx-auto">
                        Master your visa interview with expert guidance. Learn proven strategies, common questions, and how to answer confidently to get your visa approved.
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

    <div class="min-h-screen bg-gray-50 flex flex-col items-center justify-center gap-4 py-12 px-4 sm:px-6 lg:px-8">
        
        <!-- Flash Messages -->
        <div class="w-full max-w-[200px] mx-auto">
            <div id="flash-container" class="mb-2 hidden">
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

            <a href="{{ route('webinars.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-gray-500 hover:text-indigo-600 transition-colors mb-4">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                Back to home
            </a>

            <div class="bg-white border border-gray-200 rounded-2xl shadow-xl overflow-hidden">
                <div class="p-5 sm:p-6">
                <div class="text-center mb-8">
                    <div class="w-10 h-10 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center mx-auto mb-3">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="2" y="6" width="14" height="12" rx="2"/><path d="M16 10l6-3v10l-6-3"/></svg>
                    </div>
                    <h2 class="text-xl font-extrabold text-gray-900 mb-1">Register for Webinar</h2>
                    <p class="text-xs text-gray-500 mb-3">Secure your spot for the upcoming session</p>
                    @php
                        $webinar = $webinar ?? null;
                    @endphp
                    @if($webinar)
                        <span class="inline-flex items-baseline gap-1 bg-indigo-50 text-indigo-700 px-3 py-1 rounded-full font-bold">
                            <span class="text-base">${{ number_format($webinar->current_price, 2) }}</span>
                            <span class="text-[10px] font-semibold opacity-80">/ seat</span>
                        </span>
                    @endif
                </div>

                @if($webinar)
                    <form id="registrationForm" method="POST" action="{{ route('webinars.register.store', $webinar) }}" novalidate>
                        @csrf
                        <input type="hidden" name="webinar_id" value="{{ $webinar->id ?? '' }}">
                        
                        <div class="mb-4">
                            <label for="full_name" class="block text-sm font-semibold text-gray-900 mb-1.5">Full Name <span class="text-red-500">*</span></label>
                            <input type="text" name="full_name" id="full_name" required placeholder="e.g. Jane Doe" class="w-full px-3 py-2 border border-gray-200 rounded-md text-xs focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                        </div>
                        
                        <div class="mb-4">
                            <label for="email" class="block text-sm font-semibold text-gray-900 mb-1.5">Email Address <span class="text-red-500">*</span></label>
                            <input type="email" name="email" id="email" required placeholder="you@example.com" class="w-full px-3 py-2 border border-gray-200 rounded-md text-xs focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                        </div>
                        
                        <div class="mb-4">
                            <label for="phone" class="block text-sm font-semibold text-gray-900 mb-1.5">Phone Number <span class="text-red-500">*</span></label>
                            <input type="tel" name="phone" id="phone" required placeholder="+1xxxxxxxxxx" class="w-full px-3 py-2 border border-gray-200 rounded-md text-xs focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                        </div>

                        <div class="flex items-start gap-3 mb-4">
                            <input type="checkbox" name="terms" id="terms" required class="mt-0.5 w-3.5 h-3.5 accent-indigo-600">
                            <label for="terms" class="text-[11px] leading-relaxed text-gray-500">I agree to the <a href="#" class="text-indigo-600 font-semibold">terms and conditions</a> and understand that my registration will be confirmed upon payment.</label>
                        </div>

                        <button type="submit" class="w-full bg-indigo-600 text-white py-2.5 rounded-lg font-semibold text-xs hover:bg-indigo-700 transition-colors flex items-center justify-center gap-2">
                            Complete Registration
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                        </button>
                    </form>

                    <div id="registrationSuccess" class="hidden text-center py-10">
                        <div class="w-14 h-14 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto mb-4">
                            <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">You're almost in!</h3>
                        <p class="text-sm text-gray-500">This is a front-end demo — connect this form to your payment flow to confirm the registration.</p>
                    </div>

                    <p class="text-center text-xs text-gray-400 mt-6">Secure checkout &bull; Your info is never shared</p>
                    <a href="{{ route('webinars.index') }}" class="block text-center text-xs font-semibold text-gray-400 hover:text-indigo-600 mt-2">Not ready yet? Return to homepage</a>
                @else
                    <div class="text-center py-8">
                        <div class="bg-gray-100 rounded-xl p-6 mb-4 border border-gray-200">
                            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <h3 class="text-xl font-semibold text-gray-700 mb-2">Webinar Not Found</h3>
                            <p class="text-gray-600">The webinar you are looking for does not exist.</p>
                        </div>
                        <a href="{{ route('webinars.index') }}" class="inline-block px-6 py-3 bg-indigo-600 text-white rounded-lg font-semibold hover:bg-indigo-700 transition-colors shadow-md hover:shadow-lg">
                            View All Webinars
                        </a>
                    </div>
                @endif
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

        function init() {
            const card = document.querySelector('.bg-white');
            if (card) {
                card.classList.add('animate-fade-in-up');
            }
            
            const form = document.getElementById('registrationForm');
            if (form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const formData = new FormData(this);
                    const webinarId = formData.get('webinar_id');
                    
                    if (!webinarId) {
                        alert('Invalid webinar. Please try again.');
                        return;
                    }
                    
                    const submitBtn = this.querySelector('button[type="submit"]');
                    const originalText = submitBtn.textContent;
                    submitBtn.textContent = 'Processing...';
                    submitBtn.disabled = true;
                    
                    fetch(form.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                        },
                    })
                    .then(response => {
                        if (response.status === 422) {
                            return response.json().then(data => {
                                throw { validationErrors: data.errors || {} };
                            });
                        }
                        if (!response.ok) {
                            throw new Error('Network response was not ok: ' + response.status);
                        }
                        return response.json();
                    })
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
                        if (error.validationErrors) {
                            const messages = Object.values(error.validationErrors).flat().join(', ');
                            showFlash('error', messages || 'Please check your information and try again.');
                        } else {
                            showFlash('error', 'An error occurred. Please try again.');
                        }
                    })
                    .finally(() => {
                        submitBtn.textContent = originalText;
                        submitBtn.disabled = false;
                    });
                });
            }
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', init);
        } else {
            init();
        }
    </script>
@endsection
