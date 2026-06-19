@extends('layouts.customer')

@section('content')
<div class="min-h-screen bg-slate-50 text-slate-900">
    <div class="max-w-7xl mx-auto p-6">
        <div class="flex justify-between items-center mb-6 gap-4 flex-col sm:flex-row">
            <div>
                <h1 class="text-4xl font-bold text-blue-600">Visa Interview AI</h1>
                <p class="text-gray-500 mt-2">Practice. Prepare. Get Approved.</p>
            </div>

            <div class="flex gap-3 w-full sm:w-auto">
                <select class="border border-slate-200 rounded-lg px-4 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option>English</option>
                </select>
                <button onclick="endInterview()" class="border border-red-500 text-red-500 hover:bg-red-50 px-6 py-2 rounded-lg font-semibold">
                    End Interview
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white rounded-xl p-5 shadow-sm border border-slate-100">
                <p class="text-sm text-gray-500">COUNTRY</p>
                <h3 id="country-card" class="font-semibold text-lg mt-1">{{ $country ?? 'Select Country' }}</h3>
            </div>

            <div class="bg-white rounded-xl p-5 shadow-sm border border-slate-100">
                <p class="text-sm text-gray-500">VISA TYPE</p>
                <h3 id="visa-type-card" class="font-semibold text-lg mt-1">{{ $visaType ?? 'Select Visa Type' }}</h3>
            </div>

            <div class="bg-white rounded-xl p-5 shadow-sm border border-slate-100">
                <p class="text-sm text-gray-500">ELAPSED TIME</p>
                <h3 id="elapsed-time" class="font-semibold text-lg mt-1">00:00</h3>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <section class="lg:col-span-8 bg-white rounded-xl shadow-sm border border-slate-100 p-6">
                <div id="chat-messages" class="space-y-6 min-h-[420px]">
                    @foreach($conversationHistory as $msg)
                        <div class="{{ $msg['role'] === 'user' ? 'flex justify-end' : '' }}">
                            <div class="{{ $msg['role'] === 'user' ? 'bg-blue-50 text-slate-900' : 'bg-slate-100 text-slate-800' }} p-4 rounded-xl max-w-xl">
                                <p class="text-xs font-bold text-blue-600 mb-1">{{ $msg['role'] === 'user' ? 'YOU' : 'AI CONSULAR OFFICER' }}</p>
                                <p class="whitespace-pre-wrap">{{ $msg['content'] }}</p>
                            </div>
                        </div>
                    @endforeach
                    <div id="visa-options-slot"></div>
                    <div id="completion-banner" class="rounded-xl border p-4 {{ $isCompleted ? 'border-green-200 bg-green-50 text-green-700' : 'border-slate-200 bg-slate-50 text-slate-600' }}">
                        {{ $isCompleted ? 'Interview complete. Review the AI decision below or reset to begin again.' : 'Choose your country and visa type to start the AI interview.' }}
                    </div>
                </div>

                <div class="mt-8 flex gap-3">
                    <input id="chat-input" type="text" placeholder="Type your answer here..." class="flex-1 border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <button id="send-btn" onclick="sendChatMessage()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 rounded-xl font-semibold">
                        Send
                    </button>
                </div>
            </section>

            <aside class="lg:col-span-4 space-y-4">
                <div class="bg-white rounded-xl p-5 shadow-sm border border-slate-100">
                    <h3 class="font-bold text-blue-600 mb-3">INTERVIEW PROGRESS</h3>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div id="progress-bar" class="bg-blue-600 h-3 rounded-full transition-all" style="width: {{ ($currentStep / $totalQuestions) * 100 }}%"></div>
                    </div>
                    <p id="progress-text" class="mt-2 text-gray-600">{{ round(($currentStep / $totalQuestions) * 100) }}% Completed</p>
                    <p class="mt-1 text-sm text-gray-500">Step <span id="step-indicator">{{ $currentStep }}</span> / {{ $totalQuestions }}</p>
                </div>

                <div class="bg-white rounded-xl p-5 shadow-sm border border-slate-100">
                    <h3 class="font-bold text-blue-600 mb-4">AI FEEDBACK</h3>
                    <ul id="feedback-list" class="space-y-3 text-sm">
                        <li class="text-slate-600">Your latest feedback will appear here.</li>
                    </ul>
                </div>

                <div class="bg-white rounded-xl p-5 shadow-sm border border-slate-100">
                    <h3 class="font-bold text-blue-600 mb-4">COMMON TIPS</h3>
                    <ul class="space-y-3 text-sm text-slate-700">
                        <li>Be honest and consistent.</li>
                        <li>Give clear, direct answers.</li>
                        <li>Show strong home-country ties.</li>
                        <li>Know your study or travel plan.</li>
                        <li>Explain your finances clearly.</li>
                    </ul>
                </div>
            </aside>
        </div>
    </div>
</div>

<script>
(function(){
    var messages = document.getElementById('chat-messages');
    var chatInput = document.getElementById('chat-input');
    var sendBtn = document.getElementById('send-btn');
    var completionBanner = document.getElementById('completion-banner');
    var stepIndicator = document.getElementById('step-indicator');
    var progressBar = document.getElementById('progress-bar');
    var progressText = document.getElementById('progress-text');
    var countryCard = document.getElementById('country-card');
    var visaTypeCard = document.getElementById('visa-type-card');
    var feedbackList = document.getElementById('feedback-list');
    var completed = {{ $isCompleted ? 'true' : 'false' }};
    var totalQuestions = {{ $totalQuestions ?? 8 }};
    var country = '{{ $country ?? "USA" }}';
    var visaType = '{{ $visaType ?? "" }}';
    var elapsedSeconds = 0;

    if (completed) {
        disableInput();
    }

    function getCsrf() {
        var meta = document.querySelector('meta[name=csrf-token]');
        return meta ? meta.getAttribute('content') : '';
    }

    function escapeHtml(text) {
        var div = document.createElement('div');
        div.textContent = text || '';
        return div.innerHTML;
    }

    function appendMessage(role, content) {
        var wrap = document.createElement('div');
        wrap.className = role === 'user' ? 'flex justify-end' : '';
        wrap.innerHTML = '<div class="' + (role === 'user' ? 'bg-blue-50 text-slate-900' : 'bg-slate-100 text-slate-800') + ' p-4 rounded-xl max-w-xl"><p class="text-xs font-bold text-blue-600 mb-1">' + (role === 'user' ? 'YOU' : 'AI CONSULAR OFFICER') + '</p><p class="whitespace-pre-wrap">' + escapeHtml(content) + '</p></div>';
        var slot = document.getElementById('visa-options-slot');
        messages.insertBefore(wrap, slot);
        scrollToBottom();
    }

    function scrollToBottom() {
        requestAnimationFrame(function() {
            messages.scrollTop = messages.scrollHeight;
        });
    }

    function updateProgress(step) {
        var current = Math.max(0, Math.min(totalQuestions, step || 0));
        var percent = Math.round((current / totalQuestions) * 100);
        progressBar.style.width = percent + '%';
        progressText.textContent = percent + '% Completed';
        if (stepIndicator) stepIndicator.textContent = current;
    }

    function updateFeedback(content) {
        var items = [];
        var text = content || '';

        if (text.indexOf('EVALUATION BLOCK:') !== -1) {
            var decision = extractLine(text, 'Decision') || 'Pending';
            var score = extractLine(text, 'Score') || '0';
            var risk = extractLine(text, 'Risk Level') || 'Unknown';
            var remarks = extractLine(text, 'Remarks') || 'Review your answers carefully.';

            items.push(decision);
            items.push('Score: ' + score + '/100');
            items.push('Risk Level: ' + risk);
            items.push(remarks);
        } else if (text.indexOf('TIP:') === 0) {
            var tip = text.replace(/^TIP:\s*/i, '').split(/[.!?]\s/)[0] + '.';
            items.push('Coaching: ' + tip);
            items.push('Continue with the next consular question.');
        } else if (text.indexOf('Good.') === 0) {
            items.push('Answer accepted.');
            items.push(text.replace(/^Good\.\s*/i, ''));
        } else {
            items.push('Keep your answer clear, specific, and consistent.');
            items.push('Focus on purpose, finances, ties, and return plans.');
        }

        feedbackList.innerHTML = '';
        items.slice(0, 4).forEach(function(item) {
            var li = document.createElement('li');
            li.className = 'text-slate-700';
            li.textContent = item;
            feedbackList.appendChild(li);
        });
    }

    function extractLine(text, label) {
        var match = text.match(new RegExp(label + ':\\s*([^\\r\\n]+)', 'i'));
        return match ? match[1].trim() : '';
    }

    function disableInput() {
        chatInput.disabled = true;
        sendBtn.disabled = true;
        chatInput.placeholder = 'Interview ended. Click End Interview to reset.';
    }

    function sendChatMessageWithText(text) {
        if (completed) return;
        var msg = (text || '').trim();
        if (!msg) return;

        appendMessage('user', msg);
        chatInput.value = '';
        chatInput.disabled = true;
        sendBtn.disabled = true;

        if (!visaType) {
            if (msg.indexOf('F1') !== -1 || msg.indexOf('Student') !== -1) {
                visaType = 'Student';
            } else if (msg.indexOf('B1') !== -1 || msg.indexOf('B2') !== -1 || msg.indexOf('Visitor') !== -1 || msg.indexOf('Tourist') !== -1 || msg.indexOf('Business') !== -1) {
                visaType = 'Tourist';
            } else if (msg.indexOf('Work') !== -1) {
                visaType = 'Work';
            }
        }

        fetch("{{ route('visa.chat') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getCsrf()
            },
            body: JSON.stringify({ message: msg })
        })
        .then(function(response) { return response.json(); })
        .then(function(data) {
            if (data && data.success) {
                appendMessage('officer', data.reply);
                updateProgress(data.step);
                updateFeedback(data.reply);

                if (visaType) {
                    visaTypeCard.textContent = visaType;
                }

                if (data.completed) {
                    completed = true;
                    completionBanner.className = 'rounded-xl border p-4 border-green-200 bg-green-50 text-green-700';
                    completionBanner.textContent = 'Interview complete. Review the AI decision below or reset to begin again.';
                    disableInput();
                }
            } else {
                appendMessage('officer', 'Sorry, something went wrong. Please try again.');
            }
        })
        .catch(function() {
            appendMessage('officer', 'Network error. Please try again.');
        })
        .finally(function() {
            if (!completed) {
                chatInput.disabled = false;
                sendBtn.disabled = false;
                chatInput.focus();
            }
        });
    }

    function sendChatMessage() {
        if (completed) return;
        var msg = chatInput.value.trim();
        if (!msg) return;
        sendChatMessageWithText(msg);
    }

    function showCountryOptions() {
        var slot = document.getElementById('visa-options-slot');
        slot.innerHTML = '<div class="grid grid-cols-1 sm:grid-cols-2 gap-3">' +
            '<button class="country-btn p-4 rounded-xl border border-blue-200 bg-blue-50 hover:bg-blue-100 text-left font-semibold text-blue-700" data-country="USA">USA</button>' +
            '<button class="country-btn p-4 rounded-xl border border-blue-200 bg-blue-50 hover:bg-blue-100 text-left font-semibold text-blue-700" data-country="UK">UK</button>' +
            '<button class="country-btn p-4 rounded-xl border border-blue-200 bg-blue-50 hover:bg-blue-100 text-left font-semibold text-blue-700" data-country="Canada">Canada</button>' +
            '<button class="country-btn p-4 rounded-xl border border-blue-200 bg-blue-50 hover:bg-blue-100 text-left font-semibold text-blue-700" data-country="Schengen">Schengen</button>' +
            '</div>';

        slot.querySelectorAll('.country-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                country = btn.getAttribute('data-country');
                countryCard.textContent = country;
                slot.innerHTML = '';
                showVisaTypeOptions();
            });
        });
    }

    function showVisaTypeOptions() {
        var slot = document.getElementById('visa-options-slot');
        slot.innerHTML = '<div class="grid grid-cols-1 sm:grid-cols-3 gap-3">' +
            '<button class="visa-btn p-4 rounded-xl border border-blue-200 bg-blue-50 hover:bg-blue-100 text-left font-semibold text-blue-700" data-type="Tourist">Tourist</button>' +
            '<button class="visa-btn p-4 rounded-xl border border-blue-200 bg-blue-50 hover:bg-blue-100 text-left font-semibold text-blue-700" data-type="Student">Student</button>' +
            '<button class="visa-btn p-4 rounded-xl border border-blue-200 bg-blue-50 hover:bg-blue-100 text-left font-semibold text-blue-700" data-type="Work">Work</button>' +
            '</div>';

        slot.querySelectorAll('.visa-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                visaType = btn.getAttribute('data-type');
                visaTypeCard.textContent = visaType;
                slot.innerHTML = '';
                startInterview();
            });
        });
    }

    function startInterview() {
        fetch("{{ route('visa.start') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getCsrf()
            },
            body: JSON.stringify({
                country: country,
                type: visaType
            })
        })
        .then(function(res) { return res.json(); })
        .then(function(data) {
            var question = data.choices[0].message.content;
            appendMessage('officer', question);
            updateProgress(1);
        });
    }

    window.resetInterview = function() {
        window.location.replace("{{ route('visa-interview') }}");
    };

    window.endInterview = function() {
        if (confirm('End this interview and clear the current session?')) {
            resetInterview();
        }
    };

    chatInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            sendChatMessage();
        }
    });

    scrollToBottom();
    if (!country && !visaType) {
        showCountryOptions();
    }

    setInterval(function() {
        elapsedSeconds++;
        var minutes = String(Math.floor(elapsedSeconds / 60)).padStart(2, '0');
        var seconds = String(elapsedSeconds % 60).padStart(2, '0');
        document.getElementById('elapsed-time').textContent = minutes + ':' + seconds;
    }, 1000);
})();
</script>
@endsection