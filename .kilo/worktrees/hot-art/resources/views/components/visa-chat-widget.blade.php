<div id="visa-chat-widget" class="fixed bottom-4 right-4" style="z-index: 999999;">
    {{-- Chat Panel --}}
    <div
        id="visa-chat-panel"
        style="display: none; position: absolute; bottom: 100%; right: 0; width: 300px; height: 460px;"
        class="bg-white rounded-2xl shadow-2xl border border-gray-200 flex flex-col overflow-hidden"
    >
        {{-- Header --}}
        <div class="p-3 border-b border-gray-200 flex items-center justify-between bg-gradient-to-r from-indigo-600 to-purple-600 rounded-t-2xl flex-shrink-0">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-white rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                </div>
                <div>
                    <h3 class="text-white font-bold text-xs">Visa Assistant AI</h3>
                    <p class="text-indigo-200 text-[10px]">Powered by Artificial Intelligence</p>
                </div>
            </div>
            <button onclick="closeChat()" class="text-white hover:text-indigo-200 p-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        {{-- Messages (scrollable) --}}
        <div id="chat-messages" class="flex-1 overflow-y-auto p-3 space-y-2 bg-gray-50 min-h-0" style="-webkit-overflow-scrolling: touch; overscroll-behavior: contain;">
            <div class="text-center py-4">
                <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                </div>
                <p class="text-gray-800 font-semibold text-xs mb-1">Hello 👋 I'm your Visa Assistant AI.</p>
                <p class="text-gray-500 text-[10px] mb-2">Which country are you interested in?</p>
                <div class="flex flex-wrap gap-1 justify-center">
                    <button onclick="sendQuickMessage('How do I apply for a UK visa?')" class="px-2 py-1 bg-white border border-indigo-200 text-indigo-700 text-[10px] rounded-full">UK visa</button>
                    <button onclick="sendQuickMessage('Student visa requirements')" class="px-2 py-1 bg-white border border-indigo-200 text-indigo-700 text-[10px] rounded-full">Student visa</button>
                    <button onclick="sendQuickMessage('Canada visa process')" class="px-2 py-1 bg-white border border-indigo-200 text-indigo-700 text-[10px] rounded-full">Canada visa</button>
                    <button onclick="sendQuickMessage('Visa interview tips')" class="px-2 py-1 bg-white border border-indigo-200 text-indigo-700 text-[10px] rounded-full">Interview tips</button>
                    <button onclick="sendQuickMessage('Required travel documents')" class="px-2 py-1 bg-white border border-indigo-200 text-indigo-700 text-[10px] rounded-full">Documents</button>
                </div>
            </div>
        </div>

        {{-- Input --}}
        <div class="p-2 border-t border-gray-200 flex gap-2 flex-shrink-0 rounded-b-2xl">
            <input type="text" id="chat-input" placeholder="Ask about visas..." class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-xs outline-none" onkeydown="if(event.key==='Enter'){event.preventDefault();sendChatMessage();}">
            <button onclick="sendChatMessage()" class="px-3 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                <img src="{{ asset('send_message.png') }}" class="w-4 h-4">
            </button>
        </div>
    </div>

    {{-- Floating Button --}}
    <button id="chat-toggle-btn" onclick="window.location.href='{{ route('visa-training') }}'" class="w-14 h-14 bg-white border-2 border-indigo-600 rounded-full shadow-xl flex items-center justify-center hover:shadow-2xl transition-all relative" title="Visa Interview Training">
        <img src="{{ asset('chatbox_icon.png') }}" class="w-8 h-8 object-contain">
        <span class="absolute inset-0 rounded-full bg-indigo-400 animate-ping opacity-30"></span>
    </button>
</div>

<script>
(function() {
    var style = document.createElement('style');
    style.textContent = '@keyframes typingBounce{0%,100%{transform:translateY(0);opacity:.6}50%{transform:translateY(-6px);opacity:1}}';
    document.head.appendChild(style);

    var open = false;
    var sessionToken = null;
    var typingIndicator = null;

    function isGreeting(msg) {
        var m = msg.trim().toLowerCase();
        return /^(hi|hello|hey|good morning|good afternoon|good evening|howdy|greetings|yo|what'?s up|whats up|sup)$/.test(m);
    }

    function init() {
        var stored = sessionStorage.getItem('visa_chat_token');
        if (stored) {
            sessionToken = stored;
            loadHistory();
        }
    }

    function toggleChat() {
        open = !open;
        var panel = document.getElementById('visa-chat-panel');
        if (open) {
            panel.style.display = 'flex';
            panel.style.flexDirection = 'column';
            if (!sessionToken) startSession();
            scrollChat();
            document.addEventListener('click', clickAwayHandler);
        } else {
            panel.style.display = 'none';
            document.removeEventListener('click', clickAwayHandler);
        }
    }

    function clickAwayHandler(e) {
        var widget = document.getElementById('visa-chat-widget');
        if (widget && !widget.contains(e.target)) {
            closeChat();
            document.removeEventListener('click', clickAwayHandler);
        }
    }

    function closeChat() {
        open = false;
        var panel = document.getElementById('visa-chat-panel');
        if (panel) panel.style.display = 'none';
    }

    function startSession() {
        fetch('/visa-chat/start', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || '' },
        })
        .then(r => r.json())
        .then(d => {
            if (d.success) {
                sessionToken = d.session.token;
                sessionStorage.setItem('visa_chat_token', sessionToken);
            }
        })
        .catch(e => console.error('[VisaChat] Start error:', e));
    }

    function sendChatMessage() {
        var input = document.getElementById('chat-input');
        var msg = input.value.trim();
        if (!msg) return;

        if (isGreeting(msg)) {
            addMessage('user', msg);
            input.value = '';
            addMessage('assistant', "Hello! I'm your Visa Assistant AI — I'm here to help you with visa applications, travel requirements, interviews, required documents, and immigration procedures. Which country or visa type are you interested in?");
            return;
        }

        addMessage('user', msg);
        input.value = '';
        var typingStart = Date.now();
        typingIndicator = showTyping();

        fetch('/visa-chat/message', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || '' },
            body: JSON.stringify({ session_token: sessionToken, message: msg }),
        })
        .then(r => r.json())
        .then(d => {
            var elapsed = Date.now() - typingStart;
            var remaining = Math.max(0, 4000 - elapsed);
            setTimeout(function() {
                hideTyping(typingIndicator);
                if (d.success && d.reply) addMessage('assistant', d.reply);
            }, remaining);
        })
        .catch(e => { console.error('[VisaChat] Send error:', e); hideTyping(typingIndicator); });
    }

    function sendQuickMessage(q) {
        addMessage('user', q);
        var typingStart = Date.now();
        showTyping();

        fetch('/visa-chat/message', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || '' },
            body: JSON.stringify({ session_token: sessionToken, message: q }),
        })
        .then(r => r.json())
        .then(d => {
            var elapsed = Date.now() - typingStart;
            var remaining = Math.max(0, 4000 - elapsed);
            setTimeout(function() {
                hideTyping();
                if (d.success && d.reply) addMessage('assistant', d.reply);
            }, remaining);
        })
        .catch(e => { console.error('[VisaChat] Quick error:', e); hideTyping(); });
    }

    function addMessage(role, content) {
        var container = document.getElementById('chat-messages');
        var time = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        var div = document.createElement('div');
        div.className = role === 'user' ? 'flex justify-end' : 'flex justify-start';
        div.innerHTML = '<div class="' + (role === 'user' ? 'bg-indigo-600 text-white rounded-2xl' : 'bg-white text-gray-800 rounded-2xl border border-gray-200') + ' px-3 py-2 max-w-[85%] shadow-sm"><p class="text-[11px] leading-relaxed whitespace-pre-line">' + escapeHtml(content) + '</p><p class="text-[9px] mt-0.5 text-right ' + (role === 'user' ? 'text-indigo-200' : 'text-gray-400') + '">' + time + '</p></div>';
        container.appendChild(div);
        scrollChat();
    }

    function escapeHtml(text) {
        var d = document.createElement('div');
        d.textContent = text;
        return d.innerHTML;
    }

    function showTyping() {
        var container = document.getElementById('chat-messages');
        hideTyping();
        var div = document.createElement('div');
        div.id = 'typing-indicator';
        div.className = 'flex justify-start';
        div.innerHTML = '<div class="bg-white text-gray-800 rounded-xl rounded-bl-sm border border-gray-200 px-3 py-2 shadow-sm"><div class="flex gap-1.5 items-center"><div class="w-3 h-3 bg-indigo-500 rounded-full" style="animation: typingBounce 0.6s infinite alternate; animation-delay: 0ms;"></div><div class="w-3 h-3 bg-indigo-500 rounded-full" style="animation: typingBounce 0.6s infinite alternate; animation-delay: 200ms;"></div><div class="w-3 h-3 bg-indigo-500 rounded-full" style="animation: typingBounce 0.6s infinite alternate; animation-delay: 400ms;"></div></div></div>';
        container.appendChild(div);
        requestAnimationFrame(function() {
            scrollChat();
        });
        return div;
    }

    function hideTyping() {
        var el = document.getElementById('typing-indicator');
        if (el) el.remove();
    }

    function scrollChat() {
        var c = document.getElementById('chat-messages');
        if (c) c.scrollTop = c.scrollHeight;
    }

    function loadHistory() {
        if (!sessionToken) return;
        fetch('/visa-chat/history?session_token=' + sessionToken)
        .then(r => r.json())
        .then(d => {
            if (d.success && d.messages) {
                var container = document.getElementById('chat-messages');
                d.messages.forEach(function(m) {
                    var time = new Date(m.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                    var div = document.createElement('div');
                    div.className = m.role === 'user' ? 'flex justify-end' : 'flex justify-start';
                    div.innerHTML = '<div class="' + (m.role === 'user' ? 'bg-indigo-600 text-white rounded-xl rounded-br-sm' : 'bg-white text-gray-800 rounded-xl rounded-bl-sm border border-gray-200') + ' px-3 py-2 max-w-[85%] shadow-sm"><p class="text-[11px] leading-relaxed whitespace-pre-line">' + escapeHtml(m.content) + '</p><p class="text-[9px] mt-0.5 text-right ' + (m.role === 'user' ? 'text-indigo-200' : 'text-gray-400') + '">' + time + '</p></div>';
                    container.appendChild(div);
                });
                scrollChat();
            }
        })
        .catch(e => console.error('[VisaChat] History error:', e));
    }

    window.toggleChat = toggleChat;
    window.closeChat = closeChat;
    window.sendChatMessage = sendChatMessage;
    window.sendQuickMessage = sendQuickMessage;

    init();
})();
</script>
