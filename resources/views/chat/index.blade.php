@extends('layouts.admin')

@section('title', 'Chat Messages')

@section('content')
<div class="h-[calc(100vh-80px)] flex flex-col">
    <div class="mb-4 px-4 sm:px-6">
        <h1 class="text-2xl font-bold text-gray-900">Chat Messages</h1>
        <p class="text-gray-600 text-sm">Respond to customer inquiries</p>
    </div>

    <div class="flex-1 flex bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden mx-4 sm:mx-6 mb-6">
        <!-- Conversations List (Left Side) -->
        <div class="w-80 border-r border-gray-200 flex flex-col bg-gray-50">
            <div class="p-4 border-b border-gray-200 bg-white">
                <h2 class="font-semibold text-gray-900">Conversations</h2>
                <p class="text-xs text-gray-500">Select a conversation to reply</p>
            </div>
            <div id="conversations-list" class="flex-1 overflow-y-auto">
                <div class="p-4 text-center text-sm text-gray-500">Loading conversations...</div>
            </div>
        </div>

        <!-- Chat Area (Right Side) -->
        <div class="flex-1 flex flex-col">
            <div id="chat-header" class="p-4 border-b border-gray-200 bg-white hidden">
                <h3 id="chat-title" class="font-semibold text-gray-900">Select a conversation</h3>
                <p id="chat-subtitle" class="text-xs text-gray-500"></p>
            </div>

            <div id="chat-messages" class="flex-1 overflow-y-auto p-4 space-y-3" style="position: relative;">
                <div class="text-center py-12 text-gray-400">
                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                    </svg>
                    <p>Select a conversation from the left to view messages</p>
                </div>
            </div>

            <button class="scroll-to-bottom-btn" id="scrollToBottomBtn" title="Latest messages" style="display: none;">
                <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 5v14M5 12l7 7 7-7"/>
                </svg>
            </button>

            <div id="chat-reply-preview" class="chat-reply-preview" style="display: none;">
                <div class="reply-preview-header">
                    <span>Replying to:</span>
                    <button class="reply-cancel-btn" id="cancelReplyBtn">✕</button>
                </div>
                <div class="reply-preview-content" id="replyPreviewContent"></div>
            </div>

            <div id="chat-reply-area" class="p-4 border-t border-gray-200 bg-white hidden">
                <form id="reply-form" class="flex gap-2">
                    <input type="hidden" id="conversation-id" value="">
                    <input type="hidden" id="repliedToMessageId" value="">
                    <textarea id="reply-input" rows="1" placeholder="Type your reply..." class="flex-1 px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 resize-none"></textarea>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors">
                        Send
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
(function() {
    const conversationsList = document.getElementById('conversations-list');
    const chatMessages = document.getElementById('chat-messages');
    const chatHeader = document.getElementById('chat-header');
    const chatTitle = document.getElementById('chat-title');
    const chatSubtitle = document.getElementById('chat-subtitle');
    const chatReplyArea = document.getElementById('chat-reply-area');
    const replyForm = document.getElementById('reply-form');
    const conversationIdInput = document.getElementById('conversation-id');
    const replyInput = document.getElementById('reply-input');

    let selectedConversationId = null;
    let pollTimer = null;
    let shouldScrollToBottom = true;

    function loadConversations() {
        fetch('{{ route('admin.chat.conversations') }}')
            .then(response => response.json())
            .then(data => {
                if (!data.success || !data.conversations || data.conversations.length === 0) {
                    conversationsList.innerHTML = '<div class="p-4 text-center text-sm text-gray-500">No conversations yet</div>';
                    return;
                }

                conversationsList.innerHTML = data.conversations.map(conv => `
                    <div class="conversation-item p-3 border-b border-gray-100 hover:bg-white cursor-pointer transition-colors ${conv.id === selectedConversationId ? 'bg-white border-l-4 border-l-indigo-600' : ''}" data-id="${conv.id}">
                        <div class="flex items-start justify-between">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2">
                                    <h4 class="text-sm font-semibold text-gray-900 truncate">${escapeHtml(conv.sender_name || (conv.is_customer ? 'Customer' : 'Guest'))}</h4>
                                    ${conv.unread_count > 0 ? '<span class="inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold leading-none text-white bg-red-600 rounded-full">'+conv.unread_count+'</span>' : ''}
                                </div>
                                <p class="text-xs text-gray-600 mt-1 truncate">${escapeHtml(conv.preview)}</p>
                                <p class="text-xs text-gray-400 mt-1">${conv.last_message_at}</p>
                            </div>
                        </div>
                    </div>
                `).join('');

                document.querySelectorAll('.conversation-item').forEach(item => {
                    item.addEventListener('click', function() {
                        const id = this.dataset.id;
                        selectConversation(id);
                    });
                });
            })
            .catch(error => {
                console.error('Error loading conversations:', error);
                conversationsList.innerHTML = '<div class="p-4 text-center text-sm text-red-500">Failed to load</div>';
            });
    }

    function selectConversation(conversationId) {
        selectedConversationId = conversationId;
        conversationIdInput.value = conversationId;

        document.querySelectorAll('.conversation-item').forEach(item => {
            item.classList.remove('bg-white', 'border-l-4', 'border-l-indigo-600');
            if (item.dataset.id === conversationId) {
                item.classList.add('bg-white', 'border-l-4', 'border-l-indigo-600');
            }
        });

        loadConversationMessages(conversationId);
    }

    function loadConversationMessages(conversationId) {
        fetch('{{ route('admin.chat.conversation.messages', ['conversationId' => 'CONVERSATION_ID']) }}'.replace('CONVERSATION_ID', conversationId))
            .then(response => response.json())
            .then(data => {
                if (!data.success || !data.messages || data.messages.length === 0) {
                    chatMessages.innerHTML = '<div class="text-center py-12 text-gray-400">No messages in this conversation</div>';
                    chatHeader.classList.remove('hidden');
                    chatTitle.textContent = 'Conversation';
                    chatSubtitle.textContent = '';
                    chatReplyArea.classList.add('hidden');
                    return;
                }

                const firstMessage = data.messages[0];
                const isCustomer = firstMessage.is_customer;
                chatHeader.classList.remove('hidden');
                chatTitle.textContent = isCustomer ? 'Customer' : 'Guest';
                chatSubtitle.textContent = data.messages.length + ' messages';
                chatReplyArea.classList.remove('hidden');

                chatMessages.innerHTML = data.messages.map(msg => {
                    let replyHtml = '';
                    if (msg.reply_to_message) {
                        replyHtml = `
                            <div class="message-reply-preview" data-reply-to-id="${msg.reply_to_message.id}">
                                <div class="reply-preview-sender">${escapeHtml(msg.reply_to_message.sender_name || 'Message')}</div>
                                <div class="reply-preview-text">${escapeHtml(msg.reply_to_message.message)}</div>
                            </div>
                        `;
                    }
                    
                    return `
                        <div class="flex ${msg.is_customer ? 'justify-end' : 'justify-start'}">
                            <div class="chat-message ${msg.is_customer ? 'chat-message-customer' : 'chat-message-admin'}" data-message-id="${msg.id}" id="chat-message-${msg.id}" style="position: relative;">
                                ${replyHtml}
                                <p class="text-sm">${escapeHtml(msg.message)}</p>
                                <p class="text-xs opacity-60 mt-1 text-right">${msg.created_at}</p>
                                <button class="message-reply-btn" style="position: absolute; top: 4px; right: 4px;">▼</button>
                                <div class="message-reply-dropdown" style="position: absolute; top: 100%; right: 0;">
                                    <button class="reply-option-btn" data-message-id="${msg.id}" data-message-text="${escapeHtml(msg.message)}" data-sender-name="${msg.is_customer ? 'Customer' : 'You'}">Reply</button>
                                </div>
                            </div>
                        </div>
                    `;
                }).join('');

                chatMessages.querySelectorAll('.message-reply-btn').forEach(btn => {
                    btn.addEventListener('click', function(e) {
                        e.stopPropagation();
                        const dropdown = this.nextElementSibling;
                        document.querySelectorAll('.message-reply-dropdown').forEach(d => {
                            if (d !== dropdown) d.classList.remove('show');
                        });
                        dropdown.classList.toggle('show');
                    });
                });

                chatMessages.querySelectorAll('.reply-option-btn').forEach(btn => {
                    btn.addEventListener('click', function(e) {
                        e.stopPropagation();
                        const messageId = this.dataset.messageId;
                        const messageText = this.dataset.messageText;
                        const senderName = this.dataset.senderName;
                        setReplyTarget(messageId, messageText, senderName);
                        document.querySelectorAll('.message-reply-dropdown').forEach(d => d.classList.remove('show'));
                    });
                });

                chatMessages.querySelectorAll('.chat-message').forEach(msgDiv => {
                    msgDiv.addEventListener('click', function(e) {
                        if (e.target.closest('button')) return;
                        
                        document.querySelectorAll('.chat-message').forEach(m => m.classList.remove('chat-message-reply-target'));
                        this.classList.add('chat-message-reply-target');
                        
                        const messageId = this.dataset.messageId;
                        const messageText = this.querySelector('.message-content, p')?.textContent || '';
                        const isCustomer = this.classList.contains('chat-message-customer');
                        const senderName = isCustomer ? 'Customer' : 'You';
                        
                        setReplyTarget(messageId, messageText, senderName);
                    });
                });

                if (shouldScrollToBottom) {
                    chatMessages.scrollTop = chatMessages.scrollHeight;
                }
            })
            .catch(error => {
                console.error('Error loading messages:', error);
                chatMessages.innerHTML = '<div class="text-center py-12 text-red-500">Failed to load messages</div>';
            });
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function setReplyTarget(messageId, messageText, senderName) {
        const replyPreview = document.getElementById('chat-reply-preview');
        const replyPreviewContent = document.getElementById('replyPreviewContent');
        const repliedToMessageIdInput = document.getElementById('repliedToMessageId');

        if (replyPreview && replyPreviewContent && repliedToMessageIdInput) {
            replyPreviewContent.textContent = (senderName || 'Message') + ': ' + messageText;
            replyPreview.style.display = 'flex';
            repliedToMessageIdInput.value = messageId;
        }
    }

    function cancelReply() {
        const replyPreview = document.getElementById('chat-reply-preview');
        const repliedToMessageIdInput = document.getElementById('repliedToMessageId');

        if (replyPreview) {
            replyPreview.style.display = 'none';
        }
        if (repliedToMessageIdInput) {
            repliedToMessageIdInput.value = '';
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
        if (chatMessages) {
            chatMessages.scrollTo({
                top: chatMessages.scrollHeight,
                behavior: 'smooth'
            });
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

    if (replyForm) {
        replyForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const message = replyInput.value.trim();
            const conversationId = conversationIdInput.value;
            const repliedToMessageId = document.getElementById('repliedToMessageId')?.value || null;

            if (!message || !conversationId) return;

            try {
                const response = await fetch('{{ route('admin.chat.reply') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': getCsrfToken(),
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        message: message,
                        conversation_id: conversationId,
                        replied_to_message_id: repliedToMessageId
                    })
                });

                const data = await response.json();
                if (data.success) {
                    replyInput.value = '';
                    cancelReply();
                    loadConversationMessages(conversationId);
                    loadConversations();
                } else {
                    alert(data.message || 'Failed to send reply');
                }
            } catch (e) {
                alert('Something went wrong. Please try again.');
            }
        });
    }

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
        
        if (e.target.closest('.message-reply-btn')) return;
        
        document.querySelectorAll('.chat-message').forEach(m => m.classList.remove('chat-message-reply-target'));
        messageDiv.classList.add('chat-message-reply-target');
        
        const messageId = messageDiv.dataset.messageId;
        const messageText = messageDiv.querySelector('.message-content, p')?.textContent || '';
        const isCustomer = messageDiv.classList.contains('chat-message-customer');
        const senderName = isCustomer ? 'Customer' : 'You';
        
        setReplyTarget(messageId, messageText, senderName);
    });

    document.addEventListener('click', function(e) {
        if (!e.target.closest('.message-reply-btn')) {
            document.querySelectorAll('.message-reply-dropdown').forEach(d => d.classList.remove('show'));
        }
    });

    loadConversations();
    setInterval(loadConversations, 10000);
})();
</script>

<style>
.chat-message {
    max-width: 70%;
    padding: 10px 14px;
    border-radius: 14px;
    font-size: 13.5px;
    line-height: 1.5;
    word-wrap: break-word;
}
.chat-message-customer {
    background: linear-gradient(135deg, #6366f1, #22d3ee);
    color: #fff;
    border-bottom-right-radius: 4px;
}
.chat-message-admin {
    background: #f3f4f6;
    color: #1f2937;
    border-bottom-left-radius: 4px;
}
.chat-message.chat-message-reply-target {
    outline: 2px solid #6366f1;
    outline-offset: 2px;
    background: rgba(99, 102, 241, 0.05);
}
.message-reply-preview {
    background: rgba(99, 102, 241, 0.1);
    border-left: 3px solid #6366f1;
    padding: 6px 10px;
    border-radius: 6px;
    margin-bottom: 8px;
    font-size: 12px;
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

.chat-message {
    position: relative;
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
</style>
@endsection
