<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Services\VisaInterviewService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class VisaTrainingController extends Controller
{
    protected $visaInterviewService;

    protected $maxSteps = 12;

    private array $abusiveWords = [
        'fuck', 'fucking', 'shit', 'damn', 'bitch', 'asshole', 'bastard',
        'crap', 'dick', 'piss', 'slut', 'whore', 'idiot', 'stupid', 'moron',
        'dumb', 'hate', 'kill', 'die', 'nigger', 'nigga', 'fag', ' retard',
        'sap', 'yoo', 'sia', 'apuu', 'kwasia',
    ];

    public function __construct(VisaInterviewService $visaInterviewService)
    {
        $this->visaInterviewService = $visaInterviewService;
    }

    public function index(Request $request)
    {
        $sessionId = $request->session()->getId();
        $sessionKey = "visa_interview_{$sessionId}";

        // Check if this is a visa-training or visa-interview request
        $isSimpleInterview = $request->route()->getName() === 'visa-interview';

        $history = Session::get("{$sessionKey}_history", []);
        $isComplete = Session::get("{$sessionKey}_completed", false);
        $step = Session::get("{$sessionKey}_step", 0);
        $country = Session::get("{$sessionKey}_country", 'USA');
        $visaType = Session::get("{$sessionKey}_visa_type");

        if ($isSimpleInterview && empty($history) && ! $isComplete) {
            $greeting = $this->getSimpleGreeting();
            $history = [
                ['role' => 'officer', 'content' => $greeting],
            ];
            Session::put("{$sessionKey}_history", $history);
            Session::put("{$sessionKey}_step", 1);
            Session::put("{$sessionKey}_stage", 'select_type');
            $step = 1;
        } elseif (empty($history) && ! $isComplete) {
            $greeting = $this->getGreetingMessage();
            $history = [
                ['role' => 'officer', 'content' => $greeting],
            ];
            Session::put("{$sessionKey}_history", $history);
            Session::put("{$sessionKey}_step", 1);
            Session::put("{$sessionKey}_stage", 'select_type');
            $step = 1;
        }

        return view($isSimpleInterview ? 'visa-interview' : 'visa-training', [
            'conversationHistory' => $history,
            'currentStep' => $step,
            'totalQuestions' => $this->maxSteps,
            'isCompleted' => $isComplete,
            'evaluation' => null,
            'interviewMode' => true,
            'country' => $country,
            'visaType' => $visaType,
        ]);
    }

    public function chat(Request $request)
    {
        $userMessage = trim($request->input('message'));

        if (! $userMessage) {
            return response()->json([
                'success' => false,
                'error' => 'Message is required',
            ]);
        }

        $sessionId = $request->session()->getId();
        $sessionKey = "visa_interview_{$sessionId}";

        $history = Session::get("{$sessionKey}_history", []);
        $isComplete = Session::get("{$sessionKey}_completed", false);
        $visaType = Session::get("{$sessionKey}_visa_type");
        $stage = Session::get("{$sessionKey}_stage", 'select_type');

        if (! $visaType) {
            $visaType = $this->detectVisaType($userMessage);

            if (! $visaType) {
                $reply = 'I can only train F1 Student or B1/B2 Business/Tourist interviews. Please enter the correct visa type you want to practice.';
                $history[] = ['role' => 'officer', 'content' => $reply];
                Session::put("{$sessionKey}_history", $history);
                Session::put("{$sessionKey}_step", 2);
                Session::put("{$sessionKey}_stage", 'select_type');

                return response()->json([
                    'success' => true,
                    'reply' => $reply,
                    'step' => 2,
                    'totalSteps' => $this->maxSteps,
                    'completed' => false,
                ]);
            }

            $reply = 'Great. I will train you for the '.($visaType === 'f1' ? 'F1 Student Visa' : 'B1/B2 Business/Tourist Visa').' interview. TIP: Greeting shows politeness and it calms your nerves. Please greet the officer to begin.';
            $history[] = ['role' => 'officer', 'content' => $reply];
            Session::put("{$sessionKey}_history", $history);
            Session::put("{$sessionKey}_visa_type", $visaType);
            Session::put("{$sessionKey}_stage", 'awaiting_greeting');
            Session::put("{$sessionKey}_step", 2);

            return response()->json([
                'success' => true,
                'reply' => $reply,
                'step' => 2,
                'totalSteps' => $this->maxSteps,
                'completed' => false,
            ]);
        }

        $step = Session::get("{$sessionKey}_step", 0);
        $history = Session::get("{$sessionKey}_history", []);

        if ($stage === 'awaiting_greeting') {
            if (! $this->isGreetingMessage($userMessage)) {
                $reply = 'Please greet the officer first. TIP: Greeting shows politeness and it calms your nerves.';
                $history[] = ['role' => 'user', 'content' => $userMessage];
                $history[] = ['role' => 'officer', 'content' => $reply];
                Session::put("{$sessionKey}_history", $history);
                Session::put("{$sessionKey}_step", $step + 1);

                return response()->json([
                    'success' => true,
                    'reply' => $reply,
                    'step' => $step + 1,
                    'totalSteps' => $this->maxSteps,
                    'completed' => false,
                ]);
            }

            $history[] = ['role' => 'user', 'content' => $userMessage];
            $reply = 'Hello, nice to meet you. Please pass me your passport'.($visaType === 'f1' ? ' and your I-20.' : '.').' TIP: Say "Here you go" or "This is it" to show confidence.';
            $history[] = ['role' => 'officer', 'content' => $reply];
            Session::put("{$sessionKey}_history", $history);
            Session::put("{$sessionKey}_stage", 'awaiting_passport');
            Session::put("{$sessionKey}_step", $step + 1);

            return response()->json([
                'success' => true,
                'reply' => $reply,
                'step' => $step + 1,
                'totalSteps' => $this->maxSteps,
                'completed' => false,
            ]);
        }

        if ($stage === 'awaiting_passport') {
            $history[] = ['role' => 'user', 'content' => $userMessage];
            $reply = $this->visaInterviewService->getNextQuestion($history, $visaType);
            $history[] = ['role' => 'officer', 'content' => $reply];
            Session::put("{$sessionKey}_history", $history);
            Session::put("{$sessionKey}_stage", 'questions');
            Session::put("{$sessionKey}_step", $step + 1);

            return response()->json([
                'success' => true,
                'reply' => $reply,
                'step' => $step + 1,
                'totalSteps' => $this->maxSteps,
                'completed' => false,
            ]);
        }

        $history[] = ['role' => 'user', 'content' => $userMessage];

        $stepNow = max(0, (int) Session::get("{$sessionKey}_step", 0));

        if ($stepNow >= $this->maxSteps - 1) {
            $reply = $this->visaInterviewService->getEvaluation($history, $visaType);
            $completed = true;
        } else {
            $reply = $this->visaInterviewService->getNextQuestion($history, $visaType);
            $completed = false;
        }

        if (! is_string($reply) || trim($reply) === '') {
            $reply = $this->visaInterviewService->getFallbackResponse();
            $completed = $stepNow >= $this->maxSteps - 1;
        }

        $history[] = ['role' => 'officer', 'content' => $reply];

        Session::put("{$sessionKey}_history", $history);
        Session::put("{$sessionKey}_step", $step + 1);

        if ($completed) {
            Session::put("{$sessionKey}_completed", true);
        }

        return response()->json([
            'success' => true,
            'reply' => $reply,
            'step' => $step + 1,
            'totalSteps' => $this->maxSteps,
            'completed' => $completed,
        ]);
    }

    public function reset(Request $request)
    {
        $sessionId = $request->session()->getId();
        $sessionKey = "visa_interview_{$sessionId}";

        Session::forget("{$sessionKey}_history");
        Session::forget("{$sessionKey}_step");
        Session::forget("{$sessionKey}_completed");
        Session::forget("{$sessionKey}_visa_type");

        $referrer = $request->headers->get('referer', '');
        $redirectRoute = strpos($referrer, 'visa-interview') !== false ? 'visa-interview' : 'visa-training';

        if ($request->isMethod('post') || $request->expectsJson()) {
            return new JsonResponse(['success' => true]);
        }

        return redirect()->route($redirectRoute);
    }

    public function choosePlan(Request $request)
    {
        $plans = [
            'session' => ['name' => 'AI Video Interview - Single Session', 'price' => 9.99],
            'monthly' => ['name' => 'AI Video Interview - Monthly Plan', 'price' => 19.99],
            'pro' => ['name' => 'AI Video Interview - Pro Plan', 'price' => 49.99],
        ];

        $plan = $request->validate([
            'plan' => ['required', 'string', 'in:session,monthly,pro'],
        ]);

        $selected = $plans[$plan['plan']];

        Cart::create([
            'user_id' => Auth::id(),
            'book_id' => null,
            'product_name' => $selected['name'],
            'unit_price' => $selected['price'],
            'quantity' => 1,
        ]);

        return redirect()->route('checkout')->with('success', $selected['name'].' added to cart.');
    }

    protected function getGreetingMessage(): string
    {
        return 'Hi. Welcome to Visa Interview Training. Choose the visa type you want to practice: F1 Student or B1/B2 Business/Tourist.';
    }

    protected function getSimpleGreeting(): string
    {
        return 'Hi. Choose the visa type you want to practice: F1 Student or B1/B2 Business/Tourist.';
    }

    private function detectVisaType(string $text): ?string
    {
        $lower = strtolower($text);

        if (
            stripos($lower, 'f1') !== false ||
            stripos($lower, 'student visa') !== false ||
            stripos($lower, 'student') !== false
        ) {
            return 'f1';
        }

        if (
            stripos($lower, 'b1') !== false ||
            stripos($lower, 'b2') !== false ||
            stripos($lower, 'visitor') !== false ||
            stripos($lower, 'business') !== false ||
            stripos($lower, 'tourist') !== false
        ) {
            return 'b1b2';
        }

        return null;
    }

    private function isGreetingMessage(string $text): bool
    {
        $lower = strtolower($text);

        return preg_match('/\b(hi|hello|hey|good morning|good afternoon|good evening|greetings)\b/', $lower) === 1;
    }
}
