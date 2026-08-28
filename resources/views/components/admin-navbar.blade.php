<!-- Admin Navbar -->
<header class="fixed top-0 left-0 right-0 z-[9999] bg-white shadow-md font-sans" style="z-index: 9999 !important;">
    <div class="max-w-7xl mx-auto px-4 relative">
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
                  <a href="{{ route('admin.notifications.broadcast') }}" class="text-gray-600 hover:text-indigo-600 font-medium transition-colors {{ request()->routeIs('admin.notifications.broadcast') ? 'text-indigo-600' : '' }}">Broadcast</a>


                      <!-- Notifications Bell -->
                 <div class="relative">
                      <button id="notification-bell" class="relative p-2 text-gray-600 hover:text-indigo-600 transition-colors cursor-pointer">
                          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 3 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                          </svg>
                          <span id="notification-badge" class="absolute -top-1 -right-1 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold leading-none text-white transform translate-x-1/4 -translate-y-1/4 bg-red-600 rounded-full" style="display: none;">0</span>
                      </button>

                      <!-- Notifications Dropdown -->
                      <div id="notification-dropdown" class="absolute right-0 top-full mt-2 w-80 bg-white rounded-xl shadow-xl border border-gray-200 py-2 z-50 opacity-0 invisible transition-all duration-200">
                          <div class="px-4 py-2 border-b border-gray-100 flex items-center justify-between">
                              <h3 class="text-sm font-semibold text-gray-900">Notifications</h3>
                              <a href="{{ route('admin.notifications') }}" class="text-xs text-indigo-600 hover:text-indigo-700 font-medium">View All</a>
                          </div>
                          <div id="notification-list" class="max-h-96 overflow-y-auto">
                              <div class="px-4 py-6 text-center text-sm text-gray-500">Loading...</div>
                          </div>
                      </div>
                  </div>
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
    <div id="admin-mobile-menu" class="hidden border-t border-gray-200">
        <div class="px-4 py-3 space-y-1">
            <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-600 hover:bg-gray-100' }}">Dashboard</a>
            <a href="{{ route('admin.books') }}" class="block px-4 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('admin.books*') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-600 hover:bg-gray-100' }}">Books</a>
            <a href="{{ route('admin.customers') }}" class="block px-4 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('admin.customers*') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-600 hover:bg-gray-100' }}">Customers</a>
            <a href="{{ route('admin.orders') }}" class="block px-4 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('admin.orders*') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-600 hover:bg-gray-100' }}">Orders</a>
            <a href="{{ route('admin.free-books') }}" class="block px-4 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('admin.free-books*') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-600 hover:bg-gray-100' }}">Free Books</a>
                <a href="{{ route('admin.coachings.index') }}" class="block px-4 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('admin.coachings*') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-600 hover:bg-gray-100' }}">Coachings</a>
                <a href="{{ route('admin.staff.index') }}" class="block px-4 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('admin.staff*') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-600 hover:bg-gray-100' }}">Staff</a>
                  <a href="{{ route('admin.webinars.index') }}" class="block px-4 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('admin.webinars*') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-600 hover:bg-gray-100' }}">Webinars</a>
             <a href="{{ route('admin.notifications.broadcast') }}" class="block px-4 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('admin.notifications.broadcast') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-600 hover:bg-gray-100' }}">Broadcast</a>
             <a href="{{ route('admin.settings') }}" class="block px-4 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('admin.settings*') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-600 hover:bg-gray-100' }}">Settings</a>
             <a href="{{ route('admin.notifications') }}" class="block px-4 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('admin.notifications*') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-600 hover:bg-gray-100' }}">Notifications</a>
             <a href="{{ route('admin.chat.index') }}" class="block px-4 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('admin.chat*') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-600 hover:bg-gray-100' }}">Chat</a>
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

        (function() {
            const logoutUrl = '{{ route('admin.logout') }}';
            const inactivityTimeout = 60;

            let timer = setTimeout(autoLogout, inactivityTimeout * 1000);

            function autoLogout() {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = logoutUrl;
                form.innerHTML = '<input type="hidden" name="_token" value="{{ csrf_token() }}">';
                document.body.appendChild(form);
                form.submit();
            }

            function resetTimer() {
                clearTimeout(timer);
                timer = setTimeout(autoLogout, inactivityTimeout * 1000);
            }

            const events = ['mousemove', 'mousedown', 'keydown', 'scroll', 'click', 'touchstart'];
            events.forEach(function(event) {
                document.addEventListener(event, resetTimer, { passive: true });
            });
        })();

        // Notification bell functionality
        (function() {
            const bell = document.getElementById('notification-bell');
            const dropdown = document.getElementById('notification-dropdown');
            const badge = document.getElementById('notification-badge');
            const list = document.getElementById('notification-list');

            function loadNotifications() {
                fetch('{{ route('admin.notifications') }}', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (!data.notifications || data.notifications.length === 0) {
                            list.innerHTML = '<div class="px-4 py-6 text-center text-sm text-gray-500">No notifications yet</div>';
                            return;
                        }

                        list.innerHTML = data.notifications.slice(0, 5).map(notification => {
                            const typeColors = {
                                order: 'bg-blue-50 border-blue-200',
                                coaching: 'bg-green-50 border-green-200',
                                customer: 'bg-purple-50 border-purple-200',
                                payment: 'bg-emerald-50 border-emerald-200',
                                free_book: 'bg-amber-50 border-amber-200',
                                webinar: 'bg-indigo-50 border-indigo-200',
                                chat: 'bg-pink-50 border-pink-200',
                            };
                            const colorClass = typeColors[notification.type] || 'bg-gray-50 border-gray-200';

                            return `
                                <div class="mx-3 mb-2 p-3 rounded-lg border ${colorClass} ${!notification.is_read ? 'border-l-4' : ''} cursor-pointer" data-id="${notification.id}" data-read="${notification.is_read ? '1' : '0'}" data-link="${notification.link || ''}">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <h4 class="text-sm font-semibold text-gray-900">${notification.title}</h4>
                                            <p class="text-xs text-gray-600 mt-1">${notification.message}</p>
                                            <p class="text-xs text-gray-400 mt-1">${new Date(notification.created_at).toLocaleString()}</p>
                                        </div>
                                        ${!notification.is_read ? '<span class="w-2 h-2 bg-indigo-600 rounded-full mt-1 flex-shrink-0"></span>' : ''}
                                    </div>

                                </div>
                            `;
                        }).join('');

                        list.querySelectorAll('[data-id]').forEach(item => {
                            item.addEventListener('click', function(e) {
                                if (e.target.tagName === 'A' || e.target.closest('button')) return;
                                const id = this.dataset.id;
                                const isRead = this.dataset.read === '1';
                                const link = this.dataset.link;

                                if (!isRead) {
                                    fetch('{{ route('admin.notifications.toggleRead') }}', {
                                        method: 'POST',
                                        headers: {
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                            'X-Requested-With': 'XMLHttpRequest',
                                            'Content-Type': 'application/json',
                                        },
                                        body: JSON.stringify({ id }),
                                    }).then(() => {
                                        this.classList.remove('border-l-4');
                                        const dot = this.querySelector('.bg-indigo-600.rounded-full');
                                        if (dot) dot.remove();
                                        this.dataset.read = '1';
                                        updateBadge();
                                    });
                                }

                                if (link) {
                                    window.location.href = link;
                                }
                            });
                        });
                    })
                    .catch(error => {
                        console.error('Error loading notifications:', error);
                        list.innerHTML = '<div class="px-4 py-6 text-center text-sm text-red-500">Failed to load notifications</div>';
                    });
            }

            function updateBadge() {
                fetch('{{ route('admin.notifications.unreadCount') }}')
                    .then(response => response.json())
                    .then(data => {
                        if (data.unread_count > 0) {
                            badge.textContent = data.unread_count > 99 ? '99+' : data.unread_count;
                            badge.style.display = 'inline-flex';
                        } else {
                            badge.style.display = 'none';
                        }
                    });
            }

            bell.addEventListener('click', function(e) {
                e.stopPropagation();
                dropdown.classList.toggle('opacity-0');
                dropdown.classList.toggle('invisible');
                loadNotifications();
            });

            document.addEventListener('click', function(e) {
                if (!bell.contains(e.target) && !dropdown.contains(e.target)) {
                    dropdown.classList.add('opacity-0', 'invisible');
                }
            });

            updateBadge();
            setInterval(updateBadge, 30000);
        })();
    </script>
</header>
