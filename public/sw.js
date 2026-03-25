const CACHE_NAME = 'bookshop-v4';

// Only cache static assets - NEVER cache HTML pages
const urlsToCache = [
    '/manifest.json',
    '/favicon.ico',
    '/apple-touch-icon.png',
];

// Install event
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then((cache) => cache.addAll(urlsToCache))
    );
});

// Fetch event - NEVER cache any HTML or auth routes
self.addEventListener('fetch', (event) => {
    const url = new URL(event.request.url);
    
    // Get the pathname
    const pathname = url.pathname;

    // ❌ NEVER cache these routes - always get fresh content
    if (
        pathname === '/' ||
        pathname === '/login' ||
        pathname === '/register' ||
        pathname === '/logout' ||
        pathname === '/dashboard' ||
        pathname.startsWith('/login/google') ||
        pathname === '/home' ||
        pathname.endsWith('.html') ||
        event.request.headers.get('accept')?.includes('text/html')
    ) {
        // Don't use cache for HTML pages - fetch from network
        event.respondWith(
            fetch(event.request).catch(() => {
                // If offline and it's a navigation, return basic response
                return new Response('Offline', { status: 503 });
            })
        );
        return;
    }

    // ✅ Cache-first for static assets only
    if (url.pathname.match(/\.(js|css|png|jpg|jpeg|svg|woff2?|gif|ico)$/)) {
        event.respondWith(
            caches.match(event.request).then((response) => {
                return response || fetch(event.request);
            })
        );
    } else {
        // For API calls, etc - always get fresh
        return;
    }
});

// Activate event - clean up old caches
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames.map((cacheName) => {
                    if (cacheName !== CACHE_NAME) {
                        return caches.delete(cacheName);
                    }
                })
            );
        })
    );
});
