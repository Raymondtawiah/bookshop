@extends('layouts.admin')

@section('title', 'Booking Settings')

@section('content')
    <div class="content">
        <div class="page-header">
            <div>
                <h1 class="page-title">Booking Settings</h1>
                <p class="page-subtitle">Configure available session times and Zoom link for all books.</p>
            </div>
            <a href="{{ route('admin.books') }}" class="p-2.5 text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all border border-gray-200" title="Back to books">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
        </div>

        <div class="panel">
            <div class="panel-header">
                <h2 class="panel-title">Available Booking Times</h2>
            </div>
            <div class="panel-body">
                <form method="POST" action="{{ route('admin.bookingSettings.update') }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div id="booking-times-container">
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                            @php
                                $availableTimes = [
                                    '08:30 AM' => '8:30 AM',
                                    '09:20 AM' => '9:20 AM',
                                    '10:10 AM' => '10:10 AM',
                                    '11:00 AM' => '11:00 AM',
                                    '11:50 AM' => '11:50 AM',
                                    '12:40 PM' => '12:40 PM',
                                    '01:30 PM' => '1:30 PM',
                                    '02:20 PM' => '2:20 PM',
                                    '03:10 PM' => '3:10 PM',
                                    '04:00 PM' => '4:00 PM',
                                    '04:50 PM' => '4:50 PM',
                                    '05:40 PM' => '5:40 PM',
                                ];
                                $selectedTimes = old('booking_times', $bookingTimes ?? []);
                            @endphp
                            @foreach($availableTimes as $value => $label)
                                <label class="flex items-center gap-2.5 p-3 border rounded-xl cursor-pointer transition-all has-[:checked]:border-indigo-500 has-[:checked]:bg-indigo-50 hover:border-gray-300">
                                    <input type="checkbox" name="booking_times[]" value="{{ $value }}" {{ in_array($value, $selectedTimes) ? 'checked' : '' }} class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                    <span class="text-sm font-medium text-gray-700">{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="mt-6">
                        <label for="booking_zoom_link" class="block text-sm font-medium text-gray-700 mb-1">Zoom Meeting Link</label>
                        <input type="url" name="booking_zoom_link" id="booking_zoom_link" value="{{ old('booking_zoom_link', $bookingZoomLink) }}" placeholder="https://zoom.us/j/..." class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <p class="mt-1 text-xs text-gray-500">This link will be sent to customers after purchase and in booking reminders.</p>
                    </div>

                    <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
                        <button type="submit" class="px-5 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors font-medium">Save Settings</button>
                        <a href="{{ route('admin.books') }}" class="px-5 py-2.5 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors font-medium">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
