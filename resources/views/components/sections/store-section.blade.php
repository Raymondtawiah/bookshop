<!-- Store Section with Scroll Animation -->
<section id="store" class="py-20 bg-white overflow-hidden" data-animate>
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-16 opacity-0 translate-y-8 transition-all duration-700" data-animate-target>
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Featured Books</h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">Handpicked selections from our latest collection</p>
        </div>
        
        <!-- Horizontal scroll on mobile, grid on desktop -->
        <div class="flex overflow-x-auto gap-6 pb-4 md:grid md:grid-cols-3 lg:grid-cols-4 md:overflow-x-visible md:gap-6 scrollbar-hide -mx-6 px-6 md:mx-0 md:px-0">
            @forelse($books as $index => $book)
            <div class="flex-shrink-0 w-40 md:w-auto opacity-0 translate-y-8 transition-all duration-700" data-animate-target style="transition-delay: {{ $index * 100 }}ms;">
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
                        <input type="hidden" name="book_id" value="{{ $book->id }}">
                        <input type="hidden" name="quantity" value="1">
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
            @empty
            <div class="col-span-full text-center py-12">
                <p class="text-gray-500 text-lg">No books available at the moment.</p>
            </div>
            @endforelse
        </div>
    </div>
</section>


