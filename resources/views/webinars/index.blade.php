@extends('layouts.app')

@section('content')
    <!-- Hero Section -->
    <section id="home" class="relative overflow-hidden bg-gray-100">
        <div style="width:100%;max-width:1200px;height:600px;margin:0 auto;position:relative;background:linear-gradient(135deg, rgba(59, 130, 246, 0.85) 0%, rgba(99, 102, 241, 0.85) 50%, rgba(139, 92, 246, 0.85) 100%);">
            <div id="star-overlay" style="position:absolute;inset:0;background:rgba(59, 130, 246, 0.2);"></div>
            <div class="relative max-w-7xl mx-auto px-6 py-40 sm:py-56 flex items-center justify-center h-full">
                <div class="text-center max-w-3xl mx-auto space-y-5">
                    <div class="inline-flex items-center gap-2 px-4 py-2 bg-white/15 backdrop-blur-md rounded-full border border-white/20">
                        <span class="relative flex h-2.5 w-2.5 mr-2.5">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-green-500"></span>
                        </span>
                        <span class="text-sm font-medium">Upcoming Sessions • Expert Led</span>
                    </div>
                    
                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-white leading-tight tracking-tight">
                        Visa Interview
                        <span class="block text-transparent bg-clip-text bg-gradient-to-r from-yellow-300 to-orange-300">Success Webinar</span>
                    </h1>
                    
                    <p class="text-base sm:text-lg text-white/90 leading-relaxed max-w-2xl mx-auto">
                        Master your visa interview with expert guidance. Learn proven strategies, common questions, and how to answer confidently to get your visa approved.
                    </p>
                </div>
            </div>
        </div>
        <div class="relative max-w-7xl mx-auto px-6 py-2 flex items-center justify-center">
        </div>
    </section>

    <style>
      @keyframes twinkle {
          0%, 100% { opacity: 0.3; transform: scale(1); }
          50% { opacity: 1; transform: scale(1.2); }
      }
      .star {
          position: absolute;
          background: white;
          border-radius: 50%;
          animation: twinkle 2s infinite ease-in-out;
      }
    </style>

    <script>
      (function() {
        const container = document.getElementById('star-overlay');
        if (!container) return;
        const count = 60;
        for (let i = 0; i < count; i++) {
            const star = document.createElement('div');
            star.style.position = 'absolute';
            star.style.top = Math.random() * 100 + '%';
            star.style.left = Math.random() * 100 + '%';
            const size = Math.random() * 3 + 1;
            star.style.width = size + 'px';
            star.style.height = size + 'px';
            star.style.background = 'white';
            star.style.borderRadius = '50%';
            star.style.opacity = Math.random() * 0.6 + 0.2;
            star.style.animation = 'twinkle ' + (Math.random() * 2 + 1) + 's infinite ease-in-out';
            star.style.animationDelay = Math.random() * 2 + 's';
            container.appendChild(star);
        }
      })();
    </script>

    <style>
      #about{ padding: 96px 24px; }
      #about .wrap{ max-width: 1180px; margin: 0 auto; }
      #about .head{ text-align:center; margin-bottom: 64px; }
      #about .eyebrow{ display:inline-flex; align-items:center; gap:8px; font-size:12px; letter-spacing:0.14em; text-transform:uppercase; font-weight:600; color:var(--indigo); margin-bottom:18px; }
      #about .eyebrow::before{ content:""; width:16px; height:1px; background: linear-gradient(90deg, transparent, var(--indigo)); }
      #about .eyebrow::after{ content:""; width:16px; height:1px; background: linear-gradient(90deg, var(--indigo), transparent); }
      #about h2{ font-family:'Space Grotesk', sans-serif; font-size: clamp(32px, 4.2vw, 46px); font-weight:700; letter-spacing:-0.01em; margin-bottom: 14px; background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%); -webkit-background-clip: text; background-clip: text; color: transparent; }
      #about .subtext{ font-size: 17px; color: var(--muted); max-width: 480px; margin: 0 auto; }
      #about .swipe-hint{ display:flex; align-items:center; justify-content:center; gap:6px; font-size:12px; color: var(--muted-dim); margin-top:14px; }
      #about .swipe-hint svg{ width:14px; height:14px; stroke: var(--muted-dim); fill:none; stroke-width:2; stroke-linecap:round; stroke-linejoin:round; animation: swipe 1.6s ease-in-out infinite; }
      @keyframes swipe{ 0%, 100% { transform: translateX(0); opacity:0.6; } 50% { transform: translateX(4px); opacity:1; } }
      @media (min-width:768px){ #about .swipe-hint{ display:none; } }

      #about .grid{ display:flex; flex-direction:row; gap:16px; overflow-x:auto; scroll-snap-type: x mandatory; -webkit-overflow-scrolling: touch; padding-bottom: 12px; margin: 0 -24px; padding-left: 24px; padding-right: 24px; scrollbar-width: none; }
      #about .grid::-webkit-scrollbar{ display:none; }
      #about .card{ scroll-snap-align: start; flex: 0 0 78%; }
      @media (min-width:640px){ #about .card{ flex: 0 0 48%; } }
      @media (min-width:768px){ #about .grid{ display:grid; grid-template-columns: repeat(3, 1fr); gap: 22px; overflow-x: visible; margin: 0; padding: 0; } #about .card{ flex: initial; } }
      #about .card{ position:relative; background: var(--card); border: 1px solid var(--card-line); border-radius: 18px; padding: 30px 26px; transition: transform 0.25s ease, background 0.25s ease, border-color 0.25s ease, box-shadow 0.25s ease; overflow:hidden; }
      #about .card::before{ content:""; position:absolute; inset:0; background: linear-gradient(160deg, rgba(99,102,241,0.08), transparent 55%); opacity:0; transition: opacity 0.25s ease; pointer-events:none; }
      #about .card:hover{ transform: translateY(-4px); background: var(--card-hover); border-color: rgba(139,92,246,0.4); box-shadow: 0 20px 40px -20px rgba(99,102,241,0.35); }
      #about .card:hover::before{ opacity:1; }
      #about .step-index{ position:absolute; top:24px; right:26px; font-family:'Space Grotesk', sans-serif; font-size:13px; font-weight:600; color: var(--muted-dim); letter-spacing:0.02em; }
      #about .icon-badge{ width: 60px; height: 60px; border-radius: 18px; display:flex; align-items:center; justify-content:center; margin-bottom: 22px; background: #ffffff; box-shadow: 0 4px 12px rgba(0,0,0,0.25); }
      #about .icon-badge svg{ width: 32px; height: 32px; stroke: #6366f1; fill: none; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }
      #about h3{ font-family:'Space Grotesk', sans-serif; font-size: 18px; font-weight:600; margin-bottom: 10px; letter-spacing:-0.005em; }
      #about .card p{ font-size: 14.5px; line-height: 1.55; color: var(--muted); }
      #about .rail{ position:absolute; left:0; top:0; bottom:0; width:3px; background: linear-gradient(180deg, var(--indigo), var(--blue)); opacity:0; transition: opacity 0.25s ease; }
      #about .card:hover .rail{ opacity:1; }
      @media (prefers-reduced-motion: reduce){ #about .card, #about .card::before, #about .rail{ transition:none; } #about .swipe-hint svg{ animation:none; } }
    </style>

<section id="about" style="background: radial-gradient(120% 100% at 50% -10%, #171C33 0%, var(--bg) 55%); color: var(--text); font-family:'Inter', sans-serif;">
  <div class="wrap">
    <div class="head">
      <div class="eyebrow">Webinar curriculum</div>
      <h2>What you'll learn</h2>
    </div>

    <div class="grid">

      <div class="card">
        <span class="rail"></span>
        <span class="step-index">01</span>
        <div class="icon-badge">
          <svg viewBox="0 0 24 24" stroke="#6366f1"><path d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8-1.06 0-2.077-.16-3.02-.454L3 21l1.5-4.5C3.55 15.16 3 13.63 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
        </div>
        <h3>Common interview questions</h3>
        <p>Learn the questions officers ask most often and how to answer them clearly and confidently.</p>
      </div>

      <div class="card">
        <span class="rail"></span>
        <span class="step-index">02</span>
        <div class="icon-badge">
          <svg viewBox="0 0 24 24" stroke="#6366f1"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6M9 13h6M9 17h6M9 9h1"/></svg>
        </div>
        <h3>Document preparation</h3>
        <p>Know exactly which documents you need and how to organize them so nothing holds you back.</p>
      </div>

      <div class="card">
        <span class="rail"></span>
        <span class="step-index">03</span>
        <div class="icon-badge">
          <svg viewBox="0 0 24 24" stroke="#6366f1"><circle cx="12" cy="8" r="3.2"/><path d="M5 21c0-3.9 3.13-7 7-7s7 3.1 7 7"/></svg>
        </div>
        <h3>Body language &amp; confidence</h3>
        <p>Master the posture, tone, and eye contact that project confidence in under a minute.</p>
      </div>

      <div class="card">
        <span class="rail"></span>
        <span class="step-index">04</span>
        <div class="icon-badge">
          <svg viewBox="0 0 24 24" stroke="#6366f1"><path d="M4 21V4"/><path d="M4 4h13l-2.5 4L17 12H4"/></svg>
        </div>
        <h3>Red flags to avoid</h3>
        <p>Learn the common mistakes that lead to denials — and how to steer clear of them entirely.</p>
      </div>

      <div class="card">
        <span class="rail"></span>
        <span class="step-index">05</span>
        <div class="icon-badge">
          <svg viewBox="0 0 24 24" stroke="#6366f1"><rect x="2" y="6" width="14" height="12" rx="2"/><path d="M16 10l6-3v10l-6-3"/></svg>
        </div>
        <h3>Mock interview practice</h3>
        <p>Join live mock interviews and get real-time feedback from experts on your performance.</p>
      </div>

      <div class="card">
        <span class="rail"></span>
        <span class="step-index">06</span>
        <div class="icon-badge">
          <svg viewBox="0 0 24 24" stroke="#6366f1"><path d="M12 2l2.9 6.26L21.5 9l-4.75 4.4L18 20l-6-3.5L6 20l1.25-6.6L2.5 9l6.6-.74z"/></svg>
        </div>
        <h3>Success stories</h3>
        <p>Hear real approvals from past attendees and the exact strategies that worked for them.</p>
      </div>

    </div>
  </div>
</section>



    <!-- Registration Form Section -->
    @if($registrationFormEnabled)
    <section id="register" class="relative overflow-hidden bg-white">
        
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 py-20">
            <div class="bg-white border border-gray-200 rounded-[24px] p-10 sm:p-12 shadow-2xl relative overflow-hidden" style="box-shadow: 0 40px 80px -30px rgba(0,0,0,0.6);">
                
                <!-- Glow effect -->
                <div class="absolute -top-20 -right-20 w-64 h-64 bg-gradient-to-br from-purple-500/20 to-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>
                
                <!-- Signal row -->
                <div class="flex items-center gap-3 mb-5">
                    <span class="relative flex h-4 w-4">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-4 w-4 bg-indigo-600 shadow-sm" style="animation: zoomPulse 2s ease-in-out infinite;"></span>
                    </span>
                    <span class="text-xs font-semibold tracking-widest uppercase text-indigo-700">Limited spots available</span>
                </div>

                <!-- Title & subtitle -->
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-3 tracking-tight">Register for the webinar</h2>
                <p class="text-base text-gray-600 leading-relaxed mb-8 max-w-lg">Secure your spot for the upcoming session before seats run out.</p>

                <style>
                    @keyframes zoomPulse {
                        0%, 100% {
                            transform: scale(1);
                        }
                        50% {
                            transform: scale(1.2);
                        }
                    }

                    @keyframes fillBarUp {
                        from { height: 0; }
                        to { height: var(--bar-height); }
                    }
                </style>

                <!-- Seats meter -->
                <div class="mb-8">
                    <div class="flex gap-1 h-5 items-end mb-2" id="bars">
                        @php
                            $totalBars = 24;
                            $filledBars = (int) round($totalBars * 0.92);
                        @endphp
                        @for($i = 0; $i < $totalBars; $i++)
                            <span class="flex-1 rounded-sm {{ $i < $filledBars ? 'bg-gradient-to-t from-indigo-500 to-blue-500' : 'bg-gray-200' }}" style="height: 0; --bar-height: {{ 40 + ($i % 5) * 12 }}%; animation: fillBarUp 0.6s ease-out infinite; animation-delay: {{ $i * 0.04 }}s;"></span>
                        @endfor
                    </div>
                    <div class="flex justify-between text-xs text-gray-500">
                        <span>Seats filling fast</span>
                        <strong class="text-gray-900 font-semibold">92% claimed</strong>
                    </div>
                </div>

                <div class="h-px bg-gray-200 mb-6"></div>

                @php
                    $webinar = $webinars->first();
                @endphp

                @if($webinar)
                    <!-- Price row -->
                    <div class="flex items-baseline justify-between mb-8">
                        <span class="text-xs font-semibold tracking-widest uppercase text-gray-500">Price</span>
                        <span class="text-3xl font-bold text-indigo-900 tracking-tight bg-indigo-100 px-4 py-1.5 rounded-3xl shadow-sm">${{ number_format($webinar->current_price, 2) }}</span>
                    </div>

                    <!-- CTA -->
                    <a href="{{ route('webinars.register.page', $webinar->id) }}" target="_self" class="flex items-center justify-center gap-2 w-full py-4 bg-gradient-to-r from-indigo-500 to-purple-500 text-white rounded-3xl font-semibold text-base hover:from-indigo-600 hover:to-purple-600 transition-all duration-300 shadow-lg" style="box-shadow: 0 12px 24px -8px rgba(139,92,246,0.35);">
                        Proceed to registration
                        <svg class="w-4 h-4 transition-transform duration-150 group-hover:translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                    </a>
                @else
                    <div class="bg-white/5 rounded-xl p-6 border border-white/10 text-center">
                        <svg class="w-12 h-12 text-[#8C92AC] mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <h3 class="text-lg font-semibold text-white mb-1">No Webinars Available</h3>
                        <p class="text-sm text-[#8C92AC]">There are currently no webinars available for registration.</p>
                    </div>
                @endif

                <p class="text-center text-xs text-[#8C92AC] mt-5">No refunds after registration closes</p>
            </div>
        </div>
    </section>
    @endif
@endsection

  @include('chat-widget')
   @include('components.cookie-consent')