<div id="chat-widget" class="fixed bottom-5 right-5 z-50">
    <button id="chat-toggle" class="bg-indigo-600 text-white p-4 rounded-full shadow-lg hover:bg-indigo-700 transition-all duration-300 flex items-center justify-center relative">
        <svg id="chat-icon" class="w-6 h-6 absolute transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
        </svg>
        <svg id="close-icon" class="w-6 h-6 absolute transition-opacity hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </button>

    <div id="chat-box" class="hidden absolute bottom-16 right-0 w-80 sm:w-96 h-96 bg-white rounded-lg shadow-xl border flex flex-col">
        <div class="bg-indigo-600 text-white p-4 rounded-t-lg flex justify-between items-center">
            <h3 class="font-semibold">Chat with Admin</h3>
            <button id="chat-menu-btn" class="text-white hover:text-indigo-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                </svg>
            </button>
        </div>
        
        <div id="chat-menu" class="hidden absolute right-4 top-16 bg-white rounded-lg shadow-lg border py-1 z-20 w-28">
            <button id="clear-all-chat" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                Clear Chat
            </button>
        </div>
        
        <div id="chat-messages" class="flex-1 overflow-y-auto p-4 space-y-3">
        </div>

        <div class="p-4 border-t" id="chat-input-container">
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const chatToggle = document.getElementById('chat-toggle');
    const chatBox = document.getElementById('chat-box');
    const chatIcon = document.getElementById('chat-icon');
    const closeIcon = document.getElementById('close-icon');
    const chatMessages = document.getElementById('chat-messages');
    const chatInputContainer = document.getElementById('chat-input-container');
    const chatMenu = document.getElementById('chat-menu');
    const chatMenuBtn = document.getElementById('chat-menu-btn');
    const clearAllBtn = document.getElementById('clear-all-chat');

    let uniqueId = localStorage.getItem('chat_unique_id');
    let questionIndex = 0;
    let userAnswers = { name: '', email: '', question: '' };

    const questions = [
        { key: 'name', text: 'What is your name?' },
        { key: 'email', text: 'What is your email address?' },
        { key: 'question', text: 'What would you like to ask us?' }
    ];

    function initChat() {
        if (uniqueId) {
            showChatUI();
        } else {
            showQuestionUI();
        }
    }

    function showQuestionUI() {
        chatMessages.innerHTML = `
            <div class="text-center text-gray-500 text-sm py-4">
                <p class="mb-2">Hello! Welcome to chat support.</p>
                <p>Please answer a few questions to help us assist you better.</p>
            </div>
        `;
        
        const q = questions[questionIndex];
        chatInputContainer.innerHTML = `
            <div id="question-wrapper">
                <p id="current-question" class="text-sm font-medium text-gray-700 mb-2">${q.text}</p>
                <div class="flex gap-2">
                    <input type="text" id="chat-input" placeholder="Type your answer..." class="flex-1 border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" maxlength="5000">
                    <button type="button" id="chat-send-btn" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" transform="rotate(-45 12 12)"/>
                        </svg>
                    </button>
                </div>
            </div>
        `;
        
        document.getElementById('chat-send-btn').onclick = function(e) {
            e.preventDefault();
            handleAnswer();
        };
        document.getElementById('chat-input').onkeypress = function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                handleAnswer();
            }
        };
        document.getElementById('chat-input').focus();
    }

    function showChatUI() {
        chatMessages.innerHTML = '<div class="text-center text-gray-500 text-sm py-4">Loading messages...</div>';
        
        chatInputContainer.innerHTML = `
            <div id="reply-to-container" class="hidden mb-2 p-2 bg-gray-100 rounded-lg flex items-center justify-between">
                <span class="text-xs text-gray-600">Replying to: <span id="reply-to-preview" class="font-medium"></span></span>
                <input type="hidden" id="replied_message_id" value="">
                <button type="button" onclick="clearReply()" class="text-gray-500 hover:text-gray-700">×</button>
            </div>
            <div class="flex gap-2">
                <input type="text" id="chat-input" placeholder="Type your message..." class="flex-1 border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" maxlength="5000">
                <button type="button" id="chat-send-btn" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" transform="rotate(-45 12 12)"/>
                    </svg>
                </button>
            </div>
        `;
        
        document.getElementById('chat-send-btn').onclick = function(e) {
            e.preventDefault();
            sendMessage();
        };
        document.getElementById('chat-input').onkeypress = function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                sendMessage();
            }
        };
        
        loadChatMessages();
    }

    function handleAnswer() {
        const input = document.getElementById('chat-input');
        const answer = input.value.trim();
        if (!answer) return;

        const q = questions[questionIndex];
        userAnswers[q.key] = answer;
        
        // Show user's answer in chat
        const msgDiv = document.createElement('div');
        msgDiv.className = 'flex justify-end';
        msgDiv.innerHTML = `<div class="max-w-[80%] bg-indigo-600 text-white rounded-lg p-3"><p class="text-sm">${escapeHtml(answer)}</p></div>`;
        chatMessages.appendChild(msgDiv);
        
        questionIndex++;

        if (questionIndex < questions.length) {
            input.value = '';
            document.getElementById('current-question').textContent = questions[questionIndex].text;
            input.focus();
        } else {
            const finalMessage = `Name: ${userAnswers.name}\nEmail: ${userAnswers.email}\nQuestion: ${userAnswers.question}`;
            sendInitialMessage(finalMessage);
        }
    }

    function sendInitialMessage(message) {
        const formData = new FormData();
        formData.append('message', message);
        formData.append('name', userAnswers.name);
        formData.append('email', userAnswers.email);

        console.log('Sending initial message with name:', userAnswers.name, 'email:', userAnswers.email);
        
        fetch('{{ route("chat.store") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: formData
        })
        .then(res => {
            console.log('Initial message response status:', res.status);
            if (!res.ok) throw new Error('Network response was not ok: ' + res.status);
            return res.json();
        })
        .then(data => {
            console.log('Initial message response data:', data);
            if (data.success && data.chat && data.chat.unique_id) {
                uniqueId = data.chat.unique_id;
                localStorage.setItem('chat_unique_id', uniqueId);
                showChatUI();
            } else if (data.success) {
                // Try to get unique_id from existing chats
                uniqueId = localStorage.getItem('chat_unique_id');
                if (uniqueId) {
                    showChatUI();
                } else {
                    alert('Chat created but unique ID not found. Please refresh.');
                }
            } else {
                alert('Failed to send message: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(err => {
            console.error('Error sending initial message:', err);
            alert('Failed to start chat. Please try again. Error: ' + err.message);
        });
    }

    function sendMessage() {
        const input = document.getElementById('chat-input');
        const message = input.value.trim();
        if (!message || !uniqueId) {
            console.log('Cannot send: message=', message, 'uniqueId=', uniqueId);
            alert('Please enter a message');
            return;
        }

        const replyContainer = document.getElementById('reply-to-container');
        const repliedMessageId = document.getElementById('replied_message_id') ? document.getElementById('replied_message_id').value : null;

        const formData = new FormData();
        formData.append('message', message);
        formData.append('unique_id', uniqueId);
        if (repliedMessageId) {
            formData.append('replied_message_id', repliedMessageId);
        }

        console.log('Sending message with unique_id:', uniqueId);
        
        fetch('{{ route("chat.store") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: formData
        })
        .then(res => {
            console.log('Response status:', res.status);
            if (!res.ok) throw new Error('Network response was not ok: ' + res.status);
            return res.json();
        })
        .then(data => {
            console.log('Send message response:', data);
            if (data.success) {
                input.value = '';
                clearReply();
                loadChatMessages();
            } else {
                alert('Failed to send message: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(err => {
            console.error('Error sending message:', err);
            alert('Failed to send message. Please try again. Error: ' + err.message);
        });
    }

    function loadChatMessages() {
        if (!uniqueId) return;
        
        fetch('{{ route("chat.messages") }}?unique_id=' + uniqueId)
            .then(res => res.json())
            .then(data => {
                if (data.success && data.chats.length > 0) {
                    renderMessages(data.chats);
                    markAsRead();
                } else {
                    chatMessages.innerHTML = '<div class="text-center text-gray-500 text-sm py-4">Start chatting with admin...</div>';
                }
            })
            .catch(err => console.error('Error loading:', err));
    }

    function renderMessages(chats) {
        chatMessages.innerHTML = '';
        const chatsById = {};
        chats.forEach(chat => { chatsById[chat.id] = chat; });
        
        chats.forEach(chat => {
            const isAdmin = chat.sender_type === 'admin';
            const time = new Date(chat.created_at).toLocaleString();
            const checkmarks = isAdmin ? '<span class="text-xs text-blue-500">✓✓</span>' : '<span class="text-xs text-gray-300">✓</span>';
            
            let replyHtml = '';
            let highlightClass = '';
            if (chat.replied_message_id && chatsById[chat.replied_message_id]) {
                const repliedMsg = chatsById[chat.replied_message_id];
                const replyPreview = repliedMsg.message.length > 60 ? repliedMsg.message.substring(0, 60) + '...' : repliedMsg.message;
                replyHtml = `<div class="text-xs mb-1 px-2 py-1 rounded ${isAdmin ? 'bg-indigo-500 text-indigo-200' : 'bg-gray-300 text-gray-600'}">↩ ${escapeHtml(replyPreview)}</div>`;
                highlightClass = 'reply-highlight';
            }
            
            const currentPreview = chat.message.length > 50 ? chat.message.substring(0, 50) + '...' : chat.message;
            
            const messageDiv = document.createElement('div');
            messageDiv.className = `flex ${isAdmin ? 'justify-start' : 'justify-end'} group ${highlightClass}`;
            messageDiv.dataset.chatId = chat.id;
            messageDiv.ondblclick = function() { selectReply(chat.id, currentPreview); };
            messageDiv.ontouchstart = function(e) { handleDoubleTapClient(e, chat.id, currentPreview); };
            messageDiv.innerHTML = `
                <div class="max-w-[80%] ${isAdmin ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-900'} rounded-lg p-3 relative">
                    <button onclick="deleteMessage(${chat.id})" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs opacity-0 group-hover:opacity-100 transition-opacity" title="Delete">×</button>
                    ${replyHtml}
                    <p class="text-sm">${escapeHtml(chat.message)}</p>
                    <div class="flex items-center justify-end gap-1 mt-1">
                        <p class="text-xs ${isAdmin ? 'text-indigo-200' : 'text-gray-500'}">${time}</p>
                        ${!isAdmin ? checkmarks : ''}
                    </div>
                </div>
            `;
            chatMessages.appendChild(messageDiv);
        });
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    function markAsRead() {
        if (!uniqueId) return;
        fetch('{{ route("chat.read") }}?unique_id=' + uniqueId, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        }).catch(err => {});
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    let clientLastTapTime = 0;
    let clientLastTapElement = null;

    function handleDoubleTapClient(event, id, preview) {
        const currentTime = new Date().getTime();
        const tapLength = currentTime - clientLastTapTime;
        
        if (tapLength < 300 && tapLength > 0 && clientLastTapElement === event.currentTarget) {
            selectReply(id, preview);
            event.preventDefault();
        }
        clientLastTapTime = currentTime;
        clientLastTapElement = event.currentTarget;
    }

    // Toggle chat box
    chatToggle.onclick = function(e) {
        e.stopPropagation();
        chatBox.classList.toggle('hidden');
        chatIcon.classList.toggle('hidden');
        closeIcon.classList.toggle('hidden');
        
        if (!chatBox.classList.contains('hidden')) {
            initChat();
        }
    };

    // Close when clicking outside
    document.addEventListener('click', function(e) {
        if (!chatBox.contains(e.target) && !chatToggle.contains(e.target)) {
            chatBox.classList.add('hidden');
            chatIcon.classList.remove('hidden');
            closeIcon.classList.add('hidden');
        }
    });

    chatBox.onclick = function(e) {
        e.stopPropagation();
    };

    // Menu toggle
    chatMenuBtn.onclick = function(e) {
        e.stopPropagation();
        chatMenu.classList.toggle('hidden');
    };

    document.addEventListener('click', function(e) {
        if (!chatMenu.contains(e.target) && e.target !== chatMenuBtn) {
            chatMenu.classList.add('hidden');
        }
    });

    // Clear all chat
    clearAllBtn.onclick = function() {
        if (confirm('Clear chat and start over?')) {
            localStorage.removeItem('chat_unique_id');
            uniqueId = null;
            questionIndex = 0;
            userAnswers = { name: '', email: '', question: '' };
            chatMenu.classList.add('hidden');
            showQuestionUI();
        }
    };

    // Delete message
    window.deleteMessage = function(id) {
        fetch('{{ url("chat") }}/' + id, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) loadChatMessages();
        })
        .catch(err => console.error('Error:', err));
    };

    // Auto-refresh
    setInterval(function() {
        if (!chatBox.classList.contains('hidden') && uniqueId) {
            loadChatMessages();
        }
    }, 5000);
    
    // Reply functionality
    window.selectReply = function(id, preview) {
        const replyInput = document.getElementById('replied_message_id');
        if (replyInput) {
            replyInput.value = id;
            document.getElementById('reply-to-preview').textContent = preview;
            document.getElementById('reply-to-container').classList.remove('hidden');
            
            document.querySelectorAll('.reply-highlight').forEach(el => el.classList.remove('reply-highlight'));
            const chatDiv = document.querySelector(`[data-chat-id="${id}"]`);
            if (chatDiv) {
                chatDiv.classList.add('reply-highlight');
                chatDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
    };
    
    window.clearReply = function() {
        const replyInput = document.getElementById('replied_message_id');
        if (replyInput) {
            replyInput.value = '';
            document.getElementById('reply-to-container').classList.add('hidden');
            document.querySelectorAll('.reply-highlight').forEach(el => el.classList.remove('reply-highlight'));
        }
    };
});
</script>
<style>
.reply-highlight > div {
    border-left: 3px solid #10b981 !important;
    border-right: 3px solid #10b981 !important;
}
</style>