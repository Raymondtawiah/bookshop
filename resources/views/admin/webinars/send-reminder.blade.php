@extends('layouts.admin')

@section('title', 'Send Webinar Reminder')

@section('content')
    <div class="content">
        <div class="page-header">
            <div>
                <h1 class="page-title">Send Webinar Reminder</h1>
                <p class="page-subtitle">Send reminders to paid webinar attendees</p>
            </div>
        </div>

        <div class="panel">
            <div class="panel-header">
                <h2 class="panel-title">{{ $webinar->title }}</h2>
                <p class="text-sm text-gray-500 mt-1">Webinar reminder form</p>
            </div>
            <div class="panel-body">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Date</p>
                        <p class="text-sm font-medium text-gray-900">{{ $webinar->scheduled_at ? $webinar->scheduled_at->timezone('Africa/Accra')->format('l, F j, Y') : 'TBA' }}</p>
                    </div>
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Time</p>
                        <p class="text-sm font-medium text-gray-900">{{ $webinar->scheduled_at ? $webinar->scheduled_at->timezone('Africa/Accra')->format('g:i A') : 'TBA' }}</p>
                    </div>
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Duration</p>
                        <p class="text-sm font-medium text-gray-900">{{ $webinar->duration_minutes ? $webinar->duration_minutes . ' minutes' : 'TBA' }}</p>
                    </div>
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Paid Attendees</p>
                        <p class="text-sm font-medium text-gray-900">{{ $webinar->total_paid_registrations }}</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.webinars.sendWebinarReminder', $registration ? [$webinar->id, $registration->id] : [$webinar->id]) }}" class="space-y-4">
                    @csrf

                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <p class="text-sm font-medium text-gray-700">Webinar Date:
                            <span class="text-gray-900 font-semibold">
                                {{ $webinar->scheduled_at ? $webinar->scheduled_at->timezone('Africa/Accra')->format('l, F j, Y - g:i A') : 'Not scheduled' }}
                            </span>
                        </p>
                        <p class="text-xs text-gray-500 mt-1">All reminders include the webinar access link automatically.</p>
                    </div>

                    @if($registration)
                        <input type="hidden" name="registration_id" value="{{ $registration->id }}">
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                            <p class="text-sm font-medium text-gray-700">Sending to: <span class="text-gray-900">{{ $registration->full_name ?? $registration->email }}</span></p>
                        </div>
                    @else
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                            <p class="text-sm font-medium text-gray-700">Sending to: <span class="text-gray-900">All {{ $webinar->total_paid_registrations }} paid attendees</span></p>
                        </div>
                    @endif

                    <div class="form-group">
                        <label for="reminder_type">Reminder Type</label>
                        <div class="select-wrapper">
                            <select name="reminder_type" id="reminder_type" required class="w-full">
                                <option value="24_hours">24 Hours Before - Standard reminder</option>
                                <option value="1_hour">1 Hour Before - Starting soon</option>
                                <option value="15_minutes">15 Minutes Before - Urgent reminder</option>
                                <option value="post_webinar">After Webinar - Thank you follow-up</option>
                            </select>
                            <span class="select-arrow">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M6 9l6 6 6-6"></path>
                                </svg>
                            </span>
                        </div>
                        <p class="text-sm text-gray-500 mt-1">The access link will be included in all reminder emails.</p>
                    </div>

                    <div class="form-group">
                        <label for="message">Custom Message (Optional)</label>
                        <textarea name="message" id="message" rows="3" class="w-full" placeholder="Enter custom message or leave empty for default...">{{ old('message') }}</textarea>
                        <p class="text-sm text-gray-500 mt-1">Default: <span id="default-message-text" class="italic">{{ $defaultMessages['24_hours'] ?? '' }}</span></p>
                    </div>

                    <script>
                        const defaultMessages = @json($defaultMessages ?? []);
                        document.getElementById('reminder_type').addEventListener('change', function() {
                            const selectedType = this.value;
                            document.getElementById('default-message-text').textContent = defaultMessages[selectedType] || '';
                        });
                    </script>

                    <div class="panel" style="margin-top: 24px;">
                        <div class="panel-header">
                            <h2 class="panel-title">Email Preview</h2>
                        </div>
                        <div class="panel-body">
                            <p class="text-sm text-gray-600">Subject: Will be automatically set based on reminder type</p>
                            <p class="text-sm text-gray-600 mt-2">Content: Includes webinar details, your unique access link, and any important information.</p>
                            <p class="text-sm text-gray-600 mt-2 font-medium">Access Link: Will be generated for each recipient automatically.</p>
                        </div>
                    </div>

                    <div class="actions">
                        <button type="button" onclick="window.location.href='{{ route('admin.webinars.index') }}'" class="save">Cancel</button>
                        <button type="submit" class="publish">Send Reminder</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection