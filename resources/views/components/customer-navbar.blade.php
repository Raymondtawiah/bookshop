<nav class="fixed top-0 left-0 right-0 z-50 bg-white shadow-md">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex justify-between items-center h-14">
            <!-- Logo -->
            <a href="{{ route('home') }}" class="font-bold text-indigo-600 text-lg">
                Nathaniel Gyarteng
            </a>

            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center space-x-6">
                <a href="{{ route('home') }}#home" class="text-gray-700 hover:text-indigo-600">Home</a>
                @if(\App\Models\Book::count() > 0)
                <a href="{{ route('home') }}#store" class="text-gray-700 hover:text-indigo-600">Store</a>
                @endif
                <a href="{{ route('visa-tip') }}" class="text-gray-700 hover:text-indigo-600">Visa Tips</a>
                <a href="{{ route('home') }}#about" class="text-gray-700 hover:text-indigo-600">About</a>
                <a href="{{ route('home') }}#contact" class="text-gray-700 hover:text-indigo-600">Contact</a>
                
                @auth
                <a href="{{ route('cart') }}" class="relative text-gray-700 hover:text-indigo-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    @php
                        $cartCount = \App\Models\Cart::where('user_id', auth()->id())->sum('quantity') ?? 0;
                    @endphp
                    @if($cartCount > 0)
                        <span class="absolute -top-2 -right-2 bg-indigo-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">{{ $cartCount }}</span>
                    @endif
                </a>
                <div class="relative group">
                    <button class="text-gray-700 hover:text-indigo-600 flex items-center">
                        <span class="mr-1">{{ auth()->user()->name }}</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg hidden group-hover:block border">
                        <a href="{{ route('profile') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Settings</a>
                        <a href="{{ route('my-orders') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">My Orders</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-red-600 hover:bg-gray-100">Logout</button>
                        </form>
                    </div>
                </div>
                @else
                <a href="{{ route('login') }}" class="text-gray-700 hover:text-indigo-600">Sign In</a>
                @if (Route::has('register'))
                <a href="{{ route('register') }}" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Register</a>
                @endif
                @endauth
            </div>

            <!-- Mobile Menu Button -->
            <button id="mobile-menu-btn" class="md:hidden p-2 text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="menu-icon">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                <svg class="w-6 h-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="close-icon">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>

    <!-- Mobile Dropdown -->
    <div id="mobile-menu" class="hidden md:hidden bg-white border-t">
        <div class="px-4 py-3 space-y-3">
            <a href="{{ route('home') }}#home" class="block text-gray-700 hover:text-indigo-600">Home</a>
            @if(\App\Models\Book::count() > 0)
            <a href="{{ route('home') }}#store" class="block text-gray-700 hover:text-indigo-600">Store</a>
            @endif
            <a href="{{ route('visa-tip') }}" class="block text-gray-700 hover:text-indigo-600">Visa Tips</a>
            <a href="{{ route('home') }}#about" class="block text-gray-700 hover:text-indigo-600">About</a>
            <a href="{{ route('home') }}#contact" class="block text-gray-700 hover:text-indigo-600">Contact</a>
            <hr class="border-gray-200">
            
            @auth
            <div class="flex items-center gap-3 py-2">
                <div class="w-8 h-8 bg-indigo-600 rounded-full flex items-center justify-center text-white font-bold">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                </div>
            </div>
            <a href="{{ route('cart') }}" class="block text-gray-700 hover:text-indigo-600">Cart ({{ $cartCount ?? 0 }})</a>
            <a href="{{ route('profile') }}" class="block text-gray-700 hover:text-indigo-600">Settings</a>
            <a href="{{ route('my-orders') }}" class="block text-gray-700 hover:text-indigo-600">My Orders</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="block text-red-600 hover:text-red-800 w-full text-left">Logout</button>
            </form>
            @else
            <a href="{{ route('login') }}" class="block text-gray-700 hover:text-indigo-600">Sign In</a>
            @if (Route::has('register'))
            <a href="{{ route('register') }}" class="block text-indigo-600 hover:text-indigo-800 font-medium">Register</a>
            @endif
            @endauth
        </div>
    </div>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const btn = document.getElementById('mobile-menu-btn');
        const menu = document.getElementById('mobile-menu');
        const menuIcon = document.getElementById('menu-icon');
        const closeIcon = document.getElementById('close-icon');
        
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            menu.classList.toggle('hidden');
            menuIcon.classList.toggle('hidden');
            closeIcon.classList.toggle('hidden');
        });
        
        // Close when clicking outside
        document.addEventListener('click', function(e) {
            if (!menu.classList.contains('hidden')) {
                if (!menu.contains(e.target) && !btn.contains(e.target)) {
                    menu.classList.add('hidden');
                    menuIcon.classList.remove('hidden');
                    closeIcon.classList.add('hidden');
                }
            }
        });
        
        // Close when clicking a link in the menu
        menu.querySelectorAll('a, button').forEach(function(item) {
            item.addEventListener('click', function() {
                menu.classList.add('hidden');
                menuIcon.classList.remove('hidden');
                closeIcon.classList.add('hidden');
            });
        });
    });
</script>
