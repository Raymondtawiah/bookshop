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
    </style>
    </head>
    <body class="antialiased overflow-x-hidden m-0 p-0 box-border w-full min-w-0">
        <x-flash-message />
        <!-- Navigation -->
        <x-customer-navbar />
        
<div class="w-full overflow-x-hidden min-w-0 mx-0 px-0">
         
     <!-- Announcement Banner -->
     <div class="pt-16 bg-gradient-to-r from-green-50 to-emerald-50 border-b border-green-200">
             <div class="max-w-7xl mx-auto px-6 py-4 flex flex-col sm:flex-row items-center justify-between gap-4">
                 <div class="flex items-center gap-4">
                     <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                         <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                         </svg>
                     </div>
                     <div>
                         <h3 class="font-bold text-gray-900 text-lg">Limited Time Discounts!</h3>
                         <p class="text-gray-600 text-sm">Get 25% off all e-books and 30% off webinar registrations. Don't miss out!</p>
                     </div>
                 </div>
                 <a href="{{ route('discounts') }}" class="px-6 py-2.5 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-semibold rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all shadow-md">
                     Learn More
                 </a>
             </div>
         </div>

         <!-- Hero Section -->
         <section id="home" class="relative overflow-hidden hero-gradient">
             <div class="absolute inset-0 overflow-hidden pointer-events-none">
                 <div class="absolute top-20 left-10 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
                 <div class="absolute bottom-20 right-10 w-40 h-40 bg-purple-300/20 rounded-full blur-2xl"></div>
                 <div class="absolute top-1/2 left-1/3 w-24 h-24 bg-blue-300/20 rounded-full blur-xl"></div>
             </div>
             
             <div class="relative max-w-7xl mx-auto px-6 py-20 sm:py-28">
                 <div class="grid lg:grid-cols-2 gap-12 lg:gap-8 items-center">
                     <!-- Text Content -->
                     <div class="space-y-8">
                         <div class="inline-flex items-center gap-2 px-4 py-2 bg-white/15 backdrop-blur-md rounded-full border border-white/20">
                             <svg class="w-5 h-5 text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                             </svg>
                             <span class="text-sm font-bold text-white">Visa Interview Preparation Resources</span>
                         </div>
                         
                         <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white leading-tight tracking-tight">
                             Master Your
                             <span class="block text-transparent bg-clip-text bg-gradient-to-r from-yellow-300 to-orange-300">Visa Interview</span>
                         </h1>
                         
                         <p class="text-lg sm:text-xl text-white/90 leading-relaxed max-w-lg">
                             Practical guides to help students and travelers understand visa interviews, avoid common mistakes, and answer visa officer questions with confidence.
                         </p>
                         
                         <div class="flex flex-col sm:flex-row gap-4">
                             @if(\App\Models\Book::count() > 0)
                             <a href="#store" class="px-8 py-4 bg-white text-indigo-700 rounded-2xl font-bold text-lg hover:bg-indigo-50 transition-all duration-200 shadow-xl text-center">
                                 Explore Books
                             </a>
                             @else
                             <a href="{{ route('register') }}" class="px-8 py-4 bg-white text-indigo-700 rounded-2xl font-bold text-lg hover:bg-indigo-50 transition-all duration-200 shadow-xl text-center">
                                 Get Started
                             </a>
                             @endif
                             @guest
                             <a href="{{ route('register') }}" class="px-8 py-4 bg-white/15 backdrop-blur-sm text-white font-bold rounded-2xl border border-white/30 hover:bg-white/25 transition-all duration-200 text-center">
                                 Create Account
                             </a>
                             @endguest
                         </div>
                         
                         <div class="grid grid-cols-3 gap-4 sm:gap-6 pt-4">
                             <div class="bg-white/10 backdrop-blur-md rounded-2xl p-4 sm:p-5 border border-white/20">
                                 <div class="text-2xl sm:text-3xl font-extrabold text-white">{{ \App\Models\Book::count() }}</div>
                                 <div class="text-indigo-200 text-xs sm:text-sm font-medium">Resources</div>
                             </div>
                             <div class="bg-white/10 backdrop-blur-md rounded-2xl p-4 sm:p-5 border border-white/20">
                                 <div class="text-2xl sm:text-3xl font-extrabold text-white">10K+</div>
                                 <div class="text-indigo-200 text-xs sm:text-sm font-medium">Readers</div>
                             </div>
                             <div class="bg-white/10 backdrop-blur-md rounded-2xl p-4 sm:p-5 border border-white/20">
                                 <div class="text-2xl sm:text-3xl font-extrabold text-white">4.9?</div>
                                 <div class="text-indigo-200 text-xs sm:text-sm font-medium">Rating</div>
                             </div>
                         </div>
                     </div>

                     <!-- Image / Visual -->
                     <div class="hidden lg:block relative">
                         <div class="relative group">
                             <div class="absolute -inset-4 bg-gradient-to-tr from-blue-400 to-purple-400 rounded-3xl blur-2xl opacity-40 group-hover:opacity-60 transition-opacity duration-300"></div>
                             <div class="relative rounded-3xl shadow-2xl overflow-hidden border border-white/20 bg-white/10 backdrop-blur-sm p-2">
                             <img src="{{ asset('officer-charles.png') }}" alt="Visa Interview Coaching" 
                                  class="relative rounded-2xl w-full h-auto object-cover shadow-xl">
                             </div>
                             <div class="absolute -top-4 -right-4 bg-gradient-to-r from-green-500 to-emerald-600 text-white px-5 py-2 rounded-full text-sm font-black shadow-xl whitespace-nowrap inline-block z-10">
                                 START NOW
                             </div>
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

        <!-- Search Section -->
        <section class="py-8 bg-gray-50 max-w-full">
            <div class="max-w-7xl mx-auto px-6">
                <form action="{{ route('search') }}" method="GET" class="flex gap-2 max-w-xl mx-auto">
                    <div class="relative flex-1">
                        <input
                            type="text"
                            name="q"
                            value="{{ $query ?? '' }}"
                            placeholder="Search books by title, author..."
                            class="search-input w-full px-5 py-3 pl-12 rounded-xl border border-gray-200 bg-white text-gray-900 placeholder-gray-500 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all hover:shadow-lg"
                        >
                        <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <button type="submit" class="px-6 py-3 bg-indigo-600 text-white font-medium rounded-xl hover:bg-indigo-700 transition-colors">
                        Search
                    </button>
                </form>
            </div>
        </section>

        <!-- Featured Books Section -->
        @if(isset($featuredBooks) && $featuredBooks->count() > 0)
        <section class="py-16 bg-white max-w-full">
            <div class="max-w-7xl mx-auto px-6">
                <div class="flex items-center justify-between mb-8">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                            </svg>
                        </div>
                        <h2 class="text-3xl font-bold text-gray-900">Featured Books</h2>
                    </div>
                    <a href="#store" class="text-sm font-medium text-indigo-600 hover:text-indigo-700 flex items-center gap-1">
                        View All
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
                
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 md:gap-6">
                    @foreach($featuredBooks as $book)
                    <a href="{{ route('product.show', $book->id) }}" class="group">
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-xl hover:border-indigo-200 transition-all duration-300">
                            <div class="aspect-[3/4] bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center relative overflow-hidden">
                                @if($book->cover_image)
                                    <img src="{{ $book->cover_image_url }}" alt="{{ $book->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                @else
                                    <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                    </svg>
                                @endif
                                <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <span class="bg-white/90 backdrop-blur-sm text-gray-700 text-xs font-medium px-2 py-1 rounded-full shadow-sm">
                                        View Details
                                    </span>
                                </div>
                            </div>
                            <div class="p-4">
                                <h3 class="font-semibold text-gray-900 text-sm truncate group-hover:text-indigo-600 transition-colors">{{ $book->title }}</h3>
                                <p class="text-xs text-gray-500 truncate mt-1">{{ $book->author }}</p>
                                @if($book->description)
                                <div class="mt-2">
                                    <p class="text-xs text-gray-500 line-clamp-2">{{ \Illuminate\Support\Str::limit($book->description, 60) }}</p>
                                </div>
                                @endif
                                <div class="mt-3 flex items-center justify-between">
                                    @if($book->book_pdf)
                                        <p class="font-bold text-lg text-green-600">FREE</p>
                                    @else
                                        <p class="font-bold text-lg text-indigo-600">${{ number_format($book->price, 2) }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
        </section>
        @endif

        <!-- Store Section -->
        @include('components.sections.store-section', ['books' => $books])

        <!-- Search Results -->
        @if(isset($query) && $query)
        <section class="py-12 bg-gray-50 max-w-full">
            <div class="max-w-7xl mx-auto px-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Search Results for "{{ $query }}"</h2>
                @if($books->count() > 0)
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
    @foreach($books as $book)

        {{-- Detect PDF using book_pdf --}}
        @if($book->is_free && $book->book_pdf)
            <button onclick="openFreeBookModal({{ $book->id }}, '{{ $book->title }}')"
               class="block bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-100 hover:shadow-xl hover:border-indigo-200 transition-all duration-300 group">
        @else
            <a href="{{ route('product.show', $book->id) }}"
               class="block bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-100 hover:shadow-xl hover:border-indigo-200 transition-all duration-300 group">
        @endif

            <!-- Cover -->
            <div class="h-48 bg-gradient-to-br from-gray-100 to-gray-200 relative overflow-hidden">

                            @if($book->cover_image)
                                <img src="{{ $book->cover_image_url }}" 
                                    alt="{{ $book->title }}" 
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" 
                                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253"/>
                                    </svg>
                                </div>
                            @endif

                        </div>

                        <!-- Content -->
                        <div class="p-4">
                            <h3 class="font-semibold text-gray-900 text-sm truncate group-hover:text-indigo-600 transition-colors">
                                {{ $book->title }}
                            </h3>

                            <p class="text-xs text-gray-500 mt-1">
                                {{ $book->author }}
                            </p>

                            <!-- Price / FREE -->
                            <div class="mt-3 flex items-center justify-between">
                            @if($book->book_pdf)
                                    <p class="font-bold text-xl text-green-600">FREE</p>
                                @else
    <p class="font-bold text-xl text-indigo-600">
                                          {{ number_format($book->price, 2) }}
                                     </p>
                                @endif
                            </div>

                            <!-- Login prompt only for paid books -->
                            @guest
                                @if(!$book->book_pdf)
                                    <a href="{{ route('login') }}"
                                    class="block mt-3 text-center px-4 py-2 bg-gray-100 text-gray-600 text-sm font-medium rounded-xl hover:bg-gray-200 transition-colors">
                                        Sign in to Buy
                                    </a>
                                @endif
                            @endguest
                        </div>

        @if($book->is_free && $book->book_pdf)
                </button>
        @else
                    </a>
        @endif

                @endforeach
            </div>
                @else
                <p class="text-gray-600">No books found matching "{{ $query }}".</p>
                @endif
            </div>
        </section>
        @endif

        <!-- Visa Guide Promo Section -->
        <section class="py-20 bg-gradient-to-br from-blue-600 via-indigo-600 to-purple-700 max-w-full">
            
            <div class="max-w-5xl mx-auto px-6 pt-16">
                <!-- Main Headline -->
                <div class="text-center mb-10">
                    <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">
                        How to Pass Your Visa Interview in 2 Minutes.
                        <br><span class="text-yellow-300">Even If You're Nervous or Previously Denied.</span>
                    </h2>
                    <p class="text-xl text-white/90 max-w-3xl mx-auto leading-relaxed">
                        When I walked into the embassy for my visa interview, I was nervous like everyone else. But I discovered something that most applicants don't know: visa officers are not just judging your documents, they're judging your story.
                        <br><br>
                        After helping many applicants prepare, I realized the same patterns keep leading to approval. This book shows you exactly how to present your story clearly and confidently.
                    </p>
                </div>

                <!-- What's Inside -->
                <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-8 mb-12">
                    <h3 class="text-2xl font-bold text-white mb-6 text-center">Inside this guide, you'll learn:</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex items-start gap-3">
                            <svg class="w-6 h-6 text-green-400 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-white">The 3 questions visa officers almost always ask</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <svg class="w-6 h-6 text-green-400 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-white">The story formula that makes your answers convincing</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <svg class="w-6 h-6 text-green-400 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-white">How to answer "Why this school?" without sounding rehearsed</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <svg class="w-6 h-6 text-green-400 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-white">Mistakes that cause instant visa denials</span>
                        </div>
                        <div class="flex items-start gap-3 md:col-span-2">
                            <svg class="w-6 h-6 text-green-400 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-white">Real visa interview transcripts from successful applicants</span>
                        </div>
                    </div>
                </div>

                <!-- Social Proof -->
                <div class="mb-10">
                    <h3 class="text-2xl font-bold text-white mb-8 text-center">What Our Readers Say</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-white/10 backdrop-blur-sm rounded-xl p-6 border border-white/20">
                            <div class="flex items-center gap-1 mb-3">
                                <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            </div>
                            <p class="text-white italic">"I followed the strategy in this book and got approved. The story formula really helped me present my case confidently."</p>
                            <p class="text-white/70 text-sm mt-2">— F-1 Student</p>
                        </div>
                        <div class="bg-white/10 backdrop-blur-sm rounded-xl p-6 border border-white/20">
                            <div class="flex items-center gap-1 mb-3">
                                <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            </div>
                            <p class="text-white italic">"After a previous denial, this guide helped me fix my answers. I knew exactly what mistakes to avoid."</p>
                            <p class="text-white/70 text-sm mt-2">— B1/B2 Applicant</p>
                        </div>
                        <div class="bg-white/10 backdrop-blur-sm rounded-xl p-6 border border-white/20">
                            <div class="flex items-center gap-1 mb-3">
                                <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            </div>
                            <p class="text-white italic">"The interview practice questions were exactly what I needed. I felt prepared and got my visa approved first try!"</p>
                            <p class="text-white/70 text-sm mt-2">— Graduate Student</p>
                        </div>
                    </div>
                </div>

                <!-- CTA -->
                <div class="text-center">
                    <a href="{{ route('visa-tip') }}" class="inline-block px-8 py-4 bg-white text-indigo-600 font-semibold rounded-xl hover:bg-gray-100 transition-colors shadow-lg">
                        Get Your Copy Now
                    </a>
                </div>
            </div>
        </section>

        <!-- Video Section -->
        <section id="visa-video" class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                    <!-- Video on the left -->
                    <div class="order-1">
                        <div class="relative rounded-2xl overflow-hidden shadow-2xl max-w-lg">
                            <video 
                                autoplay
                                muted
                                controls
                                loop
                                playsinline
                                class="w-full h-72 md:h-96 object-cover rounded-2xl"
                                poster="{{ asset('about-us.jpg') }}"
                                onerror="this.closest('div').innerHTML='<div class=\'flex items-center justify-center h-72 md:h-96 bg-gray-100 rounded-2xl\'><p class=\'text-gray-500\'>Video coming soon</p></div>'"
                            >
                                <source src="{{ asset('For_website.mp4') }}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        </div>
                    </div>
                    <!-- Visa text on the right -->
                    <div class="order-2">
                        <h2 class="text-4xl font-bold text-gray-900 mb-6">Master Your Visa Interview</h2>
                        <p class="text-xl text-gray-600 leading-relaxed mb-4">
                            Visa interviews don't have to be intimidating. With the right preparation and knowledge, 
                            you can approach your interview with confidence and increase your chances of approval.
                        </p>
                        <p class="text-lg text-gray-700 leading-relaxed mb-4">
                            In this video, you'll learn the essential strategies that successful applicants use:
                        </p>
                        <ul class="space-y-3 text-gray-600">
                            <li class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-green-500 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span>How to structure your answers for maximum clarity and impact</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-green-500 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span>Common red flags that cause visa denials and how to avoid them</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-green-500 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span>The story formula that makes your answers authentic and convincing</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-green-500 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span>What to do if you've been denied before</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <!-- About Section -->
        @include('components.sections.about-section')

        <!-- Contact Section -->
        @include('components.sections.contact-section')
        @include('components.sections.animation-script')

        <!-- Newsletter Section -->
        <section class="py-16 bg-gradient-to-r from-indigo-600 to-purple-600 max-w-full">
            <div class="max-w-4xl mx-auto px-6 text-center">
                <h2 class="text-3xl font-bold text-white mb-4">Stay Updated</h2>
                <p class="text-indigo-100 mb-8">Subscribe to our newsletter for exclusive deals, new arrivals, and reading recommendations!</p>
                <form class="flex flex-col sm:flex-row gap-4 max-w-lg mx-auto" onsubmit="handleSubscribe(event)">
                    <input type="email" id="newsletter-email" placeholder="Enter your email" class="flex-1 px-6 py-3 rounded-lg text-gray-900 focus:outline-none focus:ring-2 focus:ring-white" required>
                    <button type="submit" class="px-8 py-3 bg-white text-indigo-600 font-semibold rounded-lg hover:bg-gray-100 transition-colors">
                        Subscribe
                    </button>
                </form>
                <p class="text-indigo-200 text-sm mt-4">Join {{ \App\Models\User::where('is_admin', false)->count() }}+ subscribers</p>
            </div>
        </section>

         <!-- Features Section - Horizontal scroll on mobile, grid on desktop -->
         <section class="py-16 bg-gray-50 overflow-hidden">
             <div class="max-w-7xl mx-auto px-6">
                  <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-8">
                     <div class="text-center">
                         <div class="w-16 h-16 bg-white rounded-2xl shadow-md flex items-center justify-center mx-auto mb-4">
                             <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                             </svg>
                         </div>
                         <h3 class="font-semibold text-gray-900 mb-2">Free Shipping</h3>
                         <p class="text-gray-600 text-sm">On orders over $100</p>
                     </div>
                     <div class="text-center">
                         <div class="w-16 h-16 bg-white rounded-2xl shadow-md flex items-center justify-center mx-auto mb-4">
                             <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                             </svg>
                         </div>
                         <h3 class="font-semibold text-gray-900 mb-2">Quality Books</h3>
                         <p class="text-gray-600 text-sm">100% authentic products</p>
                     </div>
                     <div class="text-center">
                         <div class="w-16 h-16 bg-white rounded-2xl shadow-md flex items-center justify-center mx-auto mb-4">
                             <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                             </svg>
                         </div>
                         <h3 class="font-semibold text-gray-900 mb-2">Easy Returns</h3>
                         <p class="text-gray-600 text-sm">30-day return policy</p>
                     </div>
                     <div class="text-center">
                         <div class="w-16 h-16 bg-white rounded-2xl shadow-md flex items-center justify-center mx-auto mb-4">
                             <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                             </svg>
                         </div>
                         <h3 class="font-semibold text-gray-900 mb-2">24/7 Support</h3>
                         <p class="text-gray-600 text-sm">Dedicated customer care</p>
                     </div>
                 </div>
            </div>
        </section>
        

         <style>
             @keyframes slideInFromRight {
                 from {
                     transform: translateX(100%);
                     opacity: 0;
                 }
                 to {
                     transform: translateX(0);
                     opacity: 1;
                 }
             }
             
             .animate-slide-in {
                 animation: slideInFromRight 0.3s ease-out forwards;
             }
         </style>

         <script>
             // Newsletter subscription handler
             function handleSubscribe(event) {
                 event.preventDefault();
                 
                 // Show success flash message
                 const flashHtml = `
                     <div class="fixed top-20 right-4 z-50 max-w-sm animate-slide-in" id="newsletter-flash">
                         <div class="flex items-center p-6 rounded-xl shadow-2xl bg-green-50 border-2 border-green-200">
                             <div class="flex-shrink-0 mr-4">
                                 <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                 </svg>
                             </div>
                             <div class="flex-1">
                                 <p class="text-lg font-bold text-green-800">Thank you for subscribing!</p>
                                 <p class="text-sm text-green-700 mt-1">You'll receive our latest updates.</p>
                             </div>
                             <button onclick="this.parentElement.parentElement.remove()" class="flex-shrink-0 ml-2 text-gray-500 hover:text-gray-700">
                                 <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                 </svg>
                             </button>
                         </div>
                     </div>
                 `;
                 document.body.insertAdjacentHTML('beforeend', flashHtml);
                 
                 // Remove flash message after 5 seconds
                 setTimeout(() => {
                     const flash = document.getElementById('newsletter-flash');
                     if (flash) {
                         flash.remove();
                     }
                 }, 5000);
             }
         </script>
        <x-customer-footer />

        <x-install-pwa />

        @include('components.free-book-modal')
    </div>
    </body>
</html>

