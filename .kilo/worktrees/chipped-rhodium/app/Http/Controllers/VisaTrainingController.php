<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class VisaTrainingController extends Controller
{
    public function index(Request $request)
    {
        $sessionId = $request->session()->getId();
        $sessionKey = "visa_interview_{$sessionId}";

        $existingHistory = Session::get("{$sessionKey}_history", []);
        $isComplete = Session::get("{$sessionKey}_completed", false);
        $currentStep = Session::get("{$sessionKey}_step", 0);
        $totalSteps = 11;

        if (empty($existingHistory) && !$isComplete) {
            $greeting = $this->getGreetingMessage();
            $existingHistory = [
                ['role' => 'officer', 'content' => $greeting]
            ];
            Session::put("{$sessionKey}_history", $existingHistory);
            Session::put("{$sessionKey}_step", 1);
            $currentStep = 1;
        }

        return view('visa-training', [
            'conversationHistory' => $existingHistory,
            'currentStep' => $currentStep,
            'totalQuestions' => $totalSteps,
            'isCompleted' => $isComplete,
            'evaluation' => null,
            'interviewMode' => true,
        ]);
    }

    public function chat(Request $request)
    {
        $userMessage = $request->input('message');
        
        if (!$userMessage) {
            return response()->json(['success' => false, 'error' => 'Message is required']);
        }

        $message = trim($userMessage);
        $messageLower = strtolower($message);

        $sessionId = $request->session()->getId();
        $sessionKey = "visa_interview_{$sessionId}";

        // Check for restart
        if (in_array($messageLower, ['restart', 'start over', 'try again', 'begin new', 'new interview'])) {
            $greeting = $this->getGreetingMessage();
            Session::put("{$sessionKey}_history", [
                ['role' => 'officer', 'content' => $greeting]
            ]);
            Session::put("{$sessionKey}_step", 1);
            Session::forget("{$sessionKey}_profile");
            Session::forget("{$sessionKey}_completed");
            
            return response()->json([
                'success' => true,
                'reply' => $greeting,
                'step' => 1,
                'totalSteps' => 11,
                'completed' => false,
            ]);
        }

        // Check if already complete
        $isComplete = Session::get("{$sessionKey}_completed", false);
        if ($isComplete) {
            return response()->json([
                'success' => true,
                'reply' => "The interview has ended. Your evaluation is shown above. Type 'restart' to begin a new interview.",
                'completed' => true,
            ]);
        }

        $currentStep = Session::get("{$sessionKey}_step", 0);
        
        // Get existing history
        $conversationHistory = Session::get("{$sessionKey}_history", []);
        
        // Add user message
        $conversationHistory[] = ['role' => 'user', 'content' => $message];
        
        // Get current step data
        $interviewFlow = [
            ['type' => 'greeting', 'question' => null],
            ['type' => 'passport', 'key' => 'passport', 'question' => 'Please present your passport. What is your passport number and country of issue?'],
            ['type' => 'purpose', 'key' => 'travel_purpose', 'question' => 'What is the purpose of your visit?'],
            ['type' => 'duration', 'key' => 'duration', 'question' => 'How long do you intend to stay?'],
            ['type' => 'accommodation', 'key' => 'accommodation', 'question' => 'Where will you be staying during your visit?'],
            ['type' => 'funds', 'key' => 'funds', 'question' => 'How will you finance your trip?'],
            ['type' => 'ties', 'key' => 'ties', 'question' => 'What ties do you have to your home country that will ensure your return?'],
            ['type' => 'employment', 'key' => 'employment', 'question' => 'What is your current occupation or employment status?'],
            ['type' => 'family', 'key' => 'family', 'question' => 'Do you have any family members or relatives in the country you are visiting?'],
            ['type' => 'previous', 'key' => 'previous_travel', 'question' => 'Have you traveled to other countries before? If yes, which countries?'],
            ['type' => 'evaluate', 'question' => null],
        ];
        
        $currentStepData = $interviewFlow[$currentStep] ?? null;
        
        if (!$currentStepData) {
            return response()->json(['success' => false, 'error' => 'Invalid step']);
        }
        
        // Handle each step type
        if ($currentStepData['type'] === 'greeting') {
            // First message is already the greeting, just advance step
            Session::put("{$sessionKey}_step", 2);
            
            return response()->json([
                'success' => true,
                'reply' => "What is the purpose of your visit?",
                'step' => 2,
                'totalSteps' => 11,
                'completed' => false,
            ]);
        }
        
        // Handle passport question - after user provides passport, ask about travel purpose
        if ($currentStepData['type'] === 'passport') {
            $reply = "What is the purpose of your visit?";
            $conversationHistory[] = ['role' => 'officer', 'content' => $reply];
            Session::put("{$sessionKey}_history", $conversationHistory);
            Session::put("{$sessionKey}_step", 2);
            
            return response()->json([
                'success' => true,
                'reply' => $reply,
                'step' => 2,
                'totalSteps' => 11,
                'completed' => false,
            ]);
        }
        
        if ($currentStepData['type'] === 'evaluate') {
            $userProfile = Session::get("{$sessionKey}_profile", []);
            $reply = $this->generateEvaluation($userProfile);
            $conversationHistory[] = ['role' => 'officer', 'content' => $reply];
            Session::put("{$sessionKey}_history", $conversationHistory);
            Session::put("{$sessionKey}_completed", true);
            
            return response()->json([
                'success' => true,
                'reply' => $reply,
                'step' => 11,
                'totalSteps' => 11,
                'completed' => true,
            ]);
        }
        
        // Store user answer
        $userProfile = Session::get("{$sessionKey}_profile", []);
        $userProfile[$currentStepData['key']] = $message;
        Session::put("{$sessionKey}_profile", $userProfile);
        
        // Check for follow-up based on answer
        $followUp = $this->analyzeForFollowUp($currentStepData['key'], $message, $userProfile);
        
        if ($followUp) {
            $conversationHistory[] = ['role' => 'officer', 'content' => $followUp];
            Session::put("{$sessionKey}_history", $conversationHistory);
            
            return response()->json([
                'success' => true,
                'reply' => $followUp,
                'step' => $currentStep,
                'totalSteps' => 11,
                'completed' => false,
            ]);
        }
        
        // Move to next step
        $nextStep = $currentStep + 1;
        
        if ($nextStep >= count($interviewFlow) - 1) {
            // Evaluate
            $reply = $this->generateEvaluation($userProfile);
            $conversationHistory[] = ['role' => 'officer', 'content' => $reply];
            Session::put("{$sessionKey}_history", $conversationHistory);
            Session::put("{$sessionKey}_step", $nextStep);
            Session::put("{$sessionKey}_completed", true);
            
            return response()->json([
                'success' => true,
                'reply' => $reply,
                'step' => $nextStep,
                'totalSteps' => 11,
                'completed' => true,
            ]);
        }
        
        $nextStepData = $interviewFlow[$nextStep];
        $reply = $nextStepData['question'];
        
        $conversationHistory[] = ['role' => 'officer', 'content' => $reply];
        Session::put("{$sessionKey}_history", $conversationHistory);
        Session::put("{$sessionKey}_step", $nextStep);

        return response()->json([
            'success' => true,
            'reply' => $reply,
            'step' => $nextStep,
            'totalSteps' => 11,
            'completed' => false,
        ]);
    }

    protected function analyzeForFollowUp(string $currentKey, string $userAnswer, array $userProfile): ?string
    {
        $answerLower = strtolower($userAnswer);
        $answerLength = strlen(trim($userAnswer));

        // First check for very weak / bad answers
        $minimumLengths = [
            'passport' => 5,
            'travel_purpose' => 15,
            'duration' => 8,
            'accommodation' => 12,
            'funds' => 15,
            'ties' => 30,
            'employment' => 10,
            'family' => 5,
            'previous_travel' => 8,
        ];

        if ($answerLength < $minimumLengths[$currentKey]) {
            $explanations = [
                'travel_purpose' => "This answer is too vague. Visa officers need to understand exactly what you will be doing. You should specify activities, dates, and contacts.",
                'duration' => "Please be specific. You must explain the exact dates and reason for this length of stay.",
                'accommodation' => "Visa officers require verifiable accommodation details. You must provide a specific address and proof of booking.",
                'funds' => "This is a critical question. You must clearly demonstrate you have sufficient funds and explain exactly how you will pay for your trip.",
                'ties' => "This is the most important question. You must prove you will return home. Mention employment, property, family, studies or other commitments.",
                'employment' => "You must provide clear details about your current work or studies. Unclear employment status is a major red flag for visa officers."
            ];

            if (isset($explanations[$currentKey])) {
                return "Insufficient answer. " . $explanations[$currentKey] . " Please try again.";
            }
            
            return "Please provide more specific details for this answer.";
        }

        switch ($currentKey) {
            case 'travel_purpose':
                if (str_contains($answerLower, 'business')) {
                    return "What is the name of the company you will be visiting? What is the nature of your business?";
                }
                if (str_contains($answerLower, 'medical') || str_contains($answerLower, 'hospital') || str_contains($answerLower, 'treatment')) {
                    return "What medical facility will you be visiting? Do you have an appointment letter?";
                }
                break;
            case 'duration':
                if (str_contains($answerLower, 'year')) {
                    return "How will you support yourself for such an extended period?";
                }
                break;
            case 'accommodation':
                if (str_contains($answerLower, 'hotel')) {
                    return "What is the name and address of the accommodation? Do you have a reservation confirmation?";
                }
                if (str_contains($answerLower, 'friend') || str_contains($answerLower, 'relative')) {
                    return "What is the name and address of this person? What is their relationship to you?";
                }
                break;
            case 'funds':
                if (str_contains($answerLower, 'sponsor') || str_contains($answerLower, 'someone else')) {
                    return "What is the sponsor's name? What is their relationship to you? What is their occupation?";
                }
                break;
            case 'ties':
                if (strlen($userAnswer) < 30) {
                    return "This is critical. Please provide more details about your ties to your home country. Mention employment, property, family or studies.";
                }
                break;
            case 'employment':
                if (str_contains($answerLower, 'student') || str_contains($answerLower, 'university')) {
                    return "What university do you attend? What is your course of study?";
                }
                break;
            case 'family':
                if (str_contains($answerLower, 'yes')) {
                    return "What is their name? What is their immigration status? How are you related?";
                }
                break;
        }

        return null;
    }

    protected function generateEvaluation(array $userProfile): string
    {
        $score = 0;
        $strengths = [];
        $weaknesses = [];
        
        $criteria = [
            'passport' => ['weight' => 10, 'min_length' => 5, 'label' => 'Passport information'],
            'travel_purpose' => ['weight' => 20, 'min_length' => 15, 'label' => 'Travel purpose clarity'],
            'duration' => ['weight' => 15, 'min_length' => 8, 'label' => 'Stay duration explanation'],
            'accommodation' => ['weight' => 15, 'min_length' => 12, 'label' => 'Accommodation details'],
            'funds' => ['weight' => 20, 'min_length' => 15, 'label' => 'Financial proof explanation'],
            'ties' => ['weight' => 25, 'min_length' => 30, 'label' => 'Home country ties'],
            'employment' => ['weight' => 15, 'min_length' => 10, 'label' => 'Employment status'],
            'family' => ['weight' => 10, 'min_length' => 5, 'label' => 'Family status disclosure'],
            'previous_travel' => ['weight' => 10, 'min_length' => 8, 'label' => 'Travel history'],
        ];
        
        foreach ($criteria as $key => $config) {
            if (isset($userProfile[$key]) && strlen(trim($userProfile[$key])) >= $config['min_length']) {
                $score += $config['weight'];
                $strengths[] = "- " . $config['label'];
            } else {
                $weaknesses[] = "- " . $config['label'] . " (insufficient detail)";
            }
        }
        
        $score = min(100, max(0, $score));
        
        $decision = $score >= 70 ? 'Approved' : 'Refused';
        $riskLevel = $score >= 70 ? 'Low' : ($score >= 50 ? 'Medium' : 'High');
        
        $reply = "INTERVIEW RESULT\n";
        $reply .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
        $reply .= "Decision: {$decision}\n";
        $reply .= "Score: {$score}/100\n";
        $reply .= "Risk Level: {$riskLevel}\n\n";
        
        $reply .= "Key Strengths:\n";
        if (empty($strengths)) {
            $reply .= "- No sufficient strengths identified\n";
        } else {
            foreach ($strengths as $s) $reply .= "$s\n";
        }
        
        $reply .= "\nKey Weaknesses:\n";
        if (empty($weaknesses)) {
            $reply .= "- No weaknesses identified\n";
        } else {
            foreach ($weaknesses as $w) $reply .= "$w\n";
        }
        
        $reply .= "\nOfficer's Remarks:\n";
        if ($decision === 'Approved') {
            $reply .= "Your answers demonstrated sufficient preparation, clear travel plans and adequate ties to your home country. You understand the requirements for a successful visa interview.\n";
        } else {
            $reply .= "Your answers were insufficiently detailed. Visa officers require specific, verifiable information. Weak responses about ties to home country and financial situation are the most common reasons for visa refusal. Practice providing concrete examples and specific details for all questions.\n";
        }
        
        $reply .= "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        $reply .= "Type 'restart' to practice the interview again.\n";
        
        return $reply;
    }

    protected function getGreetingMessage(): string
    {
        return "Welcome. I am Visa Officer Charles. I will conduct your visa interview simulation. Answer honestly. I will evaluate your responses.\n\nPlease provide your passport.";
    }

    public function reset(Request $request)
    {
        $sessionId = $request->session()->getId();
        $sessionKey = "visa_interview_{$sessionId}";
        
        Session::forget("{$sessionKey}_history");
        Session::forget("{$sessionKey}_step");
        Session::forget("{$sessionKey}_profile");
        Session::forget("{$sessionKey}_context");
        Session::forget("{$sessionKey}_completed");
        
        return redirect()->route('visa-training');
    }
}