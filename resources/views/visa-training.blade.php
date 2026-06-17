@extends('layouts.customer')

@section('content')
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #312e81 100%) !important;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            color: #e2e8f0;
        }
        .page-wrap {
            display: flex;
            min-height: calc(100vh - 64px);
            margin-top: 64px;
        }
        .chat-sidebar {
            width: 420px;
            min-width: 360px;
            background: #0b1220;
            border-right: 1px solid rgba(255,255,255,0.08);
            display: flex;
            flex-direction: column;
        }
        .chat-header {
            padding: 16px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: linear-gradient(180deg, rgba(99,102,241,0.1), rgba(0,0,0,0));
        }
        .chat-header-title {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .chat-header-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 14px;
        }
        .chat-header-meta h2 {
            margin: 0;
            font-size: 14px;
            color: #f8fafc;
        }
        .chat-header-meta p {
            margin: 0;
            font-size: 11px;
            color: #94a3b8;
        }
        .chat-body {
            flex: 1;
            overflow-y: auto;
            padding: 16px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .chat-input-area {
            padding: 12px 16px;
            border-top: 1px solid rgba(255,255,255,0.08);
            display: flex;
            gap: 8px;
        }
        .chat-input-area input {
            flex: 1;
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 999px;
            padding: 10px 14px;
            color: white;
            outline: none;
            font-size: 13px;
        }
        .chat-input-area input:focus {
            border-color: #6366f1;
            background: rgba(255,255,255,0.09);
        }
        .chat-send-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            border: none;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }
        .chat-send-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        .msg {
            display: flex;
            gap: 8px;
            max-width: 100%;
        }
        .msg.user {
            justify-content: flex-end;
            flex-direction: row !important;
            align-items: flex-start;
        }
        .msg.user > div {
            display: inline-block;
            max-width: 95%;
            width: auto;
            flex-shrink: 0;
        }
        .msg.user > div > .msg-bubble {
            max-width: 100%;
            width: auto;
            display: inline-block;
            white-space: normal;
        }
        .msg.officer {
            justify-content: flex-start;
        }
        .msg-bubble {
            max-width: 95%;
            padding: 10px 12px;
            border-radius: 16px;
            font-size: 13px;
            line-height: 1.45;
            white-space: pre-wrap;
            word-break: break-word;
        }
        .msg.user .msg-bubble {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: white;
            border-bottom-right-radius: 6px;
        }
        .msg.officer .msg-bubble {
            background: rgba(255,255,255,0.08);
            color: #e2e8f0;
            border: 1px solid rgba(255,255,255,0.08);
            border-bottom-left-radius: 6px;
        }
        .msg-time {
            font-size: 10px;
            color: #64748b;
            margin-top: 4px;
        }
        .msg.user .msg-time { text-align: right; }
        .main-area {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 32px;
            min-width: 0;
            margin-top: 64px;
        }
        .video-chat-button {
            position: relative;
            width: 320px;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 24px;
            padding: 28px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
        }
        .video-chat-button:hover {
            transform: translateY(-6px) scale(1.02);
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(99, 102, 241, 0.5);
            box-shadow: 0 20px 40px rgba(99, 102, 241, 0.3);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .video-chat-button::before {
            content: '';
            position: absolute;
            inset: -1px;
            border-radius: 25px;
            padding: 1px;
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.3), transparent, rgba(168, 85, 247, 0.3));
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            opacity: 0;
            transition: opacity 0.4s ease;
        }
        .video-chat-button:hover::before { opacity: 1; }
        .profile-container { position: relative; width: 110px; height: 110px; margin: 0 auto 18px; }
        .profile-ring {
            position: absolute; inset: 0; border-radius: 50%;
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            animation: rotate 4s linear infinite;
        }
        @keyframes rotate { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
        .profile-ring-inner {
            position: absolute; inset: 3px; border-radius: 50%;
            background: linear-gradient(135deg, #1e1b4b, #312e81);
        }
        .profile-img {
            position: absolute; inset: 6px; border-radius: 50%;
            overflow: hidden; z-index: 2;
        }
        .profile-img img { width: 100%; height: 100%; object-fit: cover; }
        .video-badge {
            position: absolute; bottom: 4px; right: 4px;
            width: 30px; height: 30px;
            background: #4f46e5; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            z-index: 3; border: 2px solid #1e1b4b;
        }
        .video-badge svg { width: 14px; height: 14px; color: white; }
        .live-indicator {
            position: absolute; top: 6px; left: 50%; transform: translateX(-50%);
            display: flex; align-items: center; gap: 4px;
            background: rgba(239, 68, 68, 0.9);
            padding: 3px 10px; border-radius: 20px;
            font-size: 10px; font-weight: 700; color: white;
            letter-spacing: 0.5px; z-index: 3;
        }
        .live-dot {
            width: 6px; height: 6px; background: white; border-radius: 50%;
            animation: pulse 1.5s ease-in-out infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.5; transform: scale(1.2); }
        }
        .name-section { text-align: center; margin-bottom: 10px; }
        .name { font-size: 1.1rem; font-weight: 700; color: white; margin-bottom: 2px; }
        .role { font-size: 0.75rem; color: rgba(255, 255, 255, 0.6); }
        .price-tag {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: white; text-align: center;
            font-size: 0.85rem; font-weight: 700;
            padding: 8px 16px; border-radius: 12px;
        }
        .cta-hint { text-align: center; margin-top: 14px; font-size: 0.7rem; color: rgba(255, 255, 255, 0.4); }
        .reset-btn {
            font-size: 11px;
            color: #94a3b8;
            background: transparent;
            border: 1px solid rgba(255,255,255,0.1);
            padding: 4px 10px;
            border-radius: 999px;
            cursor: pointer;
        }
        .reset-btn:hover {
            color: #f87171;
            border-color: rgba(248,113,113,0.4);
        }
        .typing-indicator {
            display: flex;
            gap: 4px;
            padding: 6px 10px;
        }
        .typing-dot {
            width: 7px; height: 7px;
            background: #94a3b8;
            border-radius: 50%;
            animation: typingBounce 1s infinite alternate;
        }
        .typing-dot:nth-child(2) { animation-delay: 0.2s; }
        .typing-dot:nth-child(3) { animation-delay: 0.4s; }
        @keyframes typingBounce {
            from { transform: translateY(0); opacity: 0.4; }
            to { transform: translateY(-5px); opacity: 1; }
        }
        .sidebar-footer {
            padding: 10px 16px;
            font-size: 10px;
            color: #475569;
            text-align: center;
            border-top: 1px solid rgba(255,255,255,0.05);
        }
        .completion-banner {
            background: rgba(16, 185, 129, 0.15);
            border: 1px solid rgba(16, 185, 129, 0.3);
            border-radius: 12px;
            padding: 10px 12px;
            margin: 4px 8px;
            color: #6ee7b7;
            font-size: 12px;
            text-align: center;
        }
        @media (max-width: 860px) {
            .page-wrap { flex-direction: column; }
            .chat-sidebar { width: 100%; min-width: 0; max-height: 55vh; border-right: none; border-bottom: 1px solid rgba(255,255,255,0.08); }
            .main-area { padding: 16px; margin-top: 64px; }
        }
        .visa-options {
            display: flex;
            gap: 10px;
            padding: 10px 4px 4px;
            flex-wrap: wrap;
        }
        .visa-btn {
            padding: 10px 16px;
            border-radius: 12px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            color: white;
            font-size: 13px;
            transition: transform .15s ease, box-shadow .15s ease;
        }
        .visa-btn-f1 { background: #4f46e5; }
        .visa-btn-f1:hover { background: #4338ca; transform: translateY(-1px); box-shadow: 0 6px 16px rgba(79,70,229,.35); }
        .visa-btn-b1b2 { background: #7c3aed; }
        .visa-btn-b1b2:hover { background: #6d28d9; transform: translateY(-1px); box-shadow: 0 6px 16px rgba(124,58,237,.35); }
    </style>

    <div class="page-wrap">
        <aside class="chat-sidebar">
            <div class="chat-header">
                <div class="chat-header-title">
                    <div class="chat-header-avatar">VC</div>
                    <div class="chat-header-meta">
                        <h2>Visa Interview Training</h2>
                        <p>Officer Charles · Step <span id="step-indicator">{{ $currentStep ?? 0 }}</span> / {{ $totalQuestions ?? 15 }}</p>
                    </div>
                </div>
                <button class="reset-btn" onclick="resetInterview()">Reset</button>
            </div>
            <div id="chat-messages" class="chat-body">
                @foreach($conversationHistory as $msg)
                    <div class="msg {{ $msg['role'] === 'user' ? 'user' : 'officer' }}">
                        <div>
                            <div class="msg-bubble">{{ $msg['content'] }}</div>
                            <div class="msg-time">{{ $msg['role'] === 'officer' ? 'Officer' : 'You' }}</div>
                        </div>
                    </div>
                @endforeach
                <div id="visa-options-slot"></div>
                <div id="completion-banner" class="completion-banner" style="display: {{ $isCompleted ? 'block' : 'none' }};">
                    Interview complete. Type "restart" or click Reset to begin again.
                </div>
            </div>
            <div id="typing-row" class="typing-indicator" style="display: none;">
                <div class="typing-dot"></div>
                <div class="typing-dot"></div>
                <div class="typing-dot"></div>
            </div>
            <div class="chat-input-area">
                <input type="text" id="chat-input" placeholder="Type your answer..." autocomplete="off">
                <button id="send-btn" class="chat-send-btn" onclick="sendChatMessage()">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M12 5l7 7-7 7"/></svg>
                </button>
            </div>
            <div class="sidebar-footer">Best experienced on desktop · Simulated interview</div>
        </aside>
        <main class="main-area">
            <div>
                <div class="video-chat-button">
                    <div class="live-indicator">
                        <span class="live-dot"></span>
                        AVAILABLE
                    </div>
                    <div class="profile-container">
                        <div class="profile-ring"></div>
                        <div class="profile-ring-inner"></div>
                        <div class="profile-img">
                            <img src="{{ asset('officer-charles.png') }}" alt="Officer Charles">
                        </div>
                        <div class="video-badge">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="name-section">
                        <div class="name">Officer Charles</div>
                        <div class="role">Visa Interview Specialist</div>
                    </div>
                    <div class="price-tag">
                        Video Interview · $5 only
                    </div>
                    <div class="cta-hint">Chat with the officer in the sidebar</div>
                </div>
                <div class="coming-soon-banner" style="margin-top: 24px; padding: 16px; background: rgba(6, 182, 212, 0.15); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 12px; text-align: center; color: #67e8f9; font-size: 13px;">
                    <strong>Coming Soon:</strong> Video call chat with Officer Charles
                </div>
            </div>
        </main>
    </div>
    <script>
    (function(){
        var chatInput = document.getElementById('chat-input');
        var sendBtn = document.getElementById('send-btn');
        var messages = document.getElementById('chat-messages');
        var typingRow = document.getElementById('typing-row');
        var completionBanner = document.getElementById('completion-banner');
        var stepIndicator = document.getElementById('step-indicator');
        var completed = {{ $isCompleted ? 'true' : 'false' }};

        if (completed) {
            chatInput.disabled = true;
            sendBtn.disabled = true;
            chatInput.placeholder = 'Interview ended. Click Reset to retry.';
        }

        function scrollToBottom() {
            requestAnimationFrame(function() {
                messages.scrollTop = messages.scrollHeight;
            });
        }

        function appendMessage(role, content, time) {
            var wrap = document.createElement('div');
            var cls = role === 'officer' ? 'officer' : 'user';
            wrap.className = 'msg ' + cls;
            var timeStr = time || (role === 'officer' ? 'Officer' : 'You');
            wrap.innerHTML = '<div><div class="msg-bubble">' + escapeHtml(content) + '</div><div class="msg-time">' + escapeHtml(timeStr) + '</div></div>';
            messages.appendChild(wrap);
            messages.scrollTop = messages.scrollHeight;
        }

        function showTyping() {
            typingRow.style.display = 'flex';
            scrollToBottom();
        }

        function hideTyping() {
            typingRow.style.display = 'none';
        }

        function escapeHtml(text) {
            var d = document.createElement('div');
            d.textContent = text;
            return d.innerHTML;
        }

        function getCsrf() {
            var m = document.querySelector('meta[name=csrf-token]');
            return m ? m.getAttribute('content') : '';
        }

        function sendChatMessage() {
            if (completed) return;
            var msg = chatInput.value.trim();
            if (!msg) return;

            appendMessage('user', msg);
            chatInput.value = '';
            chatInput.disabled = true;
            sendBtn.disabled = true;

            if (/^\s*restart\s*$/i.test(msg)) {
                resetInterview();
                return;
            }

            showTyping();
            fetch("{{ route('visa-training.chat') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': getCsrf()
                },
                body: JSON.stringify({ message: msg })
            })
            .then(function(r){ return r.json(); })
            .then(function(data) {
                hideTyping();
                if (data && data.success) {
                    appendMessage('officer', data.reply);
                    if (data.step !== undefined) stepIndicator.textContent = data.step;
                    if (data.completed) {
                        completed = true;
                        completionBanner.style.display = 'block';
                        chatInput.disabled = true;
                        sendBtn.disabled = true;
                        chatInput.placeholder = 'Interview ended. Click Reset to retry.';
                    }
                } else {
                    appendMessage('officer', 'Sorry, something went wrong. Please try again.');
                }
            })
            .catch(function(err) {
                hideTyping();
                var msg = 'Network error. Please try again.';
                if (err && err.message) msg += ' (' + err.message + ')';
                appendMessage('officer', msg);
            })
            .finally(function() {
                if (!completed) {
                    chatInput.disabled = false;
                    sendBtn.disabled = false;
                    chatInput.focus();
                }
            });
        }

        window.resetInterview = function() {
            window.location.replace("{{ route('visa-training.reset') }}");
        };

        var visaOptionsShown = false;
        var visaTypeSelected = false;

        function showVisaOptions() {
            if (visaOptionsShown || visaTypeSelected || completed) return;
            var slot = document.getElementById('visa-options-slot');
            if (!slot) return;
            var allMessages = messages.children;
            var hasUserMsg = false;
            for (var i = 0; i < allMessages.length; i++) {
                if (allMessages[i].classList.contains('user')) { hasUserMsg = true; break; }
            }
            if (hasUserMsg) return;
            visaOptionsShown = true;
            slot.innerHTML = '<div class="visa-options">' +
                '<button class="visa-btn visa-btn-f1" data-type="I want to apply for F1 Student Visa">F1 (Student Visa)</button>' +
                '<button class="visa-btn visa-btn-b1b2" data-type="I want to apply for B1/B2 Business/Tourist Visa">B1/B2 (Business/Tourist Visa)</button>' +
                '</div>';
            slot.querySelectorAll('.visa-btn').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    visaTypeSelected = true;
                    slot.innerHTML = '';
                    var text = this.getAttribute('data-type');
                    sendChatMessageWithText(text);
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
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': getCsrf()
                },
                body: JSON.stringify({ message: msg })
            })
            .then(function(r){ return r.json(); })
            .then(function(data) {
                hideTyping();
                if (data && data.success) {
                    appendMessage('officer', data.reply);
                    if (data.step !== undefined) stepIndicator.textContent = data.step;
                    if (data.completed) {
                        completed = true;
                        completionBanner.style.display = 'block';
                        chatInput.disabled = true;
                        sendBtn.disabled = true;
                        chatInput.placeholder = 'Interview ended. Click Reset to retry.';
                    }
                } else {
                    appendMessage('officer', 'Sorry, something went wrong. Please try again.');
                }
            })
            .catch(function(err) {
                hideTyping();
                var msg = 'Network error. Please try again.';
                if (err && err.message) msg += ' (' + err.message + ')';
                appendMessage('officer', msg);
            })
            .finally(function() {
                if (!completed) {
                    chatInput.disabled = false;
                    sendBtn.disabled = false;
                    chatInput.focus();
                }
            });
        }

        chatInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                sendChatMessage();
            }
        });

        scrollToBottom();
        showVisaOptions();
    })();
    </script>
@endsection