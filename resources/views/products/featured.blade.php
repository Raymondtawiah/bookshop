<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="overflow-x-hidden">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#4f46e5" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="default" />
    <meta name="apple-mobile-web-app-title" content="BookShop" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="manifest" href="/manifest.json">
    <title>{{ $book->title ?? 'Featured Book' }} - {{ config('app.name', 'Bookshop') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="apple-touch-icon" href="/favicon.ico">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then((registration) => {
                        console.log('SW registered: ', registration);
                    })
                    .catch((registrationError) => {
                        console.log('SW registration failed: ', registrationError);
                    });
            });
        });
    </script>
    <style>
        :root {
            --indigo: #6366f1;
            --purple: #8b5cf6;
            --blue: #3b82f6;
        }

        .featured-stage {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 48px;
            width: 100%;
            padding: 80px 24px;
        }

        .featured-head {
            text-align: center;
        }

        .featured-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            font-weight: 600;
            color: var(--indigo);
            margin-bottom: 16px;
        }

        .featured-eyebrow::before,
        .featured-eyebrow::after {
            content: "";
            width: 16px;
            height: 1px;
            background: var(--indigo);
            opacity: 0.6;
        }

        .featured-head h1 {
            font-family: 'Space Grotesk', sans-serif;
            font-size: clamp(22px, 3vw, 28px);
            font-weight: 600;
            color: #374151;
            max-width: 520px;
            margin: 0 auto;
            line-height: 1.4;
        }

        .featured-scene {
            perspective: 1600px;
            width: 300px;
            height: 420px;
        }

        .featured-book {
            position: relative;
            width: 100%;
            height: 100%;
            transform-style: preserve-3d;
            transform: rotateY(-28deg) rotateX(4deg);
            transition: transform 0.6s cubic-bezier(.2,.8,.2,1);
        }

        .featured-scene:hover .featured-book {
            transform: rotateY(-8deg) rotateX(6deg);
        }

        .featured-cover {
            position: absolute;
            inset: 0;
            border-radius: 3px 8px 8px 3px;
            background:
                radial-gradient(120% 140% at 15% 0%, rgba(255,255,255,0.14), transparent 55%),
                linear-gradient(135deg, var(--indigo) 0%, var(--purple) 55%, var(--blue) 100%);
            transform: translateZ(19px);
            box-shadow: 0 40px 70px -25px rgba(20,10,60,0.65);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 30px 26px 26px;
            overflow: hidden;
        }

        .featured-cover::after {
            content: "";
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 14px;
            background: linear-gradient(90deg, rgba(0,0,0,0.35), transparent);
        }

        .featured-cover-top {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .featured-imprint {
            font-size: 10px;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: rgba(255,255,255,0.75);
            font-weight: 600;
        }

        .featured-cover-title {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 26px;
            font-weight: 700;
            line-height: 1.16;
            color: #ffffff;
            letter-spacing: -0.01em;
            margin-top: 6px;
        }

        .featured-cover-mid {
            display: flex;
            align-items: center;
            justify-content: center;
            flex: 1;
        }

        .featured-seal {
            width: 96px;
            height: 96px;
            border-radius: 50%;
            border: 1.5px dashed rgba(255,255,255,0.55);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .featured-seal-inner {
            width: 74px;
            height: 74px;
            border-radius: 50%;
            border: 1.5px solid rgba(255,255,255,0.7);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .featured-seal-inner svg {
            width: 32px;
            height: 32px;
            stroke: #ffffff;
            fill: none;
            stroke-width: 1.8;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .featured-cover-bottom {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
        }

        .featured-cover-sub {
            font-size: 11.5px;
            color: rgba(255,255,255,0.8);
            line-height: 1.5;
            max-width: 170px;
        }

        .featured-steps-badge {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 11px;
            font-weight: 600;
            color: #ffffff;
            background: rgba(255,255,255,0.16);
            padding: 5px 10px;
            border-radius: 20px;
            white-space: nowrap;
        }

        .featured-spine {
            position: absolute;
            left: -19px;
            top: 0;
            bottom: 0;
            width: 38px;
            background: linear-gradient(90deg, #4649c9, var(--indigo));
            transform: rotateY(-90deg);
            transform-origin: right;
            border-radius: 3px 0 0 3px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .featured-spine span {
            writing-mode: vertical-rl;
            transform: rotate(180deg);
            font-family: 'Space Grotesk', sans-serif;
            font-size: 10px;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: rgba(255,255,255,0.85);
            font-weight: 600;
        }

        .featured-pages {
            position: absolute;
            right: -8px;
            top: 6px;
            bottom: 6px;
            width: 16px;
            background: repeating-linear-gradient(
                90deg,
                #E8E6F5 0px, #E8E6F5 1px,
                #d7d5e8 1px, #d7d5e8 2px
            );
            transform: rotateY(90deg) translateZ(8px);
            transform-origin: left;
            border-radius: 0 2px 2px 0;
        }

        .featured-base-shadow {
            width: 220px;
            height: 28px;
            margin-top: -8px;
            background: radial-gradient(50% 100% at 50% 50%, rgba(99,102,241,0.35), transparent 75%);
            filter: blur(2px);
        }

        .featured-details-link {
            display: inline-block;
            padding: 12px 28px;
            background: var(--indigo);
            color: #ffffff;
            font-weight: 600;
            border-radius: 10px;
            text-decoration: none;
            transition: background 0.2s ease, transform 0.2s ease;
        }

        .featured-details-link:hover {
            background: #4f46e5;
            transform: translateY(-1px);
        }

        @media (max-width: 420px) {
            .featured-scene { width: 240px; height: 340px; }
            .featured-cover-title { font-size: 21px; }
            .featured-seal { width: 78px; height: 78px; }
            .featured-seal-inner { width: 60px; height: 60px; }
        }

        @media (prefers-reduced-motion: reduce) {
            .featured-book { transition: none; }
        }
    </style>
</head>
<body class="antialiased overflow-x-hidden m-0 p-0 box-border w-full min-w-0 min-h-screen">
    <x-flash-message />
    <x-customer-navbar />

    <div class="w-full overflow-x-hidden min-w-0 mx-0 px-0">
        <!-- Hero Image -->
         <br>
         <br>
        <section class="w-full">
            <img src="{{ asset('hero.jpg') }}" alt="Hero" class="w-full h-auto object-cover">
        </section>

        <div class="featured-stage">
            <div class="featured-head">
                <div class="featured-eyebrow">Cover preview</div>
                <h1>{{ $book->title }} — {{ $book->author }}</h1>
            </div>

            <a href="{{ route('product.show', $book->id) }}" class="featured-scene block">
                @if($book->cover_image)
                    <img src="{{ $book->cover_image_url }}" alt="{{ $book->title }}" class="w-full h-full object-cover rounded-lg shadow-xl">
                @else
                    <div class="w-full h-full flex items-center justify-center bg-gray-100 rounded-lg">
                        <svg class="w-24 h-24 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                @endif
            </a>
            <div class="featured-base-shadow"></div>
        </div>

        <x-customer-footer />

        <x-install-pwa />

        @include('feedback-widget')
        @include('components.cookie-consent')
    </div>
</body>
</html>
