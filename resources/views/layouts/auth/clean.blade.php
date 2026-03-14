<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Log in' }}</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('favicon.ico') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            min-height: 100vh;
        }
        .bg-bookshop {
            background-image: url('https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?w=1920&q=80');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
    </style>
</head>
<body class="bg-bookshop flex items-center justify-center min-h-screen p-4">
    <!-- Dark overlay with small blur -->
    <div class="absolute inset-0 bg-black/40 backdrop-blur-[2px]"></div>
    
    <div class="w-full max-w-md relative z-10">
        <!-- Card -->
        <div class="bg-white/95 rounded-2xl shadow-2xl p-8">
            {{ $slot }}
        </div>
    </div>
</body>
</html>
