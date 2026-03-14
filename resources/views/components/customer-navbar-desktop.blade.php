<!-- Desktop Navigation -->
<div class="hidden md:flex items-center justify-between flex-1 ml-8">
    <!-- Desktop Menu Links -->
    <div class="flex items-center space-x-8">
        <a href="{{ route('home') }}#home" class="text-gray-600 hover:text-indigo-600 font-medium transition-colors">Home</a>
        @if(\App\Models\Book::count() > 0)
        <a href="{{ route('home') }}#store" class="text-gray-600 hover:text-indigo-600 font-medium transition-colors">Store</a>
        @endif
        <a href="{{ route('visa-tip') }}" class="text-gray-600 hover:text-indigo-600 font-medium transition-colors">Visa Tips</a>
        <a href="{{ route('home') }}#about" class="text-gray-600 hover:text-indigo-600 font-medium transition-colors">About</a>
        <a href="{{ route('home') }}#contact" class="text-gray-600 hover:text-indigo-600 font-medium transition-colors">Contact</a>
    </div>

    <!-- Right Side: Cart + User Icon -->
    <div class="flex items-center gap-4">
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

        <!-- User Icon with Dropdown -->
        <div class="relative" id="user-dropdown-container">
            <button id="user-menu-button" class="flex items-center gap-2 p-1.5 rounded-lg hover:bg-gray-100 transition-colors">
                <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </button>
            
            <!-- Dropdown Menu -->
            <div class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-50" id="user-dropdown-menu">
                @auth
                    <div class="px-4 py-2 border-b border-gray-100">
                        <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                    </div>
                    <a href="{{ route('profile') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Settings
                    </a>
                    <a href="{{ route('my-orders') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        My Orders
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <button type="submit" class="flex items-center gap-2 w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            Logout
                        </button>
                    </form>
                @else
                    <div class="px-4 py-2 border-b border-gray-100">
                        <p class="text-sm font-medium text-gray-900">Welcome, Guest</p>
                    </div>
                    <a href="{{ route('login') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                        </svg>
                        Sign In
                    </a>
                    @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                        Register
                    </a>
                    @endif
                @endauth
            </div>
        </div>
    </div>
</div>

<script>
    // Close user dropdown when clicking outside
    document.addEventListener('click', function(event) {
        var dropdown = document.getElementById('user-dropdown-menu');
        var container = document.getElementById('user-dropdown-container');
        if (dropdown && container) {
            if (!container.contains(event.target)) {
                dropdown.style.display = 'none';
            }
        }
    });
    
    // Toggle dropdown on button click
    document.querySelector('#user-dropdown-container button').addEventListener('click', function() {
        var dropdown = document.getElementById('user-dropdown-menu');
        if (dropdown.style.display === 'none' || dropdown.style.display === '') {
            dropdown.style.display = 'block';
        } else {
            dropdown.style.display = 'none';
        }
    });
</script>
