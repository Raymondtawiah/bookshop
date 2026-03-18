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
        <script src="https://cdn.tailwindcss.com"></script>
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
    <body class="antialiased">
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
                    <p class="text-xl md:text-2xl text-white/90 mb-10 max-w-2xl mx-auto">
                        Practical guides to help students and travelers understand visa interviews, avoid common mistakes, and answer visa officer questions with confidence.
                    </p>
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

        <!-- Featured Books - Horizontal Scroll on Mobile, Grid on Desktop -->
        @if($books && $books->count() > 0)
        <section id="store" class="py-20 bg-white overflow-hidden">
            <div class="max-w-7xl mx-auto px-6">
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-bold text-gray-900 mb-4">Featured Books</h2>
                    <p class="text-xl text-gray-600 max-w-2xl mx-auto">Handpicked selections from our latest collection</p>
                </div>
                
                <!-- Horizontal scroll on mobile, grid on desktop -->
                <div class="flex overflow-x-auto gap-6 pb-4 md:grid md:grid-cols-3 lg:grid-cols-4 md:overflow-x-visible md:gap-6 scrollbar-hide -mx-6 px-6 md:mx-0 md:px-0">
                    @foreach($books as $book)
                    <div class="flex-shrink-0 w-40 md:w-auto">
                        @if($book->is_free && $book->book_pdf_url)
                        <a href="{{ route('product.show', $book->id) }}" class="block transform group-hover:-translate-y-2 transition-all duration-300" title="View Details">
                        @else
                        <a href="{{ route('product.show', $book->id) }}" class="block transform group-hover:-translate-y-2 transition-all duration-300">
                        @endif
                            <div class="w-40 h-60 md:w-full md:h-96 rounded-xl shadow-lg overflow-hidden relative z-10 bg-white border border-gray-100 group-hover:shadow-2xl group-hover:border-indigo-200 transition-all duration-300">
                                @if($book->cover_image)
                                    <img src="{{ $book->cover_image_url }}" alt="{{ $book->title }}" class="w-full h-full object-cover opacity-90 group-hover:opacity-100 group-hover:scale-105 transition-all duration-500">
                                @else
                                    <img src="{{ asset('welcome.jpg') }}" alt="{{ $book->title }}" class="w-full h-full object-cover opacity-90 group-hover:opacity-100 group-hover:scale-105 transition-all duration-500">
                                @endif
                                
                                <!-- Free/Paid Badge Overlay on Cover -->
                                @if($book->is_free && $book->book_pdf_url)
                                <div class="absolute inset-0 bg-gradient-to-t from-green-600/80 via-green-600/20 to-transparent flex flex-col items-center justify-end pb-4">
                                    <div class="bg-white rounded-full px-4 py-1.5 shadow-lg flex items-center gap-2">
                                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                        </svg>
                                        <span class="text-green-600 font-bold text-sm">FREE PDF</span>
                                    </div>
                                </div>
                                @else
                                @endif
                                
                                @if($book->is_featured)
                                <div class="absolute top-3 right-3 bg-gradient-to-r from-yellow-500 to-orange-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow-md">FEATURED</div>
                                @endif
                                
                                <!-- Gradient overlay on hover -->
                                <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            </div>
                        </a>
                        <div class="text-center mt-4">
                            <h3 class="text-base font-bold text-gray-900 truncate w-40 mx-auto group-hover:text-indigo-600 transition-colors duration-300">{{ $book->title }}</h3>
                            @if($book->is_free && $book->book_pdf_url)
                            <p class="text-lg font-extrabold text-green-600 mt-1">FREE</p>
                            @else
                            <p class="text-lg font-extrabold text-indigo-600 mt-1">₵{{ number_format($book->price, 2) }}</p>
                            @endif
                            @if(!($book->is_free && $book->book_pdf_url))
                            @auth
                            <form action="{{ route('cart.add') }}" method="POST" class="mt-2">
                                @csrf
                                <input type="hidden" name="product_name" value="{{ $book->title }}">
                                <input type="hidden" name="product_price" value="{{ $book->price }}">
                                <input type="hidden" name="quantity" value="1">
                                <input type="hidden" name="book_id" value="{{ $book->id }}">
                                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-lg hover:bg-indigo-700 hover:shadow-lg transition-all duration-300 transform hover:scale-105">
                                    Add
                                </button>
                            </form>
                            @else
                            <a href="{{ route('login') }}" class="inline-block mt-2 px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-lg hover:bg-indigo-700 hover:shadow-lg transition-all duration-300 transform hover:scale-105">
                                Add
                            </a>
                            @endauth
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
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
        <section id="about" class="py-20 bg-gray-50">
            <div class="max-w-7xl mx-auto px-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                    <div>
                        <h2 class="text-4xl font-bold text-gray-900 mb-6">About This Store</h2>
                        <p class="text-xl text-gray-600 leading-relaxed">
                            This store was created by Nathaniel Gyarteng to help students and travelers prepare confidently for their
                             visa interviews. Many applicants feel nervous because they don't know what 
                             visa officers expect. These resources are designed to simplify the process, 
                             help you structure your answers clearly, and avoid common mistakes that lead to 
                             visa denials. Whether you are applying for a student visa or planning to travel 
                             abroad, these guides will help you walk into your interview prepared and confident.
                        </p>
                    </div>
                    <div class="relative">
                        <img src="{{ asset('welcome.jpg') }}" alt="About Bookshop" class="rounded-2xl shadow-2xl w-full max-w-md mx-auto">
                    </div>
                </div>
            </div>
        </section>

        <!-- Contact Section - Horizontal scroll on mobile -->
        <section id="contact" class="py-20 bg-white overflow-hidden">
            <div class="max-w-7xl mx-auto px-6">
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-bold text-gray-900 mb-4">Get In Touch</h2>
                    <p class="text-xl text-gray-600">Have questions? We'd love to hear from you!</p>
                </div>
                <div class="max-w-2xl mx-auto">
                    <!-- Horizontal scroll on mobile, grid on desktop -->
                    <div class="flex overflow-x-auto gap-6 pb-4 md:grid md:grid-cols-3 md:gap-8 md:overflow-x-visible md:pb-0 scrollbar-hide -mx-6 px-6 md:mx-0 md:px-0">
                        <div class="flex-shrink-0 w-40 text-center">
                            <div class="w-14 h-14 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <h3 class="font-semibold text-gray-900 mb-2">Email</h3>
                            <p class="text-gray-600">info@bookshop.com</p>
                        </div>
                        <div class="flex-shrink-0 w-40 text-center">
                            <div class="w-14 h-14 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-6 h-6 text-indigo-600" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M20.2 15.7l-1.7-1.7c-.4-.4-1-.6-1.5-.3-1.7.8-3.5-1-4.2-2.7-.4-1 .1-2.2.9-3l1.5-1.5C13.9 3.5 12.6 3 11.2 3c-2.8 0-5.3 2.1-5.7 5-.3 1.9.4 3.8 1.9 5.1 1.5 1.3 3.4 1.6 5 1.7 1.5.1 2.7-.3 3.7-1.2l1.4 1.4c.3.3.8.4 1.2.2l2.1-1c.5-.2.7-.8.5-1.3l-.8-2.2c-.3-.7-.9-1.3-1.6-1.6l-2.2-.8c-.5-.2-1.1 0-1.3.5l-1 2z"/>
                                </svg>
                            </div>
                            <h3 class="font-semibold text-gray-900 mb-2">Phone</h3>
                            <p class="text-gray-600">+1 (304) 517-4553</p>
                        </div>
                        <div class="flex-shrink-0 w-40 text-center">
                            <div class="w-14 h-14 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <h3 class="font-semibold text-gray-900 mb-2">Location</h3>
                            <p class="text-gray-600">123 Book Street</p>
                        </div>
                    </div>
                    
                    <!-- Social Media Links -->
                    <div class="flex flex-wrap justify-center gap-4 md:gap-6 mt-12">
                        <!-- TikTok -->
                        <a href="https://www.tiktok.com/@mrnate_official?_r=1&_t=ZT-94eYuEW9M7B" target="_blank" class="w-10 h-10 md:w-12 md:h-12 bg-gray-100 rounded-full flex items-center justify-center hover:bg-indigo-100 transition-colors">
                            <svg class="w-5 h-5 text-gray-700 hover:text-indigo-600" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-5.2 1.74 2.89 2.89 0 012.31-4.64 2.93 2.93 0 01.88.13V9.4a6.84 6.84 0 00-1-.05A6.33 6.33 0 005 20.1a6.34 6.34 0 0010.86-4.43v-7a8.16 8.16 0 004.77 1.52v-3.4a4.85 4.85 0 01-1-.1z"/>
                            </svg>
                        </a>
                        <!-- Instagram -->
                        <a href="https://www.instagram.com/nathanielgyarteng?igsh=MW1wMHNjcmM4ZW4xdQ%3D%3D&utm_source=qr" target="_blank" class="w-10 h-10 md:w-12 md:h-12 bg-gray-100 rounded-full flex items-center justify-center hover:bg-indigo-100 transition-colors">
                            <svg class="w-5 h-5 text-gray-700 hover:text-indigo-600" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/>
                            </svg>
                        </a>
                        <!-- YouTube -->
                        <a href="https://youtube.com/@nathanielgyarteng?si=6MDqGBKPLO9HnoCr" target="_blank" class="w-10 h-10 md:w-12 md:h-12 bg-gray-100 rounded-full flex items-center justify-center hover:bg-indigo-100 transition-colors">
                            <svg class="w-5 h-5 text-gray-700 hover:text-indigo-600" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                            </svg>
                        </a>
                        <!-- X (Twitter) -->
                        <a href="https://x.com/mrnate_official?s=21" target="_blank" class="w-10 h-10 md:w-12 md:h-12 bg-gray-100 rounded-full flex items-center justify-center hover:bg-indigo-100 transition-colors">
                            <svg class="w-5 h-5 text-gray-700 hover:text-indigo-600" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </section>

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

        <!-- Direct Install Button -->
        <div id="pwa-install-container" style="position:fixed;bottom:20px;right:20px;z-index:9999;">
            <button 
                id="pwa-install-btn"
                onclick="installApp()"
                style="background-color:#4f46e5;color:white;padding:12px 24px;border-radius:9999px;display:flex;align-items:center;gap:8px;font-weight:600;border:none;cursor:pointer;box-shadow:0 4px 6px rgba(0,0,0,0.3);"
            >
                <svg style="width:24px;height:24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                <span>Install App</span>
            </button>
        </div>

        <script>
        let deferredPrompt;
        
        // Listen for the install prompt
        window.addEventListener('beforeinstallprompt', function(e) {
            e.preventDefault();
            deferredPrompt = e;
            console.log('Install prompt available');
            // Show the button when prompt is available
            document.getElementById('pwa-install-container').style.display = 'flex';
        });
        
        // Check if app is already installed
        if (window.matchMedia('(display-mode: standalone)').matches) {
            document.getElementById('pwa-install-container').style.display = 'none';
        }
        
        function installApp() {
            if (deferredPrompt) {
                deferredPrompt.prompt();
                deferredPrompt.userChoice.then(function(choiceResult) {
                    deferredPrompt = null;
                });
            } else {
                // Try to open in new window as web app
                var iframe = document.createElement('iframe');
                iframe.style.display = 'none';
                iframe.src = window.location.href;
                document.body.appendChild(iframe);
                
                alert('INSTALL INSTRUCTIONS:\n\nOn Android Chrome:\n1. Tap the menu (3 dots)\n2. Tap "Add to Home Screen"\n\nOn iPhone Safari:\n1. Tap the Share button\n2. Tap "Add to Home Screen"\n\nOn Desktop Chrome:\nLook for install icon in address bar');
            }
        }
        </script>
    </body>
</html>

