<nav id="customer-floating-nav" class="fixed top-16 left-0 right-0 z-[9998] bg-white/90 backdrop-blur-md border-b border-gray-200 shadow-sm font-sans md:hidden transition-transform duration-300 -translate-y-full">
    <div class="flex items-center justify-center gap-2 py-3">
        <a href="{{ route('home') }}" class="flex flex-col items-center gap-1 px-5 py-2 rounded-full text-gray-700 hover:text-indigo-600 hover:bg-indigo-50 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            <span class="text-[11px] font-medium">Home</span>
        </a>
        <a href="{{ route('webinars.index') }}" class="flex flex-col items-center gap-1 px-5 py-2 rounded-full text-gray-700 hover:text-indigo-600 hover:bg-indigo-50 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14v-4z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h11a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V8a2 2 0 012-2z"/>
            </svg>
            <span class="text-[11px] font-medium">Webinar</span>
        </a>
        <a href="{{ route('coaching.booking') }}" class="flex flex-col items-center gap-1 px-5 py-2 rounded-full text-gray-700 hover:text-indigo-600 hover:bg-indigo-50 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422A12.083 12.083 0 0120 17.944L12 22l-8-4.056a12.083 12.083 0 011.84-7.366L12 14z"/>
            </svg>
            <span class="text-[11px] font-medium">Coaching</span>
        </a>
    </div>
</nav>

<script>
    (function() {
        const floatingNav = document.getElementById('customer-floating-nav');
        if (!floatingNav) return;

        let lastScrollY = window.scrollY;
        let ticking = false;
        const mainNavHeight = 64;

        function updateFloatingNav() {
            const currentScrollY = window.scrollY;

            if (currentScrollY > mainNavHeight + 10) {
                if (currentScrollY > lastScrollY) {
                    floatingNav.classList.remove('-translate-y-full');
                    floatingNav.classList.add('translate-y-0');
                } else {
                    floatingNav.classList.remove('translate-y-0');
                    floatingNav.classList.add('-translate-y-full');
                }
            } else {
                floatingNav.classList.remove('translate-y-0');
                floatingNav.classList.add('-translate-y-full');
            }

            lastScrollY = currentScrollY;
            ticking = false;
        }

        window.addEventListener('scroll', function() {
            if (!ticking) {
                requestAnimationFrame(updateFloatingNav);
                ticking = true;
            }
        }, { passive: true });

        console.log('Floating nav initialized, scrollY:', window.scrollY);
    })();
</script>
