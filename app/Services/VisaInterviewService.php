<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class VisaInterviewService
{
    protected string $apiKey;

    private array $b1b2Topics = [
        'purpose_of_visit',
        'travel_details',
        'family_friends_us',
        'employment',
        'finances',
        'home_country_ties',
        'travel_history',
        'stress_questions',
    ];

    private array $f1Topics = [
        'school_selection',
        'academic_program',
        'academic_background',
        'finances',
        'career_plans',
        'home_country_ties',
        'university_knowledge',
        'stress_questions',
    ];

    public function __construct()
    {
        $this->apiKey = config('services.gemini.api_key') ?? env('GEMINI_API_KEY', '');
    }

    public function getNextQuestion(array $conversationHistory, ?string $visaType = null): string
    {
        $visaType = $visaType ?? $this->detectVisaType($conversationHistory);
        $topics = $visaType === 'f1' ? $this->f1Topics : $this->b1b2Topics;
        $topicList = implode(', ', $topics);
        $visaName = $visaType === 'f1' ? 'F1 Student' : 'B1/B2 Visitor';

        $systemPrompt = "You are Visa Officer Charles, a strict but helpful visa officer conducting a {$visaName} visa interview simulation.

CRITICAL RULES:
1. Output ONLY one message per turn. Never output multiple messages.
2. Ask only ONE question per message.
3. Always start with coaching assessment of the applicant's last answer:
   - TIP: [brief coaching 1-2 sentences], then ask exactly one follow-up question on the same topic.
   - Good. [then ask one new question from the next topic in order].
4. Never skip coaching. Every turn MUST begin with either 'TIP:' or 'Good.'.
5. Follow this exact topic order: {$topicList}.
6. Never greet, never acknowledge, never add commentary beyond coaching and one question.

Format examples:
TIP: Your sponsor's income is unclear. A stronger answer would state the exact amount and source. What is your sponsor's annual income?
Good. What is your passport number and expiry date?";

        return $this->callGemini($systemPrompt, $conversationHistory, __FUNCTION__);
    }

    public function getEvaluation(array $conversationHistory, ?string $visaType = null): string
    {
        $visaType = $visaType ?? $this->detectVisaType($conversationHistory);
        $visaName = $visaType === 'f1' ? 'F1 Student' : 'B1/B2 Visitor';

        $weighting = $visaType === 'f1'
            ? "F1 Student Visa weights:
   - Academic Intent: 30%
   - Financial Strength: 25%
   - School Knowledge: 15%
   - Career Goals: 15%
   - Home Country Ties: 15%"
            : "B1/B2 Visitor Visa weights:
   - Purpose of Visit: 30%
   - Financial Ability: 20%
   - Employment Stability: 20%
   - Home Country Ties: 20%
   - Consistency: 10%";

        $systemPrompt = "You are Visa Officer Charles. Review the following {$visaName} visa interview transcript and provide a detailed evaluation.

EVALUATION CRITERIA:
{$weighting}

Output ONLY the evaluation block, starting with 'EVALUATION BLOCK:' and strictly following this format:

EVALUATION BLOCK:
Decision: [Approved or Refused]
Score: [0-100]
Risk Level: [Low, Medium, or High]
Strengths: [comma-separated list]
Weaknesses: [comma-separated list]
Remarks: [detailed comments explaining the decision based on the weighted criteria]

Score guidelines:
- 90-100: Excellent - clear answers, strong ties, high credibility
- 70-89: Good - minor gaps but overall satisfactory
- 50-69: Fair - several weaknesses, moderate risk
- 0-49: Poor - insufficient answers, high risk

Do not include any additional text before or after the evaluation block.";

        $response = $this->callGemini($systemPrompt, $conversationHistory);

        if (stripos($response, 'EVALUATION BLOCK:') === false) {
            $response = "EVALUATION BLOCK:\n" . $response;
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

    private function detectVisaType(array $conversationHistory): string
    {
        $fullText = '';
        foreach ($conversationHistory as $msg) {
            $fullText .= ' ' . ($msg['content'] ?? '');
        }
        $lower = strtolower($fullText);

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
