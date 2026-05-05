@extends('layouts.admin')

@section('title', 'Create Webinar')

@section('content')
            <!-- Header Section -->
            <div class="max-w-3xl mx-auto mb-8">
                <h1 class="text-2xl font-bold text-gray-900">Create Webinar</h1>
                <p class="text-gray-500">Schedule a new paid webinar</p>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 md:p-8 max-w-3xl mx-auto">
                <form action="{{ route('admin.webinars.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Title -->
                    <div>
                        <label for="title" class="block text-sm font-semibold text-gray-900 mb-2">Title *</label>
                        <input 
                            type="text" 
                            name="title" 
                            id="title"
                            value="{{ old('title') }}" 
                            required
                            class="w-full px-5 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all"
                            placeholder="e.g., Introduction to Data Science"
                        >
                        @error('title')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-semibold text-gray-900 mb-2">Description</label>
                        <textarea 
                            name="description" 
                            id="description"
                            rows="4"
                            class="w-full px-5 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all"
                            placeholder="What will participants learn?"
                        >{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Webinar Link -->
                    <div>
                        <label for="webinar_link" class="block text-sm font-semibold text-gray-900 mb-2">Webinar Link *</label>
                        <input 
                            type="url" 
                            name="webinar_link" 
                            id="webinar_link"
                            value="{{ old('webinar_link') }}" 
                            required
                            class="w-full px-5 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all"
                            placeholder="https://zoom.us/j/1234567890?pwd=..."
                        >
                        <p class="text-sm text-gray-400 mt-1">This link will be hidden from users and only shown to paid attendees.</p>
                        @error('webinar_link')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Price -->
                        <div>
                            <label for="price" class="block text-sm font-semibold text-gray-900 mb-2">Price (GHS) *</label>
                            <input 
                                type="number" 
                                name="price" 
                                id="price"
                                value="{{ old('price', 0) }}" 
                                min="0" 
                                step="0.01"
                                required
                                class="w-full px-5 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all"
                                placeholder="0.00"
                            >
                            <p class="text-sm text-gray-400 mt-1">Set to 0 for free webinars.</p>
                            @error('price')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Duration -->
                        <div>
                            <label for="duration_minutes" class="block text-sm font-semibold text-gray-900 mb-2">Duration (minutes)</label>
                            <input 
                                type="number" 
                                name="duration_minutes" 
                                id="duration_minutes"
                                value="{{ old('duration_minutes') }}" 
                                min="1"
                                class="w-full px-5 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all"
                                placeholder="60"
                            >
                            @error('duration_minutes')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Scheduled Date/Time -->
                        <div>
                            <label for="scheduled_at" class="block text-sm font-semibold text-gray-900 mb-2">Scheduled Date & Time</label>
                            <input 
                                type="datetime-local" 
                                name="scheduled_at" 
                                id="scheduled_at"
                                value="{{ old('scheduled_at') }}"
                                class="w-full px-5 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all"
                            >
                            @error('scheduled_at')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-semibold text-gray-900 mb-2">Status *</label>
                            <select 
                                name="status" 
                                id="status"
                                required
                                class="w-full px-5 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all"
                            >
                                <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active - Accepting registrations</option>
                                <option value="scheduled" {{ old('status') === 'scheduled' ? 'selected' : '' }}>Scheduled - Not yet accepting registrations</option>
                                <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive - Not visible to users</option>
                                <option value="completed" {{ old('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                            @error('status')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-4 pt-4">
                        <a href="{{ route('admin.webinars.index') }}" class="px-6 py-3 text-gray-600 hover:text-gray-900 font-medium">
                            Cancel
                        </a>
                        <button type="submit" class="px-6 py-3 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition-colors font-medium">
                            Create Webinar
                        </button>
                    </div>
                </form>
            </div>
@endsection
