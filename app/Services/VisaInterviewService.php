<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class VisaInterviewService
{
    protected string $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.openai.api_key') ?? env('OPENAI_API_KEY', '');
    }

    public function getNextQuestion(array $conversationHistory): string
    {
        $systemPrompt = "You are Visa Officer Charles, a strict and professional visa officer conducting an interview.

CORE RULES - YOU MUST FOLLOW:
1. Ask only ONE question at a time. Never ask multiple questions in one message.
2. Base each question strictly on the applicant's previous answers. Be adaptive - if an answer is vague, weak, or suspicious, immediately ask a clarifying follow-up question before moving to another topic.
3. Ensure you cover all these mandatory topics (order is flexible):
   - Passport details (number, country of issue, expiry date)
   - Purpose of travel (detailed itinerary, activities, contacts)
   - Duration of stay (exact dates)
   - Accommodation (address, proof of booking)
   - Financial situation (funds, sponsorship, income source)
   - Ties to home country (employment, property, family, studies)
   - Employment status (job title, employer, income)
   - Family in destination country (names, immigration status)
   - Previous travel history (countries visited, visa compliance)
4. Do NOT give hints, advice, or explanations.
5. Do NOT discuss evaluation or decisions during the interview.
6. Respond with ONLY the question text. No greetings, no acknowledgments, no extra commentary.

If a topic has been sufficiently answered based on previous answers, move to an uncovered topic. Use follow-up questions when an answer is insufficient. Continue until all mandatory topics are covered.";

        return $this->callOpenAI($systemPrompt, $conversationHistory);
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

        $response = $this->callOpenAI($systemPrompt, $conversationHistory);

        // Ensure the response contains the marker for frontend detection
        if (stripos($response, 'EVALUATION BLOCK:') === false) {
            $response = "EVALUATION BLOCK:\n".$response;
        }

        return $response;
    }

    private function callOpenAI(string $systemPrompt, array $conversationHistory): string
    {
        if (empty($this->apiKey)) {
            Log::warning('OpenAI API key missing');

            return $this->getFallbackResponse();
        }

        $messages = [
            ['role' => 'system', 'content' => $systemPrompt],
        ];

        foreach ($conversationHistory as $msg) {
            $role = $msg['role'] === 'officer' ? 'assistant' : 'user';
            $messages[] = [
                'role' => $role,
                'content' => $msg['content'],
            ];
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer '.$this->apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(15)->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4o-mini',
                'messages' => $messages,
                'temperature' => 0.4,
                'max_tokens' => 500,
            ]);

            if ($response->successful()) {
                return trim($response->json('choices.0.message.content'));
            }

            Log::error('OpenAI API failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return $this->getFallbackResponse();

        } catch (\Exception $e) {
            Log::error('OpenAI Exception', [
                'message' => $e->getMessage(),
            ]);

            return $this->getFallbackResponse();
        }
    }

    protected function getFallbackResponse(): string
    {
        return "I'm sorry, I'm experiencing technical difficulties. Please try again or restart the interview.";
    }
}
