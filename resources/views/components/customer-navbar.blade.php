<nav class="fixed top-0 left-0 right-0 z-[9999] bg-white shadow-md font-sans" style="z-index: 9999 !important;">
    <script>
        window.addEventListener('pageshow', function(event) {
            if (event.persisted) {
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
        .logo-gradient {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 50%, #ec4899 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .font-typewriter {
            font-family: 'Courier New', Courier, monospace;
        }
    </style>
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex justify-between items-center h-16">
            <!-- Logo -->
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <img src="{{ asset('icon.jpg') }}" alt="Visa with Nathaniel" class="w-10 h-10 rounded-xl shadow-lg object-cover">
                <div>
                    <span class="font-bold text-xl logo-gradient">Visa with Nathaniel</span>
                </div>
            </a>

             <!-- Desktop Navigation -->
             <div class="hidden md:flex items-center gap-6">
                 <a href="{{ route('home') }}#home" class="text-gray-600 hover:text-indigo-600 font-medium transition-colors">Home</a>
                 <a href="{{ route('visa-tip') }}" class="text-gray-600 hover:text-indigo-600 font-medium transition-colors">Visa Tips</a>
                 <a href="{{ route('webinars.index') }}" class="text-gray-600 hover:text-indigo-600 font-medium transition-colors">Webinars</a>
                 <a href="{{ route('visa-training') }}" class="text-gray-600 hover:text-indigo-600 font-medium transition-colors">Visa Training</a>
                 <a href="{{ route('home') }}#about" class="text-gray-600 hover:text-indigo-600 font-medium transition-colors">About</a>
                 <a href="{{ route('home') }}#contact" class="text-gray-600 hover:text-indigo-600 font-medium transition-colors">Contact</a>
                 <a href="{{ route('coaching.booking') }}" class="text-gray-600 hover:text-indigo-600 font-medium transition-colors">Visa Coaching</a>

                 @auth
                 @php
                     $navbarCartCount = auth()->check() ? \App\Models\Cart::where('user_id', auth()->id())->sum('quantity') : 0;
                 @endphp
                 <a href="{{ route('cart') }}" class="relative p-2 text-gray-600 hover:text-indigo-600 transition-colors">
                     <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                     </svg>
                     @if($navbarCartCount > 0)
                         <span class="absolute -top-1 -right-1 bg-indigo-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-bold">{{ $navbarCartCount }}</span>
                     @endif
                 </a>
                 @endauth

                 @guest
                 <a href="{{ route('cart') }}" class="relative p-2 text-gray-600 hover:text-indigo-600 transition-colors">
                     <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                     </svg>
                 </a>
                 @endguest

                @auth
                <div class="relative group">
                    <button class="flex items-center gap-2 text-gray-600 hover:text-indigo-600 transition-colors">
                        <div class="w-8 h-8 rounded-full overflow-hidden border-2 border-indigo-600">
                            <img src="{{ asset('user_icon.jpg') }}" alt="{{ auth()->user()->name }}" class="w-full h-full object-cover">
                        </div>
                    </button>
                    <div class="absolute right-0 top-full mt-2 w-48 bg-white rounded-xl shadow-xl border border-gray-200 py-2 z-50 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                        <div class="px-4 py-2 border-b border-gray-100">
                            <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                        </div>
                         <a href="{{ route('profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">Settings</a>
                         <a href="{{ route('my-orders') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">My Orders</a>
                         <a href="{{ route('cart') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">Shopping Cart</a>
                        <form method="POST" action="{{ route('logout') }}" class="border-t border-gray-100">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">Logout</button>
                        </form>
                    </div>
                </div>
                @endauth

                @guest
                <a href="{{ route('login') }}" class="text-gray-600 hover:text-indigo-600 font-medium transition-colors">Sign In</a>
                @if (Route::has('register'))
                <a href="{{ route('register') }}" class="text-indigo-600 hover:text-indigo-800 font-medium transition-colors">Register</a>
                @endif
                @endguest
            </div>


            <!-- Mobile Menu Button -->
            <div class="flex items-center gap-2 md:hidden">
                @auth
                @php
                    $navbarCartCount = auth()->check() ? \App\Models\Cart::where('user_id', auth()->id())->sum('quantity') : 0;
                @endphp
                <a href="{{ route('cart') }}" class="relative p-2 text-gray-600 hover:text-indigo-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    @if($navbarCartCount > 0)
                        <span class="absolute -top-1 -right-1 bg-indigo-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">{{ $navbarCartCount }}</span>
                    @endif
                </a>
                @endauth
                @include('components.customer-navbar-mobile')
            </div>
        </div>
    </div>

    <script>
        function toggleAnnouncement() {
            const dropdown = document.getElementById('announcement-dropdown');
            dropdown.classList.toggle('hidden');
        }

        document.addEventListener('DOMContentLoaded', function() {
            const announcementBadge = document.getElementById('announcement-badge');

            @if(!session('announcement_closed'))
                if (announcementBadge) announcementBadge.classList.remove('hidden');
            @endif
        });

        document.addEventListener('click', function(e) {
            const bell = document.getElementById('announcement-bell');
            const dropdown = document.getElementById('announcement-dropdown');
            if (bell && dropdown && !bell.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.classList.add('hidden');
            }
        });
    </script>
</nav>


