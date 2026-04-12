 i<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ $book->title }} - {{ config('app.name', 'Bookshop') }}</title>

        <link rel="icon" href="/favicon.ico" sizes="any">
<link rel="apple-touch-icon" href="/apple-touch-icon.png">

        <!-- Using Tailwind CDN -->
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body class="antialiased bg-gray-50">
        <x-flash-message />
        <x-customer-navbar />

        <!-- Main Content -->
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
                        <div class="relative h-80 md:h-[500px] bg-gray-100 flex items-center justify-center p-6">
                            @if($book->cover_image)
                                <img src="{{ asset('public/books/' . $book->cover_image) }}" alt="{{ $book->title }}" class="max-h-full max-w-full object-contain rounded-lg shadow-lg">
                            @else
                                <img src="{{ asset('welcome.jpg') }}" alt="{{ $book->title }}" class="max-h-full max-w-full object-contain rounded-lg shadow-lg">
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
                                @if($book->is_free)
                                    <span class="text-4xl font-bold text-green-600">FREE</span>
                                @else
                                    <span class="text-4xl font-bold text-indigo-600">₵{{ number_format($book->price, 2) }}</span>
                                @endif
                            </div>

                            <!-- Description -->
                            @if($book->description)
                            <div class="mb-8">
                                <p id="bookDescription" class="text-gray-600 mb-2 leading-relaxed">{{ Str::limit($book->description, 100) }}</p>
                                @if(strlen($book->description) > 100)
                                <button id="toggleDescription" onclick="toggleDescription()" class="text-indigo-600 font-medium text-sm hover:text-indigo-700">Show more</button>
                                <script>
                                    let expanded = false;
                                    const fullDescription = {!! json_encode($book->description) !!};
                                    const truncated = {!! json_encode(Str::limit($book->description, 100)) !!};
                                    
                                    function toggleDescription() {
                                        const descEl = document.getElementById('bookDescription');
                                        const btnEl = document.getElementById('toggleDescription');
                                        
                                        if (expanded) {
                                            descEl.textContent = truncated;
                                            btnEl.textContent = 'Show more';
                                        } else {
                                            descEl.textContent = fullDescription;
                                            btnEl.textContent = 'Show less';
                                        }
                                        expanded = !expanded;
                                    }
                                </script>
                                @endif
                            </div>
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

                            <!-- Add to Cart -->
                            @if($book->is_free && $book->book_pdf_url)
                                <a href="{{ $book->book_pdf_url }}" target="_blank" class="flex-1 px-6 py-4 bg-gradient-to-r from-green-600 to-teal-600 text-white font-semibold rounded-xl hover:opacity-90 transition-opacity flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                    </svg>
                                    Download Free PDF
                                </a>
                            @else
                                @auth
                                    <form action="{{ route('cart.add') }}" method="POST" class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                                        @csrf
                                        <input type="hidden" name="product_name" value="{{ $book->title }}">
                                        <input type="hidden" name="product_price" value="{{ $book->price }}">
                                        <input type="hidden" name="book_id" value="{{ $book->id }}">
                                        <input type="hidden" name="quantity" value="1">
                                        
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
                            @endif

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
                                    <img src="{{ asset('books/' . $related->cover_image) }}" alt="{{ $related->title }}" class="w-full h-full object-cover">
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

       <x-customer-footer />

        <x-chat-widget />
    </body>
</html>
