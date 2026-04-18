<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Coaching Bookings - {{ config('app.name', 'Nathaniel Gyarteng') }}</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <link rel="icon" href="/favicon.ico" sizes="any">
    </head>
    <body class="bg-gray-50 font-sans">
        <x-flash-message />
        
        <x-admin-navbar />

        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Coaching Bookings</h1>
                        <p class="text-gray-600 mt-2">View all confirmed coaching session bookings</p>
                    </div>
                    <form method="POST" action="{{ route('admin.coachings.toggleActive') }}" class="flex items-center gap-4">
                        @csrf
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" class="sr-only peer" {{ \App\Models\SiteSetting::get('coaching_booking_active', 'true') === 'true' ? 'checked' : '' }} onchange="this.form.submit()">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                            <span class="ml-3 text-sm font-medium text-gray-900">Booking {{ \App\Models\SiteSetting::get('coaching_booking_active', 'true') === 'true' ? 'Enabled' : 'Disabled' }}</span>
                        </label>
                    </form>
                </div>
            </div>

            @if($bookings->isEmpty())
                <div class="bg-white rounded-2xl shadow-sm p-12 text-center">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">No Bookings Yet</h3>
                    <p class="text-gray-600">There are no confirmed coaching bookings at the moment.</p>
</div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Package</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Interview</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Request Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Meeting Sent</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @php
                                $bookingService = app(\App\Services\CoachingBookingService::class);
                            @endphp
                            @foreach($bookings as $booking)
                                @php
                                    $statusLabel = $bookingService->getStatusLabel($booking);
                                    $statusClass = $bookingService->getStatusClass($booking);
                                    $hasMeetingLink = $bookingService->hasMeetingLinkBeenSent($booking);
                                    $meetingDetails = $bookingService->getMeetingDetails($booking);
                                @endphp
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ $booking->name }}</p>
                                            <p class="text-sm text-gray-500">{{ $booking->email }}</p>
                                            @if($booking->phone)
                                                <p class="text-xs text-gray-400">{{ $booking->phone }}</p>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-indigo-100 text-indigo-800">
                                            {{ ucfirst($booking->package) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ $booking->interview_type }}</td>
                                    <td class="px-6 py-4">
                                        <p class="text-sm text-gray-900">{{ $booking->interview_date->format('M j, Y') }}</p>
                                        <p class="text-xs text-gray-500">{{ $booking->interview_time }}</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($hasMeetingLink)
                                            <p class="text-sm text-green-600 font-medium">Link Sent</p>
                                            @if($meetingDetails['time'])
                                                <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($meetingDetails['time'])->format('M j, g:i A') }}</p>
                                            @endif
                                        @else
                                            <p class="text-sm text-gray-400">Not sent</p>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">${{ number_format($booking->amount ?? 0, 2) }}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 text-xs font-medium rounded-full {{ $statusClass }}">
                                            {{ ucfirst($statusLabel) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-3">
                                            <button type="button" onclick="openBookingModal({{ $loop->index }})" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                                View Details
                                            </button>
                                            <button type="button" onclick="openDeleteModal({{ $booking->id }}, '{{ $booking->name }}')" class="text-red-600 hover:text-red-900 text-sm font-medium">
                                                Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </main>

        <!-- Booking Modal -->
        <div id="booking-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden" onclick="closeBookingModal()">
            <div class="flex items-center justify-center min-h-screen p-4" onclick="event.stopPropagation()">
                <div class="bg-white rounded-2xl shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                    <div class="p-6 border-b border-gray-200 flex items-center justify-between">
                        <h2 class="text-xl font-bold text-gray-900">Booking Details</h2>
                        <button onclick="closeBookingModal()" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    <div class="p-6">
                        <!-- Booking Info -->
                        <div id="modal-content"></div>
                        
                        <!-- Send Meeting Link Form -->
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Send Meeting Link to Customer</h3>
                            <form id="send-link-form" method="POST" action="">
                                @csrf
                                <div class="space-y-4">
                                    <div>
                                        <label for="meeting_link" class="block text-sm font-medium text-gray-700 mb-1">Meeting Link</label>
                                        <input type="url" name="meeting_link" id="meeting_link" placeholder="https://zoom.us/j/..." required
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                    </div>
                                    <div>
                                        <label for="meeting_time" class="block text-sm font-medium text-gray-700 mb-1">Meeting Time</label>
                                        <input type="datetime-local" name="meeting_time" id="meeting_time" required
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                    </div>
                                    <div>
                                        <label for="meeting_notes" class="block text-sm font-medium text-gray-700 mb-1">Additional Notes (Optional)</label>
                                        <textarea name="meeting_notes" id="meeting_notes" rows="2" placeholder="Any additional instructions..."
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                                    </div>
                                    <button type="submit" class="w-full bg-indigo-600 text-white py-2 px-4 rounded-lg hover:bg-indigo-700 transition-colors">
                                        Send Meeting Link
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            const bookings = @json($bookings->toArray());
            let currentBookingIndex = null;

            function openBookingModal(index) {
                currentBookingIndex = index;
                const booking = bookings[index];
                
                const statusClass = booking.status === 'confirmed' ? 'bg-green-100 text-green-800' : 
                                    booking.status === 'completed' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800';
                
                const content = `
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-2">Customer Information</h4>
                            <p class="font-medium text-gray-900">${booking.name}</p>
                            <p class="text-gray-600">${booking.email}</p>
                            ${booking.phone ? `<p class="text-gray-500">${booking.phone}</p>` : ''}
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-2">Session Details</h4>
                            <p class="text-gray-900"><span class="font-medium">Package:</span> ${booking.package.charAt(0).toUpperCase() + booking.package.slice(1)}</p>
                            <p class="text-gray-900"><span class="font-medium">Interview:</span> ${booking.interview_type}</p>
                            <p class="text-gray-900"><span class="font-medium">Date:</span> ${new Date(booking.interview_date).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' })}</p>
                            <p class="text-gray-900"><span class="font-medium">Time:</span> ${booking.interview_time}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-2">Payment</h4>
                            <p class="text-2xl font-bold text-indigo-600">$${booking.amount ? parseFloat(booking.amount).toFixed(2) : '0.00'}</p>
                            <span class="inline-block mt-2 px-2 py-1 text-xs font-medium rounded-full ${booking.payment_status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'}">
                                ${booking.payment_status.charAt(0).toUpperCase() + booking.payment_status.slice(1)}
                            </span>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-2">Status</h4>
                            <span class="inline-block px-2 py-1 text-xs font-medium rounded-full ${statusClass}">
                                ${booking.status.charAt(0).toUpperCase() + booking.status.slice(1)}
                            </span>
                            <p class="text-sm text-gray-500 mt-2">Booked: ${new Date(booking.created_at).toLocaleDateString()}</p>
                        </div>
                        ${booking.notes ? `
                        <div class="md:col-span-2">
                            <h4 class="text-sm font-medium text-gray-500 mb-2">Notes</h4>
                            <p class="text-gray-600">${booking.notes}</p>
                        </div>
                        ` : ''}
                    </div>
                    
                    <!-- Status Update -->
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Update Status</h3>
                        <form method="POST" action="/admin/coachings/${booking.id}/status" class="flex items-center gap-4">
                            @csrf
                            @method('PUT')
                            <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="pending" ${booking.status === 'pending' ? 'selected' : ''}>Pending</option>
                                <option value="confirmed" ${booking.status === 'confirmed' ? 'selected' : ''}>Confirmed</option>
                                <option value="completed" ${booking.status === 'completed' ? 'selected' : ''}>Completed</option>
                                <option value="cancelled" ${booking.status === 'cancelled' ? 'selected' : ''}>Cancelled</option>
                            </select>
                            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                                Update
                            </button>
                        </form>
                    </div>
                `;
                
                document.getElementById('modal-content').innerHTML = content;
                document.getElementById('send-link-form').action = `/admin/coachings/${booking.id}/send-link`;
                document.getElementById('booking-modal').classList.remove('hidden');
            }

            function closeBookingModal() {
                document.getElementById('booking-modal').classList.add('hidden');
            }

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeBookingModal();
                }
            });
        </script>

        <script>
            function openDeleteModal(bookingId, bookingName) {
                document.getElementById('delete-booking-name').textContent = bookingName;
                document.getElementById('delete-form').action = '/admin/coachings/' + bookingId;
                document.getElementById('delete-modal').classList.remove('hidden');
            }

            function closeDeleteModal() {
                document.getElementById('delete-modal').classList.add('hidden');
            }

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeDeleteModal();
                }
            });
        </script>

        <!-- Delete Confirmation Modal -->
        <div id="delete-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden" onclick="closeDeleteModal()">
            <div class="flex items-center justify-center min-h-screen p-4" onclick="event.stopPropagation()">
                <div class="bg-white rounded-2xl shadow-xl max-w-md w-full p-6">
                    <div class="text-center">
                        <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Delete Booking</h3>
                        <p class="text-gray-600 mb-6">Are you sure you want to delete the booking for <span id="delete-booking-name" class="font-medium"></span>? This action cannot be undone.</p>
                        <form id="delete-form" method="POST">
                            @csrf
                            @method('DELETE')
                            <div class="flex gap-4">
                                <button type="button" onclick="closeDeleteModal()" class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                                    Cancel
                                </button>
                                <button type="submit" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                                    Delete
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-200 mt-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <p class="text-center text-sm text-gray-500">&copy; {{ date('Y') }} {{ config('app.name', 'Bookshop') }}. All rights reserved.</p>
            </div>
        </footer>
    </body>
</html>