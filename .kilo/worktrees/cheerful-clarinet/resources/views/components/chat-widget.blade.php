<!-- Chat Button -->
<button id="chatToggle" style="position: fixed !important; bottom: 20px !important; right: 20px !important; z-index: 99999 !important; width: 60px !important; height: 60px !important; border-radius: 50% !important; background: #2563eb !important; color: white !important; border: none !important; cursor: pointer !important; font-size: 24px !important; box-shadow: 0 4px 12px rgba(0,0,0,0.3) !important;">
  💬
</button>

<!-- Chat Container -->
<div id="chatWidget" style="position: fixed !important; bottom: 90px !important; right: 20px !important; z-index: 99999 !important; width: 350px !important; max-width: calc(100vw - 40px) !important; background: white !important; border-radius: 16px !important; box-shadow: 0 8px 30px rgba(0,0,0,0.2) !important; overflow: hidden !important; display: none; flex-direction: column !important;">
  <div style="background: #2563eb !important; color: white !important; padding: 16px !important; display: flex !important; justify-content: space-between !important; align-items: center !important;">
    <div>
      <div style="font-weight: 600 !important;">AI Assistant</div>
      <div style="font-size: 12px !important; opacity: 0.8 !important;">Ask me anything</div>
    </div>
    <button id="closeChat" style="background: none !important; border: none !important; color: white !important; font-size: 20px !important; cursor: pointer !important;">✕</button>
  </div>

  <div id="chatMessages" style="flex: 1 !important; padding: 12px !important; overflow-y: auto !important; background: #f9fafb !important; min-height: 200px !important; max-height: 250px !important;"></div>

  <div id="typing" style="padding: 8px 12px !important; font-size: 12px !important; color: #9ca3af !important; display: none !important;">AI is typing...</div>

  <div style="padding: 12px !important; border-top: 1px solid #e5e7eb !important; display: flex !important; gap: 8px !important;">
    <input id="chatInput" type="text" placeholder="Type a message..." style="flex: 1 !important; border: 1px solid #d1d5db !important; border-radius: 9999px !important; padding: 8px 16px !important; font-size: 14px !important; outline: none !important;" />
    <button id="sendBtn" style="background: #2563eb !important; color: white !important; padding: 8px 16px !important; border-radius: 9999px !important; border: none !important; cursor: pointer !important;">Send</button>
  </div>
</div>

<script>
(function() {
    var chatToggle = document.getElementById('chatToggle');
    var chatWidget = document.getElementById('chatWidget');
    var closeChat = document.getElementById('closeChat');
    var sendBtn = document.getElementById('sendBtn');
    var chatInput = document.getElementById('chatInput');
    var chatMessages = document.getElementById('chatMessages');
    var typing = document.getElementById('typing');

    var isWaitingForAI = false;
    var uniqueId = localStorage.getItem('chat_unique_id');

    function scrollToBottom() {
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    function appendUserMessage(message) {
        var div = document.createElement('div');
        div.style.cssText = 'display: flex; justify-content: flex-end;';
        div.innerHTML = '<div style="background: #2563eb; color: white; padding: 8px 12px; border-radius: 12px; font-size: 14px; max-width: 75%; word-wrap: break-word;">' + message + '</div>';
        chatMessages.appendChild(div);
        localStorage.setItem('chat_history', chatMessages.innerHTML);
        scrollToBottom();
    }

    function appendAIMessage(message) {
        var div = document.createElement('div');
        div.style.cssText = 'display: flex;';
        div.innerHTML = '<div style="background: #e5e7eb; color: #1f2937; padding: 8px 12px; border-radius: 12px; font-size: 14px; max-width: 75%; word-wrap: break-word;">' + message + '</div>';
        chatMessages.appendChild(div);
        localStorage.setItem('chat_history', chatMessages.innerHTML);
        scrollToBottom();
    }

    function toggleChat() {
        var isHidden = chatWidget.style.display === 'none' || chatWidget.style.display === '';
        chatWidget.style.display = isHidden ? 'flex' : 'none';
        
        // Initialize uniqueId on first open
        if (!uniqueId) {
            uniqueId = 'chat_' + Date.now();
            localStorage.setItem('chat_unique_id', uniqueId);
        }
    }

    function sendMessage() {
        var message = chatInput.value.trim();
        if (!message || isWaitingForAI) return;

        appendUserMessage(message);
        chatInput.value = '';
        scrollToBottom();

        isWaitingForAI = true;
        chatInput.disabled = true;
        sendBtn.disabled = true;
        typing.style.display = 'block';

        // Call the backend API with OpenAI
        var formData = new FormData();
        formData.append('message', message);
        formData.append('_token', '{{ csrf_token() }}');
        if (uniqueId) {
            formData.append('unique_id', uniqueId);
        }

        fetch('{{ route("chat.store") }}', {
            method: 'POST',
            body: formData
        })
        .then(function(res) { return res.json(); })
        .then(function(data) {
            typing.style.display = 'none';
            
            // Save unique_id for future conversations
            if (data.chat && data.chat.unique_id && !uniqueId) {
                uniqueId = data.chat.unique_id;
                localStorage.setItem('chat_unique_id', uniqueId);
            }
            
            if (data.ai_reply && data.ai_reply.message) {
                appendAIMessage(data.ai_reply.message);
            } else {
                appendAIMessage("Sorry, I couldn't process your request. Please try again.");
            }
            scrollToBottom();
        })
        .catch(function(err) {
            typing.style.display = 'none';
            appendAIMessage("Connection error. Please check your internet and try again.");
            console.error('Chat error:', err);
        })
        .finally(function() {
            isWaitingForAI = false;
            chatInput.disabled = false;
            sendBtn.disabled = false;
            chatInput.focus();
        });
    }

    chatToggle.addEventListener('click', toggleChat);
    closeChat.addEventListener('click', toggleChat);
    sendBtn.addEventListener('click', sendMessage);
    chatInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') sendMessage();
    });

    // Hide widget initially
    chatWidget.style.display = 'none';

    // Load saved messages
    var saved = localStorage.getItem('chat_history');
    if (saved) {
        chatMessages.innerHTML = saved;
        scrollToBottom();
    } else {
        appendAIMessage("Hello 👋, ask me anything!");
    }
})();
</script>