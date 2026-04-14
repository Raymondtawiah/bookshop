<?php

namespace App\Http\Controllers;

use App\Services\OpenAiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class VisaTrainingController extends Controller
{
    protected OpenAiService $openAiService;

    protected array $trainingQuestions = [
        [
            'id' => 1,
            'question' => 'What is the first step when applying for a tourist visa?',
            'answer' => 'complete the application form',
            'hint' => 'Think about what you need to fill out first. The application process starts with paperwork.',
            'teaching' => 'The first step when applying for any visa is to complete the visa application form. This form collects your personal information, travel plans, and purpose of visit. You can usually find this form on the embassy or consulate website of the country you want to visit.',
        ],
        [
            'id' => 2,
            'question' => 'How long should your passport be valid for when applying for a visa?',
            'answer' => '6 months',
            'hint' => 'Most countries require your passport to be valid for a certain period beyond your planned return date.',
            'teaching' => 'Most countries require your passport to be valid for at least 6 months beyond your planned departure date. This is called the "six-month rule" and isstandard in many countries like the US, UK, and Schengen countries. Always check the specific requirements for your destination.',
        ],
        [
            'id' => 3,
            'question' => 'What document proves you have enough money for your trip?',
            'answer' => 'bank statement',
            'hint' => 'Financial documents show you can afford the trip. Banks provide this document.',
            'teaching' => 'A bank statement is used to prove you have sufficient funds for your trip. Most embassies require bank statements from the last 3 to 6 months. This shows you can support yourself during your stay and have financial ties to your home country.',
        ],
        [
            'id' => 4,
            'question' => 'What must you have for a return trip after your visa expires?',
            'answer' => 'return ticket',
            'hint' => 'Embassies want to know you will leave before your visa expires. This document shows your plans to return.',
            'teaching' => 'A return ticket (or onward ticket) proves you intend to leave the country before your visa expires. This helps show you are a genuine tourist and not planning to overstay. Some countries accept a flight reservation or itinerary as proof.',
        ],
        [
            'id' => 5,
            'question' => 'What health document might you need for certain countries?',
            'answer' => 'vaccination certificate',
            'hint' => 'Some countries require proof of vaccination for specific diseases before entry.',
            'teaching' => 'A vaccination certificate, especially for Yellow Fever, is required for entry into many African and South American countries. The Yellow Fever vaccination certificate must be issued at least 10 days before travel. Always check health requirements for your destination.',
        ],
    ];

    public function __construct(OpenAiService $openAiService)
    {
        $this->openAiService = $openAiService;
    }

    protected function clearOldSessions(): void
    {
        $lastActivity = Session::get('visa_training_last_activity');
        
        if ($lastActivity && now()->diffInHours($lastActivity) >= 12) {
            $sessionId = request()->session()->getId();
            Session::forget("visa_training_{$sessionId}_history");
            Session::forget("visa_training_{$sessionId}_step");
            Session::forget("visa_training_{$sessionId}_fails");
            Session::forget("visa_training_{$sessionId}_completed");
            Session::forget("visa_training_{$sessionId}_greeting_replied");
        }
        
        Session::put('visa_training_last_activity', now());
    }

    public function index(Request $request)
    {
        $this->clearOldSessions();
        
        $sessionId = $request->session()->getId();
        $currentStep = Session::get("visa_training_{$sessionId}_step", 0);
        $conversationHistory = Session::get("visa_training_{$sessionId}_history", []);
        $isCompleted = Session::get("visa_training_{$sessionId}_completed", false);

        if ($currentStep === 0 && empty($conversationHistory) && !$isCompleted) {
            $welcomeMessage = $this->getWelcomeMessage();
            $conversationHistory[] = [
                'role' => 'assistant',
                'content' => $welcomeMessage,
            ];
            Session::put("visa_training_{$sessionId}_history", $conversationHistory);
            Session::put("visa_training_{$sessionId}_step", 1);
            Session::put("visa_training_{$sessionId}_greeting_replied", false);
            $currentStep = 1;
        }

        return view('visa-training', [
            'questions' => $this->trainingQuestions,
            'currentStep' => $currentStep,
            'conversationHistory' => $conversationHistory,
            'isCompleted' => $isCompleted,
            'totalQuestions' => count($this->trainingQuestions),
        ]);
    }

    public function chat(Request $request)
    {
        $this->clearOldSessions();
        
        $userMessage = $request->input('message');
        $sessionId = $request->session()->getId();
        
        if (!$userMessage) {
            return response()->json(['success' => false, 'error' => 'Message is required']);
        }

        $conversationHistory = Session::get("visa_training_{$sessionId}_history", []);
        $currentStep = Session::get("visa_training_{$sessionId}_step", 1);
        $consecutiveFails = Session::get("visa_training_{$sessionId}_fails", 0);
        $isCompleted = Session::get("visa_training_{$sessionId}_completed", false);
        $greetingReplied = Session::get("visa_training_{$sessionId}_greeting_replied", false);

        // Check if this is a greeting
        // Allow greetings at any point until we've replied to a greeting
        if (!$greetingReplied) {
            $greetingResponses = $this->generateGreetingResponse($userMessage);
            
            if ($greetingResponses['is_greeting']) {
                $reply = $greetingResponses['reply'];
                $conversationHistory[] = [
                    'role' => 'user',
                    'content' => $userMessage,
                ];
                $conversationHistory[] = [
                    'role' => 'assistant',
                    'content' => $reply,
                ];
                Session::put("visa_training_{$sessionId}_history", $conversationHistory);
                Session::put("visa_training_{$sessionId}_greeting_replied", true);
                Session::put("visa_training_{$sessionId}_step", 1);
                
                return response()->json([
                    'success' => true,
                    'reply' => $reply,
                    'completed' => false,
                    'step' => 1,
                    'history' => $conversationHistory,
                ]);
            }
        }

        if ($isCompleted) {
            return response()->json([
                'success' => true,
                'reply' => "You have already completed all the training! Feel free to practice again if you'd like. Just let me know when you're ready to restart.",
                'completed' => true,
            ]);
        }

        // Check if this is a restart request
        if (in_array(strtolower(trim($userMessage)), ['restart', 'start over', 'try again', 'begin'])) {
            Session::forget("visa_training_{$sessionId}_history");
            Session::forget("visa_training_{$sessionId}_step");
            Session::forget("visa_training_{$sessionId}_fails");
            Session::forget("visa_training_{$sessionId}_completed");
            Session::forget("visa_training_{$sessionId}_greeting_replied");
            
            return response()->json([
                'success' => true,
                'reply' => "Let's start fresh! Welcome to your Visa Training session. I'm going to ask you some questions about visa applications, and you must answer them correctly to move to the next question. Don't worry - if you get it wrong, I'll teach you the right answer first. Let us begin!\n\nQuestion 1 of " . count($this->trainingQuestions) . ": " . $this->trainingQuestions[0]['question'],
                'step' => 1,
                'history' => [
                    ['role' => 'assistant', 'content' => "Let's start fresh! Welcome to your Visa Training session. I'm going to ask you some questions about visa applications, and you must answer them correctly to move to the next question. Don't worry - if you get it wrong, I'll teach you the right answer first. Let us begin!\n\nQuestion 1 of " . count($this->trainingQuestions) . ": " . $this->trainingQuestions[0]['question']],
                ],
            ]);
        }

        // Add user message to history
        $conversationHistory[] = [
            'role' => 'user',
            'content' => $userMessage,
        ];

        $currentQuestionIndex = $currentStep - 1;
        $currentQuestion = $this->trainingQuestions[$currentQuestionIndex] ?? null;

        if (!$currentQuestion) {
            return response()->json(['success' => false, 'error' => 'No question found']);
        }

        // Check the answer using AI
        $aiResponse = $this->evaluateAnswer($userMessage, $currentQuestion);

        if ($aiResponse['correct']) {
            $consecutiveFails = 0;
            Session::put("visa_training_{$sessionId}_fails", 0);
            
            // Search for additional info to enrich the answer
            $onlineInfo = $this->searchOnline($currentQuestion['question']);
            
            // Move to next question
            if ($currentStep >= count($this->trainingQuestions)) {
                // All completed!
                Session::put("visa_training_{$sessionId}_completed", true);
                
                $reply = "Congratulations! You have successfully completed all " . count($this->trainingQuestions) . " questions in the Visa Training!\n\n" . $this->getCongratulationsMessage();
                
                $conversationHistory[] = [
                    'role' => 'assistant',
                    'content' => $reply,
                ];
                Session::put("visa_training_{$sessionId}_history", $conversationHistory);
                
                return response()->json([
                    'success' => true,
                    'reply' => $reply,
                    'completed' => true,
                    'step' => $currentStep,
                    'history' => $conversationHistory,
                ]);
            } else {
                $nextStep = $currentStep + 1;
                $nextQuestion = $this->trainingQuestions[$nextStep - 1];
                
                $reply = "Correct! Well done! " . $currentQuestion['teaching'];
                
                if ($onlineInfo) {
                    $reply .= "\n\n📚 Additional Information:\n" . $onlineInfo;
                }
                
                $reply .= "\n\nNow let's move to question {$nextStep} of " . count($this->trainingQuestions) . ": " . $nextQuestion['question'];
                
                $conversationHistory[] = [
                    'role' => 'assistant',
                    'content' => $reply,
                ];
                Session::put("visa_training_{$sessionId}_history", $conversationHistory);
                Session::put("visa_training_{$sessionId}_step", $nextStep);
                
                return response()->json([
                    'success' => true,
                    'reply' => $reply,
                    'completed' => false,
                    'step' => $nextStep,
                    'history' => $conversationHistory,
                ]);
            }
        } else {
            $consecutiveFails++;
            Session::put("visa_training_{$sessionId}_fails", $consecutiveFails);
            
            // Search for additional info online
            $onlineInfo = $this->searchOnline($currentQuestion['question']);
            
            // AI thinks answer is wrong, so teach the user with online info
            $reply = "Not quite right. Let me teach you the correct answer:\n\n" . $currentQuestion['teaching'];
            
            if ($onlineInfo) {
                $reply .= "\n\n📚 Additional Information:\n" . $onlineInfo;
            }
            
            $reply .= "\n\nNow please try again: " . $currentQuestion['question'];
            
            $conversationHistory[] = [
                'role' => 'assistant',
                'content' => $reply,
            ];
            Session::put("visa_training_{$sessionId}_history", $conversationHistory);
            
            return response()->json([
                'success' => true,
                'reply' => $reply,
                'completed' => false,
                'step' => $currentStep,
                'teaching' => true,
                'history' => $conversationHistory,
            ]);
        }
    }

    protected function searchOnline(string $question): ?string
    {
        try {
            $apiKey = config('services.openai.api_key') ?: env('OPENAI_API_KEY', '');
            
            if (empty($apiKey)) {
                return null;
            }

            // Search the web for current visa information
            $searchPrompt = <<<PROMPT
Provide a brief, up-to-date answer (2-3 sentences max) about: {$question}

Focus on: requirements, documents needed, or processing times.
Keep it concise and factual.
RESPOND WITH ONLY THE ANSWER, NO FORMATTING:
PROMPT;

            $response = \Illuminate\Support\Facades\Http::timeout(15)->withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4o-mini',
                'messages' => [
                    ['role' => 'user', 'content' => $searchPrompt]
                ],
                'temperature' => 0.3,
                'max_tokens' => 200,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $content = trim($data['choices'][0]['message']['content'] ?? '');
                
                if ($content && strlen($content) > 20) {
                    return $content;
                }
            }
            
            return null;

        } catch (\Exception $e) {
            Log::error('Visa training online search error', ['error' => $e->getMessage()]);
            return null;
        }
    }

    protected function evaluateAnswer(string $userAnswer, array $question): array
    {
        $prompt = <<<PROMPT
You are a visa training assistant. Your role is to check if the user's answer is correct or close enough to the expected answer.

Question: {$question['question']}
Expected Answer Keywords: {$question['answer']}
Hint: {$question['hint']}

User's Answer: {$userAnswer}

INSTRUCTIONS:
1. Compare the user's answer to the expected answer keywords
2. The answer should contain the key concept(s) from the expected answer
3. Accept partial matches if the main concept is present
4. Be slightly flexible with wording but the meaning must be essentially correct
5. Common sense and relevant answers should be accepted

Respond with ONLY a JSON object in this exact format:
{"correct": true or false, "reason": "brief explanation"}

Examples of correct responses:
- If expected is "6 months" and user says "six months" or "at least 6 months" or "six months validity" -> correct: true
- If expected is "bank statement" and user says "bank reference" or "my bank documents" -> correct: true
- If expected is "return ticket" and user says "a ticket back home" or "flight home" -> correct: true

Examples of incorrect responses:
- If expected is "6 months" and user says "3 months" or "one year" -> correct: false
- If expected is "bank statement" and user says "passport" -> correct: false

RESPOND WITH ONLY THE JSON:
PROMPT;

        try {
            $apiKey = config('services.openai.api_key') ?: env('OPENAI_API_KEY', '');
            
            if (empty($apiKey)) {
                // Fallback to simple matching
                return $this->simpleEvaluate($userAnswer, $question);
            }

            $response = \Illuminate\Support\Facades\Http::timeout(10)->withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4o-mini',
                'messages' => [
                    ['role' => 'user', 'content' => $prompt]
                ],
                'temperature' => 0.1,
                'max_tokens' => 100,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $content = $data['choices'][0]['message']['content'] ?? '';
                
                // Parse JSON response
                $result = json_decode($content, true);
                if ($result && isset($result['correct'])) {
                    return $result;
                }
            }
            
            // Fallback to simple matching
            return $this->simpleEvaluate($userAnswer, $question);

        } catch (\Exception $e) {
            Log::error('Visa training evaluation error', ['error' => $e->getMessage()]);
            return $this->simpleEvaluate($userAnswer, $question);
        }
    }

    protected function simpleEvaluate(string $userAnswer, array $question): array
    {
        $userAnswerLower = strtolower(trim($userAnswer));
        $expectedLower = strtolower($question['answer']);
        
        // Check if user's answer contains the key concept
        $keywords = array_map('trim', explode(',', $expectedLower));
        
        foreach ($keywords as $keyword) {
            if (str_contains($userAnswerLower, $keyword)) {
                return ['correct' => true, 'reason' => 'Answer matches key concept'];
            }
        }
        
        return ['correct' => false, 'reason' => 'Answer does not match expected keywords'];
    }

    protected function getWelcomeMessage(): string
    {
        return "Welcome to Visa Training! I'm your AI visa coach, and I'm here to help you learn about visa applications through interactive questions.\n\nHere's how it works:\n- I will ask you " . count($this->trainingQuestions) . " questions about visa requirements\n- You answer each question\n- If you get it right, we move to the next question\n- If you get it wrong, I'll teach you the correct answer first, then you try again\n- Complete all questions to get your certificate!\n\nAre you ready? Let us begin!\n\nQuestion 1 of " . count($this->trainingQuestions) . ": " . $this->trainingQuestions[0]['question'];
    }

    protected function generateGreetingResponse(string $message): array
    {
        $messageLower = strtolower(trim($message));
        
        // Remove any extra punctuation for cleaner matching
        $messageClean = preg_replace('/[^\w\s]/', '', $messageLower);
        
        // Common greeting patterns - expanded
        $greetingPatterns = [
            'hi', 'hello', 'hey', 'hiya', 'good morning', 'good afternoon', 
            'good evening', 'greetings', 'whats up', 'wassup', 'howdy', 
            'sup', 'hi there', 'hello there', 'good day', 'morning',
            'evening', 'afternoon', 'hi bot', 'hello bot', 'hey there',
            'yo', 'hola', 'namaste', 'shalom', 'bonjour', 'what\'s up'
        ];
        
        $isGreeting = false;
        
        // Direct match
        foreach ($greetingPatterns as $pattern) {
            if ($messageClean === $pattern || str_contains($messageClean, $pattern)) {
                $isGreeting = true;
                break;
            }
        }
        
        // Also check if message is very short (likely a greeting)
        if (!$isGreeting && strlen($messageClean) <= 15 && strlen($messageClean) > 0) {
            $isGreeting = true;
        }
        
        if (!$isGreeting) {
            return ['is_greeting' => false, 'reply' => ''];
        }
        
        // Generate personalized greeting responses
        $greetings = [
            "Hello there! Welcome to Visa Training! I'm excited to help you learn about visa applications.\n\nI'll guide you through some important questions about visa requirements. Let's get started!\n\nQuestion 1 of " . count($this->trainingQuestions) . ": " . $this->trainingQuestions[0]['question'],
            "Hi! Welcome aboard! I'm your Visa Training assistant, and I'll help you prepare for your visa application.\n\nWe'll go through some key questions together. Don't worry if you don't know the answers - I'll teach you!\n\nLet's begin!\n\nQuestion 1 of " . count($this->trainingQuestions) . ": " . $this->trainingQuestions[0]['question'],
            "Hey! Great to see you! Let's prepare you for your visa application.\n\nI'll ask you " . count($this->trainingQuestions) . " questions, and we'll learn together. If you get any wrong, I'll explain the correct answer.\n\nReady?\n\nQuestion 1 of " . count($this->trainingQuestions) . ": " . $this->trainingQuestions[0]['question'],
            "Hello! Welcome to Visa Training!\n\nI'm here to help you understand the visa application process better. We'll work through some questions together - think of it as a friendly quiz!\n\nLet's start!\n\nQuestion 1 of " . count($this->trainingQuestions) . ": " . $this->trainingQuestions[0]['question'],
            "Hi there! Welcome to your Visa Training session!\n\nI'm going to ask you some questions about visa requirements. If you don't know an answer, that's okay - I'll teach you!\n\nLet's begin with Question 1 of " . count($this->trainingQuestions) . ": " . $this->trainingQuestions[0]['question'],
        ];
        
        $reply = $greetings[array_rand($greetings)];
        
        return [
            'is_greeting' => true,
            'reply' => $reply
        ];
    }

    protected function getCongratulationsMessage(): string
    {
        return "🎉 CONGRATULATIONS! 🎉\n\nYou have successfully completed the Visa Training Program!\n\nYou demonstrated knowledge of:\n- Visa application procedures\n- Passport validity requirements\n- Financial documentation\n- Travel itinerary requirements\n- Health certificates\n\nYou are now better prepared for your visa application journey. Remember these key points when applying for your visa, and you will have a smoother experience!\n\nTo practice again, just type 'restart' or 'start over'. Good luck with your visa applications!";
    }

    public function reset(Request $request)
    {
        $sessionId = $request->session()->getId();
        Session::forget("visa_training_{$sessionId}_history");
        Session::forget("visa_training_{$sessionId}_step");
        Session::forget("visa_training_{$sessionId}_fails");
        Session::forget("visa_training_{$sessionId}_completed");
        Session::forget("visa_training_{$sessionId}_greeting_replied");
        
        return redirect()->route('visa-training');
    }
}