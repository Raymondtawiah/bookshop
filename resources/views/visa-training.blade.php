<?php
$progressPercent = round(($currentStep / $totalQuestions) * 100);
$isInterviewMode = $interviewMode ?? false;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visa Interview Simulator - Visa Officer Charles</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            padding-top: 5rem;
            padding-bottom: 1rem;
        }
        .chat-container {
            display: flex;
            flex-direction: column;
            height: 60vh;
            min-height: 350px;
            max-height: 500px;
        }
        .chat-messages {
            flex: 1;
            overflow-y: auto;
        }
        .chat-input-area {
            flex-shrink: 0;
        }
        .chat-bubble {
            max-width: 85%;
            word-wrap: break-word;
        }
        .chat-bubble-user {
            background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
            color: white;
        }
        .chat-bubble-officer {
            background: #fef3c7;
            color: #78350f;
            border-left: 3px solid #f59e0b;
        }
        .chat-bubble-evaluation {
            background: #ecfdf5;
            color: #065f46;
            border: 2px solid #10b981;
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
        @media (max-width: 640px) {
            body {
                padding-top: 4.5rem;
                padding-bottom: 0.5rem;
            }
            .chat-container {
                height: 55vh;
                min-height: 300px;
            }
            .chat-bubble {
                max-width: 90%;
            }
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    @include('components.customer-navbar')
    
    <div class="px-2 sm:px-4 pb-4">
        <div class="max-w-3xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-3 sm:mb-4">
                <div class="flex items-center justify-center gap-2">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Visa Officer Charles</h1>
                </div>
                <p class="text-sm sm:text-base text-gray-600 mt-1">Visa Interview Simulator</p>
            </div>
            
            <!-- Progress Bar -->
            <div id="progress-container" data-total-steps="{{ $totalQuestions }}" class="bg-white rounded-lg sm:rounded-xl shadow-sm border p-3 sm:p-4 mb-3 sm:mb-4">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-gray-700">Progress</span>
                    <span id="progress-text" class="text-sm text-gray-500">{{ $currentStep }} / {{ $totalQuestions }}</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div id="progress-bar" class="bg-indigo-600 h-2 rounded-full transition-all duration-500 ease-out" style="width: <?php echo $progressPercent; ?>%"></div>
                </div>
            </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div id="progress-bar" class="bg-indigo-600 h-2 rounded-full transition-all duration-500 ease-out" style="width: <?php echo $progressPercent; ?>%"></div>
                </div>
            </div>
            
            <!-- Chat Container -->
            <div class="chat-container bg-white rounded-xl shadow-lg border overflow-hidden">
                <!-- Chat Messages -->
                <div id="chat-messages" class="chat-messages p-3 sm:p-4">
                    @foreach($conversationHistory as $index => $message)
                        @php
                            $role = $message['role'] ?? 'assistant';
                            $isUser = $role === 'user';
                            $isOfficer = $role === 'officer';
                            $isEvaluation = isset($message['content']) && str_contains($message['content'], 'EVALUATION');
                        @endphp
                        <div class="flex {{ $isUser ? 'justify-end' : 'justify-start' }} mb-3 sm:mb-4 fade-in">
                            <div class="chat-bubble {{ $isUser ? 'chat-bubble-user' : ($isOfficer ? 'chat-bubble-officer' : ($isEvaluation ? 'chat-bubble-evaluation' : 'chat-bubble-assistant')) }} rounded-2xl px-3 sm:px-4 py-2 sm:py-3">
                                @if($isOfficer)
                                    <div class="text-xs font-bold text-amber-700 mb-1">Visa Officer Charles</div>
                                @endif
                                <div class="text-xs sm:text-sm whitespace-pre-line">{!! $message['content'] !!}</div>
                            </div>
                        </div>
                    @endforeach
                    
                    <!-- Typing Indicator (hidden by default) -->
                    <div id="typing-indicator" class="hidden justify-start mb-3 sm:mb-4">
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
                <div class="chat-input-area border-t p-3 sm:p-4">
                    <form id="chat-form" class="flex items-center gap-2 sm:space-x-3">
                        @csrf
                        <button type="button" id="voice-btn" class="p-2 text-gray-500 hover:text-amber-600 transition-colors" title="Voice input">
                            <svg id="mic-icon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/>
                            </svg>
                            <svg id="mic-active-icon" class="w-5 h-5 text-red-500 hidden animate-pulse" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 14c1.66 0 3-1.34 3-3V5c0-1.66-1.34-3-3-3S9 3.34 9 5v6c0 1.66 1.34 3 3 3z"/>
                                <path d="M17 11c0 2.76-2.24 5-5 5s-5-2.24-5-5H5c0 3.53 2.61 6.43 6 6.92V21h2v-3.08c3.39-.49 6-3.39 6-6.92h-2z"/>
                            </svg>
                        </button>
                        <input type="text" 
                               id="user-input" 
                               name="message" 
                               class="flex-1 border border-gray-300 rounded-lg px-3 sm:px-4 py-2 text-sm sm:text-base focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent"
                               placeholder="Type your answer here..."
                               autocomplete="off"
                               @if($isCompleted) disabled @endif
                        >
                        <button type="submit" 
                                class="bg-amber-600 text-white px-4 sm:px-6 py-2 rounded-lg hover:bg-amber-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed text-sm sm:text-base whitespace-nowrap"
                                @if($isCompleted) disabled @endif
                        >
                            Send
                        </button>
                    </form>
                    <div id="voice-status" class="text-xs text-center text-gray-500 mt-1 hidden">Listening...</div>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex justify-center mt-3 sm:mt-4">
                <a href="{{ route('visa-training.reset') }}" 
                   class="text-gray-600 hover:text-amber-600 text-sm"
                >
                    Start New Interview
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
        
        function updateProgress(step) {
            const container = document.getElementById('progress-container');
            const total = parseInt(container.dataset.totalSteps);
            const percent = Math.min(100, Math.round((step / total) * 100));
            
            const bar = document.getElementById('progress-bar');
            const text = document.getElementById('progress-text');
            
            bar.style.width = percent + '%';
            text.textContent = step + ' / ' + total;
        }
        
        function scrollToBottom() {
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }
        
        function addMessage(content, role) {
            const bubble = document.createElement('div');
            const isUser = role === 'user';
            const isOfficer = role === 'officer';
            const isEvaluation = content && content.includes('EVALUATION');
            
            bubble.className = `flex ${isUser ? 'justify-end' : 'justify-start'} mb-3 sm:mb-4 fade-in`;
            
            let innerHTML = '';
            if (isOfficer) {
                innerHTML = `
                    <div class="chat-bubble chat-bubble-officer rounded-2xl px-3 sm:px-4 py-2 sm:py-3">
                        <div class="text-xs font-bold text-amber-700 mb-1">Visa Officer Charles</div>
                        <div class="text-xs sm:text-sm whitespace-pre-line">${content}</div>
                    </div>
                `;
            } else if (isEvaluation) {
                innerHTML = `
                    <div class="chat-bubble chat-bubble-evaluation rounded-2xl px-3 sm:px-4 py-2 sm:py-3">
                        <div class="text-xs sm:text-sm whitespace-pre-line">${content}</div>
                    </div>
                `;
            } else {
                innerHTML = `
                    <div class="chat-bubble ${isUser ? 'chat-bubble-user' : 'chat-bubble-assistant'} rounded-2xl px-3 sm:px-4 py-2 sm:py-3">
                        <div class="text-xs sm:text-sm whitespace-pre-line">${content}</div>
                    </div>
                `;
            }
            
            bubble.innerHTML = innerHTML;
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
                
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                
                const data = await response.json();
                console.log('Server response:', data);
                
                // Hide typing indicator
                typingIndicator.classList.add('hidden');
                typingIndicator.classList.remove('flex');
                
                 if (data.success) {
                     const replyRole = data.completed ? 'officer' : 'officer';
                     addMessage(data.reply, replyRole);
                     updateProgress(data.step);
                     // Re-enable input for next answer
                     userInput.disabled = false;
                     userInput.value = '';
                     userInput.focus();
                     
                     // Disable input if completed
                     if (data.completed) {
                         userInput.disabled = false;
                         userInput.placeholder = 'Type "restart" to start a new interview...';
                     }
                 } else {
                    addMessage('Sorry, something went wrong. Please try again.', 'assistant');
                    userInput.disabled = false;
                }
            } catch (error) {
                console.error('Fetch error:', error);
                typingIndicator.classList.add('hidden');
                typingIndicator.classList.remove('flex');
                addMessage('Sorry, something went wrong: ' + error.message, 'officer');
                userInput.disabled = false;
            }
            
            isLoading = false;
        });
        
        // Auto-focus input on page load
        userInput.focus();
        scrollToBottom();

        // Voice Input
        const voiceBtn = document.getElementById('voice-btn');
        const micIcon = document.getElementById('mic-icon');
        const micActiveIcon = document.getElementById('mic-active-icon');
        const voiceStatus = document.getElementById('voice-status');
        
        let recognition = null;
        
        if ('webkitSpeechRecognition' in window || 'SpeechRecognition' in window) {
            const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
            recognition = new SpeechRecognition();
            recognition.continuous = false;
            recognition.interimResults = true;
            recognition.lang = 'en-US';
            
            recognition.onstart = function() {
                micIcon.classList.add('hidden');
                micActiveIcon.classList.remove('hidden');
                voiceStatus.classList.remove('hidden');
                userInput.placeholder = 'Listening...';
            };
            
            recognition.onend = function() {
                micIcon.classList.remove('hidden');
                micActiveIcon.classList.add('hidden');
                voiceStatus.classList.add('hidden');
                userInput.placeholder = 'Type your answer here...';
            };
            
            recognition.onresult = function(event) {
                let transcript = '';
                for (let i = event.resultIndex; i < event.results.length; i++) {
                    if (event.results[i].isFinal) {
                        transcript += event.results[i][0].transcript;
                    }
                }
                if (transcript) {
                    userInput.value = transcript;
                }
            };
            
            recognition.onerror = function(event) {
                console.error('Voice recognition error:', event.error);
                micIcon.classList.remove('hidden');
                micActiveIcon.classList.add('hidden');
                voiceStatus.classList.add('hidden');
            };
            
            voiceBtn.addEventListener('click', function() {
                if (recognition) {
                    recognition.start();
                }
            });
        } else {
            voiceBtn.classList.add('hidden');
        }
    </script>
</body>
</html>