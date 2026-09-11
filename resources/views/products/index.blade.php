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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
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

        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('live-search');
            const booksGrid = document.getElementById('books-grid');
            const resultsCount = document.getElementById('results-count');
            const noResults = document.getElementById('no-results');
            const searchForm = document.getElementById('search-form');

            let searchTimeout;

            function debounce(func, delay) {
                return function(...args) {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => func.apply(this, args), delay);
                };
            }

            function performSearch() {
                const query = searchInput.value.trim();

                booksGrid.innerHTML = '<div class="col-span-full text-center py-8"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600 mx-auto"></div></div>';
                resultsCount.classList.add('hidden');
                noResults.classList.add('hidden');

                if (query === '') {
                    fetchBooks('');
                    return;
                }

                fetchBooks(query);
            }

            function fetchBooks(query) {
                const url = new URL(window.location.origin + "{{ route('books.index') }}");
                url.searchParams.set('q', query);

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

                        if (data.count !== undefined) {
                            resultsCount.querySelector('span').textContent = data.count;
                            resultsCount.classList.remove('hidden');
                        } else {
                            resultsCount.classList.add('hidden');
                        }

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

            searchForm.addEventListener('submit', function(e) {
            });

            searchInput.addEventListener('input', debounce(function() {
                performSearch();
            }, 300));

            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    performSearch();
                }
            });

            const initialQuery = "{{ $query ?? '' }}";
            if (initialQuery) {
                performSearch();
            }
        });
    </script>
    <style>
        *{ box-sizing:border-box; }
        body{ margin:0; font-family:'Inter', sans-serif; background:#FFFFFF; color:#0F172A; }
        .bs-wrap{ max-width:1240px; margin:0 auto; padding:0 24px; }

        .bs-hero{
            position:relative;
            min-height:420px;
            display:flex;
            align-items:center;
            background: linear-gradient(120deg, #1E293B 0%, #0F172A 60%), linear-gradient(180deg, rgba(15,23,42,0.4), rgba(15,23,42,0.65));
            background-blend-mode: multiply;
        }
        .bs-hero::before{
            content:"";
            position:absolute; inset:0;
            background: radial-gradient(60% 90% at 80% 20%, rgba(79,70,229,0.35), transparent 60%);
        }
        .bs-hero-inner{ position:relative; max-width:620px; padding:60px 24px; }

        .bs-eyebrow{
            display:inline-flex; align-items:center; gap:8px;
            font-size:12.5px; font-weight:700; letter-spacing:0.1em; text-transform:uppercase;
            color:#F59E0B; margin-bottom:16px;
        }
        .bs-hero h1{ font-size:clamp(30px,4.2vw,44px); font-weight:800; line-height:1.15; color:#fff; margin:0 0 14px; }
        .bs-hero p{ font-size:16px; color:#E2E8F0; max-width:460px; margin:0 0 28px; }

        .bs-search{
            display:flex; align-items:center; gap:10px;
            background:#fff; border-radius:12px; padding:6px 6px 6px 18px; max-width:480px;
            box-shadow:0 12px 30px -12px rgba(15,23,42,0.4);
        }
        .bs-search svg{ width:18px; height:18px; color:#94A3B8; flex-shrink:0; }
        .bs-search input{ flex:1; border:none; outline:none; font-family:inherit; font-size:14.5px; color:#0F172A; padding:11px 0; }
        .bs-search input::placeholder{ color:#94A3B8; }
        .bs-search button{ background:#4F46E5; color:#fff; border:none; border-radius:8px; padding:11px 20px; font-weight:600; font-size:14px; cursor:pointer; }
        .bs-search button:hover{ background:#4338CA; }

        .bs-banner{
            position:relative; height:220px; display:flex; align-items:center; justify-content:center; text-align:center;
            background: linear-gradient(135deg, #312E81, #1E293B);
            margin:56px 0; border-radius:16px; overflow:hidden;
        }
        .bs-banner::before{
            content:""; position:absolute; inset:0;
            background: radial-gradient(50% 100% at 20% 100%, rgba(245,158,11,0.25), transparent 60%);
        }
        .bs-banner > div{ position:relative; }
        .bs-banner h2{ color:#fff; font-size:24px; font-weight:700; margin:0 0 8px; }
        .bs-banner p{ color:#E2E8F0; font-size:14.5px; margin:0 0 18px; }
        .bs-banner-btn{
            display:inline-flex; align-items:center; gap:8px; background:#F59E0B; color:#0F172A;
            font-weight:700; font-size:14px; padding:11px 22px; border-radius:8px; text-decoration:none;
        }

        .bs-section-head{ margin-bottom:22px; }
        .bs-section-head h2{ font-size:24px; font-weight:800; margin:0 0 6px; }
        .bs-section-head p{ font-size:14.5px; color:#94A3B8; margin:0; }

        .bs-chips{ display:flex; gap:10px; flex-wrap:wrap; margin-bottom:32px; }
        .bs-chip{
            font-size:13.5px; font-weight:600; color:#0F172A; background:#F8FAFC; border:1px solid #E2E8F0;
            padding:9px 18px; border-radius:999px; cursor:pointer; transition:all 0.15s ease;
        }
        .bs-chip:hover{ border-color:#4F46E5; }
        .bs-chip.active{ background:#4F46E5; border-color:#4F46E5; color:#fff; }

        .bs-grid{ display:grid; gap:24px; margin-bottom:64px; }
        @media (min-width:640px){ .bs-grid{ grid-template-columns:repeat(2,1fr); } }
        @media (min-width:960px){ .bs-grid{ grid-template-columns:repeat(3,1fr); } }
        @media (min-width:1200px){ .bs-grid{ grid-template-columns:repeat(4,1fr); } }

        .bs-grid-horizontal{
            display:grid;
            grid-auto-columns: 280px;
            grid-auto-flow: column;
            grid-template-rows: repeat(auto-fill, minmax(0, 1fr));
            gap: 24px;
            overflow-x: auto;
            padding-bottom: 12px;
        }
        @media (max-width: 1023px) {
            .bs-grid-horizontal {
                grid-template-columns: repeat(2, 1fr);
                grid-auto-flow: initial;
                overflow-x: visible;
                padding-bottom: 0;
            }
        }

        .bg-card{
            background:#fff; border:1px solid #E2E8F0; border-radius:14px; overflow:hidden;
            display:flex; flex-direction:column; transition: transform 0.18s ease, box-shadow 0.18s ease, border-color 0.18s ease;
        }
        .bg-card:hover{ transform:translateY(-4px); box-shadow:0 18px 34px -16px rgba(15,23,42,0.18); border-color:#C7D2FE; }

        .bg-cover{
            position:relative; aspect-ratio:3/4; background:linear-gradient(150deg, #EEF2FF, #F8FAFC);
            display:flex; align-items:center; justify-content:center;
        }
        .bg-cover svg{ width:44px; height:44px; color:#C7D2FE; }

        .bg-badge{ position:absolute; top:10px; left:10px; font-size:10.5px; font-weight:800; letter-spacing:0.04em; text-transform:uppercase; padding:4px 9px; border-radius:6px; color:#fff; }
        .bg-badge.sale{ background:#EF4444; }
        .bg-badge.new{ background:#4F46E5; }
        .bg-badge.bestseller{ background:#F59E0B; color:#0F172A; }

        .bg-body{ padding:16px 16px 18px; display:flex; flex-direction:column; flex:1; }
        .bg-title{ font-size:15px; font-weight:700; margin:0 0 4px; line-height:1.4; }
        .bg-author{ font-size:13px; color:#94A3B8; margin:0 0 10px; }
        .bg-rating{ display:flex; align-items:center; gap:5px; font-size:12.5px; color:#F59E0B; font-weight:600; margin-bottom:12px; }
        .bg-rating span{ color:#94A3B8; font-weight:500; }

        .bg-price-row{ display:flex; align-items:baseline; gap:8px; margin-bottom:14px; margin-top:auto; }
        .bg-price{ font-size:17px; font-weight:800; color:#10B981; }
        .bg-price-original{ font-size:13px; color:#94A3B8; text-decoration:line-through; }

        .bg-actions{ display:flex; gap:8px; }
        .bg-btn{ flex:1; text-align:center; font-size:13px; font-weight:600; padding:9px 10px; border-radius:8px; cursor:pointer; border:1px solid transparent; text-decoration:none; }
        .bg-btn-outline{ background:#fff; color:#0F172A; border-color:#E2E8F0; }
        .bg-btn-outline:hover{ background:#F8FAFC; }
        .bg-btn-primary{ background:#4F46E5; color:#fff; border:none; }
        .bg-btn-primary:hover{ background:#4338CA; }

        .bs-testimonials{ background:#F8FAFC; padding:64px 0; }
        .bs-testimonials-media{
            border-radius:16px; overflow:hidden; margin-bottom:40px; height:220px;
            background: linear-gradient(135deg, #0F172A, #312E81);
        }
        .bs-t-grid{ display:grid; grid-template-columns:1fr; gap:20px; }
        @media (min-width:860px){ .bs-t-grid{ grid-template-columns:repeat(3,1fr); } }
        .bs-t-card{ background:#fff; border:1px solid #E2E8F0; border-radius:14px; padding:24px; }
        .bs-t-card .stars{ color:#F59E0B; font-size:14px; margin-bottom:12px; }
        .bs-t-card p{ font-size:14px; color:#334155; line-height:1.6; margin:0 0 16px; }
        .bs-t-card .who{ font-size:13px; font-weight:700; }
    </style>
</head>
<body>
    <x-flash-message />
    <x-customer-navbar />

    <section class="bs-hero">
        <div class="bs-wrap">
            <div class="bs-hero-inner">
                <span class="bs-eyebrow">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l2.9 6.26L21.5 9l-4.75 4.4L18 20l-6-3.5L6 20l1.25-6.6L2.5 9l6.6-.74z"/></svg>
                    New Arrivals Every Week
                </span>
                <h1>Find Your Next Great Read</h1>
                <p>Browse our curated collection of bestsellers, classics, and hidden gems — handpicked for every kind of reader.</p>

                <form class="bs-search" action="{{ route('books.index') }}" method="GET" id="search-form" role="search">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/></svg>
                    <input type="text" id="live-search" name="q" value="{{ $query ?? '' }}" placeholder="Search by title, author, or ISBN...">
                    <button type="submit">Search</button>
                </form>
            </div>
        </div>
    </section>

    <div class="bs-wrap">
        <section class="bs-banner">
            <div>
                <h2>This Month's Featured Shelf</h2>
                <p>Staff picks, ready to ship today.</p>
                <a href="#store" class="bs-banner-btn">
                    Browse Featured Books
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                </a>
            </div>
        </section>

        <div class="bs-section-head">
            <h2>All Books</h2>
            <p>{{ $books->total() ?? 0 }} titles available</p>
        </div>

        <div class="bs-chips" role="group" aria-label="Filter by category">
            <button type="button" class="bs-chip active">All</button>
            <button type="button" class="bs-chip">Fiction</button>
            <button type="button" class="bs-chip">Non-Fiction</button>
            <button type="button" class="bs-chip">Bestsellers</button>
            <button type="button" class="bs-chip">New Releases</button>
            <button type="button" class="bs-chip">On Sale</button>
        </div>

        <div id="results-count" class="flex items-center justify-between text-gray-600 hidden">
            <p>Showing <span>{{ $books->total() ?? 0 }}</span> books</p>
        </div>
        <div id="no-results" class="text-center py-8 hidden">
            <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-gray-500 mt-2">No books found. Try a different search.</p>
        </div>

        <div id="books-grid">
            @include('products.partials.book-grid')

            <div class="mt-10" id="pagination-container">
                {{ $books->links() }}
            </div>
        </div>

        <style>
            @media (max-width: 1023px) {
                #books-grid .bs-cards {
                    display: grid;
                    grid-auto-columns: 140px;
                    grid-auto-flow: column;
                    gap: 24px;
                    overflow-x: auto;
                    padding-bottom: 12px;
                }
                #books-grid .bs-cards > article {
                    width: 140px;
                }
            }
            @media (min-width: 1024px) {
                #books-grid .bs-cards {
                    display: grid;
                    grid-template-columns: repeat(3, 1fr);
                    gap: 24px;
                }
            }
        </style>
    </div>

    <section class="bs-testimonials">
        <div class="bs-wrap">
            <div class="bs-testimonials-media"></div>
            <div class="bs-section-head" style="text-align:center;">
                <h2>What Readers Are Saying</h2>
            </div>
            <div class="bs-t-grid">
                <div class="bs-t-card">
                    <div class="stars">★★★★★</div>
                    <p>The checkout was fast and my order arrived earlier than expected. Great selection too.</p>
                    <div class="who">Amara K.</div>
                </div>
                <div class="bs-t-card">
                    <div class="stars">★★★★★</div>
                    <p>I found books here I couldn't find anywhere else locally. Will definitely order again.</p>
                    <div class="who">Kwesi B.</div>
                </div>
                <div class="bs-t-card">
                    <div class="stars">★★★★☆</div>
                    <p>Clean site, easy to browse by category, and the prices were fair.</p>
                    <div class="who">Linda A.</div>
                </div>
            </div>
        </div>
    </section>

    <x-customer-footer />

    <x-install-pwa />

    @include('components.free-book-modal')
    @include('chat-widget')
    @include('components.cookie-consent')
</body>
</html>
