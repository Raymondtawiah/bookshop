<aside class="sidebar" id="admin-sidebar">
    <div class="logo">
        <div class="logo-main">VISA</div>
        <div class="logo-sub">with <strong>Nathaniel</strong></div>
    </div>
    <nav class="nav">
        <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <span class="nav-icon">
                <svg viewBox="0 0 24 24">
                    <path d="M3 10.5L12 3l9 7.5"></path>
                    <path d="M5 9.5V21h14V9.5"></path>
                    <path d="M9 21v-6h6v6"></path>
                </svg>
            </span>
            Overview
        </a>
        <a href="{{ route('admin.webinars.index') }}" class="nav-item {{ request()->routeIs('admin.webinars*') ? 'active' : '' }}">
            <span class="nav-icon">
                <svg viewBox="0 0 24 24">
                    <rect x="3" y="5" width="18" height="16" rx="2"></rect>
                    <path d="M8 3v4"></path>
                    <path d="M16 3v4"></path>
                    <path d="M3 10h18"></path>
                </svg>
            </span>
            Webinars
        </a>
        <a href="{{ route('admin.coachings.index') }}" class="nav-item {{ request()->routeIs('admin.coachings*') ? 'active' : '' }}">
            <span class="nav-icon">
                <svg viewBox="0 0 24 24">
                    <circle cx="9" cy="8" r="3"></circle>
                    <circle cx="17" cy="9" r="2.5"></circle>
                    <path d="M3 20c0-3.5 2.5-5.5 6-5.5s6 2 6 5.5"></path>
                    <path d="M15 14c3 0 5 1.7 5.5 4.5"></path>
                </svg>
            </span>
            Coaching
        </a>
        <a href="{{ route('admin.books') }}" class="nav-item {{ request()->routeIs('admin.books*') ? 'active' : '' }}">
            <span class="nav-icon">
                <svg viewBox="0 0 24 24">
                    <path d="M4 4h7v16H4z"></path>
                    <path d="M13 4h7v16h-7z"></path>
                </svg>
            </span>
            Books
        </a>
        <a href="{{ route('home') }}" target="_blank" class="nav-item">
            <span class="nav-icon">
                <svg viewBox="0 0 24 24">
                    <rect x="4" y="4" width="16" height="16" rx="2"></rect>
                    <path d="M8 16L16 8"></path>
                    <path d="M10 8h6v6"></path>
                </svg>
            </span>
            Website
        </a>
        <a href="{{ route('admin.customers') }}" class="nav-item {{ request()->routeIs('admin.customers*') ? 'active' : '' }}">
            <span class="nav-icon">
                <svg viewBox="0 0 24 24">
                    <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                    <path d="M23 21v-2a4 4 0 00-3-3.87"></path>
                    <path d="M16 3.13a4 4 0 010 7.75"></path>
                </svg>
            </span>
            Customers
        </a>
        <a href="{{ route('admin.notifications.broadcast') }}" class="nav-item {{ request()->routeIs('admin.notifications.broadcast') ? 'active' : '' }}">
            <span class="nav-icon">
                <svg viewBox="0 0 24 24">
                    <path d="M22 17H6a2 2 0 01-2-2V5a2 2 0 012-2h16a2 2 0 012 2v10a2 2 0 01-2 2z"></path>
                    <path d="M2 17v2a2 2 0 002 2h16"></path>
                    <path d="M12 7v6"></path>
                </svg>
            </span>
            Broadcast
        </a>
        <a href="{{ route('admin.notifications') }}" class="nav-item {{ request()->routeIs('admin.notifications') ? 'active' : '' }}">
            <span class="nav-icon">
                <svg viewBox="0 0 24 24">
                    <path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                    <path d="M13.73 21a2 2 0 01-3.46 0"></path>
                </svg>
            </span>
            Notifications
            @php $unreadCount = \App\Models\AdminNotification::getUnreadCount(); @endphp
            @if($unreadCount > 0)
                <span class="notification-badge">{{ $unreadCount }}</span>
            @endif
        </a>
    </nav>

    <form method="POST" action="{{ route('admin.logout') }}" class="mt-auto" onsubmit="return confirm('Are you sure you want to logout?')">
        @csrf
        <button type="submit" class="nav-item" style="border: none; background: none; cursor: pointer; width: 100%;">
            <span class="nav-icon">
                <svg viewBox="0 0 24 24">
                    <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"></path>
                    <polyline points="16,17 21,12 16,7"></polyline>
                    <line x1="21" y1="12" x2="9" y2="12"></line>
                </svg>
            </span>
            Logout
        </button>
    </form>
</aside>
