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
    <title>All Books - {{ config('app.name', 'Bookshop') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="apple-touch-icon" href="/favicon.ico">
    <script>
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
        });
        
        // Live search functionality
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('live-search');
            const booksGrid = document.getElementById('books-grid');
            const resultsCount = document.getElementById('results-count');
            const noResults = document.getElementById('no-results');
            const searchForm = document.getElementById('search-form');
            
            let searchTimeout;
            
            // Debounce function
            function debounce(func, delay) {
                return function(...args) {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => func.apply(this, args), delay);
                };
            }
            
            // Perform search
            function performSearch() {
                const query = searchInput.value.trim();
                
                // Show loading state
                booksGrid.innerHTML = '<div class="col-span-full text-center py-8"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600 mx-auto"></div></div>';
                resultsCount.classList.add('hidden');
                noResults.classList.add('hidden');
                
                // If empty query, show all books (or you could show nothing)
                if (query === '') {
                    fetchBooks('');
                    return;
                }
                
                fetchBooks(query);
            }
            
            // Fetch books via AJAX
            function fetchBooks(query) {
                const url = new URL(window.location.origin + "{{ route('books.index') }}");
                url.searchParams.set('q', query);
                
                // Add AJAX header so controller knows to return partial
                fetch(url.toString(), {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.html) {
                        booksGrid.innerHTML = data.html;
                        
                        // Update results count if available
                        if (data.count !== undefined) {
                            resultsCount.querySelector('span').textContent = data.count;
                            resultsCount.classList.remove('hidden');
                        } else {
                            resultsCount.classList.add('hidden');
                        }
                        
                        // Show no results message if needed
                        if (data.count === 0) {
                            noResults.classList.remove('hidden');
                        } else {
                            noResults.classList.add('hidden');
                        }
                    }
                })
                .catch(error => {
                    console.error('Error fetching books:', error);
                    booksGrid.innerHTML = '<div class="col-span-full text-center py-8 text-red-500">Error loading books. Please try again.</div>';
                });
            }
            
            // Handle form submission (fallback for non-JS)
            searchForm.addEventListener('submit', function(e) {
                // Don't prevent default - let it submit normally for non-JS fallback
                // The debounced search will still run on input
            });
            
            // Live search with debounce
            searchInput.addEventListener('input', debounce(function() {
                performSearch();
            }, 300));
            
            // Also search on Enter key
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    performSearch();
                }
            });
            
            // Initial load - show all books or empty state based on initial query
            const initialQuery = "{{ $query ?? '' }}";
            if (initialQuery) {
                performSearch();
            }
        });
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
        .search-input:focus {
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.2);
        }
    </style>
</head>
<body class="antialiased overflow-x-hidden m-0 p-0 box-border w-full min-w-0 pt-16">
    <x-flash-message />
    <x-customer-navbar />

    <div class="w-full overflow-x-hidden min-w-0 mx-0 px-0">
        
        <!-- Page Header -->
        <section class="w-full">
            <img src="{{ asset('hero.jpg') }}" alt="Hero" class="w-full h-auto object-cover">
        </section>

        <section class="w-full">
            <img src="{{ asset('bookshelp.jpg') }}" alt="Books Help" class="w-full h-[520px] object-cover">
        </section>

        <!-- Search Section -->
        <section class="py-8 bg-gray-50 max-w-full">
            <div class="max-w-7xl mx-auto px-6">
                <form action="{{ route('books.index') }}" method="GET" id="search-form" class="flex gap-2 max-w-xl mx-auto">
                    <div class="relative flex-1">
                        <input
                            type="text"
                            id="live-search"
                            name="q"
                            value="{{ $query ?? '' }}"
                            placeholder="Search books by title, author..."
                            class="search-input w-full px-5 py-3 pl-12 rounded-3xl border border-gray-200 bg-white text-gray-900 placeholder-gray-500 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all hover:shadow-lg"
                        >
                        <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <button type="submit" class="px-6 py-3 bg-indigo-600 text-white font-medium rounded-3xl hover:bg-indigo-700 transition-colors">
                        Search
                    </button>
                </form>
            </div>
        </section>

         <!-- Results Info -->
        <div class="max-w-7xl mx-auto px-6 mb-4">
            <div id="results-count" class="flex items-center justify-between text-gray-600 hidden">
                <p>Showing <span>0</span> books</p>
            </div>
            <div id="no-results" class="text-center py-8 hidden">
                <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-gray-500 mt-2">No books found. Try a different search.</p>
            </div>
        </div>

        <!-- Books Grid -->
        <section id="store" class="py-12 bg-white">
            <div class="max-w-7xl mx-auto px-6">
                <div id="books-grid">
                    @include('products.partials.book-grid')
                </div>
                
                <!-- Pagination (hidden for live search, will show if JS disabled) -->
                <div class="mt-10" id="pagination-container">
                    {{ $books->links() }}
                </div>
            </div>
        </section>

          <section class="w-full">
            <img src="{{ asset('testimonials.jpg') }}" alt="Testimonials" class="w-full h-[520px] object-cover">
        </section>

        <x-customer-footer />

        <x-install-pwa />

        @include('components.free-book-modal')
        @include('chat-widget')
        @include('components.cookie-consent')
    </div>
</body>
</html>
