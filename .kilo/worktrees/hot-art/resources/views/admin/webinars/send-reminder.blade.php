@extends('layouts.admin')

@section('title', 'Send Webinar Reminder')

@section('content')
<div class="max-w-4xl mx-auto py-8">
    <!-- Header Section -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-4">Send Webinar Reminder</h1>
        <p class="text-gray-600">Send reminders to paid webinar attendees</p>
    </div>

    <!-- Webinar Info -->
    <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 mb-8">
        <div class="flex items-start gap-3">
            <svg class="w-6 h-6 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div class="flex-1">
                <h3 class="font-semibold text-blue-900 mb-2">{{ $webinar->title }}</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-blue-800">
                    <div>
                        <span class="font-medium">Date:</span> 
                        {{ $webinar->scheduled_at ? $webinar->scheduled_at->timezone('Africa/Accra')->format('l, F j, Y') : 'TBA' }}
                    </div>
                    <div>
                        <span class="font-medium">Time:</span> 
                        {{ $webinar->scheduled_at ? $webinar->scheduled_at->timezone('Africa/Accra')->format('g:i A') : 'TBA' }}
                    </div>
                    <div>
                        <span class="font-medium">Duration:</span> 
                        {{ $webinar->duration_minutes ? $webinar->duration_minutes . ' minutes' : 'TBA' }}
                    </div>
                    <div>
                        <span class="font-medium">Paid Attendees:</span> 
                        {{ $webinar->total_paid_registrations }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reminder Form -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <form method="POST" action="{{ route('admin.webinars.sendWebinarReminder', [$webinar->id, $registration->id ?? null]) }}" class="space-y-6">
                    @csrf
                    
                    <!-- Webinar Date Info (read-only, shows which webinar this is for) -->
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <p class="text-sm font-medium text-gray-700">Webinar Date: 
                            <span class="text-gray-900 font-semibold">
                                {{ $webinar->scheduled_at ? $webinar->scheduled_at->timezone('Africa/Accra')->format('l, F j, Y - g:i A') : 'Not scheduled' }}
                            </span>
                        </p>
                        <p class="text-xs text-gray-500 mt-1">All reminders include the webinar access link automatically.</p>
                    </div>

                    <!-- To Individual or All -->
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

            <!-- Reminder Type -->
            <div>
                <label for="reminder_type" class="block text-sm font-medium text-gray-700 mb-2">Reminder Type</label>
                <select name="reminder_type" id="reminder_type" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="24_hours">24 Hours Before - Standard reminder</option>
                    <option value="1_hour">1 Hour Before - Starting soon</option>
                    <option value="15_minutes">15 Minutes Before - Urgent reminder</option>
                    <option value="post_webinar">After Webinar - Thank you follow-up</option>
                </select>
                <p class="text-sm text-gray-500 mt-1">The access link will be included in all reminder emails.</p>
            </div>

            <!-- Custom Message -->
            <div>
                <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Custom Message (Optional)</label>
                <textarea name="message" id="message" rows="3"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="Enter custom message or leave empty for default...">{{ old('message') }}</textarea>
                <p class="text-sm text-gray-500 mt-1">Default: <span id="default-message-text" class="italic">{{ $defaultMessages['24_hours'] ?? '' }}</span></p>
            </div>
            
            <script>
                const defaultMessages = @json($defaultMessages ?? []);
                document.getElementById('reminder_type').addEventListener('change', function() {
                    const selectedType = this.value;
                    document.getElementById('default-message-text').textContent = defaultMessages[selectedType] || '';
                });
            </script>

            <!-- Preview -->
            <div class="border-t border-gray-100 pt-6">
                <h3 class="text-sm font-medium text-gray-700 mb-3">Email Preview</h3>
                <div class="bg-gray-50 rounded-lg p-4 text-sm">
                    <p class="text-gray-600">Subject: Will be automatically set based on reminder type</p>
                    <p class="text-gray-600 mt-2">Content: Includes webinar details, your unique access link, and any important information.</p>
                    <p class="text-gray-600 mt-2 font-medium">Access Link: Will be generated for each recipient automatically.</p>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex flex-col gap-4 pt-6 border-t border-gray-100">
                <button type="submit" class="w-full py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors font-medium flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a21 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    Send Reminder
                </button>
                
                <div class="text-center">
                    <a href="{{ route('admin.webinars.registrations') }}" class="text-gray-600 hover:text-gray-700 text-sm">
                        Cancel
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection