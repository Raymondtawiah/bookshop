@extends('layouts.admin')

@section('title', 'Create Staff')

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-zinc-900 dark:text-zinc-100">Create New Staff</h1>
        <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Add a new staff member to the system.</p>
    </div>

    <div class="bg-white dark:bg-zinc-800 rounded-3xl border border-zinc-200 dark:border-zinc-700 shadow-sm p-8">
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-2xl font-medium">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-2xl font-medium">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form class="space-y-6" method="POST" action="{{ route('admin.staff.store') }}">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-zinc-700 dark:text-zinc-300 mb-2">Full Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-4 py-3 rounded-xl border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all" placeholder="Enter full name">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-zinc-700 dark:text-zinc-300 mb-2">Email Address <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" required class="w-full px-4 py-3 rounded-xl border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all" placeholder="Enter email address">
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-zinc-700 dark:text-zinc-300 mb-2">Phone Number <span class="text-red-500">*</span></label>
                    <input type="tel" name="phone_number" value="{{ old('phone_number') }}" required class="w-full px-4 py-3 rounded-xl border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all" placeholder="Enter phone number">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-zinc-700 dark:text-zinc-300 mb-2">Role <span class="text-red-500">*</span></label>
                    <select name="role" required class="w-full px-4 py-3 rounded-xl border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500">
                        <option value="">Select Role</option>
                        <option value="employee" {{ old('role') === 'employee' ? 'selected' : '' }}>Employee</option>
                        <option value="finances" {{ old('role') === 'finances' ? 'selected' : '' }}>Finances</option>
                        <option value="inventory" {{ old('role') === 'inventory' ? 'selected' : '' }}>Inventory</option>
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-zinc-700 dark:text-zinc-300 mb-2">Password <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <input type="password" name="password" id="password" required class="w-full px-4 py-3 rounded-xl border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all pr-10" placeholder="Enter password">
                        <button type="button" onclick="togglePassword('password')" class="absolute right-3 top-1/2 -translate-y-1/2 text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-300">
                            <svg id="password-eye" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </button>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-zinc-700 dark:text-zinc-300 mb-2">Confirm Password <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <input type="password" name="password_confirmation" id="confirm_password" required class="w-full px-4 py-3 rounded-xl border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all pr-10" placeholder="Confirm password">
                        <button type="button" onclick="togglePassword('confirm_password')" class="absolute right-3 top-1/2 -translate-y-1/2 text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-300">
                            <svg id="confirm_password-eye" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </button>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-3 pt-4">
                <button type="submit" class="flex-1 sm:flex-none px-6 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-sm shadow-lg transition-colors">Create Staff</button>
                <a href="{{ route('admin.staff.index') }}" class="flex-1 sm:flex-none px-6 py-3 rounded-xl border border-zinc-300 dark:border-zinc-600 text-sm font-semibold text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-700 transition-colors text-center">Cancel</a>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
    function togglePassword(fieldId) {
        const input = document.getElementById(fieldId);
        const eyeIcon = document.getElementById(fieldId + '-eye');
        if (input.type === 'password') {
            input.type = 'text';
            eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.05 10.05 0 013.536-4.352M9.88 9.88a3 3 0 104.243 4.243m-4.243-4.243l4.243 4.243M9.88 9.88l-4.243 4.243M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>';
        } else {
            input.type = 'password';
            eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>';
        }
    }
</script>
@endpush