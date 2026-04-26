<nav class="fixed top-0 left-0 right-0 z-[9999] bg-white shadow-md" style="z-index: 9999 !important;">
<script>
// Handle back-forward cache (bfcache) - ensures fresh content on navigation
window.addEventListener('pageshow', function(event) {
    if (event.persisted) {
        // Page was loaded from bfcache - reload to get fresh content
        window.location.reload();
    }
});
</script>
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
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex justify-between items-center h-14">
            <!-- Logo -->
            <a href="{{ route('home') }}" class="font-bold text-indigo-600 text-lg">
                VISA WITH NATHANIEL
            </a>

             <!-- Desktop Menu -->
             <div class="hidden md:flex items-center space-x-8">
                <a href="{{ route('home') }}#home" class="text-gray-700 hover:text-indigo-600">Home</a>
                <!-- Always show Store link - it will scroll to store section -->
                <a href="{{ route('home') }}#store" class="text-gray-700 hover:text-indigo-600">Store</a>
                <a href="{{ route('visa-tip') }}" class="text-gray-700 hover:text-indigo-600">Visa Tips</a>
                {{-- <a href="{{ route('visa-training') }}" class="text-gray-700 hover:text-indigo-600">Visa Training</a> --}}
                <a href="{{ route('home') }}#about" class="text-gray-700 hover:text-indigo-600">About</a>
                <a href="{{ route('home') }}#contact" class="text-gray-700 hover:text-indigo-600">Contact</a>
                <a href="{{ route('coaching.booking') }}" class="text-gray-700 hover:text-indigo-600 font-medium">Visa Coaching</a>
                
                @auth
                @php
                    $navbarCartCount = auth()->check() ? \App\Models\Cart::where('user_id', auth()->id())->sum('quantity') : 0;
                @endphp
                 <a href="{{ route('cart') }}" class="relative text-gray-700 hover:text-indigo-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    @if($navbarCartCount > 0)
                        <span class="absolute -top-2 -right-2 bg-indigo-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">{{ $navbarCartCount }}</span>
                    @endif
                </a>
                <div class="relative group">
                    <button class="text-gray-700 hover:text-indigo-600 flex items-center">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center animate-gradient">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                     <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg hidden group-hover:block border z-[9999]" style="z-index: 9999 !important;">
                        <a href="{{ route('profile') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">My Profile</a>
                        <a href="{{ route('my-orders') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">My Orders</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-red-600 hover:bg-gray-100">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
                @endauth

                @guest
                <a href="{{ route('login') }}" class="text-gray-700 hover:text-indigo-600">Sign In</a>
                <a href="{{ route('register') }}" class="text-indigo-600 hover:text-indigo-800 font-medium">Register</a>
                @endguest
            </div>

            <!-- Mobile Menu Button (hidden on desktop) -->
            <div class="md:hidden">
                @include('components.customer-navbar-mobile')
            </div>
        </div>
    </div>
</nav>
