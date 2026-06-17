<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Login - Bookshop</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" href="/favicon.ico" sizes="any">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .code-input:focus {
            outline: none;
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.15);
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center px-4">
    <x-flash-message />
    <div class="w-full max-w-sm">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
            <div class="text-center mb-6">
                <div class="inline-flex items-center justify-center w-20 h-20 mb-4">
                    <img src="{{ asset('favicon.jpg') }}" alt="Logo" class="w-12 h-12 object-contain">
                </div>
                <h1 class="text-2xl font-bold text-gray-800">OTP Verification</h1>
                <p class="text-sm text-gray-500 mt-1">Enter the 6-digit code sent to your email</p>
            </div>

            <div class="text-center mb-5">
                <p class="text-sm text-gray-600">
                    Code sent to <span class="font-medium text-gray-900">{{ $user->email }}</span>
                </p>
            </div>

            <form method="POST" action="{{ route('verification.login.verify') }}" class="space-y-4">
                @csrf
                <div>
                    <label for="code" class="block text-sm font-medium text-gray-700 mb-1.5">Verification Code</label>
                    <input
                        type="text"
                        name="code"
                        id="code"
                        maxlength="6"
                        inputmode="numeric"
                        autocomplete="one-time-code"
                        class="code-input w-full px-4 py-2.5 text-center text-xl tracking-widest border border-gray-300 rounded-lg bg-white text-gray-900"
                        placeholder="000000"
                        required
                        autofocus
                    >
                    @error('code')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <button
                    type="submit"
                    class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors"
                >
                    Verify & Login
                </button>
            </form>

            <div class="mt-5 text-center">
                <p class="text-sm text-gray-500">Didn't get the code?</p>
                <form method="POST" action="{{ route('verification.login.resend') }}" class="inline">
                    @csrf
                    <button type="submit" class="text-sm text-gray-900 font-medium hover:underline mt-1">
                        Resend Code
                    </button>
                </form>
            </div>

            <div class="mt-5 pt-4 border-t border-gray-100 text-center">
                <a href="{{ route('login') }}" class="text-sm text-gray-500 hover:text-gray-900">
                    Back to Login
                </a>
            </div>
        </div>
    </div>
</body>
</html>
