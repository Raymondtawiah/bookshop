<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register Staff - {{ config('app.name', 'Bookshop') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" href="/favicon.ico" sizes="any">
    <style>
        .input-field {
            @apply w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 outline-none transition-all;
        }
        .btn-primary {
            @apply px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-2xl font-bold hover:from-indigo-700 hover:to-purple-700 transition-all duration-200 shadow-lg shadow-indigo-200;
        }
    </style>
</head>
<body class="bg-gray-50 font-sans pt-20">
<x-admin-navbar />

<main class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex items-center gap-4 mb-8">
        <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
            </svg>
        </div>
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900">Register New Staff</h1>
            <p class="text-gray-500 font-medium">Add a new staff member to your team</p>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-xl shadow-gray-200/60 border border-gray-100 p-8">
        <form method="POST" action="{{ route('admin.staff.store') }}" class="space-y-6">
            @csrf

            <div>
                <label for="name" class="block text-sm font-bold text-gray-700 mb-2">Full Name</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}"
                    class="input-field" placeholder="John Doe">
                @error('name')
                    <p class="mt-1.5 text-sm text-red-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="block text-sm font-bold text-gray-700 mb-2">Email Address <span class="text-red-500">*</span></label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required
                    class="input-field" placeholder="staff@example.com">
                @error('email')
                    <p class="mt-1.5 text-sm text-red-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="phone_number" class="block text-sm font-bold text-gray-700 mb-2">Phone Number</label>
                <input type="tel" name="phone_number" id="phone_number" value="{{ old('phone_number') }}"
                    class="input-field" placeholder="+1 (555) 123-4567">
                @error('phone_number')
                    <p class="mt-1.5 text-sm text-red-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="password" class="block text-sm font-bold text-gray-700 mb-2">Password <span class="text-red-500">*</span></label>
                    <input type="password" name="password" id="password" required
                        class="input-field" placeholder="Minimum 8 characters">
                    @error('password')
                        <p class="mt-1.5 text-sm text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-bold text-gray-700 mb-2">Confirm Password <span class="text-red-500">*</span></label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                        class="input-field" placeholder="Re-enter password">
                </div>
            </div>

            <div>
                <label for="role" class="block text-sm font-bold text-gray-700 mb-2">Role <span class="text-red-500">*</span></label>
                <div class="relative">
                    <select name="role" id="role" required
                        class="input-field appearance-none pr-10 cursor-pointer">
                        <option value="">Select a role...</option>
                        <option value="employee" {{ old('role') === 'employee' ? 'selected' : '' }}>Employee</option>
                        <option value="finances" {{ old('role') === 'finances' ? 'selected' : '' }}>Finances</option>
                        <option value="inventory" {{ old('role') === 'inventory' ? 'selected' : '' }}>Inventory</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </div>
                @error('role')
                    <p class="mt-1.5 text-sm text-red-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between pt-4">
                <a href="{{ route('admin.staff.index') }}" class="text-gray-600 hover:text-indigo-600 font-medium transition-colors">
                    ← Back to Staff List
                </a>
                <button type="submit" class="btn-primary flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Register Staff
                </button>
            </div>
        </form>
    </div>
</main>

</body>
</html>
