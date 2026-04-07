<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Admin Dashboard - {{ config('app.name', 'Bookshop') }}</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <link rel="icon" href="/favicon.ico" sizes="any">
    </head>
    <body class="bg-gray-50 font-sans">
        <x-flash-message />
        
        <x-admin-navbar />

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Welcome Section with Wave -->
            <div class="relative bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl p-8 mb-8 text-white overflow-hidden">
                <h1 class="text-3xl font-bold mb-2">Welcome back, {{ auth()->user()->name }}! ⚙️</h1>
                <p class="text-white/90 relative z-30 mb-6">Manage your bookstore from this dashboard</p>
                <!-- Wave Shape -->
                <div class="absolute bottom-0 left-0 w-full h-12">
                    <svg class="absolute bottom-0 w-full h-full" viewBox="0 0 1440 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0 25C120 10 240 0 360 0C480 0 540 10 600 20C660 30 720 35 840 35C960 35 1080 20 1200 10C1320 5 1380 5 1440 10V50H0V25Z" fill="white"/>
                    </svg>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Total Books</p>
                            <p class="text-3xl font-bold mt-1 text-gray-900">{{ \App\Models\Book::count() }}</p>
                        </div>
                        <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center">
                            <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Total Customers</p>
                            <p class="text-3xl font-bold mt-1 text-gray-900">{{ \App\Models\User::where('is_admin', false)->count() }}</p>
                        </div>
                        <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center">
                            <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Total Orders</p>
                            <p class="text-3xl font-bold mt-1 text-gray-900">{{ \App\Models\Order::count() }}</p>
                        </div>
                        <div class="w-14 h-14 bg-purple-100 rounded-xl flex items-center justify-center">
                            <svg class="w-7 h-7 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Total Revenue</p>
                            <p class="text-3xl font-bold mt-1 text-gray-900">₵{{ number_format(\App\Models\Order::where('payment_status', 'paid')->sum('total_amount'), 2) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Coaching Statistics -->
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 mb-8">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Coaching</h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Total Bookings</p>
                            <p class="text-2xl font-bold mt-1 text-gray-900">{{ \App\Models\CoachingBooking::count() }}</p>
                        </div>
                        <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Paid Bookings</p>
                            <p class="text-2xl font-bold mt-1 text-gray-900">{{ \App\Models\CoachingBooking::where('payment_status', 'paid')->count() }}</p>
                        </div>
                        <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Pending Payment</p>
                            <p class="text-2xl font-bold mt-1 text-gray-900">{{ \App\Models\CoachingBooking::where('payment_status', 'pending')->count() }}</p>
                        </div>
                        <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>

                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Revenue</p>
                        <p class="text-2xl font-bold mt-1 text-gray-900">₵{{ number_format(\App\Models\CoachingBooking::where('payment_status', 'paid')->sum('amount'), 2) }}</p>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="grid grid-cols-2 md:grid-cols-3 gap-6 mb-8">
                <a href="{{ route('admin.books.create') }}" class="group bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-lg hover:border-indigo-200 transition-all">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-orange-100 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-7 h-7 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">Add New Book</h3>
                            <p class="text-sm text-gray-500">Upload a book</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.books') }}" class="group bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-lg hover:border-indigo-200 transition-all">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-indigo-100 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-7 h-7 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">Manage Books</h3>
                            <p class="text-sm text-gray-500">Edit or delete</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.customers') }}" class="group bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-lg hover:border-indigo-200 transition-all">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-purple-100 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-7 h-7 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">View Customers</h3>
                            <p class="text-sm text-gray-500">Customer list</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Recent Activity -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                    <h2 class="text-lg font-semibold text-gray-900">Recent Orders</h2>
                    <a href="{{ route('admin.orders') }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">View All</a>
                </div>
                @php
                    $recentOrders = \App\Models\Order::with('user')->latest()->take(10)->get();
                @endphp
                @if($recentOrders->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-100">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order #</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($recentOrders as $order)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="hover:text-indigo-600">
                                        {{ $order->order_number ?? 'ORD-' . $order->id }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $order->customer_name ?? $order->user->name ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    ₵{{ number_format($order->total_amount, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusColors = [
                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                            'confirmed' => 'bg-blue-100 text-blue-800',
                                            'processing' => 'bg-indigo-100 text-indigo-800',
                                            'shipped' => 'bg-purple-100 text-purple-800',
                                            'delivered' => 'bg-green-100 text-green-800',
                                            'cancelled' => 'bg-red-100 text-red-800',
                                        ];
                                        $color = $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <span class="px-2 py-1 text-xs font-medium rounded-full {{ $color }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $order->created_at->format('M d, Y') }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="p-6 text-center">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                    </div>
                    <p class="text-gray-500">No orders yet</p>
                </div>
                @endif
            </div>
        </main>

        <!-- Meeting Notification Container -->
        <div id="meeting-notification" class="fixed bottom-4 right-4 z-50 max-w-md hidden">
            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl shadow-2xl p-4">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="font-semibold">Upcoming Meeting<span id="meeting-count"></span>!</p>
                        <p id="meeting-customer-name" class="text-sm text-white/90"></p>
                        <p id="meeting-additional" class="text-xs text-white/70 mt-1"></p>
                        <p id="meeting-time" class="text-sm text-white/80 mt-1"></p>
                        <a id="meeting-link" href="#" target="_blank" class="inline-block mt-2 text-sm bg-white text-indigo-600 px-3 py-1 rounded-lg hover:bg-white/90">
                            Join Meeting →
                        </a>
                        <button id="send-reminder-btn" onclick="sendManualReminder()" class="mt-2 ml-2 text-xs bg-yellow-500 text-white px-2 py-1 rounded hover:bg-yellow-600">
                            🔔 Send Reminder
                        </button>
                    </div>
                    <button onclick="dismissMeetingNotification()" class="text-white/60 hover:text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <script>
            let lastMeetingId = null;
            let notificationTimeout = null;

            function checkUpcomingMeetings() {
                fetch('{{ route("admin.coachings.upcoming") }}')
                    .then(res => res.json())
                    .then(data => {
                        const container = document.getElementById('meeting-notification');
                        if (data.upcoming && data.upcoming.length > 0) {
                            // Show notification whenever there are upcoming meetings
                            showMeetingNotification(data.upcoming);
                        } else {
                            // Hide when no meetings coming up
                            container.classList.add('hidden');
                            lastMeetingId = null;
                        }
                    })
                    .catch(err => console.error('Error checking meetings:', err));
            }

            function showMeetingNotification(meetings) {
                const meeting = meetings[0]; // First meeting determines the time
                currentMeetingId = meeting.id;
                const container = document.getElementById('meeting-notification');
                
                // Show count if multiple meetings at same time
                if (meetings.length > 1) {
                    document.getElementById('meeting-count').textContent = ` (${meetings.length} people)`;
                    const names = meetings.map(m => m.name).join(', ');
                    document.getElementById('meeting-customer-name').textContent = names;
                    // Show remaining count if more than 2
                    if (meetings.length > 2) {
                        const remaining = meetings.length - 2;
                        document.getElementById('meeting-additional').textContent = `+${remaining} more`;
                    } else {
                        document.getElementById('meeting-additional').textContent = '';
                    }
                } else {
                    document.getElementById('meeting-count').textContent = '';
                    document.getElementById('meeting-customer-name').textContent = meeting.name;
                    document.getElementById('meeting-additional').textContent = '';
                }
                
                const minutesUntil = meeting.minutes_until;
                let timeText = '';
                if (minutesUntil <= 0) {
                    timeText = 'Now!';
                } else if (minutesUntil <= 30) {
                    timeText = `In ${minutesUntil} minute${minutesUntil !== 1 ? 's' : ''}`;
                }
                document.getElementById('meeting-time').textContent = timeText;
                
                if (meeting.meeting_link) {
                    document.getElementById('meeting-link').href = meeting.meeting_link;
                }
                
                container.classList.remove('hidden');
                
                // Play notification sound
                if ('speechSynthesis' in window) {
                    const utterance = new SpeechSynthesisUtterance(`Meeting with ${meeting.name} in ${minutesUntil} minutes`);
                    window.speechSynthesis.speak(utterance);
                }
                
                // Auto dismiss after 2 minutes
                if (notificationTimeout) clearTimeout(notificationTimeout);
                notificationTimeout = setTimeout(() => {
                    dismissMeetingNotification();
                }, 120000);
            }

            function dismissMeetingNotification() {
                document.getElementById('meeting-notification').classList.add('hidden');
            }

            function sendManualReminder() {
                const btn = document.getElementById('send-reminder-btn');
                const originalText = btn.textContent;
                btn.textContent = 'Sending...';
                btn.disabled = true;

                fetch('/admin/coachings/' + lastMeetingId + '/send-reminder', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        btn.textContent = '✓ Reminder Sent!';
                        setTimeout(() => {
                            btn.textContent = originalText;
                            btn.disabled = false;
                        }, 3000);
                    } else {
                        btn.textContent = 'Failed!';
                        setTimeout(() => {
                            btn.textContent = originalText;
                            btn.disabled = false;
                        }, 3000);
                    }
                })
                .catch(err => {
                    btn.textContent = 'Error!';
                    setTimeout(() => {
                        btn.textContent = originalText;
                        btn.disabled = false;
                    }, 3000);
                });
            }

            // Store current meeting ID globally
            let currentMeetingId = null;

            // Check immediately and then every minute
            checkUpcomingMeetings();
            setInterval(checkUpcomingMeetings, 60000);
        </script>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-200 mt-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <p class="text-center text-sm text-gray-500">&copy; {{ date('Y') }} Bookshop Admin. All rights reserved.</p>
            </div>
        </footer>
    </body>
</html>