@extends('layouts.admin')

@section('title', 'Settings')

@section('content')
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-gray-900">Settings</h1>
                <p class="text-gray-500">Manage your admin account settings</p>
            </div>

            <!-- Profile Section -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                    <h2 class="text-lg font-semibold text-gray-900">Profile Information</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                            <input type="text" value="{{ auth()->user()->name }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50" disabled>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" value="{{ auth()->user()->email }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50" disabled>
                        </div>

                        <div class="pt-4">
                            <p class="text-sm text-gray-500">Admin settings coming soon...</p>
                        </div>
                    </div>
                </div>
            </div>
        @endsection