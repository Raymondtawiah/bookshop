@extends('layouts.admin')

@section('title', 'Send Notification - ' . $webinar->title)

@section('content')
    <div class="max-w-4xl mx-auto">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Send Notification</h1>
                <p class="text-gray-500">Send updates to all registered attendees</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.webinars.admin.show', $webinar->id) }}" class="px-4 py-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Webinar
                </a>
            </div>
        </div>

        <!-- Webinar Info -->
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 mb-8">
            <div class="flex items-start gap-3">
                <svg class="w-6 h-6 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div class="flex-1">
                    <h3 class="font-semibold text-blue-900 mb-2">{{ $webinar->title }}</h3>
                    <p class="text-sm text-blue-700 mb-3">
                        This notification will be sent to {{ $webinar->total_paid_registrations }} paid attendees only
                    </p>
                    
                    <!-- Webinar Link Display -->
                    <div class="bg-white border border-blue-200 rounded-lg p-3">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-medium text-gray-600 mb-1">Current Webinar Link:</p>
                                <p class="text-sm text-gray-900 font-mono break-all">{{ $webinar->webinar_link }}</p>
                            </div>
                            <div class="flex gap-2 ml-4">
                                <a href="{{ $webinar->webinar_link }}" target="_blank" 
                                   class="px-3 py-1.5 bg-blue-600 text-white text-xs rounded hover:bg-blue-700 transition-colors flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                    </svg>
                                    Test Link
                                </a>
                                <a href="{{ route('admin.webinars.edit', $webinar->id) }}" 
                                   class="px-3 py-1.5 bg-gray-600 text-white text-xs rounded hover:bg-gray-700 transition-colors flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    Edit
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notification Form -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <form method="POST" action="{{ route('admin.webinars.notifications.store', $webinar->id) }}" class="space-y-6">
                @csrf

                <!-- Notification Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Notification Type</label>
                    <select name="type" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="info">General Information</option>
                        <option value="urgent">Urgent Update</option>
                        <option value="schedule">Schedule Update</option>
                        <option value="zoom_update">Zoom Meeting Update</option>
                    </select>
                    <p class="text-sm text-gray-500 mt-1">Choose the type of notification you're sending</p>
                </div>

                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Notification Title</label>
                    <input 
                        type="text" 
                        name="title" 
                        id="title"
                        required 
                        maxlength="255"
                        placeholder="e.g., Important Schedule Update"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                    >
                    @error('title')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Message -->
                <div>
                    <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Message</label>
                    <textarea 
                        name="message" 
                        id="message"
                        required 
                        rows="6"
                        placeholder="Enter your message here. Be clear and concise about the update or information you're sharing."
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 resize-vertical"
                    ></textarea>
                    @error('message')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="text-sm text-gray-500 mt-1">This message will be visible to all registered attendees</p>
                </div>

                <!-- Expiration -->
                <div>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input 
                            type="checkbox" 
                            name="set_expiration" 
                            id="set_expiration"
                            class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                        >
                        <span class="text-sm text-gray-700">Set expiration time for this notification</span>
                    </label>
                </div>

                <!-- Expiration Date/Time (shown when checkbox is checked) -->
                <div id="expiration_section" class="hidden">
                    <label for="expires_at" class="block text-sm font-medium text-gray-700 mb-2">Expires At</label>
                    <input 
                        type="datetime-local" 
                        name="expires_at" 
                        id="expires_at"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                    >
                    <p class="text-sm text-gray-500 mt-1">After this time, the notification will no longer be visible</p>
                </div>

                <!-- Quick Templates -->
                <div class="border-t border-gray-100 pt-6">
                    <h3 class="text-sm font-medium text-gray-700 mb-3">Quick Templates</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <button type="button" onclick="useTemplate('schedule')" class="text-left p-3 border border-gray-200 rounded-lg hover:border-indigo-300 hover:bg-indigo-50 transition-colors">
                            <div class="font-medium text-gray-900">Schedule Update</div>
                            <div class="text-sm text-gray-500">Update webinar date/time</div>
                        </button>
                        <button type="button" onclick="useTemplate('zoom')" class="text-left p-3 border border-gray-200 rounded-lg hover:border-indigo-300 hover:bg-indigo-50 transition-colors">
                            <div class="font-medium text-gray-900">Zoom Meeting Update</div>
                            <div class="text-sm text-gray-500">Share new Zoom meeting details</div>
                        </button>
                        <button type="button" onclick="useTemplate('reminder')" class="text-left p-3 border border-gray-200 rounded-lg hover:border-indigo-300 hover:bg-indigo-50 transition-colors">
                            <div class="font-medium text-gray-900">Reminder</div>
                            <div class="text-sm text-gray-500">Send reminder about upcoming webinar</div>
                        </button>
                        <button type="button" onclick="useTemplate('urgent')" class="text-left p-3 border border-gray-200 rounded-lg hover:border-indigo-300 hover:bg-indigo-50 transition-colors">
                            <div class="font-medium text-gray-900">Urgent Notice</div>
                            <div class="text-sm text-gray-500">Important urgent information</div>
                        </button>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex flex-col gap-4 pt-6 border-t border-gray-100">
                    <button 
                        type="submit" 
                        class="w-full py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors font-medium flex items-center justify-center gap-2"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        Send Notification to {{ $webinar->total_registrations }} Attendees
                    </button>
                    
                    <div class="text-center">
                        <a href="{{ route('admin.webinars.admin.show', $webinar->id) }}" class="text-gray-600 hover:text-gray-700 text-sm">
                            Cancel
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Toggle expiration section
        document.getElementById('set_expiration').addEventListener('change', function() {
            const expirationSection = document.getElementById('expiration_section');
            const expiresAtInput = document.getElementById('expires_at');
            
            if (this.checked) {
                expirationSection.classList.remove('hidden');
                // Set default expiration to 24 hours from now
                const tomorrow = new Date();
                tomorrow.setHours(tomorrow.getHours() + 24);
                expiresAtInput.value = tomorrow.toISOString().slice(0, 16);
            } else {
                expirationSection.classList.add('hidden');
                expiresAtInput.value = '';
            }
        });

        // Template functions
        function useTemplate(type) {
            const titleField = document.getElementById('title');
            const messageField = document.getElementById('message');
            const typeField = document.querySelector('select[name="type"]');
            
            const templates = {
                schedule: {
                    type: 'schedule',
                    title: 'Webinar Schedule Update',
                    message: `Dear Attendees,

We have an important update regarding the webinar schedule.

New Date & Time: [Insert new date and time]
Original Date & Time: [Insert original date and time]

Please update your calendars accordingly. If you have any questions about the new timing, please contact us.

Thank you for your understanding!`
                },
                zoom: {
                    type: 'zoom_update',
                    title: 'Updated Zoom Meeting Details',
                    message: `Dear Attendees,

We have updated the Zoom meeting details for this webinar.

New Meeting Link: [Insert new Zoom link]
Meeting ID: [Insert meeting ID]
Password: [Insert password if applicable]

Please save these new details. The previous link will no longer work.

If you have any trouble accessing the meeting, please contact support immediately.

Looking forward to seeing you there!`
                },
                reminder: {
                    type: 'info',
                    title: 'Webinar Reminder',
                    message: `Dear Attendees,

This is a friendly reminder about the upcoming webinar:

Date: [Insert date]
Time: [Insert time]
Duration: [Insert duration]

Please ensure you have completed payment and verification before the webinar starts. Check your email for your personal join link.

We're excited to have you join us!

Best regards,
The Team`
                },
                urgent: {
                    type: 'urgent',
                    title: 'Important Webinar Update',
                    message: `Dear Attendees,

We have an important update regarding the webinar that requires your immediate attention.

[Insert urgent information here]

This affects all attendees, so please read carefully and take any necessary action.

If you have any questions or concerns, please contact us immediately.

Thank you for your prompt attention to this matter.`
                }
            };
            
            const template = templates[type];
            if (template) {
                typeField.value = template.type;
                titleField.value = template.title;
                messageField.value = template.message;
            }
        }
    </script>
@endsection
