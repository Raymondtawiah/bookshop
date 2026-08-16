<nav id="customer-bottom-nav" class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 z-50 md:hidden transition-transform duration-300 translate-y-full">
    <div class="flex items-center justify-around py-2">
        <a href="{{ route('home') }}" class="flex flex-col items-center gap-1 p-2 text-gray-600 hover:text-indigo-600 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            <span class="text-xs font-medium">Home</span>
        </a>
        <a href="{{ route('cart') }}" class="flex flex-col items-center gap-1 p-2 text-gray-600 hover:text-indigo-600 transition-colors relative">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
            <span class="text-xs font-medium">Cart</span>
            @php
                $cartCount = auth()->check() ? App\Models\Cart::where('user_id', auth()->id())->sum('quantity') : 0;
            @endphp
            @if($cartCount > 0)
                <span class="absolute top-1 right-1 bg-indigo-600 text-white text-xs rounded-full h-4 w-4 flex items-center justify-center font-bold">{{ $cartCount }}</span>
            @endif
        </a>
        <a href="{{ route('profile') }}" class="flex flex-col items-center gap-1 p-2 text-gray-600 hover:text-indigo-600 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317a1.724 1.724 0 013.35 0l.174.696a1.724 1.724 0 002.591 1.066l.61-.35a1.724 1.724 0 012.366.632l.35.61a1.724 1.724 0 01-1.066 2.591l-.696.174a1.724 1.724 0 000 3.35l.696.174a1.724 1.724 0 011.066 2.591l-.35.61a1.724 1.724 0 01-2.366.632l-.61-.35a1.724 1.724 0 00-2.591 1.066l-.174.696a1.724 1.724 0 01-3.35 0l-.174-.696a1.724 1.724 0 00-2.591-1.066l-.61.35a1.724 1.724 0 01-2.366-.632l-.35-.61a1.724 1.724 0 011.066-2.591l.696-.174a1.724 1.724 0 000-3.35l-.696-.174a1.724 1.724 0 01-1.066-2.591l.35-.61a1.724 1.724 0 012.366-.632l.61.35a1.724 1.724 0 002.591-1.066l.174-.696z"/>
                <circle cx="12" cy="12" r="3"/>
            </svg>
            <span class="text-xs font-medium">Settings</span>
        </a>
    </div>
</nav>

<script>
    (function() {
        const nav = document.getElementById('customer-bottom-nav');
        if (!nav) return;

        let lastScrollY = window.scrollY;
        let ticking = false;

        function updateNav() {
            const currentScrollY = window.scrollY;
            if (currentScrollY > lastScrollY && currentScrollY > 60) {
                nav.classList.add('translate-y-full');
                nav.classList.remove('translate-y-0');
            } else {
                nav.classList.remove('translate-y-full');
                nav.classList.add('translate-y-0');
            }
            lastScrollY = currentScrollY;
            ticking = false;
        }

        window.addEventListener('scroll', function() {
            if (!ticking) {
                requestAnimationFrame(updateNav);
                ticking = true;
            }
        }, { passive: true });
    })();
</script>
