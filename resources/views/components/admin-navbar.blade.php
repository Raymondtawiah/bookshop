<!-- Admin Navbar -->
<header class="fixed top-0 left-0 right-0 z-[9999] bg-white shadow-md font-sans" style="z-index: 9999 !important;">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex justify-between items-center h-16 gap-3">
            <!-- Logo -->
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                <img src="{{ asset('icon.jpg') }}" alt="Visa with Nathaniel" class="w-10 h-10 rounded-xl shadow-lg object-cover">
                <div>
                    <span class="font-bold text-xl logo-gradient">Admin Panel</span>
                </div>
            </a>

             <!-- Desktop Navigation -->
             <nav class="hidden md:flex items-center gap-6">
                 <a href="{{ route('admin.dashboard') }}" class="text-gray-600 hover:text-indigo-600 font-medium transition-colors {{ request()->routeIs('admin.dashboard') ? 'text-indigo-600' : '' }}">Dashboard</a>
                 <a href="{{ route('admin.books') }}" class="text-gray-600 hover:text-indigo-600 font-medium transition-colors {{ request()->routeIs('admin.books*') ? 'text-indigo-600' : '' }}">Books</a>
                 <a href="{{ route('admin.customers') }}" class="text-gray-600 hover:text-indigo-600 font-medium transition-colors {{ request()->routeIs('admin.customers*') ? 'text-indigo-600' : '' }}">Customers</a>
                 <a href="{{ route('admin.orders') }}" class="text-gray-600 hover:text-indigo-600 font-medium transition-colors {{ request()->routeIs('admin.orders*') ? 'text-indigo-600' : '' }}">Orders</a>
                 <a href="{{ route('admin.free-books') }}" class="text-gray-600 hover:text-indigo-600 font-medium transition-colors {{ request()->routeIs('admin.free-books*') ? 'text-indigo-600' : '' }}">Free Books</a>
                 <a href="{{ route('admin.staff.index') }}" class="text-gray-600 hover:text-indigo-600 font-medium transition-colors {{ request()->routeIs('admin.staff*') ? 'text-indigo-600' : '' }}">Staff</a>
                 <a href="{{ route('admin.coachings.index') }}" class="text-gray-600 hover:text-indigo-600 font-medium transition-colors {{ request()->routeIs('admin.coachings*') ? 'text-indigo-600' : '' }}">Coachings</a>
                 <a href="{{ route('admin.webinars.index') }}" class="text-gray-600 hover:text-indigo-600 font-medium transition-colors {{ request()->routeIs('admin.webinars*') ? 'text-indigo-600' : '' }}">Webinars</a>

                <div class="relative group">
                    <button class="flex items-center gap-2 text-gray-600 hover:text-indigo-600 transition-colors">
                        <div class="w-8 h-8 rounded-full overflow-hidden border-2 border-indigo-600">
                            <img src="{{ asset('user_icon.jpg') }}" alt="{{ auth()->user()->name }}" class="w-full h-full object-cover">
                        </div>
                    </button>
                    <div class="absolute right-0 top-full mt-2 w-48 bg-white rounded-xl shadow-xl border border-gray-200 py-2 z-50 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                        <div class="px-4 py-2 border-b border-gray-100">
                            <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-500">Administrator</p>
                        </div>
                        <a href="{{ route('admin.settings') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">Settings</a>
                        <a href="{{ route('admin.attendance.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">Request Attendance</a>
                        <form method="POST" action="{{ route('admin.logout') }}" class="border-t border-gray-100">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">Logout</button>
                        </form>
                    </div>
                </div>
            </nav>

            <!-- Mobile Menu Button -->
            <button id="admin-mobile-menu-btn" class="md:hidden p-2 text-gray-600 hover:text-indigo-600" onclick="toggleAdminMobileMenu()">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="admin-menu-icon">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                <svg class="w-6 h-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="admin-close-icon">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>

    <!-- Admin Mobile Navigation Menu -->
    <div id="admin-mobile-menu" class="hidden md:hidden border-t border-gray-200">
        <div class="px-4 py-3 space-y-1">
            <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-600 hover:bg-gray-100' }}">Dashboard</a>
            <a href="{{ route('admin.books') }}" class="block px-4 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('admin.books*') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-600 hover:bg-gray-100' }}">Books</a>
            <a href="{{ route('admin.customers') }}" class="block px-4 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('admin.customers*') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-600 hover:bg-gray-100' }}">Customers</a>
            <a href="{{ route('admin.orders') }}" class="block px-4 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('admin.orders*') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-600 hover:bg-gray-100' }}">Orders</a>
            <a href="{{ route('admin.free-books') }}" class="block px-4 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('admin.free-books*') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-600 hover:bg-gray-100' }}">Free Books</a>
                <a href="{{ route('admin.coachings.index') }}" class="block px-4 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('admin.coachings*') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-600 hover:bg-gray-100' }}">Coachings</a>
                <a href="{{ route('admin.staff.index') }}" class="block px-4 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('admin.staff*') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-600 hover:bg-gray-100' }}">Staff</a>
                <a href="{{ route('admin.webinars.index') }}" class="block px-4 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('admin.webinars*') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-600 hover:bg-gray-100' }}">Webinars</a>
            <a href="{{ route('admin.settings') }}" class="block px-4 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('admin.settings*') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-600 hover:bg-gray-100' }}">Settings</a>
        </div>
        <div class="border-t border-gray-200 px-4 py-3">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-9 h-9 rounded-full overflow-hidden border-2 border-indigo-600">
                    <img src="{{ asset('user_icon.jpg') }}" alt="{{ auth()->user()->name }}" class="w-full h-full object-cover">
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-gray-500">Administrator</p>
                </div>
            </div>
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" class="w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 rounded-lg text-left">
                    Logout
                </button>
            </form>
        </div>
    </div>

    <script>
        function toggleAdminMobileMenu() {
            const mobileMenu = document.getElementById('admin-mobile-menu');
            const menuIcon = document.getElementById('admin-menu-icon');
            const closeIcon = document.getElementById('admin-close-icon');

            mobileMenu.classList.toggle('hidden');
            menuIcon.classList.toggle('hidden');
            closeIcon.classList.toggle('hidden');
        }
    </script>
</header>
