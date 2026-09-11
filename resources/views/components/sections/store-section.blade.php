<!-- Store Section with Scroll Animation -->
<section id="store" class="py-20 bg-white" data-animate>
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-16 opacity-0 translate-y-8 transition-all duration-700" data-animate-target>
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Available Books</h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">Handpicked selections from our latest collection</p>
        </div>
        
        <!-- 3 books per row -->
        <div class="grid grid-cols-3 gap-4">
            @forelse($books->take(5) as $index => $book)
              @php
                  $coverUrl = $book->cover_image_url ?? asset('welcome.jpg');
                  $badge = $book->is_featured ? 'featured' : ($book->is_free ? 'new' : null);
                  $badgeLabel = match($badge) {
                      'featured' => 'Featured',
                      'new' => 'Free',
                      default => null,
                  };
              @endphp
              <div>
                  <div class="group relative bg-white border border-gray-100 rounded-2xl overflow-hidden shadow-sm hover:shadow-xl hover:border-indigo-200 transition-all duration-300 flex flex-col h-full">
                      <div class="relative aspect-[3/4] bg-gradient-to-br from-gray-100 to-gray-200 overflow-hidden">
                          @if(!empty($badge))
                              <span class="absolute top-2.5 left-2.5 z-10 text-[10.5px] font-extrabold tracking-widest uppercase px-2.5 py-1 rounded-md text-white shadow-sm bg-amber-500">
                                  {{ $badgeLabel }}
                              </span>
                          @endif
                          <img src="{{ $coverUrl }}" alt="Cover of {{ $book->title }}" loading="lazy" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out">
                      </div>
                      <div class="p-2 flex flex-col flex-1">
                          <h3 class="text-xs font-bold text-gray-900 leading-snug line-clamp-2 mb-1 group-hover:text-indigo-600 transition-colors">{{ $book->title }}</h3>
                          <p class="text-[10px] text-gray-500 truncate mb-1">{{ $book->author }}</p>
                          <div class="flex items-baseline gap-1 mb-2 mt-auto">
                              @if($book->is_free && $book->book_pdf)
                                  <span class="text-sm font-extrabold text-emerald-600">FREE</span>
                              @else
                                  <span class="text-sm font-extrabold text-emerald-600">${{ number_format($book->price, 2) }}</span>
                              @endif
                          </div>
                          <div class="flex gap-1">
                              <a href="{{ route('product.show', $book->id) }}" class="flex-1 text-center text-[10px] font-semibold px-1.5 py-1 rounded border border-gray-200 text-gray-900 hover:bg-gray-50 transition-colors">Preview</a>
                          </div>
                      </div>
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