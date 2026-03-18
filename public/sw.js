const CACHE_NAME = 'bookshop-v2';

// Only cache STATIC assets (NOT auth pages)
const urlsToCache = [
    '/',
    '/manifest.json',
];

// Routes we should NEVER cache
const EXCLUDED_ROUTES = ['/login', '/register', '/logout', '/dashboard'];

// Install event
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then((cache) => cache.addAll(urlsToCache))
    );
});

// Fetch event
self.addEventListener('fetch', (event) => {
    const url = new URL(event.request.url);

    // ❌ Skip caching for auth & dynamic routes
    if (
        EXCLUDED_ROUTES.includes(url.pathname) ||
        event.request.method !== 'GET'
    ) {
        return; // Let browser handle normally
    }

    // ✅ Cache-first for static assets only
    event.respondWith(
        caches.match(event.request).then((response) => {
            return response || fetch(event.request);
        })
    );
});

// Activate event
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