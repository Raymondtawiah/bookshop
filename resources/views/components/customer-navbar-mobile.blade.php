<!-- Hamburger Navigation -->
<div class="flex items-center gap-2 font-sans">
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
<div id="customer-mobile-menu"  class="absolute top-full right-0 mt-3 w-80 bg-white rounded-2xl shadow-2xl border border-gray-200 p-4 z-[9999]" style="display:none;">
    <div class="flex flex-col space-y-3">
        <a href="{{ route('home') }}#home" class="flex items-center gap-3 px-3 py-3 rounded-lg text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 10l9-7 9 7v10a1 1 0 01-1 1h-5v-6H9v6H4a1 1 0 01-1-1V10z"/>
            </svg>
            <span>Home</span>
        </a>
        <a href="{{ route('visa-tip') }}"  class="flex items-center gap-3 px-3 py-3 rounded-lg text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 font-medium transition border-b border-gray-100">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4l3 3M12 3a9 9 0 100 18 9 9 0 000-18z"/>
            </svg>
            <span>Visa Tips</span>
        </a>
        <a href="{{ route('webinars.index') }}" class="flex items-center gap-3 px-3 py-3 rounded-lg text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 font-medium transition border-b border-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14v-4z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 6h11a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V8a2 2 0 012-2z"/>
                </svg>
                <span>Webinars</span>
        </a>
        <a href="{{ route('home') }}#about" class="flex items-center gap-3 px-3 py-3 rounded-lg text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 font-medium transition border-b border-gray-100">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13 16h-1v-4h-1m1-4h.01M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10z"/>
            </svg>
            <span>About</span>
        </a>
          <a href="{{ route('home') }}#contact" class="flex items-center gap-3 px-3 py-3 rounded-lg text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 font-medium transition border-b border-gray-100">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 5a2 2 0 012-2h14a2 2 0 012 2v14l-4-3H5a2 2 0 01-2-2V5z"/>
            </svg>
            <span>Contact</span>
        </a>
          <a href="{{ route('coaching.booking') }}" class="flex items-center gap-3 px-3 py-3 rounded-lg text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 font-medium transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 14l9-5-9-5-9 5 9 5z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 14l6.16-3.422A12.083 12.083 0 0120 17.944L12 22l-8-4.056a12.083 12.083 0 011.84-7.366L12 14z"/>
                </svg>
                <span>Visa Coaching</span>
            </a>
        @auth
        <hr class="border-gray-200 my-2">
        <a href="{{ route('profile') }}" class="flex items-center gap-3 px-3 py-3 rounded-lg text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 font-medium transition border-b border-gray-100">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M10.325 4.317a1.724 1.724 0 013.35 0l.174.696a1.724 1.724 0 002.591 1.066l.61-.35a1.724 1.724 0 012.366.632l.35.61a1.724 1.724 0 01-1.066 2.591l-.696.174a1.724 1.724 0 000 3.35l.696.174a1.724 1.724 0 011.066 2.591l-.35.61a1.724 1.724 0 01-2.366.632l-.61-.35a1.724 1.724 0 00-2.591 1.066l-.174.696a1.724 1.724 0 01-3.35 0l-.174-.696a1.724 1.724 0 00-2.591-1.066l-.61.35a1.724 1.724 0 01-2.366-.632l-.35-.61a1.724 1.724 0 011.066-2.591l.696-.174a1.724 1.724 0 000-3.35l-.696-.174a1.724 1.724 0 01-1.066-2.591l.35-.61a1.724 1.724 0 012.366-.632l.61.35a1.724 1.724 0 002.591-1.066l.174-.696z"/>
                <circle cx="12" cy="12" r="3"/>
            </svg>
            <span>Settings</span>
        </a>
        <a href="{{ route('my-orders') }}" class="flex items-center gap-3 px-3 py-3 rounded-lg text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 font-medium transition border-b border-gray-100">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M20 7l-8-4-8 4m16 0v10l-8 4m8-14l-8 4m0 10l-8-4V7m8 4v10"/>
            </svg>
            <span>My Orders</span>
        </a>
        <form method="POST" action="{{ route('logout') }}" class="w-full">
            @csrf
           <button type="submit" class="flex items-center gap-3 w-full px-3 py-3 rounded-lg text-red-600 hover:bg-red-50 hover:text-red-700 font-medium transition border-b border-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="w-5 h-5"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H9m4 8H5a2 2 0 01-2-2V6a2 2 0 012-2h8"/>
                </svg>

                <span>Logout</span>
            </button>
        </form>
        @endauth
    </div>
            
         @guest
        <hr class="border-gray-200 my-2">
        <a href="{{ route('login') }}"    class="flex items-center gap-3 px-3 py-3 rounded-lg text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 font-medium transition border-b border-gray-100">
            <svg xmlns="http://www.w3.org/2000/svg"
                class="w-5 h-5"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M15 3h4a2 2 0 012 2v14a2 2 0 01-2 2h-4M10 17l5-5m0 0l-5-5m5 5H3"/>
            </svg>
            <span>Sign In</span>
        </a>
         @if (Route::has('register'))
        <a href="{{ route('register') }}" class="flex items-center gap-3 px-3 py-3 rounded-lg text-indigo-600 hover:bg-indigo-50 hover:text-indigo-800 font-medium transition">
            <svg xmlns="http://www.w3.org/2000/svg"
                class="w-5 h-5"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M18 9v6m3-3h-6M15 21H5a2 2 0 01-2-2v-1a4 4 0 014-4h4a4 4 0 014 4v1a2 2 0 01-2 2zm-6-10a4 4 0 100-8 4 4 0 000 8z"/>
            </svg>
            <span>Register</span>
        </a>
        @endif
         @endguest
    </div>
</div>

<script>
    function toggleCustomerMobileMenu() {
        console.log('Toggle clicked');
        const mobileMenu = document.getElementById('customer-mobile-menu');
        const menuIcon = document.getElementById('menu-icon');
        const closeIcon = document.getElementById('close-icon');
        
        console.log('Menu:', mobileMenu.style.display);
        
        // Force toggle display - add/remove a class instead of inline style
        if (mobileMenu.style.display === 'none') {
            mobileMenu.style.display = 'block';
            mobileMenu.classList.remove('hidden');
        } else {
            mobileMenu.style.display = 'none';
            mobileMenu.classList.add('hidden');
        }
        menuIcon.classList.toggle('hidden');
        closeIcon.classList.toggle('hidden');
    }
</script>
