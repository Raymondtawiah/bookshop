<!DOCTYPE html>
    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="overflow-x-hidden">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#4f46e5" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="default" />
    <meta name="apple-mobile-web-app-title" content="BookShop" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="manifest" href="/manifest.json">
    <title>{{ config('app.name', 'Bookshop') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="apple-touch-icon" href="/favicon.ico">
        <script>
            // Register service worker for PWA
            if ('serviceWorker' in navigator) {
                window.addEventListener('load', () => {
                    navigator.serviceWorker.register('/sw.js')
                        .then((registration) => {
                            console.log('SW registered: ', registration);
                        })
                        .catch((registrationError) => {
                            console.log('SW registration failed: ', registrationError);
                        });
                });
            }
            
        </script>
    <style>
        html, body {
            max-width: 100% !important;
            overflow-x: hidden !important;
            position: relative;
        }
        #app, body > div:first-child {
            max-width: 100% !important;
            overflow-x: hidden !important;
        }
        .search-input:hover {
            box-shadow: 0 4px 20px rgba(79, 70, 229, 0.35);
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
    </head>
    <body class="antialiased overflow-x-hidden m-0 p-0 box-border w-full min-w-0">
        <x-page-loader />
        <x-flash-message />
        <!-- Navigation -->
        <x-customer-navbar />
        
<div class="w-full overflow-x-hidden min-w-0 mx-0 px-0">
    <div class="h-16"></div>
        <section class="relative overflow-hidden bg-white">
            <div class="relative max-w-7xl mx-auto px-6 py-10 flex items-center justify-center">
                    <div class="text-center max-w-3xl mx-auto space-y-5">
                        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-gray-900 leading-tight tracking-tight">
                            Prepare for your visa interview with confidence
                        </h1>
                        <p class="text-base sm:text-lg text-gray-600 leading-relaxed max-w-2xl mx-auto">
                            Choose the support that works for you
                        </p>
                    </div>
            </div>
        </section>
</div>

        @if(isset($featuredBooks) && $featuredBooks->count() > 0)
        <section class="py-6 bg-white max-w-full">
            <div class="max-w-7xl mx-auto px-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @php
                        $book = $featuredBooks->first();
                        $coverUrl = $book->cover_image_url ?? asset('welcome.jpg');
                    @endphp
                    <div>
                        <div class="group relative bg-white/10 backdrop-blur-md border border-gray-200/70 rounded-2xl overflow-hidden shadow-sm hover:shadow-xl hover:border-indigo-300 transition-all duration-300 flex flex-col h-full">
                            <div class="flex gap-4 p-3 flex-1">
                                <div class="relative w-24 h-36 flex-shrink-0 bg-gradient-to-br from-gray-100 to-gray-200 rounded-xl overflow-hidden">
                                    <img src="{{ $coverUrl }}" alt="Cover of {{ $book->title }}" loading="lazy" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out">
                                </div>
                                <div class="flex flex-col flex-1 min-w-0">
                                    <h3 class="text-sm font-bold text-gray-900 leading-snug line-clamp-2 mb-1 group-hover:text-indigo-600 transition-colors">{{ $book->title }}</h3>
                                    <p class="text-xs text-gray-500 truncate mb-2">{{ $book->author }}</p>
                                    <div class="flex items-baseline gap-1 mb-3">
                                        @if($book->is_free && $book->book_pdf)
                                            <span class="text-base font-extrabold text-emerald-600">FREE</span>
                                        @else
                                            <span class="text-base font-extrabold text-emerald-600">${{ number_format($book->price, 2) }}</span>
                                        @endif
                                    </div>
                                    <div class="flex gap-1 mt-auto">
                                            <a href="{{ route('product.show', $book->id) }}" class="flex-1 text-center text-xs font-semibold px-2 py-2 rounded-md bg-violet-600 text-white hover:bg-violet-700 transition-colors">View Book</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @php
                        $webinar = $featuredWebinar ?? null;
                    @endphp
                    <div>
                        <div class="group relative bg-white/10 backdrop-blur-md border border-gray-200/70 rounded-2xl overflow-hidden shadow-sm hover:shadow-xl hover:border-indigo-300 transition-all duration-300 flex flex-col h-full">
                            <div class="flex gap-4 p-3 flex-1">
                                <div class="relative w-24 h-36 flex-shrink-0 bg-gradient-to-br from-indigo-100 to-indigo-200 rounded-xl overflow-hidden flex items-center justify-center">
                                    <img src="{{ asset('webinar.jpeg') }}" alt="Webinar" class="w-full h-full object-cover">
                                </div>
                                <div class="flex flex-col flex-1 min-w-0">
                                    <h3 class="text-sm font-bold text-gray-900 leading-snug line-clamp-2 mb-1 group-hover:text-indigo-600 transition-colors">{{ $webinar->title ?? 'Live Webinar' }}</h3>
                                    <p class="text-xs text-gray-500 truncate mb-2">{{ $webinar->description ?? 'Learn with Nathaniel and ask your questions live.' }}</p>
                                    <div class="flex items-baseline gap-1 mb-3">
                                        @if($webinar && ($webinar->payment_enabled ?? false) !== false && ($webinar->current_price ?? 0) > 0)
                                            <span class="text-base font-extrabold text-emerald-600">${{ number_format($webinar->current_price, 2) }}</span>
                                        @else
                                            <span class="text-base font-extrabold text-emerald-600">FREE</span>
                                        @endif
                                    </div>
                                    <div class="flex gap-1 mt-auto">
                                        <a href="{{ $webinar ? route('webinars.index') : route('webinars.index') }}" class="flex-1 text-center text-xs font-semibold px-2 py-2 rounded-md bg-violet-600 text-white hover:bg-violet-700 transition-colors">View Webinar</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="group relative bg-white/10 backdrop-blur-md border border-gray-200/70 rounded-2xl overflow-hidden shadow-sm hover:shadow-xl hover:border-indigo-300 transition-all duration-300 flex flex-col h-full">
                            <div class="flex gap-4 p-3 flex-1">
                                <div class="relative w-24 h-36 flex-shrink-0 bg-gradient-to-br from-indigo-100 to-indigo-200 rounded-xl overflow-hidden flex items-center justify-center">
                                    <img src="{{ asset('coaching.jpeg') }}" alt="Coaching" class="w-full h-full object-cover">
                                </div>
                                <div class="flex flex-col flex-1 min-w-0">
                                    <h3 class="text-sm font-bold text-gray-900 leading-snug line-clamp-2 mb-1 group-hover:text-indigo-600 transition-colors">Personal Coaching</h3>
                                    <p class="text-xs text-gray-500 truncate mb-2">Practice your answers and receive personal feedback</p>
                                    <p class="text-xs text-gray-500 truncate mb-2">30 minute session</p>
                                    <div class="flex items-baseline gap-1 mb-3">
                                        <span class="text-base font-extrabold text-emerald-600">$50</span>
                                    </div>
                                    <div class="flex gap-1 mt-auto">
                                        <a href="{{ route('coaching.booking') }}" class="flex-1 text-center text-xs font-semibold px-2 py-2 rounded-md bg-violet-600 text-white hover:bg-violet-700 transition-colors">View Coaching</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        @endif

        <x-customer-footer />

    @include('chat-widget')

