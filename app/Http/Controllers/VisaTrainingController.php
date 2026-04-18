<?php

namespace App\Http\Controllers;

use App\Services\VisaInterviewService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class VisaTrainingController extends Controller
{
    protected $visaInterviewService;

    protected $maxSteps = 15; // Total officer messages for progress bar

    public function __construct(VisaInterviewService $visaInterviewService)
    {
        $this->visaInterviewService = $visaInterviewService;
    }

    public function index(Request $request)
    {
        $sessionId = $request->session()->getId();
        $sessionKey = "visa_interview_{$sessionId}";

        $history = Session::get("{$sessionKey}_history", []);
        $isComplete = Session::get("{$sessionKey}_completed", false);
        $step = Session::get("{$sessionKey}_step", 0);

        if (empty($history) && ! $isComplete) {
            $greeting = $this->getGreetingMessage();
            $history = [
                ['role' => 'officer', 'content' => $greeting],
            ];
            Session::put("{$sessionKey}_history", $history);
            Session::put("{$sessionKey}_step", 1);
            $step = 1;
        }

        return view('visa-training', [
            'conversationHistory' => $history,
            'currentStep' => $step,
            'totalQuestions' => $this->maxSteps,
            'isCompleted' => $isComplete,
            'evaluation' => null,
            'interviewMode' => true,
        ]);
    }

    public function chat(Request $request)
    {
        $userMessage = trim($request->input('message'));
        if (! $userMessage) {
            return response()->json(['success' => false, 'error' => 'Message is required']);
        }

        $sessionId = $request->session()->getId();
        $sessionKey = "visa_interview_{$sessionId}";

        // Handle restart command
        if (in_array(strtolower($userMessage), ['restart', 'start over', 'try again', 'begin new', 'new interview'])) {
            $greeting = $this->getGreetingMessage();
            $newHistory = [['role' => 'officer', 'content' => $greeting]];
            Session::put("{$sessionKey}_history", $newHistory);
            Session::put("{$sessionKey}_step", 1);
            Session::forget("{$sessionKey}_completed");

            return response()->json([
                'success' => true,
                'reply' => $greeting,
                'step' => 1,
                'totalSteps' => $this->maxSteps,
                'completed' => false,
            ]);
        }

        // If already completed
        $isComplete = Session::get("{$sessionKey}_completed", false);
        if ($isComplete) {
            return response()->json([
                'success' => true,
                'reply' => "The interview has ended. Your evaluation is shown above. Type 'restart' to begin a new interview.",
                'completed' => true,
            ]);
        }

        $step = Session::get("{$sessionKey}_step", 1);
        $history = Session::get("{$sessionKey}_history", []);

        // Append user message
        $history[] = ['role' => 'user', 'content' => $userMessage];

        // Enforce max steps to prevent infinite; force evaluation after maxSteps
        if ($step >= $this->maxSteps) {
            $reply = $this->visaInterviewService->getEvaluation($history);
            $completed = true;
        } else {
            $reply = $this->visaInterviewService->getNextQuestion($history);
            // Check if AI already included evaluation
            $completed = (stripos($reply, 'EVALUATION') !== false || stripos($reply, 'Decision:') !== false);
        }

        // Append officer response
        $history[] = ['role' => 'officer', 'content' => $reply];
        Session::put("{$sessionKey}_history", $history);

        $newStep = $step + 1;
        Session::put("{$sessionKey}_step", $newStep);
        if ($completed) {
            Session::put("{$sessionKey}_completed", true);
        }

        return response()->json([
            'success' => true,
            'reply' => $reply,
            'step' => $newStep,
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

        return redirect()->route('visa-training');
    }

    protected function getGreetingMessage(): string
    {
        return "Welcome. I am Visa Officer Charles. I will conduct your visa interview simulation. Answer honestly. I will evaluate your responses.\n\nPlease provide your passport details: number, country of issue, and expiry date.";
    }
}
