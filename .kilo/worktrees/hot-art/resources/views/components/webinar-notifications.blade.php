@php
    $notifications = $webinar->notifications()->active()->get();
@endphp

@if($notifications->count() > 0)
    <div class="space-y-4 mb-6">
        @foreach($notifications as $notification)
            <div class="rounded-xl p-4 border @if($notification->type === 'urgent') bg-red-50 border-red-200 @elseif($notification->type === 'schedule') bg-blue-50 border-blue-200 @elseif($notification->type === 'zoom_update') bg-purple-50 border-purple-200 @else bg-gray-50 border-gray-200 @endif">
                <div class="flex items-start gap-3">
                    <!-- Icon based on type -->
                    <div class="flex-shrink-0 w-6 h-6 rounded-full flex items-center justify-center @if($notification->type === 'urgent') bg-red-100 @elseif($notification->type === 'schedule') bg-blue-100 @elseif($notification->type === 'zoom_update') bg-purple-100 @else bg-gray-100 @endif">
                        @if($notification->type === 'urgent')
                            <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 15.5c-.77.833.192 2.5 1.732 2.5z"/>
                            </svg>
                        @elseif($notification->type === 'schedule')
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        @elseif($notification->type === 'zoom_update')
                            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                        @else
                            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        @endif
                    </div>
                    
                    <!-- Content -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between mb-1">
                            <h3 class="text-sm font-semibold @if($notification->type === 'urgent') text-red-800 @elseif($notification->type === 'schedule') text-blue-800 @elseif($notification->type === 'zoom_update') text-purple-800 @else text-gray-800 @endif">
                                {{ $notification->title }}
                            </h3>
                            <span class="text-xs @if($notification->type === 'urgent') text-red-600 @elseif($notification->type === 'schedule') text-blue-600 @elseif($notification->type === 'zoom_update') text-purple-600 @else text-gray-600 @endif">
                                {{ $notification->created_at->format('M d, g:i A') }}
                            </span>
                        </div>
                        <div class="text-sm @if($notification->type === 'urgent') text-red-700 @elseif($notification->type === 'schedule') text-blue-700 @elseif($notification->type === 'zoom_update') text-purple-700 @else text-gray-700 @endif whitespace-pre-line">
                            {{ $notification->message }}
                        </div>
                        @if($notification->expires_at)
                            <div class="text-xs @if($notification->type === 'urgent') text-red-600 @elseif($notification->type === 'schedule') text-blue-600 @elseif($notification->type === 'zoom_update') text-purple-600 @else text-gray-600 @endif mt-2">
                                Expires: {{ $notification->expires_at->format('M d, Y g:i A') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
