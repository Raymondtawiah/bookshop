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
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #4f46e5;
            --primary-dark: #3730a3;
            --primary-light: #e0e7ff;
            --accent: #f59e0b;
            --accent-dark: #d97706;
            --success: #10b981;
            --success-light: #d1fae5;
            --officer-bg: #fffbeb;
            --officer-border: #fcd34d;
            --user-gradient: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%);
            min-height: 100vh;
            padding-top: 5rem;
            padding-bottom: 2rem;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .page-wrapper {
            max-width: 900px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        /* Header */
        .page-header {
            text-align: center;
            margin-bottom: 2rem;
            animation: slideDown 0.5s ease-out;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .header-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 9999px;
            padding: 0.5rem 1rem;
            margin-bottom: 1rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .header-badge svg {
            color: var(--accent);
        }

        .header-badge span {
            font-size: 0.875rem;
            font-weight: 500;
            color: #64748b;
        }

        .page-title {
            font-size: 2rem;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.025em;
            margin: 0 0 0.5rem 0;
        }

        .page-subtitle {
            font-size: 1rem;
            color: #64748b;
            margin: 0;
            font-weight: 400;
        }

        /* Progress Section */
        .progress-card {
            background: white;
            border-radius: 1rem;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05), 0 1px 2px rgba(0, 0, 0, 0.03);
            border: 1px solid #e2e8f0;
            animation: fadeIn 0.5s ease-out 0.1s both;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .progress-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.75rem;
        }

        .progress-label {
            font-size: 0.875rem;
            font-weight: 600;
            color: #334155;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .progress-label svg {
            width: 1.125rem;
            height: 1.125rem;
            color: var(--primary);
        }

        .progress-text {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--primary);
            background: var(--primary-light);
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
        }

        .progress-track {
            width: 100%;
            height: 0.5rem;
            background: #e2e8f0;
            border-radius: 9999px;
            overflow: hidden;
            position: relative;
        }

        .progress-fill {
            height: 100%;
            background: var(--user-gradient);
            border-radius: 9999px;
            transition: width 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
        }

        .progress-fill::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            animation: shimmer 2s infinite;
        }

        @keyframes shimmer {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        /* Chat Container */
        .chat-wrapper {
            background: white;
            border-radius: 1.25rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03), 0 0 0 1px rgba(0, 0, 0, 0.02);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            animation: fadeIn 0.5s ease-out 0.2s both;
        }

        .chat-header {
            background: linear-gradient(135deg, #1e1b4b 0%, #312e81 50%, #3730a3 100%);
            padding: 1rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            position: relative;
            overflow: hidden;
        }

        .chat-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 200px;
            height: 200px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
            border-radius: 50%;
        }

        .officer-avatar {
            width: 2.5rem;
            height: 2.5rem;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .officer-avatar svg {
            width: 1.25rem;
            height: 1.25rem;
            color: white;
        }

        .officer-info {
            flex: 1;
            min-width: 0;
        }

        .officer-name {
            font-size: 0.9375rem;
            font-weight: 700;
            color: white;
            margin: 0;
            letter-spacing: -0.01em;
        }

        .officer-status {
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.7);
            margin: 0.125rem 0 0 0;
            display: flex;
            align-items: center;
            gap: 0.375rem;
        }

        .status-dot {
            width: 0.5rem;
            height: 0.5rem;
            background: #10b981;
            border-radius: 50%;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.7; transform: scale(1.1); }
        }

        /* Chat Messages */
        .chat-messages {
            flex: 1;
            overflow-y: auto;
            padding: 1.5rem;
            background: #fafafa;
            background-image: 
                radial-gradient(circle at 20% 50%, rgba(79, 70, 229, 0.02) 0%, transparent 50%),
                radial-gradient(circle at 80% 50%, rgba(245, 158, 11, 0.02) 0%, transparent 50%);
        }

        .chat-messages::-webkit-scrollbar {
            width: 6px;
        }

        .chat-messages::-webkit-scrollbar-track {
            background: transparent;
        }

        .chat-messages::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 9999px;
        }

        .chat-messages::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        .message-row {
            display: flex;
            margin-bottom: 1rem;
            animation: messageIn 0.3s ease-out;
        }

        @keyframes messageIn {
            from { opacity: 0; transform: translateY(8px) scale(0.98); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        .message-row.user {
            justify-content: flex-end;
        }

        .message-row.officer,
        .message-row.assistant {
            justify-content: flex-start;
        }

        .message-row.evaluation {
            justify-content: center;
        }

        .chat-bubble {
            max-width: 85%;
            padding: 0.875rem 1.125rem;
            border-radius: 1rem;
            word-wrap: break-word;
            position: relative;
            line-height: 1.5;
        }

        .chat-bubble-user {
            background: var(--user-gradient);
            color: white;
            border-bottom-right-radius: 0.25rem;
            box-shadow: 0 2px 8px rgba(79, 70, 229, 0.2);
        }

        .chat-bubble-officer {
            background: white;
            color: #1e293b;
            border: 1px solid #fef3c7;
            border-bottom-left-radius: 0.25rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        }

        .chat-bubble-officer::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 3px;
            background: var(--accent);
            border-radius: 3px 0 0 3px;
        }

        .officer-label {
            font-size: 0.6875rem;
            font-weight: 700;
            color: var(--accent-dark);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.375rem;
            display: flex;
            align-items: center;
            gap: 0.375rem;
        }

        .officer-label svg {
            width: 0.75rem;
            height: 0.75rem;
        }

        .chat-bubble-evaluation {
            background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
            color: #065f46;
            border: 2px solid var(--success);
            border-radius: 1rem;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.15);
            max-width: 95%;
        }

        .chat-bubble-evaluation::before {
            content: '✓';
            position: absolute;
            top: -0.75rem;
            left: 1rem;
            background: var(--success);
            color: white;
            width: 1.5rem;
            height: 1.5rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 700;
        }

        .evaluation-title {
            font-weight: 700;
            font-size: 0.875rem;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .message-content {
            font-size: 0.9375rem;
            line-height: 1.6;
        }

        .message-time {
            font-size: 0.6875rem;
            margin-top: 0.5rem;
            opacity: 0.7;
            text-align: right;
        }

        .user .message-time {
            color: rgba(255, 255, 255, 0.8);
        }

        /* Typing Indicator */
        .typing-indicator {
            display: none;
            justify-content: flex-start;
            margin-bottom: 1rem;
            animation: messageIn 0.3s ease-out;
        }

        .typing-indicator.active {
            display: flex;
        }

        .typing-bubble {
            background: white;
            border: 1px solid #fef3c7;
            border-radius: 1rem;
            border-bottom-left-radius: 0.25rem;
            padding: 1rem 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        }

        .typing-dot {
            width: 0.5rem;
            height: 0.5rem;
            background: var(--accent);
            border-radius: 50%;
            animation: typingBounce 1.4s infinite ease-in-out both;
        }

        .typing-dot:nth-child(1) { animation-delay: -0.32s; }
        .typing-dot:nth-child(2) { animation-delay: -0.16s; }
        .typing-dot:nth-child(3) { animation-delay: 0s; }

        @keyframes typingBounce {
            0%, 80%, 100% { transform: scale(0.6); opacity: 0.4; }
            40% { transform: scale(1); opacity: 1; }
        }

        /* Input Area */
        .chat-input-area {
            padding: 1rem 1.5rem;
            background: white;
            border-top: 1px solid #e2e8f0;
            flex-shrink: 0;
        }

        .input-form {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .voice-btn {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 50%;
            border: 1px solid #e2e8f0;
            background: white;
            color: #64748b;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
            flex-shrink: 0;
        }

        .voice-btn:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
            color: var(--primary);
        }

        .voice-btn.recording {
            background: #fef2f2;
            border-color: #dc2626;
            color: #dc2626;
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.15);
            animation: pulse 1.5s infinite;
        }

        .text-input.recording {
            border-color: #dc2626 !important;
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.15) !important;
            background: #fff5f5 !important;
            animation: recordingPulse 2s infinite;
        }

        @keyframes recordingPulse {
            0%, 100% { box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.15); }
            50% { box-shadow: 0 0 0 6px rgba(220, 38, 38, 0.05); }
        }

        .voice-status.active {
            display: block;
            color: #dc2626;
            font-weight: 500;
        }

        .speech-preview {
            font-size: 0.75rem;
            color: #64748b;
            text-align: center;
            margin-top: 0.35rem;
            min-height: 1.1rem;
            word-break: break-word;
        }

        .speech-preview.active {
            color: #0f172a;
            font-weight: 500;
        }

        .mic-level {
            display: flex;
            gap: 2px;
            align-items: flex-end;
            height: 16px;
            margin-left: 6px;
        }

        .mic-level .bar {
            width: 3px;
            background: #dc2626;
            border-radius: 2px;
            transition: height 0.08s ease;
        }

        .text-input.recording {
            border-color: #dc2626 !important;
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.15) !important;
            background: #fff5f5 !important;
            animation: recordingPulse 2s infinite;
        }

        @keyframes recordingPulse {
            0%, 100% { box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.15); }
            50% { box-shadow: 0 0 0 6px rgba(220, 38, 38, 0.05); }
        }

        .text-input {
            flex: 1;
            min-width: 0;
            border: 1px solid #e2e8f0;
            border-radius: 0.75rem;
            padding: 0.625rem 1rem;
            font-size: 0.9375rem;
            font-family: inherit;
            transition: all 0.2s;
            background: #fafafa;
        }

        .text-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
            background: white;
        }

        .text-input.recording {
            border-color: #dc2626 !important;
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.15) !important;
            background: #fff5f5 !important;
            animation: recordingPulse 2s infinite;
        }

        .text-input:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            background: #f1f5f9;
        }

        .send-btn {
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 0.75rem;
            padding: 0.625rem 1.25rem;
            font-size: 0.9375rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            white-space: nowrap;
            font-family: inherit;
            flex-shrink: 0;
        }

        .send-btn:hover:not(:disabled) {
            background: var(--primary-dark);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
        }

        .send-btn:active:not(:disabled) {
            transform: translateY(0);
        }

        .send-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .voice-status {
            font-size: 0.75rem;
            color: #64748b;
            text-align: center;
            margin-top: 0.5rem;
            display: none;
        }

        .voice-status.active {
            display: block;
            color: #dc2626;
            font-weight: 500;
        }

        .speech-preview {
            font-size: 0.75rem;
            color: #64748b;
            text-align: center;
            margin-top: 0.35rem;
            min-height: 1.1rem;
            word-break: break-word;
        }

        .speech-preview.active {
            color: #0f172a;
            font-weight: 500;
        }

        /* Action Bar */
        .action-bar {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-top: 1.5rem;
            padding: 0 0.5rem;
            animation: fadeIn 0.5s ease-out 0.3s both;
        }

        .restart-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: #64748b;
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            transition: all 0.2s;
            background: white;
            border: 1px solid #e2e8f0;
        }

        .restart-link:hover {
            background: #f8fafc;
            color: var(--primary);
            border-color: var(--primary);
            transform: translateY(-1px);
        }

        /* Responsive */
        @media (max-width: 640px) {
            body {
                padding-top: 4rem;
                padding-bottom: 1rem;
            }

            .page-title {
                font-size: 1.5rem;
            }

            .chat-messages {
                padding: 1rem;
            }

            .chat-bubble {
                max-width: 90%;
            }

            .chat-input-area {
                padding: 0.875rem 1rem;
            }

            .progress-card {
                padding: 1rem;
            }

            .chat-header {
                padding: 0.875rem 1rem;
            }

            .input-form {
                gap: 0.5rem;
            }

            .voice-btn {
                width: 2.25rem;
                height: 2.25rem;
            }

            .voice-btn svg {
                width: 1.25rem;
                height: 1.25rem;
            }

            .text-input {
                padding: 0.5rem 0.75rem;
                font-size: 0.875rem;
            }

            .send-btn {
                padding: 0.5rem 1rem;
                font-size: 0.875rem;
            }
        }

        @media (min-width: 1024px) {
            .chat-messages {
                min-height: 400px;
                max-height: 500px;
            }
        }
    </style>
</head>
<body class="min-h-screen">
    @include('components.customer-navbar')

    <div class="page-wrapper">
        <!-- Header -->
        <div class="page-header">
            <div class="header-badge">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
                <span>Visa Interview Training</span>
            </div>
            <h1 class="page-title">Visa Officer Charles</h1>
            <p class="page-subtitle">Practice your visa interview with realistic scenarios and get detailed feedback</p>
        </div>

        <!-- Progress Bar -->
        <div id="progress-container" data-total-steps="{{ $totalQuestions }}" class="progress-card">
            <div class="progress-header">
                <div class="progress-label">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    Interview Progress
                </div>
                <div class="progress-text">{{ $currentStep }} / {{ $totalQuestions }}</div>
            </div>
            <div class="progress-track">
                <div id="progress-bar" class="progress-fill" style="width: <?php echo $progressPercent; ?>%"></div>
            </div>
        </div>

        <!-- Chat Container -->
        <div class="chat-wrapper">
            <!-- Chat Header -->
            <div class="chat-header">
                <div class="officer-avatar">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <div class="officer-info">
                    <h3 class="officer-name">Visa Officer Charles</h3>
                    <p class="officer-status">
                        <span class="status-dot"></span>
                        Conducting interview
                    </p>
                </div>
            </div>

            <!-- Chat Messages -->
            <div id="chat-messages" class="chat-messages">
                @foreach($conversationHistory as $index => $message)
                    @php
                        $role = $message['role'] ?? 'assistant';
                        $isUser = $role === 'user';
                        $isOfficer = $role === 'officer';
                        $isEvaluation = isset($message['content']) && str_contains($message['content'], 'EVALUATION');
                    @endphp
                        <div class="message-row {{ $isUser ? 'user' : ($isOfficer ? 'officer' : ($isEvaluation ? 'evaluation' : 'assistant')) }}">
                            <div class="chat-bubble {{ $isUser ? 'chat-bubble-user' : ($isOfficer ? 'chat-bubble-officer' : ($isEvaluation ? 'chat-bubble-evaluation' : 'chat-bubble-assistant')) }}">
                                @if($isOfficer)
                                    <div class="officer-label">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                        </svg>
                                        Visa Officer Charles
                                    </div>
                                    @php
                                        $content = $message['content'] ?? '';
                                        $tipText = null;
                                        $questionText = $content;
                                        if (preg_match('/^TIP:\s*(.*?)(?:\n|$)/i', $content, $tipMatches)) {
                                            $tipText = trim($tipMatches[1]);
                                            $questionText = preg_replace('/^TIP:\s*.*?(\n|$)/i', '', $content);
                                            $questionText = trim($questionText);
                                        }
                                    @endphp
                                    @if($tipText)
                                        <div style="background: #fffbeb; border-left: 3px solid #f59e0b; padding: 0.75rem; border-radius: 0.5rem; margin-bottom: 0.75rem;">
                                            <div style="font-size: 0.6875rem; font-weight: 700; color: #b45309; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.25rem;">💡 Coaching Tip</div>
                                            <div class="message-content" style="font-size: 0.875rem; color: #78350f;">{{ $tipText }}</div>
                                        </div>
                                    @endif
                                    <div class="message-content">{!! $questionText !!}</div>
                                @elseif($isEvaluation)
                                    <div class="evaluation-title">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Interview Evaluation
                                    </div>
                                    <div class="message-content">{!! $message['content'] !!}</div>
                                @else
                                    <div class="message-content">{!! $message['content'] !!}</div>
                                @endif
                        </div>
                    </div>
                @endforeach

                <!-- Typing Indicator -->
                <div id="typing-indicator" class="typing-indicator">
                    <div class="typing-bubble">
                        <div class="typing-dot"></div>
                        <div class="typing-dot"></div>
                        <div class="typing-dot"></div>
                    </div>
                </div>
            </div>

            <!-- Input Area -->
            <div class="chat-input-area">
                <form id="chat-form" class="input-form">
                    @csrf
                    {{-- Voice input disabled for now --}}
                    {{-- <button type="button" id="voice-btn" class="voice-btn" title="Voice input">
                        <svg id="mic-icon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/>
                        </svg>
                        <svg id="mic-active-icon" class="w-5 h-5 hidden" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 14c1.66 0 3-1.34 3-3V5c0-1.66-1.34-3-3-3S9 3.34 9 5v6c0 1.66 1.34 3 3 3z"/>
                            <path d="M17 11c0 2.76-2.24 5-5 5s-5-2.24-5-5H5c0 3.53 2.61 6.43 6 6.92V21h2v-3.08c3.39-.49 6-3.39 6-6.92h-2z"/>
                        </svg>
                        <div id="mic-level" class="mic-level" style="display: none;">
                            <div class="bar" style="height: 4px;"></div>
                            <div class="bar" style="height: 4px;"></div>
                            <div class="bar" style="height: 4px;"></div>
                            <div class="bar" style="height: 4px;"></div>
                            <div class="bar" style="height: 4px;"></div>
                        </div>
                    </button> --}}
                    <input type="text"
                           id="user-input"
                           name="message"
                           class="text-input"
                           placeholder="Type your answer here..."
                           autocomplete="off"
                           @if($isCompleted) disabled @endif
                    >
                    <button type="submit"
                            class="send-btn"
                            @if($isCompleted) disabled @endif
                    >
                        Send
                    </button>
                </form>
                {{-- Voice status disabled for now --}}
                {{-- <div id="voice-status" class="voice-status">Listening...</div>
                <div id="speech-preview" class="speech-preview"></div> --}}
            </div>
        </div>

        <!-- Action Bar -->
        <div class="action-bar">
            <a href="{{ route('visa-training.reset') }}"
               class="restart-link"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Start New Interview
            </a>
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
            const text = container.querySelector('.progress-text');

            bar.style.width = percent + '%';
            text.textContent = step + ' / ' + total;
        }

        function scrollToBottom() {
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        function addMessage(content, role) {
            const isUser = role === 'user';
            const isOfficer = role === 'officer';
            const isEvaluation = content && content.includes('EVALUATION');
            const hasTip = !isUser && !isEvaluation && content && content.includes('TIP:');

            const row = document.createElement('div');
            row.className = `message-row ${isUser ? 'user' : (isOfficer ? 'officer' : (isEvaluation ? 'evaluation' : 'assistant'))}`;

            let innerHTML = '';
            if (isOfficer || (!isUser && !isEvaluation)) {
                const tipMatch = hasTip ? content.match(/TIP:\s*(.*?)(?:\n|$)/i) : null;
                const tipText = tipMatch ? tipMatch[1].trim() : null;
                const questionText = tipText ? content.replace(/TIP:\s*.*?(\n|$)/i, '').trim() : content;

                innerHTML = `
                    <div class="chat-bubble chat-bubble-officer">
                        <div class="officer-label">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                            Visa Officer Charles
                        </div>
                        ${tipText ? `
                            <div style="background: #fffbeb; border-left: 3px solid #f59e0b; padding: 0.75rem; border-radius: 0.5rem; margin-bottom: 0.75rem;">
                                <div style="font-size: 0.6875rem; font-weight: 700; color: #b45309; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.25rem;">💡 Coaching Tip</div>
                                <div class="message-content" style="font-size: 0.875rem; color: #78350f;">${tipText}</div>
                            </div>
                        ` : ''}
                        <div class="message-content">${questionText}</div>
                    </div>
                `;
            } else if (isEvaluation) {
                innerHTML = `
                    <div class="chat-bubble chat-bubble-evaluation">
                        <div class="evaluation-title">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Interview Evaluation
                        </div>
                        <div class="message-content">${content}</div>
                    </div>
                `;
            } else {
                innerHTML = `
                    <div class="chat-bubble ${isUser ? 'chat-bubble-user' : 'chat-bubble-assistant'}">
                        <div class="message-content">${content}</div>
                    </div>
                `;
            }

            row.innerHTML = innerHTML;
            chatMessages.appendChild(row);
            scrollToBottom();
        }

        chatForm.addEventListener('submit', async function(e) {
            e.preventDefault();

            if (isLoading) return;

            const message = userInput.value.trim();
            if (!message) return;

            isLoading = true;
            userInput.disabled = true;

            addMessage(message, 'user');
            userInput.value = '';

            typingIndicator.classList.add('active');
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

                typingIndicator.classList.remove('active');

                if (data.success) {
                    addMessage(data.reply, 'officer');
                    updateProgress(data.step);
                    userInput.disabled = false;
                    userInput.value = '';
                    userInput.focus();

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
                typingIndicator.classList.remove('active');
                addMessage('Sorry, something went wrong: ' + error.message, 'officer');
                userInput.disabled = false;
            }

            isLoading = false;
        });

        userInput.focus();
        scrollToBottom();

        /* Voice input disabled for now - can be re-enabled later
        // Voice Input
        const voiceBtn = document.getElementById('voice-btn');
        const micIcon = document.getElementById('mic-icon');
        const micActiveIcon = document.getElementById('mic-active-icon');
        const voiceStatus = document.getElementById('voice-status');
        const speechPreview = document.getElementById('speech-preview');
        let recognition = null;
        let speechActive = false;

        function hideRecordingState() {
            micIcon.classList.remove('hidden');
            micActiveIcon.classList.add('hidden');
            voiceStatus.classList.remove('active');
            userInput.classList.remove('recording');
            voiceBtn.classList.remove('recording');
            speechPreview.classList.remove('active');
            speechPreview.textContent = '';
            speechActive = false;
        }

        if ('webkitSpeechRecognition' in window || 'SpeechRecognition' in window) {
            const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;

            recognition = new SpeechRecognition();
            recognition.interimResults = true;
            recognition.lang = 'en-US';

            recognition.onstart = function() {
                speechActive = true;
                micIcon.classList.add('hidden');
                micActiveIcon.classList.remove('hidden');
                voiceStatus.classList.add('active');
                userInput.classList.add('recording');
                voiceBtn.classList.add('recording');
                userInput.placeholder = 'Listening...';
                speechPreview.textContent = 'Listening...';
                speechPreview.classList.add('active');
            };

            recognition.onend = function() {
                hideRecordingState();
                userInput.placeholder = 'Type your answer here...';
            };

            recognition.onresult = function(event) {
                let interimTranscript = '';
                let finalTranscript = '';

                for (let i = event.resultIndex; i < event.results.length; ++i) {
                    if (event.results[i].isFinal) {
                        finalTranscript += event.results[i][0].transcript;
                    } else {
                        interimTranscript += event.results[i][0].transcript;
                    }
                }

                const text = finalTranscript + (interimTranscript ? ' ' + interimTranscript : '');
                if (text.trim()) {
                    userInput.value = text.trim();
                    speechPreview.textContent = 'Recognized: ' + text.trim();
                }
            };

            recognition.onerror = function(event) {
                const error = event.error;
                console.log('[Voice] Error:', error);
                hideRecordingState();
                userInput.placeholder = 'Type your answer here...';

                if (error === 'not-allowed') {
                    speechPreview.textContent = 'Microphone access denied. Please allow mic access.';
                } else if (error === 'no-speech') {
                    speechPreview.textContent = 'No speech detected. Try again.';
                } else if (error !== 'aborted') {
                    speechPreview.textContent = 'Voice error: ' + error;
                }
            };

            voiceBtn.addEventListener('click', function() {
                if (speechActive) return;
                try {
                    recognition.start();
                } catch (e) {
                    console.error('[Voice] Start error:', e);
                    speechPreview.textContent = 'Could not start voice recognition';
                }
            });
        } else {
            voiceBtn.style.display = 'none';
            speechPreview.textContent = 'Voice input requires Chrome or Edge browser';
        }
        */
    </script>
</body>
</html>
