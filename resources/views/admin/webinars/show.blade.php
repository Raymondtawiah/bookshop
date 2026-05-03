@extends('layouts.admin')

@section('title', 'Webinar Details - ' . $webinar->title)

@section('content')
        <div class="max-w-5xl mx-auto">
            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $webinar->title }}</h1>
                    <p class="text-gray-500">Webinar management and attendee list</p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('admin.webinars.notifications.create', $webinar->id) }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        Send Notification
                    </a>
                    <a href="{{ route('admin.webinars.index') }}" class="px-4 py-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Back
                    </a>
                </div>
            </div>

            <!-- Webinar Info Cards -->
            <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6">
                    <div class="text-xs sm:text-sm text-gray-500 mb-1">Total Registrations</div>
                    <div class="text-2xl sm:text-3xl font-bold text-gray-900">{{ $webinar->total_registrations }}</div>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6">
                    <div class="text-xs sm:text-sm text-gray-500 mb-1">Paid Attendees</div>
                    <div class="text-2xl sm:text-3xl font-bold text-green-600">{{ $webinar->total_paid_registrations }}</div>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6">
                    <div class="text-xs sm:text-sm text-gray-500 mb-1">Pending Payment</div>
                    <div class="text-2xl sm:text-3xl font-bold text-yellow-600">{{ $webinar->registrations()->where('payment_status', 'pending')->count() }}</div>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6">
                    <div class="text-xs sm:text-sm text-gray-500 mb-1">Webinar Price</div>
                    <div class="text-2xl sm:text-3xl font-bold text-indigo-600">₵{{ number_format($webinar->price, 2) }}</div>
                </div>
            </div>

            <!-- Webinar Details -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Webinar Details</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm text-gray-500">Description</label>
                        <p class="text-gray-900">{{ $webinar->description ?? 'No description provided' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-500">Status</label>
                        <span class="inline-block px-2 py-1 rounded text-sm mt-1
                            @if($webinar->status === 'active') bg-green-100 text-green-700
                            @elseif($webinar->status === 'scheduled') bg-blue-100 text-blue-700
                            @elseif($webinar->status === 'completed') bg-gray-100 text-gray-700
                            @else bg-red-100 text-red-700 @endif">
                            {{ ucfirst($webinar->status) }}
                        </span>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-500">Scheduled Date & Time</label>
                        <p class="text-gray-900">
                            @if($webinar->scheduled_at)
                                {{ $webinar->scheduled_at->format('F j, Y \a\t g:i A') }}
                            @else
                                <span class="text-gray-400">TBA</span>
                            @endif
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-500">Duration</label>
                        <p class="text-gray-900">{{ $webinar->duration_minutes ? $webinar->duration_minutes . ' minutes' : 'Not specified' }}</p>
                    </div>
                </div>
            </div>

            <!-- Notification History -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Notification History</h3>
                    <a href="{{ route('admin.webinars.notifications.create', $webinar->id) }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors text-sm font-medium flex items-center justify-center gap-2 w-full sm:w-auto">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Send New Notification
                    </a>
                </div>
                
                @php
                    $notifications = $webinar->notifications()->with('creator')->latest()->get();
                @endphp
                
                @if($notifications->count() > 0)
                    <div class="space-y-3">
                        @foreach($notifications as $notification)
                            <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                                <div class="flex flex-col lg:flex-row lg:items-start justify-between gap-4">
                                    <div class="flex-1">
                                        <div class="flex flex-col sm:flex-row sm:items-center gap-2 mb-2">
                                            <span class="px-2 py-1 text-xs rounded-full flex-shrink-0
                                                @if($notification->type === 'urgent')
                                                    bg-red-100 text-red-700
                                                @elseif($notification->type === 'schedule')
                                                    bg-blue-100 text-blue-700
                                                @elseif($notification->type === 'zoom_update')
                                                    bg-purple-100 text-purple-700
                                                @else
                                                    bg-gray-100 text-gray-700
                                                @endif">
                                                {{ ucfirst($notification->type) }}
                                            </span>
                                            <h4 class="font-medium text-gray-900 text-sm sm:text-base">{{ $notification->title }}</h4>
                                        </div>
                                        
                                        <div class="text-sm text-gray-600 mb-2 whitespace-pre-line">{{ Str::limit($notification->message, 150) }}</div>
                                        
                                        <div class="flex flex-wrap items-center gap-2 sm:gap-4 text-xs text-gray-500">
                                            <span class="flex items-center">
                                                <svg class="w-3 h-3 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                <span class="truncate">{{ $notification->created_at->format('M d, Y g:i A') }}</span>
                                            </span>
                                            <span class="flex items-center">
                                                <svg class="w-3 h-3 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                </svg>
                                                <span class="truncate">{{ $notification->creator->name }}</span>
                                            </span>
                                            <span class="flex items-center">
                                                <svg class="w-3 h-3 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                                </svg>
                                                <span class="truncate">{{ $webinar->total_paid_registrations }} users</span>
                                            </span>
                                            @if($notification->expires_at)
                                                <span class="flex items-center">
                                                    <svg class="w-3 h-3 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    <span class="truncate">{{ $notification->expires_at->format('M d, g:i A') }}</span>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2 flex-wrap lg:flex-nowrap">
                                        @if($notification->is_active && (!$notification->expires_at || $notification->expires_at > now()))
                                            <span class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full">Active</span>
                                        @else
                                            <span class="px-2 py-1 bg-gray-100 text-gray-700 text-xs rounded-full">Expired</span>
                                        @endif
                                        
                                        <!-- Show users who haven't received this notification -->
                                        @php
                                            $paidUsersCount = $webinar->registrations()->where('payment_status', 'paid')->count();
                                            $recipientsCount = $notification->recipients()->count();
                                            $missingUsersCount = $paidUsersCount - $recipientsCount;
                                        @endphp
                                        
                                        @if($missingUsersCount > 0)
                                            <a href="{{ route('admin.webinars.admin.show', [$webinar->id, 'show_missing' => $notification->id]) }}" class="px-2 py-1 bg-amber-100 text-amber-700 text-xs rounded-full hover:bg-amber-200 transition-colors">
                                                ⚠️ {{ $missingUsersCount }} missed
                                            </a>
                                        @endif
                                        
                                        @if(request()->get('show_full') == $notification->id)
                                            <a href="{{ route('admin.webinars.admin.show', $webinar->id) }}" class="text-xs text-indigo-600 hover:text-indigo-700 font-medium whitespace-nowrap">
                                                Hide
                                            </a>
                                        @else
                                            <a href="{{ route('admin.webinars.admin.show', [$webinar->id, 'show_full' => $notification->id]) }}" class="text-xs text-indigo-600 hover:text-indigo-700 font-medium whitespace-nowrap">
                                                Show
                                            </a>
                                        @endif
                                    </div>
                                </div>
                                
                                @if(request()->get('show_full') == $notification->id)
                                    <div class="mt-3 p-3 bg-gray-50 rounded border border-gray-200">
                                        <div class="text-sm text-gray-700 whitespace-pre-line">{{ $notification->message }}</div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <p class="text-gray-500 text-sm mb-3">No notifications sent yet</p>
                        <a href="{{ route('admin.webinars.notifications.create', $webinar->id) }}" class="text-indigo-600 hover:text-indigo-700 text-sm font-medium">
                            Send your first notification →
                        </a>
                    </div>
                @endif

            <!-- Missing Users Section -->
            @if(request()->get('show_missing'))
                @php
                    $notificationToShow = \App\Models\WebinarNotification::find(request()->get('show_missing'));
                    $missingUsers = [];
                    if ($notificationToShow) {
                        $receivedUserIds = $notificationToShow->recipients()->pluck('user_id')->toArray();
                        $missingUsers = $webinar->registrations()
                            ->with('user')
                            ->where('payment_status', 'paid')
                            ->whereNotIn('user_id', $receivedUserIds)
                            ->get();
                    }
                @endphp
                
                @if($notificationToShow && $missingUsers->count() > 0)
                    <div class="bg-amber-50 border border-amber-200 rounded-xl p-6 mb-8">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h3 class="text-lg font-semibold text-amber-900 mb-1">
                                    Users Who Missed: "{{ $notificationToShow->title }}"
                                </h3>
                                <p class="text-sm text-amber-700">
                                    {{ $missingUsers->count() }} paid users haven't received this notification
                                </p>
                            </div>
                            <a href="{{ route('admin.webinars.admin.show', $webinar->id) }}" class="text-amber-600 hover:text-amber-700 text-sm font-medium">
                                ✕ Close
                            </a>
                        </div>
                        
                        <form method="POST" action="{{ route('admin.webinars.notifications.sendToUsers', [$webinar, $notificationToShow]) }}" class="mb-4">
                            @csrf
                            <div class="bg-white rounded-lg p-4 border border-amber-200">
                                <div class="flex items-center justify-between mb-3">
                                    <label class="text-sm font-medium text-gray-700">
                                        <input type="checkbox" id="selectAll" onchange="toggleAllUsers()" class="mr-2">
                                        Select All Users ({{ $missingUsers->count() }})
                                    </label>
                                    <button type="submit" class="px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors text-sm font-medium">
                                        Send to Selected Users
                                    </button>
                                </div>
                                
                                <div class="space-y-2 max-h-60 overflow-y-auto">
                                    @foreach($missingUsers as $registration)
                                        <div class="flex items-center justify-between p-2 bg-gray-50 rounded">
                                            <label class="flex items-center cursor-pointer flex-1">
                                                <input type="checkbox" name="user_ids[]" value="{{ $registration->user->id }}" 
                                                       class="user-checkbox mr-3" data-user-id="{{ $registration->user->id }}">
                                                <div>
                                                    <div class="font-medium text-gray-900">{{ $registration->user->name }}</div>
                                                    <div class="text-sm text-gray-600">{{ $registration->user->email }}</div>
                                                </div>
                                            </label>
                                            <div class="text-xs text-gray-500">
                                                Joined: {{ $registration->created_at->format('M d, Y') }}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </form>
                    </div>
                @endif
            @endif
            </div>

            <!-- Quick Filter Buttons -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
                <div class="mb-4">
                    <h3 class="text-sm font-semibold text-gray-900 mb-1">Quick Filters</h3>
                    <p class="text-xs text-gray-500">Click to instantly filter attendees</p>
                </div>
                
                <div class="flex flex-col sm:flex-row flex-wrap gap-2 sm:gap-3 mb-6">
                    <!-- Payment Status Filters -->
                    <a href="{{ route('admin.webinars.admin.show', $webinar->id) }}" 
                       class="px-3 py-2 sm:px-4 sm:py-2 rounded-lg text-xs sm:text-sm font-medium transition-all text-center
                           @if(!request()->has('payment_status') && !request()->has('registration_status'))
                               bg-indigo-600 text-white
                           @else
                               bg-gray-100 text-gray-700 hover:bg-gray-200
                           @endif">
                        📊 All ({{ $webinar->total_registrations }})
                    </a>
                    <a href="{{ route('admin.webinars.admin.show', [$webinar->id, 'payment_status' => 'paid']) }}" 
                       class="px-3 py-2 sm:px-4 sm:py-2 rounded-lg text-xs sm:text-sm font-medium transition-all text-center
                           @if(request('payment_status') === 'paid')
                               bg-green-600 text-white
                           @else
                               bg-gray-100 text-gray-700 hover:bg-gray-200
                           @endif">
                        ✅ Paid ({{ $webinar->total_paid_registrations }})
                    </a>
                    <a href="{{ route('admin.webinars.admin.show', [$webinar->id, 'payment_status' => 'pending']) }}" 
                       class="px-3 py-2 sm:px-4 sm:py-2 rounded-lg text-xs sm:text-sm font-medium transition-all text-center
                           @if(request('payment_status') === 'pending')
                               bg-yellow-600 text-white
                           @else
                               bg-gray-100 text-gray-700 hover:bg-gray-200
                           @endif">
                        ⏳ Pending ({{ $webinar->registrations()->where('payment_status', 'pending')->count() }})
                    </a>
                </div>

                <!-- Advanced Filters -->
                <div class="border-t border-gray-100 pt-4">
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="text-xs font-semibold text-gray-700 uppercase tracking-wider">Advanced Filters</h4>
                        @if(request()->has('payment_status') || request()->has('registration_status'))
                            <a href="{{ route('admin.webinars.admin.show', $webinar->id) }}" 
                               class="text-xs text-indigo-600 hover:text-indigo-700 font-medium">
                                Clear All Filters
                            </a>
                        @endif
                    </div>
                    
                    <div class="flex flex-col sm:flex-row flex-wrap gap-2">
                        <!-- Joined Status -->
                        <a href="{{ route('admin.webinars.admin.show', [$webinar->id, 'joined' => 'yes']) }}" 
                           class="px-3 py-2 rounded-lg text-xs font-medium transition-all text-center
                               @if(request('joined') === 'yes')
                                   bg-blue-600 text-white
                               @else
                                   bg-gray-50 text-gray-600 hover:bg-gray-100 border border-gray-200
                               @endif">
                            🎯 Joined ({{ $webinar->registrations()->whereNotNull('joined_at')->count() }})
                        </a>
                        <a href="{{ route('admin.webinars.admin.show', [$webinar->id, 'joined' => 'no']) }}" 
                           class="px-3 py-2 rounded-lg text-xs font-medium transition-all text-center
                               @if(request('joined') === 'no')
                                   bg-gray-600 text-white
                               @else
                                   bg-gray-50 text-gray-600 hover:bg-gray-100 border border-gray-200
                               @endif">
                            ⭕ Not Joined ({{ $webinar->registrations()->whereNull('joined_at')->count() }})
                        </a>
                        
                        <!-- Registration Status -->
                        <a href="{{ route('admin.webinars.admin.show', [$webinar->id, 'registration_status' => 'registered']) }}" 
                           class="px-3 py-2 rounded-lg text-xs font-medium transition-all text-center
                               @if(request('registration_status') === 'registered')
                                   bg-purple-600 text-white
                               @else
                               bg-gray-50 text-gray-600 hover:bg-gray-100 border border-gray-200
                               @endif">
                            📝 Registered Only
                        </a>
                    </div>
                </div>

                <!-- Current Filter Status -->
                @if(request()->has('payment_status') || request()->has('registration_status') || request()->has('joined'))
                    <div class="mt-4 p-3 bg-indigo-50 border border-indigo-200 rounded-lg">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.707A1 1 0 013 7V4z"/>
                            </svg>
                            <span class="text-sm text-indigo-700 font-medium">
                                Active Filters:
                                @if(request('payment_status')) 
                                    <span class="bg-indigo-100 text-indigo-800 px-2 py-0.5 rounded text-xs">
                                        Payment: {{ ucfirst(request('payment_status')) }}
                                    </span>
                                @endif
                                @if(request('registration_status'))
                                    <span class="bg-indigo-100 text-indigo-800 px-2 py-0.5 rounded text-xs">
                                        Status: {{ ucfirst(request('registration_status')) }}
                                    </span>
                                @endif
                                @if(request('joined'))
                                    <span class="bg-indigo-100 text-indigo-800 px-2 py-0.5 rounded text-xs">
                                        Joined: {{ request('joined') === 'yes' ? 'Yes' : 'No' }}
                                    </span>
                                @endif
                            </span>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Attendees Table -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                @if($registrations->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[1200px]">
                            <thead class="bg-gray-50 border-b border-gray-100">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-900 uppercase tracking-wider whitespace-nowrap">User Name</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-900 uppercase tracking-wider whitespace-nowrap">Email</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-900 uppercase tracking-wider whitespace-nowrap">Phone</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-900 uppercase tracking-wider whitespace-nowrap">Registration Status</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-900 uppercase tracking-wider whitespace-nowrap">Payment Status</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-900 uppercase tracking-wider whitespace-nowrap">Payment Reference</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-900 uppercase tracking-wider whitespace-nowrap">Joined Status</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-900 uppercase tracking-wider whitespace-nowrap">Amount Paid</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-900 uppercase tracking-wider whitespace-nowrap">Paid At</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($registrations as $registration)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-4 py-3">
                                            @if($registration->user)
                                                <div class="font-medium text-gray-900 text-sm break-words">{{ $registration->user->name }}</div>
                                                <div class="text-xs text-gray-500 break-words">{{ $registration->user->email }}</div>
                                            @else
                                                <div class="font-medium text-gray-900 text-sm">User not found</div>
                                                <div class="text-xs text-gray-500">-</div>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-gray-600 text-sm break-words">
                                            @if($registration->user)
                                                {{ $registration->user->email }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-gray-600 text-sm break-words">
                                            @if($registration->user && $registration->user->phone)
                                                {{ $registration->user->phone }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="px-2 py-1 text-xs rounded-full whitespace-nowrap
                                                @if($registration->registration_status === 'registered')
                                                    bg-green-100 text-green-700
                                                @elseif($registration->registration_status === 'cancelled')
                                                    bg-red-100 text-red-700
                                                @else
                                                    bg-gray-100 text-gray-700
                                                @endif">
                                                {{ ucfirst($registration->registration_status) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="px-2 py-1 text-xs rounded-full whitespace-nowrap
                                                @if($registration->payment_status === 'paid')
                                                    bg-green-100 text-green-700
                                                @elseif($registration->payment_status === 'pending')
                                                    bg-yellow-100 text-yellow-700
                                                @else
                                                    bg-red-100 text-red-700
                                                @endif">
                                                {{ ucfirst($registration->payment_status) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-gray-600 font-mono text-xs break-all">
                                            {{ $registration->transaction_reference ?? '-' }}
                                        </td>
                                        <td class="px-4 py-3">
                                            @if($registration->joined_at)
                                                <span class="px-2 py-1 text-xs bg-blue-100 text-blue-700 rounded-full whitespace-nowrap">
                                                    Joined
                                                </span>
                                            @else
                                                <span class="px-2 py-1 text-xs bg-gray-100 text-gray-700 rounded-full whitespace-nowrap">
                                                    Not Joined
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 font-medium text-gray-900 text-sm whitespace-nowrap">
                                            ₵{{ number_format($registration->amount_paid ?? 0, 2) }}
                                        </td>
                                        <td class="px-4 py-3 text-gray-600 text-xs whitespace-nowrap">
                                            {{ $registration->paid_at ? $registration->paid_at->format('M d, Y h:i A') : '-' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="px-6 py-4 border-t border-gray-100">
                        {{ $registrations->appends(request()->query())->links() }}
                    </div>
                @else
                    <div class="p-12 text-center">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <p class="text-gray-500 mb-4">No attendees registered yet.</p>
                    </div>
                @endif
            </div>
        </div>
@endsection

<script>
function toggleAllUsers() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.user-checkbox');
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
}

// Update select all checkbox when individual checkboxes change
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('.user-checkbox');
    const selectAll = document.getElementById('selectAll');
    
    if (checkboxes.length > 0 && selectAll) {
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const allChecked = Array.from(checkboxes).every(cb => cb.checked);
                const someChecked = Array.from(checkboxes).some(cb => cb.checked);
                
                selectAll.checked = allChecked;
                selectAll.indeterminate = someChecked && !allChecked;
            });
        });
    }
});
</script>
