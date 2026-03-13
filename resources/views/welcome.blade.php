<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Bookshop') }}</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="icon" href="/favicon.ico" sizes="any">
        <link rel="icon" href="/favicon.svg" type="image/svg+xml">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">
    </head>
    <body class="antialiased">
        <!-- Navigation -->
        <nav class="fixed top-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-sm shadow-sm">
            <div class="max-w-7xl mx-auto px-6 py-4">
                <div class="flex items-center justify-between">
                    <a href="{{ route('home') }}" class="text-2xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                        Nathaniel Gyarteng
                    </a>

                    <div class="hidden md:flex items-center space-x-8">
                        <a href="{{ route('home') }}#home" class="text-gray-600 hover:text-indigo-600 font-medium transition-colors">Home</a>
                        @if(\App\Models\Book::count() > 0)
                        <a href="{{ route('home') }}#store" class="text-gray-600 hover:text-indigo-600 font-medium transition-colors">Store</a>
                        @endif
                        <a href="{{ route('home') }}#about" class="text-gray-600 hover:text-indigo-600 font-medium transition-colors">About</a>
                        <a href="{{ route('home') }}#contact" class="text-gray-600 hover:text-indigo-600 font-medium transition-colors">Contact</a>
                    </div>

                    <div class="flex items-center gap-4">
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ route('cart') }}" class="relative p-2 text-gray-600 hover:text-indigo-600">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                    @php
                                        $cartCount = \App\Models\Cart::where('user_id', auth()->id())->sum('quantity') ?? 0;
                                    @endphp
                                    @if($cartCount > 0)
                                        <span class="absolute -top-1 -right-1 bg-indigo-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">{{ $cartCount }}</span>
                                    @endif
                                </a>
                                
                                <!-- User Menu with Dropdown -->
                                <div class="relative">
                                    <button id="user-menu-button" class="flex items-center gap-2 p-1.5 rounded-lg hover:bg-gray-100 transition-colors">
                                        <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                                            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                                        </div>
                                    </button>
                                    
                                    <!-- Dropdown Menu -->
                                    <div id="user-dropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-50">
                                        <div class="px-4 py-2 border-b border-gray-100">
                                            <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                                            <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                                        </div>
                                        <a href="{{ route('profile') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                            Settings
                                        </a>
                                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                                            @csrf
                                            <button type="submit" class="flex items-center gap-2 w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                                </svg>
                                                Logout
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                
                                <script>
                                    document.getElementById('user-menu-button')?.addEventListener('click', function() {
                                        document.getElementById('user-dropdown').classList.toggle('hidden');
                                    });

                                    // Close dropdown when clicking outside
                                    document.addEventListener('click', function(event) {
                                        var dropdown = document.getElementById('user-dropdown');
                                        var button = document.getElementById('user-menu-button');
                                        if (dropdown && button && !dropdown.classList.contains('hidden') && !button.contains(event.target) && !dropdown.contains(event.target)) {
                                            dropdown.classList.add('hidden');
                                        }
                                    });
                                </script>
                            @else
                                <a href="{{ route('login') }}" class="px-4 py-2 text-gray-600 font-medium hover:text-indigo-600 transition-colors">
                                    Sign In
                                </a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="px-5 py-2.5 text-white font-medium rounded-lg bg-gradient-to-r from-indigo-600 to-purple-600 hover:opacity-90 transition-opacity">
                                        Register
                                    </a>
                                @endif
                            @endauth
                        @endif
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section with S-Wave -->
        <section id="home" class="relative pt-32 pb-20 bg-gradient-to-br from-blue-600 via-indigo-600 to-purple-700">
            <!-- S-Wave Shape -->
            <div class="absolute bottom-0 left-0 w-full h-24 md:h-32">
                <svg class="absolute bottom-0 w-full h-full" viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M0 60C120 30 240 0 360 0C480 0 540 30 600 45C660 60 720 75 840 75C960 75 1080 45 1200 30C1320 15 1380 15 1440 30V120H0V60Z" fill="white"/>
                </svg>
            </div>
            <div class="max-w-7xl mx-auto px-6 relative z-10">
                <div class="text-center">
                    <h1 class="text-5xl md:text-6xl font-bold text-white mb-6">
                        Discover Your Next
                        <span class="bg-gradient-to-r from-yellow-400 to-orange-400 bg-clip-text text-transparent">Great Read</span>
                    </h1>
                    <p class="text-xl md:text-2xl text-white/90 mb-10 max-w-2xl mx-auto">
                        Explore thousands of books across all genres. From bestsellers to hidden gems, find your perfect read today.
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

        <!-- Featured Books
        <!-- Featured Books - Staggered Shelf Layout -->
        @if($books && $books->count() > 0)
        <section id="store" class="py-20 bg-white overflow-hidden">
            <div class="max-w-7xl mx-auto px-6">
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-bold text-gray-900 mb-4">Featured Books</h2>
                    <p class="text-xl text-gray-600 max-w-2xl mx-auto">Handpicked selections from our latest collection</p>
                </div>
                
                <!-- Staggered Shelf Layout -->
                <div class="flex flex-wrap justify-center items-end gap-4">
                    @foreach($books as $index => $book)
                    <div class="relative" style="margin-top: {{ $index * 30 }}px; margin-left: {{ $index * 10 }}px;">
                        <a href="{{ route('product.show', $book->id) }}" class="block transform hover:-translate-y-2 transition-all duration-300">
                            <div class="w-48 h-72 rounded-lg shadow-lg overflow-hidden relative z-10 bg-white">
                                @if($book->cover_image)
                                    <img src="{{ Storage::url($book->cover_image) }}" alt="{{ $book->title }}" class="w-full h-full object-cover">
                                @else
                                    <img src="{{ asset('welcome.jpg') }}" alt="{{ $book->title }}" class="w-full h-full object-cover">
                                @endif
                                @if($book->is_featured)
                                <div class="absolute top-2 right-2 bg-yellow-500 text-white text-xs font-bold px-2 py-1 rounded">FEATURED</div>
                                @endif
                                @if($book->pdf_file)
                                <div class="absolute top-2 left-2 bg-green-600 text-white text-xs font-bold px-2 py-1 rounded">PDF</div>
                                @endif
                            </div>
                            <!-- Book spine effect -->
                            <div class="absolute left-0 top-0 w-2 h-full bg-gray-300 -z-10 rounded-l-lg"></div>
                        </a>
                        <div class="text-center mt-2">
                            <p class="text-sm font-medium text-gray-900 truncate w-48">{{ $book->title }}</p>
                            <p class="text-lg font-bold text-indigo-600">₵{{ number_format($book->price, 2) }}</p>
                            @auth
                            <form action="{{ route('cart.add') }}" method="POST" class="mt-1">
                                @csrf
                                <input type="hidden" name="product_name" value="{{ $book->title }}">
                                <input type="hidden" name="product_price" value="{{ $book->price }}">
                                <input type="hidden" name="quantity" value="1">
                                <input type="hidden" name="book_id" value="{{ $book->id }}">
                                <button type="submit" class="text-xs text-indigo-600 font-medium hover:underline">Add to Cart</button>
                            </form>
                            @else
                            <a href="{{ route('login') }}" class="text-xs text-indigo-600 font-medium hover:underline">Add to Cart</a>
                            @endauth
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>
        @endif

        <!-- About Section -->
        <section id="about" class="py-20 bg-gray-50">
            <div class="max-w-7xl mx-auto px-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                    <div>
                        <h2 class="text-4xl font-bold text-gray-900 mb-6">About Our Bookshop</h2>
                        <p class="text-xl text-gray-600 leading-relaxed">
                            We are dedicated to bringing the joy of reading to everyone. Our carefully curated collection includes books for all ages and interests. Whether you're looking for the latest bestseller or a timeless classic, you'll find it here.
                        </p>
                        <div class="mt-8 grid grid-cols-2 gap-6">
                            <div class="bg-white p-4 rounded-xl shadow-sm">
                                <div class="text-3xl font-bold text-indigo-600 counter" data-target="500">0</div>
                                <div class="text-gray-600">Books Available</div>
                            </div>
                            <div class="bg-white p-4 rounded-xl shadow-sm">
                                <div class="text-3xl font-bold text-indigo-600 counter" data-target="1000">0</div>
                                <div class="text-gray-600">Happy Customers</div>
                            </div>
                        </div>
                    </div>
                    <div class="relative">
                        <img src="{{ asset('welcome.jpg') }}" alt="About Bookshop" class="rounded-2xl shadow-2xl">
                    </div>
                </div>
            </div>
        </section>

        <!-- Contact Section -->
        <section id="contact" class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-6">
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-bold text-gray-900 mb-4">Get In Touch</h2>
                    <p class="text-xl text-gray-600">Have questions? We'd love to hear from you!</p>
                </div>
                <div class="max-w-2xl mx-auto">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div class="text-center">
                            <div class="w-14 h-14 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <h3 class="font-semibold text-gray-900 mb-2">Email</h3>
                            <p class="text-gray-600">info@bookshop.com</p>
                        </div>
                        <div class="text-center">
                            <div class="w-14 h-14 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01.21l--.502 12.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                            </div>
                            <h3 class="font-semibold text-gray-900 mb-2">Phone</h3>
                            <p class="text-gray-600">+1 (555) 123-4567</p>
                        </div>
                        <div class="text-center">
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
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-gray-900 text-white py-12">
            <div class="max-w-7xl mx-auto px-6 text-center">
                <p class="text-gray-400">&copy; {{ date('Y') }} Bookshop. All rights reserved.</p>
            </div>
        </footer>

        <script>
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
    </body>
</html>

