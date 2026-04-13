<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- PWA Meta Tags -->
        <meta name="theme-color" content="#4f46e5" />
        <meta name="apple-mobile-web-app-capable" content="yes" />
        <meta name="apple-mobile-web-app-status-bar-style" content="default" />
        <meta name="apple-mobile-web-app-title" content="BookShop" />
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
    </head>
    <body class="antialiased" style="overflow-x: hidden; max-width: 100vw;">
        <x-flash-message />
        <!-- Navigation -->
        <x-customer-navbar />

        <!-- Hero Section with S-Wave -->
        <section id="home" class="relative pt-32 pb-20 bg-gradient-to-br from-blue-600 via-indigo-600 to-purple-700">
            <!-- Background Image -->
            <div class="absolute inset-0">
                <img src="{{ asset('welcome.jpg') }}" alt="Bookshop" class="w-full h-full object-cover object-[center_70%]">
                <div class="absolute inset-0 bg-gradient-to-r from-indigo-900/80 to-purple-900/80"></div>
            </div>
            <div class="max-w-7xl mx-auto px-6 relative z-10">
                <div class="text-center">
                    <h1 class="text-5xl md:text-6xl font-bold text-white mb-6">
                        Visa Interview Preparation 
                        <span class="bg-gradient-to-r from-yellow-400 to-orange-400 bg-clip-text text-transparent">Resources</span>
                    </h1>
                    <p class="text-xl md:text-2xl text-white/90 mb-6 max-w-2xl mx-auto">
                        Practical guides to help students and travelers understand visa interviews, avoid common mistakes, and answer visa officer questions with confidence.
                    </p>
                    <div class="mb-8">
                        <form action="{{ route('search') }}" method="GET" class="flex gap-2 max-w-xl mx-auto">
                            <div class="relative flex-1">
                                <input 
                                    type="text" 
                                    name="q" 
                                    value="{{ $query ?? '' }}"
                                    placeholder="Search books by title, author, category..." 
                                    class="w-full px-5 py-3 pl-12 rounded-xl border border-white/20 bg-white/10 text-white placeholder-white/70 focus:bg-white focus:text-gray-900 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all"
                                >
                                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            <button type="submit" class="px-6 py-3 bg-white text-indigo-600 font-medium rounded-xl hover:bg-gray-100 transition-colors">
                                Search
                            </button>
                        </form>
                    </div>
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-4 top-1.5">
                        @if(\App\Models\Book::count() > 0)
                        <a href="#store" class="px-6 py-3 bg-white text-indigo-600 font-medium rounded-lg hover:bg-gray-100 transition-colors">
                            Explore Books
                        </a>
                        @else
                        <a href="{{ route('register') }}" class="px-8 py-4 bg-white text-indigo-600 font-semibold rounded-xl hover:bg-gray-100 transition-colors">
                            Get Started
                        </a>
                        @endif
                        @guest
                        <a href="{{ route('register') }}" class="px-6 py-3 bg-white/20 text-white font-medium rounded-lg border border-white/30 hover:bg-white/30 transition-colors">
                            Create Account
                        </a>
                        @endguest
                    </div>
                </div>
            </div>
        </section>

        <!-- Store Section -->
        @if($books && $books->count() > 0)
        @include('components.sections.store-section', ['books' => $books])
        @endif

        <!-- Search Results -->
        @if(isset($query) && $query)
        <section class="py-12 bg-gray-50">
            <div class="max-w-7xl mx-auto px-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Search Results for "{{ $query }}"</h2>
                @if($books->count() > 0)
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    @foreach($books as $book)
                    @if($book->is_free && $book->book_pdf_url)
                    <a href="{{ route('product.download', $book->id) }}" class="block bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-100 hover:shadow-xl hover:border-indigo-200 transition-all duration-300 group">
                    @else
                    <a href="{{ route('product.show', $book->id) }}" class="block bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-100 hover:shadow-xl hover:border-indigo-200 transition-all duration-300 group">
                    @endif
                        <div class="h-48 bg-gradient-to-br from-gray-100 to-gray-200 relative overflow-hidden">
                            @if($book->cover_image)
                                <img src="{{ $book->cover_image_url }}" alt="{{ $book->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div class="p-4">
                            <h3 class="font-semibold text-gray-900 text-sm truncate group-hover:text-indigo-600 transition-colors">{{ $book->title }}</h3>
                            <p class="text-xs text-gray-500 mt-1">{{ $book->author }}</p>
                            <div class="mt-3 flex items-center justify-between">
                                @if($book->is_free && $book->book_pdf_url)
                                <p class="font-bold text-xl text-green-600">FREE</p>
                                @else
<p class="font-bold text-xl text-indigo-600">₵{{ number_format($book->price, 2) }}</p>
                            </div>
                            @guest
                            @if(!($book->is_free && $book->book_pdf_url))
                            <a href="{{ route('login') }}" class="block mt-3 text-center px-4 py-2 bg-gray-100 text-gray-600 text-sm font-medium rounded-xl hover:bg-gray-200 transition-colors">
                                Sign in to Buy
                            </a>
                            @endif
                            @endguest
                        </div>
                    </a>
                    @endforeach
                </div>
                @else
                <p class="text-gray-600">No books found matching "{{ $query }}".</p>
                @endif
            </div>
        </section>
        @endif

        <!-- Visa Guide Promo Section -->
        <section class="py-20 bg-gradient-to-br from-blue-600 via-indigo-600 to-purple-700">
            
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

        <!-- About Section -->
        @include('components.sections.about-section')

        <!-- Contact Section -->
        @include('components.sections.contact-section')
        @include('components.sections.animation-script')

        <!-- Newsletter Section -->
        <section class="py-16 bg-gradient-to-r from-indigo-600 to-purple-600">
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
                <!-- Horizontal scroll on mobile, grid on desktop -->
                <div class="flex overflow-x-auto gap-6 pb-4 md:grid md:grid-cols-4 md:gap-8 md:overflow-x-visible md:pb-0 scrollbar-hide -mx-6 px-6 md:mx-0 md:px-0">
                    <div class="flex-shrink-0 w-40 text-center">
                        <div class="w-16 h-16 bg-white rounded-2xl shadow-md flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2">Free Shipping</h3>
                        <p class="text-gray-600 text-sm">On orders over ₵100</p>
                    </div>
                    <div class="flex-shrink-0 w-40 text-center">
                        <div class="w-16 h-16 bg-white rounded-2xl shadow-md flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2">Quality Books</h3>
                        <p class="text-gray-600 text-sm">100% authentic products</p>
                    </div>
                    <div class="flex-shrink-0 w-40 text-center">
                        <div class="w-16 h-16 bg-white rounded-2xl shadow-md flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2">Easy Returns</h3>
                        <p class="text-gray-600 text-sm">30-day return policy</p>
                    </div>
                    <div class="flex-shrink-0 w-40 text-center">
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
                
                // Clear the input
                document.getElementById('newsletter-email').value = '';
                
                // Remove after 5 seconds
                setTimeout(() => {
                    const flash = document.getElementById('newsletter-flash');
                    if (flash) flash.remove();
                }, 5000);
            }

            document.addEventListener('DOMContentLoaded', function() {
                const counters = document.querySelectorAll('.counter');
                const speed = 20;

                counters.forEach(counter => {
                    const updateCount = () => {
                        const target = +counter.getAttribute('data-target');
                        const count = +counter.innerText;
                        const inc = target / speed;

                        if (count < target) {
                            counter.innerText = Math.ceil(count + inc);
                            setTimeout(updateCount, 10);
                        } else {
                            counter.innerText = target + '+';
                        }
                    };
                    updateCount();
                });
            });
        </script>
        <x-customer-footer />

        <x-install-pwa />

        <x-chat-widget />
    </body>
</html>

