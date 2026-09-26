@extends('layouts.admin')

@section('title', 'Create Staff')

@section('content')
    <div class="content">
        <!-- PAGE HEADER -->
        <div class="page-header">
            <div>
                <h1 class="page-title">Create New Staff</h1>
                <p class="page-subtitle">Add a new staff member to the system.</p>
            </div>
        </div>

        <div class="cards">
            <div class="card">
                <h2 class="card-title">Staff Information</h2>

                @if(session('success'))
                    <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl font-medium">
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl font-medium">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.staff.store') }}" class="space-y-4">
                    @csrf
                    <div class="two-fields">
                        <div class="form-group">
                            <label>Full Name <span class="text-red-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}" required placeholder="Enter full name">
                        </div>
                        <div class="form-group">
                            <label>Email Address <span class="text-red-500">*</span></label>
                            <input type="email" name="email" value="{{ old('email') }}" required placeholder="Enter email address">
                        </div>
                    </div>
                    <div class="two-fields">
                        <div class="form-group">
                            <label>Phone Number <span class="text-red-500">*</span></label>
                            <input type="tel" name="phone_number" value="{{ old('phone_number') }}" required placeholder="Enter phone number">
                        </div>
                        <div class="form-group">
                            <label>Role <span class="text-red-500">*</span></label>
                            <div class="select-wrapper">
                                <select name="role" required>
                                    <option value="">Select Role</option>
                                    <option value="employee" {{ old('role') === 'employee' ? 'selected' : '' }}>Employee</option>
                                    <option value="Finance Admin" {{ old('role') === 'Finance Admin' ? 'selected' : '' }}>Finance Admin</option>
                                    <option value="Finance Member" {{ old('role') === 'Finance Member' ? 'selected' : '' }}>Finance Member</option>
                                    <option value="inventory" {{ old('role') === 'inventory' ? 'selected' : '' }}>Inventory</option>
                                </select>
                                <span class="select-arrow">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M6 9l6 6 6-6"></path>
                                    </svg>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="two-fields">
                        <div class="form-group">
                            <label>Password <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <input type="password" name="password" id="password" required placeholder="Enter password" class="pr-10">
                                <button type="button" onclick="togglePassword('password')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                    <svg id="password-eye" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </button>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Confirm Password <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <input type="password" name="password_confirmation" id="confirm_password" required placeholder="Confirm password" class="pr-10">
                                <button type="button" onclick="togglePassword('confirm_password')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                    <svg id="confirm_password-eye" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="actions">
                        <button type="button" onclick="window.location.href='{{ route('admin.staff.index') }}'" class="save">Cancel</button>
                        <button type="submit" class="publish">Create Staff</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function togglePassword(fieldId) {
        const input = document.getElementById(fieldId);
        const eyeIcon = document.getElementById(fieldId + '-eye');
        if (input.type === 'password') {
            input.type = 'text';
            eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l3.29 3.29m7.532 7.532l-3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>';
        } else {
            input.type = 'password';
            eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>';
        }
    }
</script>
@endpush