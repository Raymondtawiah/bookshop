<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Login - Bookshop</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" href="/favicon.ico" sizes="any">
    <style>
        @keyframes gradient-shift {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }
        .animate-gradient {
            background: linear-gradient(-45deg, #ee7752, #e73c7e, #23a6d5, #23d5ab);
            background-size: 300% 300%;
            animation: gradient-shift 3s ease infinite;
        }
        .code-input:focus {
            outline: none;
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.2);
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center">
    <x-flash-message />
    <div class="max-w-md w-full mx-4">
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <!-- Header -->
            <div class="animate-gradient p-8 text-center">
                <svg class="w-16 h-16 mx-auto text-white mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                <h2 class="text-2xl font-bold text-white">Verify Your Login</h2>
                <p class="text-white/80 mt-2">Enter the 6-digit code sent to your email</p>
            </div>

            <!-- Form -->
            <div class="p-8">
                <div class="text-center mb-6">
                    <p class="text-gray-600">
                        We've sent a verification code to<br>
                        <span class="font-semibold text-indigo-600">{{ $user->email }}</span>
                    </p>
                </div>

                <form method="POST" action="{{ route('verification.login.verify') }}">
                    @csrf
                    
                    <div class="mb-6">
                        <label for="code" class="block text-sm font-medium text-gray-700 mb-2">Verification Code</label>
                        <input type="text" 
                               name="code" 
                               id="code" 
                               maxlength="6" 
                               class="code-input w-full px-4 py-3 text-center text-2xl tracking-[0.5em] border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                               placeholder="000000"
                               required
                               autofocus>
                        @error('code')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" 
                            class="w-full bg-indigo-600 text-white py-3 rounded-lg font-semibold hover:bg-indigo-700 transition-colors">
                        Verify & Login
                    </button>
                </form>

                <div class="mt-6 text-center">
                    <p class="text-gray-600 text-sm">Didn't receive the code?</p>
                    <form method="POST" action="{{ route('verification.login.resend') }}" class="inline">
                        @csrf
                        <button type="submit" 
                                class="mt-2 text-indigo-600 font-medium hover:text-indigo-800">
                            Resend Code
                        </button>
                    </form>
                </div>

                <div class="mt-6 pt-6 border-t border-gray-200 text-center">
                    <a href="{{ route('login') }}" class="text-gray-600 hover:text-indigo-600 text-sm">
                        ← Back to Login
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
