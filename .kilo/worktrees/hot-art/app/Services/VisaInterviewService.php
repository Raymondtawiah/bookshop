<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class VisaInterviewService
{
    protected string $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.gemini.api_key') ?? env('GEMINI_API_KEY', '');
    }

    public function getNextQuestion(array $conversationHistory): string
    {
        $systemPrompt = "You are Visa Officer Charles, a strict but helpful visa officer.

CRITICAL RULES:
1. Output ONLY one message per turn. Never output multiple messages.
2. Always start with a coaching assessment of the applicant's last answer.
3. If the answer is vague, incomplete, or raises a red flag, start with: TIP: [brief coaching 1-2 sentences]. Then ask exactly one follow-up question on the same topic.
4. If the answer is strong and complete, say: Good. [next one question].
5. Never skip coaching. Every turn MUST begin with either 'TIP:' or 'Good.'.
6. Ask only ONE question per message.
7. Cover these topics in order: passport, purpose of travel, duration, accommodation, finances, home ties, employment, family in destination country, travel history.
8. Never greet, never acknowledge, never add commentary beyond coaching and one question.

Format examples:
TIP: Your sponsor's income is unclear. A stronger answer would state the exact amount and source. What is your sponsor's annual income?
Good. What is your passport number and expiry date?";

        return $this->callGemini($systemPrompt, $conversationHistory, __FUNCTION__);
    }

    public function getEvaluation(array $conversationHistory): string
    {
        $systemPrompt = "You are Visa Officer Charles. Review the following visa interview transcript and provide a detailed evaluation.

Output ONLY the evaluation block, starting with 'EVALUATION BLOCK:' and strictly following this format:

EVALUATION BLOCK:
Decision: [Approved or Refused]
Score: [0-100]
Risk Level: [Low, Medium, or High]
Strengths: [comma-separated list]
Weaknesses: [comma-separated list]
Remarks: [detailed comments explaining the decision]

Score guidelines:
- 90-100: Excellent - clear answers, strong ties, high credibility
- 70-89: Good - minor gaps but overall satisfactory
- 50-69: Fair - several weaknesses, moderate risk
- 0-49: Poor - insufficient answers, high risk

Do not include any additional text before or after the evaluation block.";

        $response = $this->callGemini($systemPrompt, $conversationHistory);

        if (stripos($response, 'EVALUATION BLOCK:') === false) {
            $response = "EVALUATION BLOCK:\n".$response;
        }

        return $response;
    }

    private function callGemini(string $systemPrompt, array $conversationHistory, string $functionName = 'unknown'): string
    {
        if (empty($this->apiKey)) {
            Log::warning('Gemini API key missing', ['function' => $functionName]);

            return $this->getFallbackResponse();
        }

        $contents = [];

        foreach ($conversationHistory as $msg) {
            $role = $msg['role'] === 'officer' ? 'model' : 'user';
            $contents[] = [
                'role' => $role,
                'parts' => [['text' => $msg['content']]],
            ];
        }

        $baseUrl = config('services.gemini.base_url', 'https://generativelanguage.googleapis.com/v1beta');
        $model = config('services.gemini.model', 'gemini-2.0-flash');

        try {
            $response = Http::timeout(15)->post("{$baseUrl}/models/{$model}:generateContent?key={$this->apiKey}", [
                'system_instruction' => [
                    'parts' => [['text' => $systemPrompt]],
                ],
                'contents' => $contents,
                'generationConfig' => [
                    'temperature' => 0.4,
                    'maxOutputTokens' => 800,
                ],
            ]);

            if ($response->successful()) {
                $candidates = $response->json('candidates');
                if (isset($candidates[0]['content']['parts'][0]['text'])) {
                    $content = trim($candidates[0]['content']['parts'][0]['text']);
                    return $content;
                }

                Log::error('Gemini response missing content', [
                    'function' => $functionName,
                    'response' => $response->json(),
                ]);
            } else {
                Log::error('Gemini API failed', [
                    'function' => $functionName,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Gemini Exception', [
                'function' => $functionName,
                'message' => $e->getMessage(),
            ]);
        }

        return $this->getFallbackResponse();
    }

    protected function getFallbackResponse(): string
    {
        return "I'm sorry, I'm experiencing technical difficulties. Please try again or restart the interview.";
    }
}
