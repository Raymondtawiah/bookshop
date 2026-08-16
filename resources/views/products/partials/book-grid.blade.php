@if($books->count() > 0)
<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 md:gap-6">
    @foreach($books as $book)
    @if($book->is_free && $book->book_pdf)
    <button onclick="openFreeBookModal({{ $book->id }}, '{{ $book->title }}')" class="group block text-left">
    @else
    <a href="{{ route('product.show', $book->id) }}" class="group block text-left">
    @endif
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-xl hover:border-indigo-200 transition-all duration-300">
            <div class="aspect-[3/4] bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center relative overflow-hidden">
                @if($book->cover_image)
                    <img src="{{ $book->cover_image_url }}" alt="{{ $book->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                @else
                    <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                @endif
                <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
                    <span class="bg-white/90 backdrop-blur-sm text-gray-700 text-xs font-medium px-2 py-1 rounded-full shadow-sm">View Details</span>
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
    @if($book->is_free && $book->book_pdf)
    </button>
    @else
    </a>
    @endif
    @endforeach
</div>
@else
<div class="text-center py-16">
    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18c-1.746 0-3.332.477-4.5 1.253"/>
    </svg>
    <h3 class="text-xl font-semibold text-gray-900 mb-2">No books found</h3>
    <p class="text-gray-600 mb-6">Try adjusting your search</p>
</div>
@endif
