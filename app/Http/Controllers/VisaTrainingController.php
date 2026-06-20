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
            $step = 1;
        } elseif (empty($history) && ! $isComplete) {
            $greeting = $this->getGreetingMessage();
            $history = [
                ['role' => 'officer', 'content' => $greeting],
            ];
            Session::put("{$sessionKey}_history", $history);
            Session::put("{$sessionKey}_step", 1);
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

        // Detect visa type from first message if not set
        if (! $visaType) {
            $visaType = $this->detectVisaType($userMessage);
            Session::put("{$sessionKey}_visa_type", $visaType);
        }

        $step = Session::get("{$sessionKey}_step", 0);
        $history = Session::get("{$sessionKey}_history", []);

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
        return 'Welcome to Visa Interview Training. Please select your visa type to begin:';
    }

    protected function getSimpleGreeting(): string
    {
        return 'Select your country and visa type to begin the AI interview:';
    }

    private function detectVisaType(string $text): string
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

        return 'b1b2';
    }
}
