@if((is_countable($books) ?? false) && count($books) > 0)
    <div class="bs-cards">
        @foreach ($books as $book)
            @php
                $coverUrl = $book->cover_image_url ?? ($book->cover_url ?? asset('welcome.jpg'));
                $badge = $book->badge ?? ($book->is_featured ? 'featured' : ($book->is_free ? 'new' : null));
                $badgeLabel = match($badge) {
                    'sale' => 'Sale',
                    'new' => 'New',
                    'featured' => 'Featured',
                    default => null,
                };
                $previewUrl = route('product.show', $book->id ?? 0);
            @endphp

            <article class="group relative bg-white border border-gray-100 rounded-2xl overflow-hidden shadow-sm hover:shadow-xl hover:border-indigo-200 transition-all duration-300 flex flex-col h-full">
                <div class="relative aspect-[3/4] bg-gradient-to-br from-gray-100 to-gray-200 overflow-hidden">
                    @if(!empty($badge))
                        <span class="absolute top-2.5 left-2.5 z-10 text-[10.5px] font-extrabold tracking-widest uppercase px-2.5 py-1 rounded-md text-white shadow-sm">
                            {{ $badgeLabel }}
                        </span>
                    @endif

                    @if(!empty($coverUrl))
                        <img src="{{ $coverUrl }}" alt="Cover of {{ $book->title }}" loading="lazy" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <svg class="w-14 h-14 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                    @endif
                </div>

                <div class="p-4 flex flex-col flex-1">
                    <h3 class="text-[15px] font-bold text-gray-900 leading-snug line-clamp-2 mb-1">{{ $book->title }}</h3>
                    <p class="text-xs text-gray-500 truncate mb-2">{{ $book->author }}</p>

                    @if(!empty($book->rating))
                        <div class="flex items-center gap-1.5 text-xs font-semibold text-amber-500 mb-3">
                            <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.957a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.37 2.448a1 1 0 00-.364 1.118l1.287 3.957c.3.921-.755 1.688-1.54 1.118l-3.37-2.448a1 1 0 00-1.176 0l-3.37 2.448c-.784.57-1.838-.197-1.539-1.118l1.287-3.957a1 1 0 00-.364-1.118L2.063 9.384c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.95-.69l1.285-3.957z"/></svg>
                            {{ number_format($book->rating, 1) }}
                            <span class="text-gray-400 font-medium">/ 5</span>
                        </div>
                    @endif

                    <div class="flex items-baseline gap-2 mb-4 mt-auto">
                        <span class="text-lg font-extrabold text-emerald-600">${{ number_format($book->price, 2) }}</span>
                        @if(!empty($book->original_price))
                            <span class="text-xs text-gray-400 line-through">${{ number_format($book->original_price, 2) }}</span>
                        @endif
                    </div>

                    <div class="flex gap-2">
                        <a href="{{ $previewUrl }}" class="flex-1 text-center text-xs font-semibold px-3 py-2.5 rounded-lg border border-gray-200 text-gray-900 hover:bg-gray-50 transition-colors">Preview</a>
                    </div>
                </div>
            </article>
        @endforeach
    </div>
@else
    <div class="text-center py-20">
        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18c-1.746 0-3.332.477-4.5 1.253"/>
        </svg>
        <h3 class="text-xl font-semibold text-gray-900 mb-2">No books found</h3>
        <p class="text-gray-600 mb-6">Try adjusting your search</p>
    </div>
@endif
