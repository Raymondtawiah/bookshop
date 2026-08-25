@extends('layouts.admin')

@section('title', 'Send Broadcast')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Send Email Broadcast</h1>
        <p class="text-gray-500 text-sm">Notify all customers about new books, webinars, and website updates</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="{{ route('admin.notifications.sendBroadcast') }}" class="space-y-6">
            @csrf

            <div>
                <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">Subject *</label>
                <input type="text" name="subject" id="subject" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="e.g., New Visa Interview Books Available!">
                @error('subject')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Main Message *</label>
                <textarea name="message" id="message" rows="5" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="Write your main message to customers..."></textarea>
                @error('message')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="book_update" class="block text-sm font-medium text-gray-700 mb-1">New Books Update (optional)</label>
                <textarea name="book_update" id="book_update" rows="3"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="Tell customers about new books available..."></textarea>
                @error('book_update')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="webinar_update" class="block text-sm font-medium text-gray-700 mb-1">Webinar Updates (optional)</label>
                <textarea name="webinar_update" id="webinar_update" rows="3"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="Tell customers about upcoming webinars..."></textarea>
                @error('webinar_update')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between">
                <p class="text-sm text-gray-500">
                    This will send an email to all customers in the system.
                </p>
                <button type="submit" class="px-6 py-3 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition-colors">
                    Send Broadcast
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
