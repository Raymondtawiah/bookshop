<!-- Mobile Navigation (Phone View) -->
<div class="md:hidden flex items-center gap-2">
    <!-- Cart Icon (Show only when logged in) -->
    @auth
    <a href="{{ route('cart') }}" class="relative p-2 text-gray-600 hover:text-indigo-600">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
        </svg>
        @php
            $cartCount = \App\Models\Cart::where('user_id', auth()->id())->sum('quantity') ?? 0;
        @endphp
        @if($cartCount > 0)
            <span class="absolute -top-1 -right-1 bg-indigo-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">{{ $cartCount }}</span>
        @endif
    </a>
    @endauth

    <!-- Toggle Button -->
    <button id="mobile-menu-btn" class="p-2 text-gray-600 hover:text-indigo-600" onclick="toggleCustomerMobileMenu()">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="menu-icon">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
        <svg class="w-6 h-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="close-icon">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </button>
</div>

<!-- Mobile Menu Dropdown -->
<div id="customer-mobile-menu" class="hidden absolute top-full left-0 right-0 bg-white shadow-lg border-t border-gray-200 p-4" style="display: none;">
    <div class="flex flex-col space-y-3">
        <a href="{{ route('home') }}#home" class="text-gray-600 hover:text-indigo-600 font-medium transition-colors">Home</a>
        <!-- Always show Store link - it will scroll to store section -->
        <a href="{{ route('home') }}#store" class="text-gray-600 hover:text-indigo-600 font-medium transition-colors">Store</a>
        <a href="{{ route('visa-tip') }}" class="text-gray-600 hover:text-indigo-600 font-medium transition-colors">Visa Tips</a>
        <a href="{{ route('home') }}#about" class="text-gray-600 hover:text-indigo-600 font-medium transition-colors">About</a>
        <a href="{{ route('home') }}#contact" class="text-gray-600 hover:text-indigo-600 font-medium transition-colors">Contact</a>
        
        @auth
        <hr class="border-gray-200 my-2">
        
        @auth
        <div class="flex items-center gap-3 py-2">
            <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
            </div>
        </div>
        <a href="{{ route('profile') }}" class="text-gray-600 hover:text-indigo-600 font-medium transition-colors">Settings</a>
        <a href="{{ route('my-orders') }}" class="text-gray-600 hover:text-indigo-600 font-medium transition-colors">My Orders</a>
        <form method="POST" action="{{ route('logout') }}" class="w-full">
            @csrf
            <button type="submit" class="text-left text-red-600 hover:text-red-800 font-medium transition-colors w-full">
                Logout
            </button>
        </form>
        @endauth

        @guest
        <hr class="border-gray-200 my-2">
        <a href="{{ route('login') }}" class="text-gray-600 hover:text-indigo-600 font-medium transition-colors">Sign In</a>
        @if (Route::has('register'))
        <a href="{{ route('register') }}" class="text-indigo-600 hover:text-indigo-800 font-medium transition-colors">Register</a>
        @endif
        @endguest
    </div>
</div>

<script>
    function toggleCustomerMobileMenu() {
        const mobileMenu = document.getElementById('customer-mobile-menu');
        const menuIcon = document.getElementById('menu-icon');
        const closeIcon = document.getElementById('close-icon');
        
        mobileMenu.classList.toggle('hidden');
        if (mobileMenu.classList.contains('hidden')) {
            mobileMenu.style.display = 'none';
        } else {
            mobileMenu.style.display = 'block';
        }
        menuIcon.classList.toggle('hidden');
        closeIcon.classList.toggle('hidden');
    }
</script>
