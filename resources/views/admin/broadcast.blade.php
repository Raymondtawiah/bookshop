@extends('layouts.admin')

@section('title', 'Send Broadcast')

@section('content')
    <div class="content">
        <div class="page-header">
            <div>
                <h1 class="page-title">Send Broadcast</h1>
                <p class="page-subtitle">Notify all customers about new books, webinars, and website updates.</p>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <form method="POST" action="{{ route('admin.notifications.sendBroadcast') }}" class="space-y-6">
                @csrf

                <div>
                    <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">Subject <span class="text-red-500">*</span></label>
                    <input type="text" name="subject" id="subject" required value="{{ old('subject') }}"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                        placeholder="e.g., New Visa Interview Books Available!">
                    @error('subject')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Main Message <span class="text-red-500">*</span></label>
                    <textarea name="message" id="message" rows="5" required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 resize-none"
                        placeholder="Write your main message to customers...">{{ old('message') }}</textarea>
                    @error('message')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="book_update" class="block text-sm font-medium text-gray-700 mb-1">New Books Update <span class="text-gray-400 font-normal">(optional)</span></label>
                    <textarea name="book_update" id="book_update" rows="3"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 resize-none"
                        placeholder="Tell customers about new books available...">{{ old('book_update') }}</textarea>
                    @error('book_update')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="webinar_update" class="block text-sm font-medium text-gray-700 mb-1">Webinar Updates <span class="text-gray-400 font-normal">(optional)</span></label>
                    <textarea name="webinar_update" id="webinar_update" rows="3"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 resize-none"
                        placeholder="Tell customers about upcoming webinars...">{{ old('webinar_update') }}</textarea>
                    @error('webinar_update')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between pt-2">
                    <p class="text-sm text-gray-500">This will send an email to all customers in the system.</p>
                    <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 text-white font-semibold rounded-xl hover:bg-indigo-700 transition-colors shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                        Send Broadcast
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
