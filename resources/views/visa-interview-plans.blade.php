@extends('layouts.customer')

@section('content')
<div class="min-h-[calc(100vh-12rem)] bg-[radial-gradient(circle_at_top_left,#4f46e522,transparent_34%),linear-gradient(180deg,#f8fafc,#eef2ff)] rounded-[2rem] p-6 md:p-10">
    <div class="mx-auto max-w-6xl">
        <div class="grid gap-8 lg:grid-cols-[1fr_360px] lg:items-center">
            <div>
                <div class="inline-flex items-center gap-2 rounded-full bg-indigo-100 px-4 py-2 text-sm font-bold text-indigo-700">
                    <span class="h-2 w-2 rounded-full bg-indigo-600"></span>
                    Premium AI Video Interview
                </div>
                <h1 class="mt-6 text-4xl font-black tracking-tight text-slate-950 md:text-6xl">
                    Choose the plan that fits your visa interview practice
                </h1>
                <p class="mt-5 max-w-2xl text-lg leading-8 text-slate-600">
                    Upgrade to AI Video Interview and practice with a realistic visa officer, voice feedback, facial expression analysis, eye-contact scoring, speaking speed insights, and a detailed report.
                </p>
                <div class="mt-8 flex flex-wrap gap-4">
                    <a href="{{ route('visa-training') }}" class="rounded-xl bg-slate-950 px-6 py-3 font-bold text-white shadow-lg shadow-indigo-500/20 transition hover:-translate-y-0.5 hover:bg-slate-800">
                        Back to Chat Training
                    </a>
                    <a href="#plans" class="rounded-xl border border-indigo-200 bg-white px-6 py-3 font-bold text-indigo-700 shadow-sm transition hover:-translate-y-0.5">
                        View Plans
                    </a>
                </div>
            </div>

            <div class="rounded-[2rem] border border-white/70 bg-white/80 p-6 shadow-2xl shadow-indigo-500/20 backdrop-blur">
                <div class="mx-auto h-44 w-44 rounded-full bg-gradient-to-br from-indigo-600 to-purple-600 p-2 shadow-xl shadow-indigo-500/30">
                    <div class="flex h-full w-full items-center justify-center overflow-hidden rounded-full bg-slate-950">
                        <img src="{{ asset('officer-charles.png') }}" alt="Officer Charles" class="h-full w-full object-cover" onerror="this.style.display='none'; this.parentElement.innerHTML='<span class=\'text-5xl font-black text-white\'>VC</span>'">
                    </div>
                </div>
                <div class="mt-6 text-center">
                    <h2 class="text-2xl font-black text-slate-950">Officer Charles</h2>
                    <p class="mt-2 text-sm font-semibold uppercase tracking-wide text-indigo-600">AI Visa Interview Specialist</p>
                    <p class="mt-3 text-slate-600">Practice real interview pressure with instant AI feedback.</p>
                </div>
            </div>
        </div>
        <br/>
        <div id="plans" class="mt-14 grid gap-6 md:grid-cols-3">
            <div class="rounded-[1.75rem] border border-slate-200 bg-white p-7 shadow-xl shadow-slate-900/5 transition hover:-translate-y-1 hover:shadow-2xl hover:shadow-indigo-500/10">
                <h3 class="text-2xl font-black text-slate-950">Single Session</h3>
                <div class="mt-5 flex items-end gap-2">
                    <span class="text-5xl font-black tracking-tight text-slate-950">$9.99</span>
                    <span class="mb-2 text-sm font-bold text-slate-500">/ session</span>
                </div>
                <ul class="mt-6 space-y-3 text-slate-600">
                    <li class="flex gap-3"><span class="font-black text-emerald-500">✓</span>1 AI video interview session</li>
                    <li class="flex gap-3"><span class="font-black text-emerald-500">✓</span>AI visa officer avatar</li>
                    <li class="flex gap-3"><span class="font-black text-emerald-500">✓</span>Voice and expression feedback</li>
                    <li class="flex gap-3"><span class="font-black text-emerald-500">✓</span>Detailed session report</li>
                </ul>
                <form action="{{ route('visa-training.choose-plan') }}" method="POST" class="mt-8">
                    @csrf
                    <input type="hidden" name="plan" value="session">
                    <button type="submit" class="w-full rounded-xl bg-indigo-600 px-6 py-3 font-black text-white transition hover:-translate-y-0.5 hover:bg-indigo-700">
                        Choose Single
                    </button>
                </form>
            </div>

            <div class="relative rounded-[1.75rem] border-2 border-indigo-500 bg-slate-950 p-7 shadow-2xl shadow-indigo-500/20 transition hover:-translate-y-1">
                <span class="absolute right-6 top-6 rounded-full bg-amber-300 px-4 py-1 text-xs font-black uppercase tracking-wide text-slate-950">Popular</span>
                <h3 class="text-2xl font-black text-white">Monthly Access</h3>
                <div class="mt-5 flex items-end gap-2">
                    <span class="text-5xl font-black tracking-tight text-white">$19.99</span>
                    <span class="mb-2 text-sm font-bold text-slate-400">/ month</span>
                </div>
                <ul class="mt-6 space-y-3 text-slate-300">
                    <li class="flex gap-3"><span class="font-black text-emerald-400">✓</span>5 video interview sessions</li>
                    <li class="flex gap-3"><span class="font-black text-emerald-400">✓</span>Priority AI video processing</li>
                    <li class="flex gap-3"><span class="font-black text-emerald-400">✓</span>Facial and eye-contact analysis</li>
                    <li class="flex gap-3"><span class="font-black text-emerald-400">✓</span>Downloadable performance reports</li>
                </ul>
                <form action="{{ route('visa-training.choose-plan') }}" method="POST" class="mt-8">
                    @csrf
                    <input type="hidden" name="plan" value="monthly">
                    <button type="submit" class="w-full rounded-xl bg-gradient-to-r from-indigo-500 to-purple-500 px-6 py-3 font-black text-white transition hover:-translate-y-0.5">
                        Choose Monthly
                    </button>
                </form>
            </div>

            <div class="rounded-[1.75rem] border border-slate-200 bg-white p-7 shadow-xl shadow-slate-900/5 transition hover:-translate-y-1 hover:shadow-2xl hover:shadow-indigo-500/10">
                <h3 class="text-2xl font-black text-slate-950">Pro Access</h3>
                <div class="mt-5 flex items-end gap-2">
                    <span class="text-5xl font-black tracking-tight text-slate-950">$49.99</span>
                    <span class="mb-2 text-sm font-bold text-slate-500">/ month</span>
                </div>
                <ul class="mt-6 space-y-3 text-slate-600">
                    <li class="flex gap-3"><span class="font-black text-emerald-500">✓</span>15 video interview sessions</li>
                    <li class="flex gap-3"><span class="font-black text-emerald-500">✓</span>Advanced speaking speed analysis</li>
                    <li class="flex gap-3"><span class="font-black text-emerald-500">✓</span>Full report history</li>
                    <li class="flex gap-3"><span class="font-black text-emerald-500">✓</span>Best value for serious practice</li>
                </ul>
                <form action="{{ route('visa-training.choose-plan') }}" method="POST" class="mt-8">
                    @csrf
                    <input type="hidden" name="plan" value="pro">
                    <button type="submit" class="w-full rounded-xl border-2 border-indigo-600 px-6 py-3 font-black text-indigo-700 transition hover:-translate-y-0.5 hover:bg-indigo-50">
                        Choose Pro
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
