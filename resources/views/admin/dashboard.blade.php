@extends('layouts.admin')

@section('title', 'Overview - Visa with Nathaniel')

@section('content')
    <div class="content">
        <!-- =================================
             PAGE HEADER
        ================================== -->
        <div class="page-header">
            <div>
                <h1 class="page-title">Overview</h1>
                <p class="page-subtitle">Your business at a glance</p>
            </div>
        </div>

        <!-- =================================
             STATISTICS
        ================================== -->
        <button type="button" id="stats-toggle" class="filter-toggle" aria-label="Toggle statistics" onclick="toggleFilters('stats-body')">
            <span style="font-size:18px;font-weight:bold;line-height:1;">&lt;</span>
        </button>
        <div class="stats-grid" id="stats-body">
            <div class="stat-card">
                <div class="stat-icon">
                    <svg viewBox="0 0 24 24">
                        <circle cx="9" cy="8" r="3"></circle>
                        <circle cx="17" cy="9" r="2.5"></circle>
                        <path d="M3 20c0-3.5 2.5-5.5 6-5.5s6 2 6 5.5"></path>
                        <path d="M15 14c3 0 5 1.7 5.5 4.5"></path>
                    </svg>
                </div>
                <div>
                    <div class="stat-label">Webinar registrations</div>
                    <div class="stat-value">{{ \App\Models\WebinarRegistration::count() }}</div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <svg viewBox="0 0 24 24">
                        <rect x="3" y="5" width="18" height="16" rx="2"></rect>
                        <path d="M8 3v4"></path>
                        <path d="M16 3v4"></path>
                        <path d="M3 10h18"></path>
                    </svg>
                </div>
                <div>
                    <div class="stat-label">Coaching bookings</div>
                    <div class="stat-value">{{ \App\Models\CoachingBooking::count() }}</div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <svg viewBox="0 0 24 24">
                        <path d="M4 4h7v16H4z"></path>
                        <path d="M13 4h7v16h-7z"></path>
                    </svg>
                </div>
                <div>
                    <div class="stat-label">Orders</div>
                    <div class="stat-value">{{ \App\Models\Order::where('payment_status', 'paid')->count() }}</div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <svg viewBox="0 0 24 24">
                        <path d="M4 20V12"></path>
                        <path d="M10 20V7"></path>
                        <path d="M16 20V10"></path>
                        <path d="M22 20V4"></path>
                    </svg>
                </div>
                <div>
                    <div class="stat-label">Paid revenue</div>
                    <div class="stat-value">${{ number_format(\App\Models\Order::where('payment_status', 'paid')->sum('total_amount'), 2) }}</div>
                </div>
            </div>
        </div>

        <!-- =================================
             WEEKEND + ATTENTION
        ================================== -->
        <div class="two-column">
            <!-- THIS WEEKEND -->
            <div class="panel">
                <div class="panel-header">
                    <h2 class="panel-title">This weekend</h2>
                </div>
                <div class="weekend-body">
                    @php
                        $upcoming = \App\Models\CoachingBooking::where('interview_date', '>=', now()->toDateString())
                            ->where('interview_date', '<=', now()->addDays(2)->toDateString())
                            ->orderBy('interview_date')
                            ->orderBy('interview_time')
                            ->limit(3)
                            ->get();
                    @endphp

                    @forelse($upcoming as $booking)
                        <div class="event">
                            <div>
                                <div class="event-time">{{ $booking->interview_date->format('D · g:i A') }}</div>
                                <div class="event-date">{{ $booking->interview_date->format('M d, Y') }}</div>
                            </div>
                            <div class="event-name">{{ $booking->name }}</div>
                            <div class="event-right">
                                @if($booking->payment_status === 'paid')
                                    <span class="confirmed">Confirmed</span>
                                @else
                                    <span class="text-yellow-600">Pending</span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="event">
                            <div class="event-name" style="grid-column: 1 / -1; text-align: center; color: #737d8e;">
                                No upcoming sessions this weekend
                            </div>
                        </div>
                    @endforelse

                    <div class="weekend-note">
                        <svg viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="9"></circle>
                            <path d="M12 7v5l3 2"></path>
                        </svg>
                        Times shown in your account time zone (GMT+0).
                    </div>
                </div>
            </div>

            <!-- NEEDS ATTENTION -->
            <div class="panel">
                <div class="panel-header">
                    <h2 class="panel-title">Needs attention</h2>
                </div>
                @php
                    $pendingPayments = \App\Models\Order::where('payment_status', 'pending')->count();
                    $pendingCoachings = \App\Models\CoachingBooking::where('payment_status', 'pending')->count();
                    $missingLinks = \App\Models\CoachingBooking::where('status', '!=', 'cancelled')->whereNull('meeting_link')->orWhere('meeting_link', '')->count();
                @endphp

                @if($pendingPayments > 0 || $pendingCoachings > 0 || $missingLinks > 0)
                    @if($pendingPayments > 0 || $pendingCoachings > 0)
                        <div class="attention-item">
                            <div class="attention-icon red">
                                <svg viewBox="0 0 24 24">
                                    <rect x="3" y="5" width="18" height="14" rx="2"></rect>
                                    <path d="M3 10h18"></path>
                                </svg>
                            </div>
                            <div class="attention-text">
                                <div class="attention-title">{{ $pendingPayments + $pendingCoachings }} payment{{ $pendingPayments + $pendingCoachings > 1 ? 's' : '' }} pending</div>
                                <div class="attention-subtitle">Complete payment review</div>
                            </div>
                            <svg class="attention-arrow" viewBox="0 0 24 24">
                                <path d="M9 6l6 6-6 6"></path>
                            </svg>
                        </div>
                    @endif

                    @if($missingLinks > 0)
                        <div class="attention-item">
                            <div class="attention-icon red">
                                <svg viewBox="0 0 24 24">
                                    <path d="M10 13a5 5 0 0 0 7.1.1l2-2a5 5 0 0 0-7.1-7.1l-1.2 1.2"></path>
                                    <path d="M14 11a5 5 0 0 0-7.1-.1l-2 2a5 5 0 0 0 7.1 7.1l1.2-1.2"></path>
                                </svg>
                            </div>
                            <div class="attention-text">
                                <div class="attention-title">Next webinar: joining link missing</div>
                                <div class="attention-subtitle">Add a joining link before the event</div>
                            </div>
                            <svg class="attention-arrow" viewBox="0 0 24 24">
                                <path d="M9 6l6 6-6 6"></path>
                            </svg>
                        </div>
                    @endif
                @else
                    <div class="attention-item">
                        <div class="attention-text" style="text-align: center; color: #737d8e; width: 100%;">
                            All caught up!
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- =================================
             QUICK ACTIONS + ACTIVITY
        ================================== -->
        <div class="bottom-grid">
            <!-- QUICK ACTIONS -->
            <div class="panel quick-actions">
                <div class="panel-header">
                    <h2 class="panel-title">Quick actions</h2>
                </div>
                <div class="quick-body">
                    <div class="quick-buttons">
                        <a href="{{ route('admin.webinars.create') }}" class="quick-btn primary">
                            <svg viewBox="0 0 24 24">
                                <rect x="3" y="5" width="18" height="16" rx="2"></rect>
                                <path d="M8 3v4"></path>
                                <path d="M16 3v4"></path>
                                <path d="M3 10h18"></path>
                            </svg>
                            Create webinar
                        </a>
                        <a href="{{ route('admin.coachings.index') }}" class="quick-btn">
                            <svg viewBox="0 0 24 24">
                                <circle cx="9" cy="8" r="3"></circle>
                                <circle cx="17" cy="9" r="2.5"></circle>
                                <path d="M3 20c0-3.5 2.5-5.5 6-5.5s6 2 6 5.5"></path>
                            </svg>
                            Manage availability
                        </a>
                        <a href="{{ route('admin.books.create') }}" class="quick-btn">
                            <svg viewBox="0 0 24 24">
                                <path d="M4 20h4L19 9l-4-4L4 16v4z"></path>
                                <path d="M13 6l4 4"></path>
                            </svg>
                            Add new book
                        </a>
                    </div>
                </div>
            </div>

            <!-- RECENT ACTIVITY -->
            <div class="panel activity">
                <div class="activity-header">
                    <h2 class="panel-title">Recent activity</h2>
                </div>
                <div class="activity-body">
                    @php
                        $recentOrders = \App\Models\Order::latest()->limit(3)->get();
                    @endphp

                    @forelse($recentOrders as $order)
                        <div class="activity-item">
                            <div class="activity-icon">
                                <svg viewBox="0 0 24 24">
                                    <circle cx="9" cy="20" r="1"></circle>
                                    <circle cx="18" cy="20" r="1"></circle>
                                    <path d="M3 4h2l2.5 11h10l2-7H6"></path>
                                </svg>
                            </div>
                            <div class="activity-text">
                                <div class="activity-title">Book order completed</div>
                                <div class="activity-date">{{ $order->created_at->format('M d, Y · g:i A') }}</div>
                            </div>
                            <div class="activity-price">
                                @if($order->currency === 'GHS')
                                    ₵{{ number_format($order->total_amount, 2) }}
                                @else
                                    ${{ number_format($order->total_amount, 2) }}
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="activity-item">
                            <div class="activity-text" style="text-align: center; color: #737d8e; width: 100%;">
                                No recent activity
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- FOOTER -->
        <div class="footer">
            Design concept • Sample data
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Meeting notification functions
    let currentMeetingId = null;
    let notificationTimeout = null;
    let lastMeetingId = null;

    function checkUpcomingMeetings() {
        fetch('{{ route("admin.coachings.upcoming") }}')
            .then(res => res.json())
            .then(data => {
                const container = document.getElementById('meeting-notification');
                if (data.upcoming && data.upcoming.length > 0) {
                    showMeetingNotification(data.upcoming);
                } else {
                    container.classList.add('hidden');
                    lastMeetingId = null;
                }
            })
            .catch(err => console.error('Error checking meetings:', err));
    }

    function showMeetingNotification(meetings) {
        const meeting = meetings[0];
        currentMeetingId = meeting.id;
        const container = document.getElementById('meeting-notification');
        
        if (meetings.length > 1) {
            document.getElementById('meeting-count').textContent = ` (${meetings.length} people)`;
            const names = meetings.map(m => m.name).join(', ');
            document.getElementById('meeting-customer-name').textContent = names;
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
        
        const minutesUntil = Math.max(0, Math.round(meeting.minutes_until));
        let timeText = '';
        if (minutesUntil === 0) {
            timeText = 'Now!';
        } else if (minutesUntil < 60) {
            timeText = `In ${minutesUntil} minute${minutesUntil !== 1 ? 's' : ''}`;
        } else {
            const hours = Math.floor(minutesUntil / 60);
            const mins = minutesUntil % 60;
            timeText = `In ${hours}h ${mins}m`;
        }
        document.getElementById('meeting-time').textContent = timeText;
        
        if (meeting.meeting_link) {
            document.getElementById('meeting-link').href = meeting.meeting_link;
        }
        
        container.classList.remove('hidden');
        
        if ('speechSynthesis' in window) {
            const utterance = new SpeechSynthesisUtterance(`Meeting with ${meeting.name} in ${minutesUntil} minutes`);
            window.speechSynthesis.speak(utterance);
        }
        
        const autoDismissTime = minutesUntil <= 0 ? 30000 : Math.min(180000, minutesUntil * 60000);
        if (notificationTimeout) clearTimeout(notificationTimeout);
        notificationTimeout = setTimeout(() => {
            dismissMeetingNotification();
            lastMeetingId = null;
        }, autoDismissTime);
    }

    function dismissMeetingNotification() {
        document.getElementById('meeting-notification').classList.add('hidden');
    }

    function sendManualReminder() {
        const btn = document.getElementById('send-reminder-btn');
        const originalText = btn.textContent;
        btn.textContent = 'Sending...';
        btn.disabled = true;

        fetch('/admin/coachings/' + currentMeetingId + '/send-reminder', {
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

    checkUpcomingMeetings();
    setInterval(checkUpcomingMeetings, 60000);
</script>

@if(isset($chartLabels) && isset($chartValues))
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    (function() {
        const ctx = document.getElementById('ordersChart').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($chartLabels) !!},
                datasets: [{
                    label: 'Orders',
                    data: {!! json_encode($chartValues) !!},
                    backgroundColor: [
                        'rgba(79, 70, 229, 0.7)',
                        'rgba(16, 185, 129, 0.7)',
                        'rgba(245, 158, 11, 0.7)',
                        'rgba(239, 68, 68, 0.7)',
                        'rgba(6, 182, 212, 0.7)',
                        'rgba(139, 92, 246, 0.7)',
                    ],
                    borderColor: '#ffffff',
                    borderWidth: 2,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                    }
                }
            }
        });
    })();
</script>
@endif
@endpush
