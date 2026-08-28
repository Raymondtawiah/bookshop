@extends('layouts.admin')

@section('title', 'Notifications')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Notifications</h1>
            <p class="text-gray-500 text-sm">Stay updated with orders, bookings, and registrations</p>
        </div>
        <button id="mark-all-read" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors text-sm font-medium">
            Mark all as read
        </button>
    </div>

    <div id="notifications-list" class="space-y-3">
        @php
            $chatNotifications = \App\Models\ChatMessage::where('status', 'unread')
                ->whereIn('sender_type', ['customer', 'guest'])
                ->latest()
                ->limit(10)
                ->get();
        @endphp

        @if($chatNotifications->count() > 0)
            @foreach($chatNotifications as $chat)
                <div class="p-4 rounded-xl border bg-indigo-50 border-indigo-200 hover:shadow-md transition-shadow cursor-pointer" data-chat-id="{{ $chat->id }}">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <h3 class="text-sm font-semibold text-gray-900">New Chat Message</h3>
                            <p class="text-sm text-gray-600 mt-1">{{ $chat->message }}</p>
                            <p class="text-xs text-gray-400 mt-2">{{ $chat->created_at->format('M d, Y g:i A') }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif

        @if($notifications->count() > 0)
            @foreach($notifications as $notification)
                @php
                    $typeColors = [
                        'order' => 'bg-blue-50 border-blue-200',
                        'coaching' => 'bg-green-50 border-green-200',
                        'customer' => 'bg-purple-50 border-purple-200',
                        'payment' => 'bg-emerald-50 border-emerald-200',
                        'free_book' => 'bg-amber-50 border-amber-200',
                        'webinar' => 'bg-indigo-50 border-indigo-200',
                    ];
                    $colorClass = $typeColors[$notification->type] ?? 'bg-gray-50 border-gray-200';
                    $unreadClass = !$notification->is_read ? 'border-l-4' : '';
                @endphp
                <div class="p-4 rounded-xl border {{ $colorClass }} {{ $unreadClass }} hover:shadow-md transition-shadow cursor-pointer" data-id="{{ $notification->id }}" data-read="{{ $notification->is_read ? '1' : '0' }}" data-link="{{ $notification->link ?? '' }}">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <h3 class="text-sm font-semibold text-gray-900">{{ $notification->title }}</h3>
                            <p class="text-sm text-gray-600 mt-1">{{ $notification->message }}</p>
                            <p class="text-xs text-gray-400 mt-2">{{ $notification->created_at->format('M d, Y g:i A') }}</p>
                        </div>
                        <div class="flex items-center gap-2 ml-3">
                            <button class="toggle-read p-2 rounded-lg hover:bg-gray-100 transition-colors cursor-pointer" data-id="{{ $notification->id }}" title="{{ $notification->is_read ? 'Mark as unread' : 'Mark as read' }}">
                                @if($notification->is_read)
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                @else
                                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                @endif
                            </button>
                            @if($notification->link)
                                <a href="{{ $notification->link }}" class="p-2 rounded-lg hover:bg-gray-100 transition-colors cursor-pointer" title="View details">
                                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                    </svg>
                                </a>
                            @endif
                            <button class="delete-notification p-2 rounded-lg hover:bg-red-50 transition-colors cursor-pointer" data-id="{{ $notification->id }}" title="Delete notification">
                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            @if($chatNotifications->count() === 0)
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                    </div>
                    <p class="text-gray-500">No notifications yet</p>
                </div>
            @endif
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const markAllReadBtn = document.getElementById('mark-all-read');
    const notificationsList = document.getElementById('notifications-list');

    function updateUnreadCount() {
        fetch('{{ route('admin.notifications.unreadCount') }}')
            .then(response => response.json())
            .then(data => {
                const badge = document.getElementById('notification-badge');
                if (badge) {
                    badge.textContent = data.unread_count;
                    badge.style.display = data.unread_count > 0 ? 'inline-flex' : 'none';
                }
            });
    }

    markAllReadBtn.addEventListener('click', function() {
        fetch('{{ route('admin.notifications.markAllRead') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        });
    });

    document.querySelectorAll('.toggle-read').forEach(button => {
        button.addEventListener('click', function(e) {
            e.stopPropagation();
            const id = this.dataset.id;
            const card = this.closest('[data-id]');

            fetch('{{ route('admin.notifications.toggleRead') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ id }),
            }).then(() => {
                const isRead = card.dataset.read === '1';
                if (isRead) {
                    card.dataset.read = '0';
                    card.classList.add('border-l-4');
                    this.innerHTML = `<svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>`;
                    this.title = 'Mark as read';
                } else {
                    card.dataset.read = '1';
                    card.classList.remove('border-l-4');
                    this.innerHTML = `<svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>`;
                    this.title = 'Mark as unread';
                }
                updateUnreadCount();
            });
        });
    });

    document.querySelectorAll('.delete-notification').forEach(button => {
        button.addEventListener('click', function(e) {
            e.stopPropagation();
            const id = this.dataset.id;
            const card = this.closest('[data-id]');

            if (!confirm('Are you sure you want to delete this notification?')) {
                return;
            }

            fetch('{{ route('admin.notifications.delete') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ id }),
            }).then(() => {
                card.remove();
                updateUnreadCount();
            });
        });
    });

    document.querySelectorAll('[data-id]').forEach(item => {
        item.addEventListener('click', function(e) {
            if (e.target.tagName === 'A' || e.target.closest('button')) return;
            const link = this.dataset.link;
            if (link) {
                window.location.href = link;
            }
        });
    });

    document.querySelectorAll('[data-chat-id]').forEach(item => {
        item.addEventListener('click', function(e) {
            if (e.target.closest('button')) return;

            const chatId = this.dataset.chatId;

            fetch('{{ route('admin.chat.read') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json',
                },
            }).then(() => {
                this.style.opacity = '0.5';
                this.style.pointerEvents = 'none';
                updateUnreadCount();
                setTimeout(() => {
                    window.location.href = '{{ route('admin.chat.index') }}';
                }, 500);
            });
        });
    });
});
</script>
@endsection