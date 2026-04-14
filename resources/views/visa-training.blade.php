<?php $progressPercent = round(($currentStep / $totalQuestions) * 100); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visa Training - Learn Visa Applications</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .chat-bubble {
            max-width: 80%;
            word-wrap: break-word;
        }
        .chat-bubble-user {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: white;
        }
        .chat-bubble-assistant {
            background: #f3f4f6;
            color: #1f2937;
        }
        .typing-indicator span {
            animation: bounce 1.4s infinite ease-in-out both;
        }
        .typing-indicator span:nth-child(1) { animation-delay: -0.32s; }
        .typing-indicator span:nth-child(2) { animation-delay: -0.16s; }
        @keyframes bounce {
            0%, 80%, 100% { transform: scale(0); }
            40% { transform: scale(1); }
        }
        .fade-in {
            animation: fadeIn 0.3s ease-in;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    @include('components.customer-navbar')
    
    <div class="pt-16 pb-20">
        <div class="max-w-3xl mx-auto px-4 py-8">
            <!-- Header -->
            <div class="text-center mb-6">
                <h1 class="text-2xl font-bold text-gray-900">Visa Training</h1>
                <p class="text-gray-600 mt-1">Learn visa applications through interactive questions</p>
            </div>
            
            <!-- Progress Bar -->
            <div class="bg-white rounded-xl shadow-sm border p-4 mb-4">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-gray-700">Progress</span>
                    <span class="text-sm text-gray-500">{{ $currentStep }} / {{ $totalQuestions }}</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-indigo-600 h-2 rounded-full transition-all duration-300" style="width: <?php echo $progressPercent; ?>%"></div>
                </div>
            </div>
            
            <!-- Chat Container -->
            <div class="bg-white rounded-xl shadow-lg border overflow-hidden" style="height: 500px;">
                <!-- Chat Messages -->
                <div id="chat-messages" class="p-4 overflow-y-auto" style="height: calc(100% - 80px);">
                    @foreach($conversationHistory as $index => $message)
                        <div class="flex {{ $message['role'] === 'user' ? 'justify-end' : 'justify-start' }} mb-4 fade-in">
                            <div class="chat-bubble {{ $message['role'] === 'user' ? 'chat-bubble-user' : 'chat-bubble-assistant' }} rounded-2xl px-4 py-3">
                                <div class="text-sm whitespace-pre-line">{!! $message['content'] !!}</div>
                            </div>
                        </div>
                    @endforeach
                    
                    <!-- Typing Indicator (hidden by default) -->
                    <div id="typing-indicator" class="hidden justify-start mb-4">
                        <div class="chat-bubble chat-bubble-assistant rounded-2xl px-4 py-3">
                            <div class="typing-indicator flex items-center space-x-1">
                                <span class="w-2 h-2 bg-gray-400 rounded-full"></span>
                                <span class="w-2 h-2 bg-gray-400 rounded-full"></span>
                                <span class="w-2 h-2 bg-gray-400 rounded-full"></span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Input Area -->
                <div class="border-t p-4" style="height: 80px;">
                    <form id="chat-form" class="flex items-center space-x-2">
                        @csrf
                        <input type="text" 
                               id="user-input" 
                               name="message" 
                               class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                               placeholder="Type your answer here..."
                               autocomplete="off"
                               @if($isCompleted) disabled @endif
                        >
                        <button type="submit" 
                                class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                @if($isCompleted) disabled @endif
                        >
                            Send
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex justify-center mt-4 space-x-4">
                <a href="{{ route('visa-training.reset') }}" 
                   class="text-gray-600 hover:text-indigo-600 text-sm"
                >
                    Restart Training
                </a>
            </div>
        </div>
    </div>
    
    <script>
        const chatMessages = document.getElementById('chat-messages');
        const chatForm = document.getElementById('chat-form');
        const userInput = document.getElementById('user-input');
        const typingIndicator = document.getElementById('typing-indicator');
        let isLoading = false;
        
        function scrollToBottom() {
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }
        
        function addMessage(content, role) {
            const bubble = document.createElement('div');
            bubble.className = `flex ${role === 'user' ? 'justify-end' : 'justify-start'} mb-4 fade-in`;
            bubble.innerHTML = `
                <div class="chat-bubble ${role === 'user' ? 'chat-bubble-user' : 'chat-bubble-assistant'} rounded-2xl px-4 py-3">
                    <div class="text-sm whitespace-pre-line">${content}</div>
                </div>
            `;
            chatMessages.appendChild(bubble);
            scrollToBottom();
        }
        
        chatForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            if (isLoading) return;
            
            const message = userInput.value.trim();
            if (!message) return;
            
            isLoading = true;
            userInput.disabled = true;
            
            // Add user message
            addMessage(message, 'user');
            userInput.value = '';
            
            // Show typing indicator
            typingIndicator.classList.remove('hidden');
            typingIndicator.classList.add('flex');
            scrollToBottom();
            
            try {
                const response = await fetch('{{ route("visa-training.chat") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ message: message })
                });
                
                const data = await response.json();
                
                // Hide typing indicator
                typingIndicator.classList.add('hidden');
                typingIndicator.classList.remove('flex');
                
                if (data.success) {
                    addMessage(data.reply, 'assistant');
                    // Re-enable input for next answer
                    userInput.disabled = false;
                    userInput.value = '';
                    userInput.focus();
                    
                    // Disable input if completed
                    if (data.completed) {
                        userInput.placeholder = 'Type "restart" to try again...';
                    }
                } else {
                    addMessage('Sorry, something went wrong. Please try again.', 'assistant');
                    userInput.disabled = false;
                }
            } catch (error) {
                typingIndicator.classList.add('hidden');
                typingIndicator.classList.remove('flex');
                addMessage('Sorry, something went wrong. Please try again.', 'assistant');
                userInput.disabled = false;
            }
            
            isLoading = false;
        });
        
        // Auto-focus input on page load
        userInput.focus();
        scrollToBottom();
    </script>
</body>
</html>