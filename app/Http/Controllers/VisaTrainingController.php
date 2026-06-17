<?php

namespace App\Http\Controllers;

use App\Services\VisaInterviewService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class VisaTrainingController extends Controller
{
    protected $visaInterviewService;

    protected $maxSteps = 15;

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
            \Log::info('Visa training greeting returned', ['greeting' => $greeting]);

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

        if ($isComplete) {
            return response()->json([
                'success' => true,
                'reply' => "The interview has ended. Type 'restart' to begin again.",
                'completed' => true,
            ]);
        }

        if (! $visaType) {
            $visaType = $this->detectVisaType($userMessage);
            Session::put("{$sessionKey}_visa_type", $visaType);
        }

        $step = Session::get("{$sessionKey}_step", 1);
        $history = Session::get("{$sessionKey}_history", []);

        $history[] = ['role' => 'user', 'content' => $userMessage];

        if ($step >= $this->maxSteps) {
            $reply = $this->visaInterviewService->getEvaluation($history, $visaType);
            $completed = true;
        } else {
            $reply = $this->visaInterviewService->getNextQuestion($history, $visaType);

            $completed = (
                stripos($reply, 'EVALUATION') !== false ||
                stripos($reply, 'Decision:') !== false
            );
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

        if ($request->isMethod('post') || $request->expectsJson()) {
            return new JsonResponse(['success' => true]);
        }

        return redirect()->route('visa-training');
    }

    protected function getGreetingMessage(): string
    {
        return 'Welcome to Visa Interview Training. Please select your visa type to begin:';
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
