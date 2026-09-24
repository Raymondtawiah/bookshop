<nav class="fixed top-0 left-0 right-0 z-[9999] bg-white shadow-md font-sans" style="z-index: 9999 !important;">
    <div class="max-w-7xl mx-auto px-4 relative">
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
                <a href="{{ route('home') }}#home" class="nav-link text-gray-600 hover:text-indigo-600 font-medium transition-colors">Home</a>
                <a href="{{ route('webinars.index') }}" class="nav-link text-gray-600 hover:text-indigo-600 font-medium transition-colors">Webinars</a>
                <a href="{{ route('coaching.booking') }}" class="nav-link text-gray-600 hover:text-indigo-600 font-medium transition-colors">Visa Coaching</a>
                
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
                            <form method="POST" action="{{ route('logout') }}" class="border-t border-gray-100">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">Logout</button>
                            </form>
                        </div>
                    </div>
                @endauth
            </div>

            <!-- Mobile Navigation -->
            <div class="md:hidden flex items-center gap-3">
                <a href="{{ route('home') }}#home" class="nav-link text-indigo-600 hover:text-indigo-700 transition-colors" title="Home">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10l9-7 9 7v10a1 1 0 01-1 1h-5v-6H9v6H4a1 1 0 01-1-1V10z"/>
                    </svg>
                </a>
                <a href="{{ route('webinars.index') }}" class="nav-link text-indigo-600 hover:text-indigo-700 transition-colors" title="Webinars">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14v-4z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h11a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V8a2 2 0 012-2z"/>
                    </svg>
                </a>
                <a href="{{ route('coaching.booking') }}" class="nav-link text-indigo-600 hover:text-indigo-700 transition-colors" title="Visa Coaching">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422A12.083 12.083 0 0120 17.944L12 22l-8-4.056a12.083 12.083 0 011.84-7.366L12 14z"/>
                    </svg>
                </a>

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
                            <form method="POST" action="{{ route('logout') }}" class="border-t border-gray-100">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">Logout</button>
                            </form>
                        </div>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</nav>

<script>
    (function() {
        const navLinks = document.querySelectorAll('.nav-link');

        navLinks.forEach(function(link) {
            link.addEventListener('click', function() {
                const loader = document.getElementById('page-loader');
                if (loader) {
                    loader.style.opacity = '1';
                    loader.classList.remove('hidden');
                }
            });
        });
    })();
</script>
