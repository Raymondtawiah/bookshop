<!-- Desktop Navigation -->
<style>
@keyframes gradient-shift {
    0%, 100% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
}
.animate-gradient {
    background: linear-gradient(-45deg, #ee7752, #e73c7e, #23a6d5, #23d5ab);
    background-size: 300% 300%;
    animation: gradient-shift 3s ease infinite;
}
</style>
<div class="hidden md:flex items-center justify-center flex-1">
    <!-- Desktop Menu Links - Centered -->
    <div class="flex items-center space-x-8">
        <a href="{{ route('home') }}#home" class="text-gray-600 hover:text-indigo-600 font-medium transition-colors">Home</a>
        <a href="{{ route('home') }}#store" class="text-gray-600 hover:text-indigo-600 font-medium transition-colors">Store</a>
        <a href="{{ route('visa-tip') }}" class="text-gray-600 hover:text-indigo-600 font-medium transition-colors">Visa Tips</a>
        <a href="{{ route('home') }}#about" class="text-gray-600 hover:text-indigo-600 font-medium transition-colors">About</a>
        <a href="{{ route('home') }}#contact" class="text-gray-600 hover:text-indigo-600 font-medium transition-colors">Contact</a>
    </div>
</div>

<!-- Right Side: Cart + User Icon (outside, right aligned) -->
<div class="hidden md:flex items-center gap-4">
    @auth
    @php
        $desktopCartCount = auth()->check() ? \App\Models\Cart::where('user_id', auth()->id())->count() : 0;
    @endphp
    <!-- Cart Icon -->
    <a href="{{ route('cart') }}" class="relative p-2 text-gray-600 hover:text-indigo-600">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
        </svg>
        @if($desktopCartCount > 0)
            <span class="absolute -top-1 -right-1 bg-indigo-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">{{ $desktopCartCount }}</span>
        @endif
    </a>
    @endauth

    <!-- User Icon with Dropdown -->
    <div class="relative" id="user-dropdown-container">
        <button id="user-menu-button" class="flex items-center gap-2 p-1.5 rounded-lg hover:bg-gray-100 transition-colors">
            <div class="w-8 h-8 rounded-full flex items-center justify-center animate-gradient">
                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                </svg>
            </div>
        </button>

        <!-- Dropdown Menu -->
        <div id="user-dropdown" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg hidden border z-50">
            @auth
            <div class="px-4 py-3 border-b">
                <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
            </div>
            <a href="{{ route('profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
            <a href="{{ route('my-orders') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">My Orders</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                    Logout
                </button>
            </form>
            @endauth

            @guest
            <a href="{{ route('login') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Sign In</a>
            <a href="{{ route('register') }}" class="block px-4 py-2 text-sm text-indigo-600 hover:bg-gray-100">Register</a>
            @endguest
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const userMenuButton = document.getElementById('user-menu-button');
        const userDropdown = document.getElementById('user-dropdown');
        const container = document.getElementById('user-dropdown-container');

        if (userMenuButton && userDropdown) {
            userMenuButton.addEventListener('click', function(e) {
                e.stopPropagation();
                userDropdown.classList.toggle('hidden');
            });

            document.addEventListener('click', function(e) {
                if (!container.contains(e.target)) {
                    userDropdown.classList.add('hidden');
                }
            });
        }
    });
</script>
