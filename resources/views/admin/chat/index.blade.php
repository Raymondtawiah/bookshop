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

            <div id="chat-messages" class="flex-1 overflow-y-auto p-4 space-y-3">
                <div class="text-center py-12 text-gray-400">
                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                    </svg>
                    <p>Select a conversation from the left to view messages</p>
                </div>
            </div>

            <div id="chat-reply-area" class="p-4 border-t border-gray-200 bg-white hidden">
                <form id="reply-form" class="flex gap-2">
                    <input type="hidden" id="conversation-id" value="">
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

                chatMessages.innerHTML = data.messages.map(msg => `
                    <div class="flex ${msg.is_customer ? 'justify-end' : 'justify-start'}">
                        <div class="chat-message ${msg.is_customer ? 'chat-message-customer' : 'chat-message-admin'}">
                            <p class="text-sm">${escapeHtml(msg.message)}</p>
                            <p class="text-xs opacity-60 mt-1 text-right">${msg.created_at}</p>
                        </div>
                    </div>
                `).join('');

                chatMessages.scrollTop = chatMessages.scrollHeight;
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

    if (replyForm) {
        replyForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const message = replyInput.value.trim();
            const conversationId = conversationIdInput.value;

            if (!message || !conversationId) return;

            try {
                const response = await fetch('{{ route('admin.chat.reply') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        message: message,
                        conversation_id: conversationId
                    })
                });

                const data = await response.json();
                if (data.success) {
                    replyInput.value = '';
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
</style>
@endsection
