<!-- Chat Widget -->
<button class="chat-fab" id="chatFab" aria-label="Chat with us">
    <svg class="icon-chat" viewBox="0 0 24 24" width="26" height="26" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
    </svg>
    <svg class="icon-close" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" style="display:none;">
        <path d="M6 6l12 12M18 6L6 18"/>
    </svg>
</button>

<!-- Chat Popup Tooltip -->
<div class="chat-popup show" id="chatPopup">
    <div class="chat-popup-bubble">
        <span class="chat-popup-icon">💬</span>
        <span class="chat-popup-text">Chat with an agent for anything you need help with!</span>
        <button class="chat-popup-close" id="chatPopupClose" aria-label="Dismiss">✕</button>
        <span class="chat-popup-arrow"></span>
    </div>
</div>

<div class="overlay" id="overlay"></div>

<div class="chat-card" id="chatCard">
    <div class="chat-header">
        <div>
            <h3>Chat with us</h3>
            <p class="chat-status">We typically reply in a few minutes</p>
        </div>
        <button class="close-btn" id="closeBtn" aria-label="Close">✕</button>
    </div>

    <div class="chat-messages" id="chatMessages">
        <button class="scroll-to-bottom-btn" id="scrollToBottomBtn" title="Latest messages" style="display: none;">
            <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 5v14M5 12l7 7 7-7"/>
            </svg>
        </button>
    </div>

    <div id="chat-reply-preview" class="chat-reply-preview" style="display: none;">
        <div class="reply-preview-header">
            <span>Replying to:</span>
            <button class="reply-cancel-btn" id="cancelReplyBtn">✕</button>
        </div>
        <div class="reply-preview-content" id="replyPreviewContent"></div>
    </div>

    <div id="chat-name-area" class="chat-input-area" style="display: none;">
        <input type="text" id="chatNameInput" placeholder="Your name" class="chat-name-input" style="display: block; margin-bottom: 0;">
        <button type="button" class="send-btn" id="sendNameBtn" disabled>
            <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="22" y1="2" x2="11" y2="13"/>
                <polygon points="22 2 15 22 11 13 2 9 22 2"/>
            </svg>
        </button>
    </div>

    <form class="chat-input-area" id="chatForm" style="display: none;">
        @csrf
        <input type="hidden" id="repliedToMessageId" value="">
        <textarea id="chatInput" placeholder="Type your message..." rows="1"></textarea>
        <button type="submit" class="send-btn" id="sendBtn" disabled>
            <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="22" y1="2" x2="11" y2="13"/>
                <polygon points="22 2 15 22 11 13 2 9 22 2"/>
            </svg>
        </button>
    </form>
</div>

<style>
  .chat-fab {
    position: fixed;
    bottom: 26px;
    right: 26px;
    width: 58px;
    height: 58px;
    border-radius: 50%;
    border: none;
    background: linear-gradient(135deg, #6366f1, #22d3ee);
    color: #fff;
    font-size: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    box-shadow: 0 10px 25px -6px rgba(99,102,241,0.55);
    transition: transform 0.25s cubic-bezier(.4,1.6,.5,1), box-shadow 0.25s ease;
    z-index: 20;
  }
  .chat-fab:hover { transform: scale(1.08); }
  .chat-fab.active { transform: scale(1.08); }

  .chat-popup {
    position: fixed;
    bottom: 100px;
    right: 26px;
    z-index: 19;
    opacity: 0;
    visibility: hidden;
    transform: translateY(10px) scale(0.95);
    transition: opacity 0.3s cubic-bezier(.4,1.6,.5,1),
                transform 0.3s cubic-bezier(.4,1.6,.5,1),
                visibility 0.3s ease;
  }
  .chat-popup.show { opacity: 1; visibility: visible; transform: translateY(0) scale(1); }

  .chat-popup-bubble {
    background: #ffffff;
    border-radius: 16px;
    padding: 14px 18px;
    box-shadow: 0 20px 50px rgba(20, 24, 38, 0.20);
    display: flex;
    align-items: center;
    gap: 10px;
    max-width: 280px;
    border: 1px solid #f3f4f6;
    animation: chatBounce 2s ease-in-out infinite;
  }

  .chat-popup-icon {
    font-size: 22px;
    flex-shrink: 0;
  }

  .chat-popup-text {
    font-size: 13px;
    font-weight: 500;
    color: #1f2937;
    line-height: 1.4;
  }

  .chat-popup-arrow {
    position: absolute;
    bottom: -8px;
    right: 24px;
    width: 16px;
    height: 16px;
    background: #ffffff;
    transform: rotate(45deg);
    border-right: 1px solid #f3f4f6;
    border-bottom: 1px solid #f3f4f6;
  }

  .chat-popup-close {
    position: absolute;
    top: 6px;
    right: 8px;
    background: none;
    border: none;
    font-size: 14px;
    color: #9ca3af;
    cursor: pointer;
    line-height: 1;
    transition: color 0.2s ease;
  }
  .chat-popup-close:hover { color: #1f2937; }

  @keyframes chatBounce {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-6px); }
  }

  @keyframes chatPulse {
    0%, 100% { box-shadow: 0 10px 25px -6px rgba(99,102,241,0.55); }
    50% { box-shadow: 0 10px 35px -6px rgba(99,102,241,0.75); }
  }

  .chat-fab {
    animation: chatPulse 2s ease-in-out infinite;
  }

  .overlay {
    position: fixed;
    inset: 0;
    background: rgba(15, 17, 23, 0.35);
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.3s ease, visibility 0.3s ease;
    z-index: 10;
    backdrop-filter: blur(2px);
  }
  .overlay.show { opacity: 1; visibility: visible; }

  .chat-card {
    position: fixed;
    bottom: 96px;
    right: 26px;
    width: 100%;
    max-width: 360px;
    height: 460px;
    background: #ffffff;
    border-radius: 16px;
    box-shadow: 0 20px 50px rgba(20, 24, 38, 0.20);
    z-index: 20;
    opacity: 0;
    visibility: hidden;
    transform: translateY(16px) scale(0.95);
    transform-origin: bottom right;
    transition: opacity 0.3s cubic-bezier(.4,1.6,.5,1),
                transform 0.3s cubic-bezier(.4,1.6,.5,1),
                visibility 0.3s ease;
    display: flex;
    flex-direction: column;
  }
  .chat-card.show { opacity: 1; visibility: visible; transform: translateY(0) scale(1); }

  .chat-header {
    padding: 16px 20px;
    border-bottom: 1px solid #f3f4f6;
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: linear-gradient(135deg, #6366f1, #22d3ee);
    color: white;
    border-radius: 16px 16px 0 0;
  }
  .chat-header h3 { margin: 0; font-size: 16px; font-weight: 700; }
  .chat-status { margin: 2px 0 0; font-size: 12px; opacity: 0.9; }
  .close-btn {
    background: none;
    border: none;
    font-size: 18px;
    color: rgba(255,255,255,0.9);
    cursor: pointer;
    line-height: 1;
    transition: color 0.2s ease;
  }
  .close-btn:hover { color: #fff; }

  .chat-messages {
    flex: 1;
    overflow-y: auto;
    padding: 16px;
    display: flex;
    flex-direction: column;
    gap: 10px;
  }
  .chat-loading {
    text-align: center;
    color: #9ca3af;
    font-size: 13px;
    padding: 40px 0;
  }
  .chat-message {
    max-width: 80%;
    padding: 10px 14px;
    border-radius: 14px;
    font-size: 13.5px;
    line-height: 1.5;
    word-wrap: break-word;
  }
  .chat-message.customer {
    align-self: flex-end;
    background: linear-gradient(135deg, #6366f1, #22d3ee);
    color: #fff;
    border-bottom-right-radius: 4px;
  }
  .chat-message.guest {
    align-self: flex-end;
    background: linear-gradient(135deg, #6366f1, #22d3ee);
    color: #fff;
    border-bottom-right-radius: 4px;
  }
  .chat-message.admin {
    align-self: flex-start;
    background: #f3f4f6;
    color: #1f2937;
    border-bottom-left-radius: 4px;
  }
  .chat-message.chat-welcome {
    align-self: flex-start;
    background: #ffffff;
    color: #374151;
    border: 1px dashed #6366f1;
    border-bottom-left-radius: 14px;
    font-size: 13px;
  }
  .chat-message.chat-message-reply-target {
    outline: 2px solid #6366f1;
    outline-offset: 2px;
    background: rgba(99, 102, 241, 0.05);
  }
  .message-reply-btn {
    position: absolute;
    top: 4px;
    right: 4px;
    background: rgba(99, 102, 241, 0.1);
    border: none;
    border-radius: 4px;
    padding: 2px 6px;
    font-size: 12px;
    cursor: pointer;
    color: #6366f1;
    opacity: 0;
    transition: opacity 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .chat-message:hover .message-reply-btn {
    opacity: 1;
  }
  .message-reply-btn:hover {
    background: rgba(99, 102, 241, 0.2);
  }
  .message-reply-dropdown {
    position: absolute;
    top: 100%;
    right: 0;
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    z-index: 10;
    min-width: 120px;
    display: none;
  }
  .message-reply-dropdown.show {
    display: block;
  }
  .message-reply-dropdown button {
    display: block;
    width: 100%;
    padding: 8px 12px;
    border: none;
    background: none;
    text-align: left;
    font-size: 13px;
    cursor: pointer;
    color: #374151;
    transition: background 0.2s ease;
  }
  .message-reply-dropdown button:hover {
    background: #f3f4f6;
    color: #6366f1;
  }
  .message-reply-dropdown button:first-child {
    border-radius: 8px 8px 0 0;
  }
  .message-reply-dropdown button:last-child {
    border-radius: 0 0 8px 8px;
  }
  .message-reply-preview {
    background: rgba(99, 102, 241, 0.1);
    border-left: 3px solid #6366f1;
    padding: 6px 10px;
    border-radius: 6px;
    margin-bottom: 8px;
    font-size: 12px;
    cursor: pointer;
    transition: background 0.2s ease;
  }
  .message-reply-preview:hover {
    background: rgba(99, 102, 241, 0.2);
  }
  .reply-preview-sender {
    font-weight: 600;
    color: #6366f1;
    margin-bottom: 2px;
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }
  .reply-preview-text {
    color: #374151;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }
  .message-content {
    word-wrap: break-word;
    white-space: pre-wrap;
  }
  .chat-messages {
    position: relative;
  }
  .chat-time {
    font-size: 10px;
    opacity: 0.7;
    margin-top: 4px;
    text-align: right;
  }

  .scroll-to-bottom-btn {
    position: absolute;
    bottom: 12px;
    left: 50%;
    transform: translateX(-50%);
    width: 36px;
    height: 36px;
    border-radius: 50%;
    border: none;
    background: #6366f1;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    box-shadow: 0 4px 12px rgba(99, 102, 241, 0.4);
    z-index: 5;
    transition: opacity 0.2s ease, transform 0.2s ease;
  }
  .scroll-to-bottom-btn:hover {
    transform: translateX(-50%) scale(1.1);
  }

  .chat-message-highlight {
    animation: highlightPulse 2s ease-out;
    outline: 2px solid #6366f1;
    outline-offset: 2px;
  }

  @keyframes highlightPulse {
    0% {
      background: rgba(99, 102, 241, 0.3);
      transform: scale(1.02);
    }
    100% {
      background: transparent;
      transform: scale(1);
    }
  }

  .chat-input-area {
    padding: 12px 16px;
    border-top: 1px solid #f3f4f6;
    display: flex;
    gap: 8px;
    align-items: flex-end;
  }
  .chat-input-area textarea {
    flex: 1;
    min-height: 42px;
    max-height: 100px;
    padding: 10px 14px;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    font-family: inherit;
    font-size: 13.5px;
    color: #333;
    resize: none;
    outline: none;
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
  }
  .chat-name-input {
    width: 100%;
    padding: 8px 12px;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    font-family: inherit;
    font-size: 13px;
    color: #333;
    outline: none;
    margin-bottom: 8px;
    display: none;
  }
  .chat-name-input:focus {
    border-color: #6366f1;
    box-shadow: 0 0 0 3px rgba(99,102,241,0.12);
  }
  .chat-input-area textarea:focus {
    border-color: #6366f1;
    box-shadow: 0 0 0 3px rgba(99,102,241,0.12);
  }
  .send-btn {
    width: 42px;
    height: 42px;
    border: none;
    border-radius: 50%;
    background: linear-gradient(135deg, #6366f1, #22d3ee);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: transform 0.2s ease, opacity 0.2s ease;
    flex-shrink: 0;
  }
  .send-btn:hover { transform: translateY(-2px); }
  .send-btn:disabled { opacity: 0.5; cursor: not-allowed; transform: none; }

  .chat-reply-preview {
    padding: 8px 16px;
    background: #f9fafb;
    border-top: 1px solid #f3f4f6;
    display: flex;
    flex-direction: column;
    gap: 4px;
  }
  .reply-preview-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 11px;
    color: #6b7280;
    font-weight: 500;
  }
  .reply-cancel-btn {
    background: none;
    border: none;
    color: #9ca3af;
    cursor: pointer;
    font-size: 14px;
    line-height: 1;
    padding: 0;
    width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    transition: all 0.2s ease;
  }
  .reply-cancel-btn:hover {
    color: #ef4444;
    background: #fee2e2;
  }
  .reply-preview-content {
    font-size: 12px;
    color: #374151;
    background: white;
    padding: 6px 10px;
    border-radius: 6px;
    border-left: 3px solid #6366f1;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }

  @media (max-width: 480px) {
    .chat-card { left: 16px; right: 16px; bottom: 90px; max-width: none; }
  }
</style>

<script>
(function() {
    const fab = document.getElementById('chatFab');
    const overlay = document.getElementById('overlay');
    const chatCard = document.getElementById('chatCard');
    const closeBtn = document.getElementById('closeBtn');
    const chatMessages = document.getElementById('chatMessages');
    const chatForm = document.getElementById('chatForm');
    const chatInput = document.getElementById('chatInput');
    const chatNameInput = document.getElementById('chatNameInput');
    const chatNameArea = document.getElementById('chat-name-area');
    const sendBtn = document.getElementById('sendBtn');
    const sendNameBtn = document.getElementById('sendNameBtn');
    const iconChat = fab.querySelector('.icon-chat');
    const iconClose = fab.querySelector('.icon-close');
    const chatPopup = document.getElementById('chatPopup');
    const chatPopupClose = document.getElementById('chatPopupClose');

    let messagesLoaded = false;
    let pollTimer = null;
    let userName = '';
    let shouldScrollToBottom = true;

    function updateSendButton() {
        const hasText = chatInput.value.trim().length > 0;
        sendBtn.disabled = !hasText;
    }

    function showNameInput() {
        if (chatNameArea) chatNameArea.style.display = 'flex';
        if (chatForm) chatForm.style.display = 'none';
        chatNameInput.style.display = 'block';
        chatNameInput.focus();
        chatInput.style.display = 'none';
        sendBtn.disabled = true;
    }

    function hideNameInput() {
        if (chatNameArea) chatNameArea.style.display = 'none';
        if (chatForm) chatForm.style.display = 'flex';
        chatNameInput.style.display = 'none';
        chatInput.style.display = 'block';
        chatInput.focus();
        updateSendButton();
    }

    function saveUserName() {
        const name = chatNameInput.value.trim();
        if (name) {
            userName = name;
            localStorage.setItem('chat_user_name', name);
            hideNameInput();
            appendNamePrompt(name);
            appendReplyInstruction();
        }
    }

    function loadUserName() {
        const savedName = localStorage.getItem('chat_user_name');
        if (savedName) {
            userName = savedName;
            hideNameInput();
        } else {
            showNameInput();
        }
    }

    function openCard() {
        chatCard.classList.add('show');
        overlay.classList.add('show');
        fab.classList.add('active');
        iconChat.style.display = 'none';
        iconClose.style.display = 'block';
        if (chatPopup) chatPopup.classList.remove('show');
        if (!messagesLoaded) {
            loadMessages();
            messagesLoaded = true;
        }
        startPolling();
        loadUserName();
    }

    function closeCard() {
        chatCard.classList.remove('show');
        overlay.classList.remove('show');
        fab.classList.remove('active');
        iconChat.style.display = 'block';
        iconClose.style.display = 'none';
        stopPolling();
    }

    function showPopup() {
        if (chatPopup) {
            chatPopup.classList.add('show');
        }
    }

    function hidePopup() {
        if (chatPopup) {
            chatPopup.classList.remove('show');
        }
    }

    let currentReplyToId = null;

    function setReplyTarget(messageId, messageText, senderName) {
        currentReplyToId = messageId;
        const replyPreview = document.getElementById('chat-reply-preview');
        const replyPreviewContent = document.getElementById('replyPreviewContent');
        const repliedToMessageIdInput = document.getElementById('repliedToMessageId');
        const replyInstruction = document.getElementById('chat-reply-instruction');

        if (replyPreview && replyPreviewContent && repliedToMessageIdInput) {
            replyPreviewContent.textContent = (senderName || 'Message') + ': ' + messageText;
            replyPreview.style.display = 'flex';
            repliedToMessageIdInput.value = messageId;
        }

        if (replyInstruction) {
            replyInstruction.style.display = 'none';
        }
    }

    function cancelReply() {
        currentReplyToId = null;
        const replyPreview = document.getElementById('chat-reply-preview');
        const repliedToMessageIdInput = document.getElementById('repliedToMessageId');
        const replyInstruction = document.getElementById('chat-reply-instruction');

        if (replyPreview) {
            replyPreview.style.display = 'none';
        }
        if (repliedToMessageIdInput) {
            repliedToMessageIdInput.value = '';
        }

        if (replyInstruction) {
            replyInstruction.style.display = '';
        }
    }

    function scrollToMessage(messageId) {
        const targetMessage = document.getElementById('chat-message-' + messageId);
        if (targetMessage) {
            targetMessage.scrollIntoView({ behavior: 'smooth', block: 'center' });
            targetMessage.classList.add('chat-message-highlight');
            setTimeout(() => {
                targetMessage.classList.remove('chat-message-highlight');
            }, 2000);
        }
    }

    function scrollToBottom() {
        chatMessages.scrollTo({
            top: chatMessages.scrollHeight,
            behavior: 'smooth'
        });
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function appendMessage(message, senderType, messageId, replyToMessage) {
        const div = document.createElement('div');
        div.className = 'chat-message ' + (senderType === 'admin' ? 'admin' : (senderType === 'guest' ? 'guest' : 'customer'));
        div.id = 'chat-message-' + (messageId || Date.now());
        div.dataset.messageId = messageId || '';
        
        let replyHtml = '';
        if (replyToMessage && replyToMessage.id) {
            replyHtml = `
                <div class="message-reply-preview" data-reply-to-id="${replyToMessage.id}">
                    <div class="reply-preview-sender">${escapeHtml(replyToMessage.sender_name || 'Message')}</div>
                    <div class="reply-preview-text">${escapeHtml(replyToMessage.message)}</div>
                </div>
            `;
        }
        
        div.innerHTML = `
            ${replyHtml}
            <div class="message-content">${escapeHtml(message)}</div>
            <div class="chat-time">${new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</div>
        `;
        
        const replyBtn = document.createElement('button');
        replyBtn.className = 'message-reply-btn';
        replyBtn.innerHTML = '▼';
        replyBtn.title = 'Options';
        
        const dropdown = document.createElement('div');
        dropdown.className = 'message-reply-dropdown';
        
        const replyOption = document.createElement('button');
        replyOption.textContent = 'Reply';
        replyOption.addEventListener('click', function(e) {
            e.stopPropagation();
            setReplyTarget(messageId, message, senderType === 'admin' ? 'Agent' : (senderType === 'guest' ? 'Guest' : 'You'));
            dropdown.classList.remove('show');
        });
        
        dropdown.appendChild(replyOption);
        div.appendChild(replyBtn);
        div.appendChild(dropdown);
        
        replyBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            document.querySelectorAll('.message-reply-dropdown').forEach(d => {
                if (d !== dropdown) d.classList.remove('show');
            });
            dropdown.classList.toggle('show');
        });
        
        chatMessages.appendChild(div);
        if (shouldScrollToBottom) {
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }
    }

    function appendWelcomeMessage() {
        const div = document.createElement('div');
        div.className = 'chat-message chat-welcome';
        div.id = 'welcome-message';
        div.innerHTML = `
            Hi! Nice to meet you. Please what is your name?
            <div class="chat-time">${new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</div>
        `;
        chatMessages.appendChild(div);
        if (shouldScrollToBottom) {
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }
    }

    function appendNamePrompt(name) {
        const div = document.createElement('div');
        div.className = 'chat-message chat-welcome';
        div.innerHTML = `
            Hi ${escapeHtml(name)}, proceed with what you want to ask.
            <div class="chat-time">${new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</div>
        `;
        chatMessages.appendChild(div);
        if (shouldScrollToBottom) {
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }
    }

    function appendReplyInstruction() {
        const div = document.createElement('div');
        div.className = 'chat-message chat-welcome';
        div.id = 'chat-reply-instruction';
        div.innerHTML = `
            Please type your message in the box below and press the send button to start chatting with an agent.
            And you can reply to a specific message by clicking the reply button  or tab on that message.
            <div class="chat-time">${new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</div>
        `;
        chatMessages.appendChild(div);
        if (shouldScrollToBottom) {
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    async function loadMessages() {
        try {
            const response = await fetch('{{ route('chat.messages') }}', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            const data = await response.json();
            if (data.success) {
                chatMessages.innerHTML = '';
                if (data.messages.length === 0) {
                    appendWelcomeMessage();
                } else {
                    data.messages.forEach(function(msg) {
                        appendMessage(msg.message, msg.sender_type, msg.id, msg.reply_to_message);
                    });
                }
                if (shouldScrollToBottom) {
                    chatMessages.scrollTop = chatMessages.scrollHeight;
                }
            }
        } catch (e) {
            console.error('Failed to load messages:', e);
            chatMessages.innerHTML = '';
            appendWelcomeMessage();
        }
    }

    function startPolling() {
        stopPolling();
        pollTimer = setInterval(loadMessages, 5000);
    }

    function stopPolling() {
        if (pollTimer) {
            clearInterval(pollTimer);
            pollTimer = null;
        }
    }

    function updateScrollButton() {
        const scrollToBottomBtn = document.getElementById('scrollToBottomBtn');
        if (!scrollToBottomBtn || !chatMessages) return;

        const isNearBottom = chatMessages.scrollHeight - chatMessages.scrollTop - chatMessages.clientHeight < 100;
        scrollToBottomBtn.style.display = isNearBottom ? 'none' : 'flex';
        shouldScrollToBottom = isNearBottom;
    }

    if (chatMessages) {
        chatMessages.addEventListener('scroll', updateScrollButton);
    }

    const scrollToBottomBtn = document.getElementById('scrollToBottomBtn');
    if (scrollToBottomBtn) {
        scrollToBottomBtn.addEventListener('click', function() {
            scrollToBottom();
            shouldScrollToBottom = true;
        });
    }

    if (chatInput) {
        chatInput.addEventListener('input', function() {
            updateSendButton();
        });
    }

    if (chatNameInput) {
        chatNameInput.addEventListener('input', function() {
            sendNameBtn.disabled = !this.value.trim();
        });

        chatNameInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter' && this.value.trim()) {
                e.preventDefault();
                saveUserName();
            }
        });
    }

    if (sendNameBtn) {
        sendNameBtn.addEventListener('click', function() {
            saveUserName();
        });
    }

    if (fab) {
        fab.addEventListener('click', () => {
            hidePopup();
            chatCard.classList.contains('show') ? closeCard() : openCard();
        });
    }

    if (overlay) overlay.addEventListener('click', closeCard);
    if (closeBtn) closeBtn.addEventListener('click', closeCard);

    const cancelReplyBtn = document.getElementById('cancelReplyBtn');
    if (cancelReplyBtn) {
        cancelReplyBtn.addEventListener('click', cancelReply);
    }

    chatMessages.addEventListener('click', function(e) {
        const replyPreview = e.target.closest('.message-reply-preview');
        if (replyPreview) {
            e.stopPropagation();
            const replyToId = replyPreview.dataset.replyToId;
            if (replyToId) {
                scrollToMessage(replyToId);
            }
            return;
        }
        
        const messageDiv = e.target.closest('.chat-message');
        if (!messageDiv) return;
        
        document.querySelectorAll('.chat-message').forEach(m => m.classList.remove('chat-message-reply-target'));
        messageDiv.classList.add('chat-message-reply-target');
        
        const messageId = messageDiv.dataset.messageId;
        const messageText = messageDiv.querySelector('.message-content')?.textContent || '';
        const senderType = messageDiv.classList.contains('chat-message-admin') ? 'admin' : 
                          messageDiv.classList.contains('chat-message-guest') ? 'guest' : 'customer';
        const senderName = senderType === 'admin' ? 'Agent' : (senderType === 'guest' ? 'Guest' : 'You');
        
        setReplyTarget(messageId, messageText, senderName);
    });

    document.addEventListener('click', function(e) {
        if (!e.target.closest('.message-reply-btn')) {
            document.querySelectorAll('.message-reply-dropdown').forEach(d => d.classList.remove('show'));
        }
    });

    if (chatPopupClose) {
        chatPopupClose.addEventListener('click', () => {
            hidePopup();
        });
    }

    function getCsrfToken() {
        const metaToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if (metaToken) {
            return metaToken;
        }

        const cookieToken = document.cookie.split('; ').find(row => row.startsWith('XSRF-TOKEN='));
        if (cookieToken) {
            return decodeURIComponent(cookieToken.split('=')[1]);
        }

        return '';
    }

    if (chatForm) {
        chatForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const message = chatInput.value.trim();
            const senderName = chatNameInput.value.trim() || userName;
            const repliedToMessageId = document.getElementById('repliedToMessageId')?.value || null;

            if (!message) return;

            if (!userName && chatNameInput.style.display !== 'none') {
                saveUserName();
                return;
            }

            sendBtn.disabled = true;
            chatInput.value = '';

            try {
                const response = await fetch('{{ route('chat.store') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': getCsrfToken(),
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ 
                        message: message, 
                        sender_name: senderName,
                        replied_to_message_id: repliedToMessageId
                    })
                });

                const data = await response.json();
                if (data.success) {
                    cancelReply();
                    loadMessages();
                } else {
                    alert(data.message || 'Failed to send message');
                }
            } catch (e) {
                alert('Something went wrong. Please try again.');
            } finally {
                updateSendButton();
            }
        });
    }
})();
</script>
