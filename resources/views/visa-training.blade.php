@extends('layouts.customer')

@section('content')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@500;600;700&display=swap" rel="stylesheet">
<style>
    * { box-sizing: border-box; }
    body { margin: 0; min-height: 100vh; background: #0a0e1a !important; font-family: 'Inter', ui-sans-serif, system-ui, sans-serif; color: #e2e8f0; }
    .page-wrap { display: flex; min-height: calc(100vh - 64px); margin-top: 64px; }
    .app-sidebar { width: 280px; min-width: 260px; background: linear-gradient(180deg, #111827, #0f172a); border-right: 1px solid rgba(255,255,255,0.06); display: flex; flex-direction: column; }
    .sidebar-brand { padding: 24px 20px; border-bottom: 1px solid rgba(255,255,255,0.06); }
    .brand-row { display: flex; align-items: center; gap: 12px; margin-bottom: 16px; }
    .brand-avatar { width: 40px; height: 40px; border-radius: 10px; background: linear-gradient(135deg, #6366f1, #8b5cf6); display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(99,102,241,0.3); overflow: hidden; }
    .brand-avatar img { width: 100%; height: 100%; object-fit: cover; }
    .brand-text { flex: 1; }
    .brand-name { font-family: 'Playfair Display', Georgia, serif; font-size: 1.15rem; font-weight: 700; color: #f8fafc; letter-spacing: -0.02em; }
    .brand-sub { font-size: 0.68rem; color: #64748b; text-transform: uppercase; letter-spacing: 0.08em; font-weight: 500; margin-top: 2px; }
    .new-btn { width: 100%; padding: 10px; background: linear-gradient(135deg, #6366f1, #8b5cf6); color: white; border: none; border-radius: 10px; font-size: 0.8rem; font-weight: 600; cursor: pointer; transition: all 0.2s; box-shadow: 0 4px 12px rgba(99,102,241,0.25); }
    .new-btn:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(99,102,241,0.35); }
    .sidebar-nav { flex: 1; padding: 12px; display: flex; flex-direction: column; gap: 2px; overflow-y: auto; }
    .nav-item { display: flex; align-items: center; gap: 10px; padding: 9px 12px; border-radius: 8px; color: #94a3b8; text-decoration: none; font-size: 0.82rem; font-weight: 500; transition: all 0.15s; cursor: pointer; }
    .nav-item:hover { background: rgba(255,255,255,0.04); color: #e2e8f0; }
    .nav-item.active { background: rgba(99,102,241,0.15); color: #a5b4fc; }
    .nav-item svg { width: 18px; height: 18px; flex-shrink: 0; }
    .chat-panel { flex: 1; display: flex; flex-direction: column; min-width: 0; background: #0f172a; position: relative; }
    .chat-header-bar { padding: 14px 24px; border-bottom: 1px solid rgba(255,255,255,0.06); display: flex; align-items: center; justify-content: space-between; background: rgba(15,23,42,0.8); backdrop-filter: blur(12px); flex-shrink: 0; }
    .chat-header-bar h2 { margin: 0; font-size: 1.05rem; font-weight: 600; color: #f8fafc; }
    .chat-header-bar .sub { font-size: 0.72rem; color: #64748b; margin-top: 2px; }
    .badge { font-size: 0.68rem; padding: 4px 10px; border-radius: 999px; background: rgba(99,102,241,0.15); color: #a5b4fc; font-weight: 500; }
    .view-container { flex: 1; position: relative; overflow: hidden; min-height: 0; }
    .view-panel { position: absolute; inset: 0; transition: transform 0.45s cubic-bezier(0.4, 0, 0.2, 1); display: flex; }
    .view-panel.panel-left { transform: translateX(-100%); pointer-events: none; }
    .view-panel.panel-right { transform: translateX(100%); pointer-events: none; }
    .view-panel.panel-center { transform: translateX(0); pointer-events: auto; }
    #video-template { align-items: center; justify-content: center; z-index: 1; }
    #chat-messages { z-index: 2; overflow-y: auto; padding: 20px 24px; flex-direction: column; gap: 12px; background: #0f172a; }
    .video-card { max-width: 380px; margin: 0 auto; text-align: center; padding: 32px; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); border-radius: 20px; backdrop-filter: blur(8px); width: 100%; }
    .visa-options { display: flex; flex-direction: column; gap: 8px; padding: 8px 0; align-items: center; }
    .visa-select-wrapper { position: relative; width: 100%; max-width: 280px; }
    .visa-selected { width: 100%; padding: 12px 16px; background: #1e1b4b; border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; color: #f8fafc; font-size: 0.85rem; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: space-between; transition: all 0.2s; font-family: inherit; }
    .visa-selected:hover { border-color: rgba(99,102,241,0.5); background: rgba(99,102,241,0.08); }
    .visa-arrow { width: 16px; height: 16px; transition: transform 0.25s ease; }
    .visa-options-list { display: none; flex-direction: column; gap: 6px; margin-top: 6px; }
    .visa-options-list.open { display: flex; }
    .visa-option-btn { width: 100%; padding: 11px 16px; background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); border-radius: 10px; color: #cbd5e1; font-size: 0.82rem; font-weight: 500; cursor: pointer; text-align: left; transition: all 0.15s; font-family: inherit; }
    .visa-option-btn:hover { background: rgba(99,102,241,0.15); border-color: rgba(99,102,241,0.4); color: #f8fafc; }
    .visa-option-btn:hover { background: rgba(99,102,241,0.15); border-color: rgba(99,102,241,0.4); color: #f8fafc; }
    .visa-btn-b1b2 { background: #4f46e5 !important; color: white !important; border: none !important; font-weight: 600 !important; }
    .visa-btn-b1b2:hover { background: #4338ca !important; }
    .chat-input-bar { padding: 14px 24px; border-top: 1px solid rgba(255,255,255,0.06); display: flex; gap: 10px; background: rgba(15,23,42,0.6); backdrop-filter: blur(12px); flex-shrink: 0; }
    .session-status-bar { padding: 16px 24px; background: rgba(15,23,42,0.4); border-bottom: 1px solid rgba(255,255,255,0.04); flex-shrink: 0; }
    .session-card { max-width: 380px; margin: 0 auto; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); border-radius: 16px; padding: 16px 20px; display: flex; align-items: center; justify-content: space-between; cursor: pointer; transition: all 0.2s; user-select: none; }
    .session-card:hover { border-color: rgba(99,102,241,0.5); background: rgba(99,102,241,0.06); transform: translateY(-1px); box-shadow: 0 6px 20px rgba(99,102,241,0.15); }
    .session-card-left { display: flex; align-items: center; gap: 12px; }
    .session-card-icon { width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #4f46e5, #7c3aed); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .session-card-text { font-size: 0.85rem; font-weight: 600; color: #f8fafc; }
    .session-card-sub { font-size: 0.72rem; color: #64748b; margin-top: 2px; }
    .session-timer { font-size: 0.78rem; font-weight: 600; color: #a5b4fc; font-variant-numeric: tabular-nums; }
    .session-timer.running { color: #fbbf24; }
    .session-timer.ended { color: #ef4444; }
    .chat-input-bar input { flex: 1; background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1); border-radius: 999px; padding: 10px 16px; color: white; outline: none; font-size: 0.88rem; font-family: inherit; }
    .chat-input-bar input:focus { border-color: #6366f1; background: rgba(255,255,255,0.08); }
    .send-btn { width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #6366f1, #8b5cf6); border: none; color: white; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.2s; box-shadow: 0 4px 12px rgba(99,102,241,0.3); }
    .send-btn:hover:not(:disabled) { transform: scale(1.05); }
    .send-btn:disabled { opacity: 0.4; cursor: not-allowed; }
    .typing-indicator { display: flex; align-items: center; gap: 6px; padding: 6px 24px; color: #475569; font-size: 0.72rem; flex-shrink: 0; }
    .typing-dots { display: flex; gap: 3px; }
    .typing-dot { width: 5px; height: 5px; background: #475569; border-radius: 50%; }
    .completion-banner { background: rgba(16,185,129,0.12); border: 1px solid rgba(16,185,129,0.25); border-radius: 10px; padding: 10px 14px; margin: 4px 0; color: #6ee7b7; font-size: 0.82rem; text-align: center; }
    @media (max-width: 860px) { .page-wrap { flex-direction: column; } .app-sidebar { width: 100%; min-width: 0; max-height: 45vh; } }
</style>

<div class="page-wrap">
    <aside class="app-sidebar">
        <div class="sidebar-brand">
            <div class="brand-row">
                <div class="brand-avatar">
                    <img src="{{ asset('officer-charles.png') }}" alt="Officer Charles" onerror="this.style.display='none'; this.parentNode.innerHTML='<svg width=\\'20\\' height=\\'20\\' fill=\\'none\\' stroke=\\'white\\' viewBox=\\'0 0 24 24\\'><path stroke-linecap=\\'round\\' stroke-linejoin=\\'round\\' stroke-width=\\'2\\' d=\\'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z\\'/></svg>'">
                </div>
                <div class="brand-text">
                    <div class="brand-name">Visa Interview AI</div>
                    <div class="brand-sub">Training Platform</div>
                </div>
            </div>
            <button class="new-btn" onclick="resetInterview()">+ New Interview</button>
        </div>

        <nav class="sidebar-nav">
            <a class="nav-item" onclick="switchToDashboard()">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                Dashboard
            </a>
            <a class="nav-item active" onclick="switchToChat()">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                Chat Interview
            </a>
            <a class="nav-item" href="{{ route('visa-interview.plans') }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                Video Interview
                <span style="margin-left:auto; background:#fbbf24; color:#78350f; font-size:0.65rem; padding:2px 8px; border-radius:999px; font-weight:600;">Premium</span>
            </a>
            <a class="nav-item">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg>
                Saved Answers
            </a>
            <a class="nav-item">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                Reports
            </a>
            <a class="nav-item">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                Study Tips
            </a>
            <a class="nav-item">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Settings
            </a>
        </nav>
    </aside>

    <main class="chat-panel">
        <div class="chat-header-bar" id="chat-header-bar" style="display: none;">
            <div>
                <h2 id="panel-title" style="display: flex; align-items: center; gap: 8px;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.8" style="flex-shrink: 0;"><circle cx="12" cy="8" r="4"/><path d="M4 21c0-4 4-7 8-7s8 3 8 7"/></svg>
                    Officer Charlotte
                </h2>
            </div>
            <span id="session-timer" class="session-timer" style="display: none; font-size: 0.75rem; font-weight: 600; color: #a5b4fc; font-variant-numeric: tabular-nums; margin-left: 6px;">05:00</span>
            <button id="session-toggle-btn" onclick="toggleSession()" style="display: none; margin-left: 10px; background: linear-gradient(135deg, #6366f1, #8b5cf6); color: white; border: none; padding: 6px 14px; border-radius: 8px; font-size: 0.72rem; font-weight: 600; cursor: pointer; box-shadow: 0 4px 12px rgba(99,102,241,0.3); white-space: nowrap; transition: all 0.2s;">
                Start Session
            </button>
        </div>

        <div class="view-container">
            <div id="video-template" class="view-panel panel-center">
                <div class="video-card">
                    <div style="width: 120px; height: 120px; margin: 0 auto 18px; border-radius: 50%; background: linear-gradient(135deg, #4f46e5, #7c3aed); padding: 3px; box-shadow: 0 8px 30px rgba(99,102,241,0.35);">
                        <div style="width: 100%; height: 100%; border-radius: 50%; overflow: hidden; background: #1e1b4b;">
                            <img src="{{ asset('officer-charles.png') }}" alt="Officer Charles" style="width: 100%; height: 100%; object-fit: cover;" onerror="this.style.display='none'; this.parentNode.innerHTML='<div style=\\'display:flex;align-items:center;justify-content:center;height:100%;font-size:36px;font-weight:700;color:white\\'>VC</div>'">
                        </div>
                    </div>
                    <div style="display: inline-flex; align-items: center; gap: 6px; background: rgba(239,68,68,0.15); border: 1px solid rgba(239,68,68,0.3); padding: 6px 14px; border-radius: 999px; margin-bottom: 14px;">
                        <span style="width: 7px; height: 7px; background: #ef4444; border-radius: 50%; box-shadow: 0 0 8px rgba(239,68,68,0.6);"></span>
                        <span style="font-size: 0.72rem; font-weight: 600; color: #fca5a5;">Ready to Connect</span>
                    </div>
                    <h3 style="margin: 0 0 4px; font-size: 1.05rem; font-weight: 600; color: #f8fafc;">Officer Charles</h3>
                    <p style="margin: 0 0 20px; font-size: 0.78rem; color: #64748b;">Visa Interview Specialist</p>
                    <a href="{{ route('visa-interview.plans') }}" style="background: linear-gradient(135deg, #6366f1, #8b5cf6); color: white; border: none; padding: 11px 26px; border-radius: 12px; font-size: 0.85rem; font-weight: 600; cursor: pointer; box-shadow: 0 4px 18px rgba(99,102,241,0.35); transition: transform 0.2s; display: inline-block; text-decoration: none;">
                        Start Video Interview
                    </a>
                </div>
            </div>

            <div id="chat-messages" class="view-panel panel-right" style="flex-direction: column; gap: 12px;">
                <div id="visa-options-slot"></div>
            </div>
        </div>

        <div id="typing-row" class="typing-indicator" style="display: none;">
            <div class="typing-dots"><span class="typing-dot"></span><span class="typing-dot"></span><span class="typing-dot"></span></div>
            <span>Officer Charles is typing...</span>
        </div>

        <div class="chat-input-bar">
            <input type="text" id="chat-input" placeholder="Type your answer..." autocomplete="off" disabled>
            <button id="send-btn" class="send-btn" onclick="sendChatMessage()" disabled>
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M12 5l7 7-7 7"/></svg>
            </button>
        </div>
    </main>
</div>

<script>
(function(){
    var chatInput = document.getElementById('chat-input');
    var sendBtn = document.getElementById('send-btn');
    var messages = document.getElementById('chat-messages');
    var typingRow = document.getElementById('typing-row');
    var stepIndicator = document.getElementById('step-indicator');
    var completed = {{ $isCompleted ? 'true' : 'false' }};

    if (completed) {
        chatInput.disabled = true;
        sendBtn.disabled = true;
        chatInput.placeholder = 'Interview ended. Click + New Interview to retry.';
    }

    function scrollToBottom() {
        if (messages) {
            requestAnimationFrame(function() { messages.scrollTop = messages.scrollHeight; });
        }
    }

    function appendMessage(role, content, time) {
        var wrap = document.createElement('div');
        var cls = role === 'officer' ? 'officer' : 'user';
        wrap.className = 'msg ' + cls;
        var timeStr = time || (role === 'officer' ? '' : 'You');
        var avatarHtml = '';
        if (role === 'officer') {
            avatarHtml = '<div class="msg-avatar"><img src="{{ asset('officer-charles.png') }}" alt="Charles" onerror="this.style.display=\'none\'; this.innerHTML=\'<span class=initials>VC</span>\'"></div>';
        } else {
            avatarHtml = '<div class="msg-avatar"><span class="initials">You</span></div>';
        }
        wrap.innerHTML = avatarHtml + '<div class="msg-content"><div class="msg-bubble">' + escapeHtml(content) + '</div><div class="msg-meta">' + escapeHtml(timeStr) + '</div></div>';
        messages.appendChild(wrap);
        messages.scrollTop = messages.scrollHeight;
    }

    function showTyping() { typingRow.style.display = 'flex'; scrollToBottom(); }
    function hideTyping() { typingRow.style.display = 'none'; }
    function escapeHtml(text) { var d = document.createElement('div'); d.textContent = text; return d.innerHTML; }
    function getCsrf() { var m = document.querySelector('meta[name=csrf-token]'); return m ? m.getAttribute('content') : ''; }

    function sendChatMessage() {
        if (completed) return;
        var msg = chatInput.value.trim();
        if (!msg) return;

        appendMessage('user', msg);
        chatInput.value = '';
        chatInput.disabled = true;
        sendBtn.disabled = true;

        showTyping();
        fetch("{{ route('visa-training.chat') }}", {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': getCsrf() },
            body: JSON.stringify({ message: msg })
        })
        .then(function(r){ return r.json(); })
        .then(function(data) {
            hideTyping();
            if (data && data.success) {
                appendMessage('officer', data.reply);
                if (data.step !== undefined && stepIndicator) stepIndicator.textContent = data.step;
                if (data.completed) {
                    completed = true;
                    chatInput.disabled = true;
                    sendBtn.disabled = true;
                    chatInput.placeholder = 'Interview ended. Click + New Interview to retry.';
                }
            } else {
                appendMessage('officer', 'Sorry, something went wrong. Please try again.');
            }
        })
        .catch(function(err) {
            hideTyping();
            appendMessage('officer', 'Network error. Please try again.');
        })
        .finally(function() {
            if (!completed) { chatInput.disabled = false; sendBtn.disabled = false; chatInput.focus(); }
        });
    }

    window.resetInterview = function() {
        if (sessionInterval) clearInterval(sessionInterval);
        sessionSeconds = 300;
        var timerEl = document.getElementById('session-timer');
        if (timerEl) { timerEl.textContent = '05:00'; timerEl.classList.remove('running', 'ended'); }
        var toggleBtn = document.getElementById('session-toggle-btn');
        if (toggleBtn) { toggleBtn.textContent = 'Start Session'; toggleBtn.style.background = 'linear-gradient(135deg, #6366f1, #8b5cf6)'; toggleBtn.style.display = 'inline-flex'; }
        if (chatInput) { chatInput.disabled = true; chatInput.value = ''; chatInput.placeholder = 'Type your answer...'; }
        if (sendBtn) sendBtn.disabled = true;
        window.location.replace("{{ route('visa-training.reset') }}");
    };

    var sessionRunning = false;

    window.toggleSession = function() {
        var toggleBtn = document.getElementById('session-toggle-btn');
        var timerEl = document.getElementById('session-timer');
        if (!sessionRunning) {
            switchToChat();
            startTimer();
            sessionRunning = true;
            if (toggleBtn) { toggleBtn.textContent = 'Stop'; toggleBtn.style.background = 'linear-gradient(135deg, #ef4444, #dc2626)'; }
            if (timerEl) timerEl.style.display = 'inline-flex';
        } else {
            if (sessionInterval) clearInterval(sessionInterval);
            sessionSeconds = 300;
            sessionRunning = false;
            if (timerEl) { timerEl.textContent = '05:00'; timerEl.classList.remove('running', 'ended'); }
            if (toggleBtn) { toggleBtn.textContent = 'Start Session'; toggleBtn.style.background = 'linear-gradient(135deg, #6366f1, #8b5cf6)'; }
            if (chatInput) { chatInput.disabled = true; }
            if (sendBtn) sendBtn.disabled = true;
        }
    };

    var sessionInterval = null;
    var sessionSeconds = 300;

    function formatTime(seconds) {
        var m = Math.floor(seconds / 60);
        var s = seconds % 60;
        return String(m).padStart(2, '0') + ':' + String(s).padStart(2, '0');
    }

    function startTimer() {
        sessionSeconds = 300;
        var timerEl = document.getElementById('session-timer');
        if (timerEl) timerEl.textContent = formatTime(sessionSeconds);

        if (sessionInterval) clearInterval(sessionInterval);
        sessionInterval = setInterval(function() {
            sessionSeconds--;
            if (timerEl) timerEl.textContent = formatTime(sessionSeconds);
            if (sessionSeconds <= 60 && timerEl) timerEl.classList.add('running');
            if (sessionSeconds <= 0) {
                clearInterval(sessionInterval);
                if (timerEl) { timerEl.classList.remove('running'); timerEl.classList.add('ended'); timerEl.textContent = '00:00'; }
                if (chatInput) chatInput.disabled = true;
                if (sendBtn) sendBtn.disabled = true;
                if (chatInput) chatInput.placeholder = 'Session ended. Click + New Interview to retry.';
            }
        }, 1000);
    }

    window.startSession = function() {
        switchToChat();
        startTimer();
    };

    window.switchToChat = function() {
        var videoEl = document.getElementById('video-template');
        var chatEl = document.getElementById('chat-messages');
        var titleEl = document.getElementById('panel-title');
        var badgeEl = document.getElementById('session-badge');
        var inputEl = document.getElementById('chat-input');
        var btnEl = document.getElementById('send-btn');
        var headerEl = document.getElementById('chat-header-bar');
        var timerEl = document.getElementById('session-timer');
        var toggleBtn = document.getElementById('session-toggle-btn');

        if (videoEl) { videoEl.classList.remove('panel-center'); videoEl.classList.add('panel-left'); }
        if (chatEl) { chatEl.classList.remove('panel-right'); chatEl.classList.add('panel-center'); }
        if (headerEl) headerEl.style.display = 'flex';
        if (badgeEl) badgeEl.style.display = 'none';
        if (timerEl) timerEl.style.display = 'inline-flex';
        if (toggleBtn) { toggleBtn.style.display = 'inline-flex'; toggleBtn.textContent = 'Start Session'; toggleBtn.style.background = 'linear-gradient(135deg, #6366f1, #8b5cf6)'; }
        sessionRunning = false;
        if (inputEl) inputEl.disabled = false;
        if (btnEl) btnEl.disabled = false;

        setTimeout(function() { showVisaOptions(); }, 350);
    };

    window.switchToVideo = function() {
        var videoEl = document.getElementById('video-template');
        var chatEl = document.getElementById('chat-messages');
        var titleEl = document.getElementById('panel-title');
        var badgeEl = document.getElementById('session-badge');
        var inputEl = document.getElementById('chat-input');
        var btnEl = document.getElementById('send-btn');
        var headerEl = document.querySelector('.chat-header-bar');

        if (chatEl) { chatEl.classList.remove('panel-center'); chatEl.classList.add('panel-right'); }
        if (videoEl) { videoEl.classList.remove('panel-left'); videoEl.classList.add('panel-center'); }
        if (headerEl) headerEl.style.display = 'none';
        if (inputEl) { inputEl.disabled = true; inputEl.value = ''; }
        if (btnEl) btnEl.disabled = true;
    };

    window.switchToDashboard = function() { switchToVideo(); };
    window.startVideoCall = window.switchToChat;

    var visaOptionsShown = false;
    var visaTypeSelected = false;

    function showVisaOptions() {
        if (visaOptionsShown || visaTypeSelected || completed) return;
        var slot = document.getElementById('visa-options-slot');
        if (!slot) return;
        var hasUserMsg = false;
        if (messages) {
            for (var i = 0; i < messages.children.length; i++) {
                if (messages.children[i].classList.contains('user')) { hasUserMsg = true; break; }
            }
        }
        if (hasUserMsg) return;
        visaOptionsShown = true;

        slot.innerHTML = '<div class="visa-options">' +
            '<div class="visa-select-wrapper">' +
                '<button class="visa-selected" id="visa-dropdown-toggle">' +
                    'F1 (Student Visa)' +
                    '<svg class="visa-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>' +
                '</button>' +
                '<div class="visa-options-list" id="visa-dropdown-list">' +
                    '<button class="visa-option-btn visa-btn-b1b2" data-type="I want to apply for B1/B2 Business/Tourist Visa">B1/B2 (Business/Tourist Visa)</button>' +
                '</div>' +
            '</div>' +
        '</div>';

        var toggle = document.getElementById('visa-dropdown-toggle');
        var list = document.getElementById('visa-dropdown-list');
        var arrow = toggle ? toggle.querySelector('.visa-arrow') : null;

        if (toggle && list) {
            toggle.addEventListener('click', function() {
                var isOpen = list.classList.contains('open');
                list.classList.toggle('open');
                if (arrow) {
                    arrow.style.transform = isOpen ? 'rotate(0deg)' : 'rotate(180deg)';
                }
            });
        }

        list.querySelectorAll('.visa-option-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                visaTypeSelected = true;
                slot.innerHTML = '';
                sendChatMessageWithText(this.getAttribute('data-type'));
            });
        });
    }

    function sendChatMessageWithText(text) {
        if (completed) return;
        var msg = text.trim();
        if (!msg) return;

        appendMessage('user', msg);
        chatInput.value = '';
        chatInput.disabled = true;
        sendBtn.disabled = true;

        showTyping();
        fetch("{{ route('visa-training.chat') }}", {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': getCsrf() },
            body: JSON.stringify({ message: msg })
        })
        .then(function(r){ return r.json(); })
        .then(function(data) {
            hideTyping();
            if (data && data.success) {
                appendMessage('officer', data.reply);
                if (data.step !== undefined && stepIndicator) stepIndicator.textContent = data.step;
                if (data.completed) {
                    completed = true;
                    chatInput.disabled = true;
                    sendBtn.disabled = true;
                    chatInput.placeholder = 'Interview ended. Click + New Interview to retry.';
                }
            } else {
                appendMessage('officer', 'Sorry, something went wrong. Please try again.');
            }
        })
        .catch(function(err) {
            hideTyping();
            appendMessage('officer', 'Network error. Please try again.');
        })
        .finally(function() {
            if (!completed) { chatInput.disabled = false; sendBtn.disabled = false; chatInput.focus(); }
        });
    }

    chatInput.addEventListener('keydown', function(e) { if (e.key === 'Enter') { e.preventDefault(); sendChatMessage(); } });
    scrollToBottom();
})();
</script>
@endsection
