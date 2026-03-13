<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ $book->title }} - {{ config('app.name', 'Bookshop') }}</title>

        <link rel="icon" href="/favicon.ico" sizes="any">
        <link rel="icon" href="/favicon.svg" type="image/svg+xml">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">

        <!-- Using Tailwind CDN -->
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body class="antialiased bg-gray-50">
        <!-- Navigation -->
        <nav class="fixed top-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-sm shadow-sm">
            <div class="max-w-7xl mx-auto px-6 py-4">
                <div class="flex items-center justify-between">
                    <!-- Logo -->
                    <a href="{{ route('home') }}" class="text-2xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                        Bookshop
                    </a>

                    <!-- Nav Links -->
                    <div class="hidden md:flex items-center space-x-8">
                        <a href="{{ route('home') }}#home" class="text-gray-600 hover:text-indigo-600 font-medium transition-colors">Home</a>
                        <a href="{{ route('home') }}#store" class="text-gray-600 hover:text-indigo-600 font-medium transition-colors">Store</a>
                        <a href="{{ route('home') }}#about" class="text-gray-600 hover:text-indigo-600 font-medium transition-colors">About</a>
                        <a href="{{ route('home') }}#contact" class="text-gray-600 hover:text-indigo-600 font-medium transition-colors">Contact</a>
                    </div>

                    <!-- Auth Buttons -->
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

        <!-- Product Details -->
        <div class="pt-28 pb-16">
            <div class="max-w-7xl mx-auto px-6">
                <!-- Breadcrumb -->
                <nav class="mb-8">
                    <ol class="flex items-center gap-2 text-sm text-gray-500">
                        <li><a href="{{ route('home') }}" class="hover:text-indigo-600">Home</a></li>
                        <li>/</li>
                        <li><a href="{{ route('home') }}#store" class="hover:text-indigo-600">Store</a></li>
                        <li>/</li>
                        <li class="text-gray-900 font-medium">{{ $book->title }}</li>
                    </ol>
                </nav>

                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    <div class="grid grid-cols-1 md:grid-cols-2">
                        <!-- Product Image -->
                        <div class="relative h-96 md:h-full bg-gray-100">
                            @if($book->cover_image)
                                <img src="{{ Storage::url($book->cover_image) }}" alt="{{ $book->title }}" class="w-full h-full object-cover">
                            @else
                                <img src="{{ asset('welcome.jpg') }}" alt="{{ $book->title }}" class="w-full h-full object-cover">
                            @endif
                            @if($book->pdf_file)
                                <div class="absolute top-4 left-4 bg-green-600 text-white text-sm font-bold px-3 py-1 rounded">
                                    PDF Available
                                </div>
                            @endif
                            @if($book->category)
                            <div class="absolute top-4 right-4 bg-indigo-600 text-white text-sm font-bold px-3 py-1 rounded">
                                {{ $book->category }}
                            </div>
                            @endif
                        </div>

                        <!-- Product Info -->
                        <div class="p-8 md:p-12">
                            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-2">{{ $book->title }}</h1>
                            <p class="text-lg text-gray-600 mb-4">by <span class="font-medium text-indigo-600">{{ $book->author }}</span></p>

                            <!-- Price -->
                            <div class="mb-6">
                                <span class="text-4xl font-bold text-indigo-600">₵{{ number_format($book->price, 2) }}</span>
                            </div>

                            <!-- Description -->
                            @if($book->description)
                            <p class="text-gray-600 mb-8 leading-relaxed">{{ $book->description }}</p>
                            @endif

                            <!-- Product Details -->
                            <div class="grid grid-cols-2 gap-4 mb-8 text-sm">
                                @if($book->isbn)
                                <div class="bg-gray-50 rounded-lg p-3">
                                    <span class="text-gray-500">ISBN</span>
                                    <p class="font-medium text-gray-900">{{ $book->isbn }}</p>
                                </div>
                                @endif
                                @if($book->pages)
                                <div class="bg-gray-50 rounded-lg p-3">
                                    <span class="text-gray-500">Pages</span>
                                    <p class="font-medium text-gray-900">{{ $book->pages }}</p>
                                </div>
                                @endif
                                @if($book->published_year)
                                <div class="bg-gray-50 rounded-lg p-3">
                                    <span class="text-gray-500">Published</span>
                                    <p class="font-medium text-gray-900">{{ $book->published_year }}</p>
                                </div>
                                @endif
                                @if($book->category)
                                <div class="bg-gray-50 rounded-lg p-3">
                                    <span class="text-gray-500">Category</span>
                                    <p class="font-medium text-gray-900">{{ $book->category }}</p>
                                </div>
                                @endif
                            </div>

                            <!-- PDF Info -->
                            @if($book->pdf_file)
                            <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
                                <div class="flex items-center gap-3">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <div>
                                        <p class="font-medium text-green-900">PDF Book Available</p>
                                        <p class="text-sm text-green-700">This book can be personalized with your name as a watermark on every page!</p>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- Add to Cart -->
                            @auth
                                <form action="{{ route('cart.add') }}" method="POST" class="flex items-center gap-4">
                                    @csrf
                                    <input type="hidden" name="product_name" value="{{ $book->title }}">
                                    <input type="hidden" name="product_price" value="{{ $book->price }}">
                                    <input type="hidden" name="quantity" value="1">
                                    <input type="hidden" name="book_id" value="{{ $book->id }}">
                                    <button type="submit" class="flex-1 px-6 py-4 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-xl hover:opacity-90 transition-opacity flex items-center justify-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                        Add to Cart
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('login') }}" class="block w-full text-center px-6 py-4 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-xl hover:opacity-90 transition-opacity">
                                    Sign In to Add to Cart
                                </a>
                            @endauth

                            <!-- Back to Store -->
                            <div class="mt-6 text-center">
                                <a href="{{ route('home') }}#store" class="text-gray-500 hover:text-indigo-600 text-sm font-medium">
                                    ← Back to Store
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Related Products -->
                @if($relatedProducts->count() > 0)
                <div class="mt-16">
                    <h2 class="text-2xl font-bold text-gray-900 mb-8">Related Products</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                        @foreach($relatedProducts as $related)
                        <a href="{{ route('product.show', $related->id) }}" class="bg-white rounded-2xl overflow-hidden shadow-lg hover:-translate-y-2 hover:shadow-xl transition-all duration-300">
                            <div class="h-56 bg-gray-100 relative overflow-hidden">
                                @if($related->cover_image)
                                    <img src="{{ Storage::url($related->cover_image) }}" alt="{{ $related->title }}" class="w-full h-full object-cover">
                                @else
                                    <img src="{{ asset('welcome.jpg') }}" alt="{{ $related->title }}" class="w-full h-full object-cover">
                                @endif
                            </div>
                            <div class="p-5">
                                <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ $related->title }}</h3>
                                <p class="text-sm text-gray-500 mb-2">{{ $related->category ?? 'Uncategorized' }}</p>
                                <span class="text-xl font-bold text-indigo-600">₵{{ number_format($related->price, 2) }}</span>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-gray-900 text-white py-12">
            <div class="max-w-7xl mx-auto px-6 text-center">
                <p class="text-gray-400">&copy; {{ date('Y') }} Bookshop. All rights reserved.</p>
            </div>
        </footer>
    </body>
</html>
