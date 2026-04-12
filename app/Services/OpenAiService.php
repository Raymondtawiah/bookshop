<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OpenAiService
{
    protected string $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.openai.api_key') ?: env('OPENAI_API_KEY', '');
        Log::info('OpenAI key loaded', ['key_prefix' => substr($this->apiKey, 0, 20)]);
    }

    public function generateResponse(string $userMessage): ?string
    {
        if (empty($this->apiKey)) {
            Log::warning('OpenAI API key not configured');
            return null;
        }

        try {
            $prompt = <<<PROMPT
You are a professional TRAVEL and VISA consultant with years of experience.

YOUR SPECIALTIES:
- Visa requirements (tourist, student, work, business)
- Flight routes and booking
- Passport applications
- Embassy processes
- Travel documents
- Immigration questions
- Study abroad

IMPORTANT RULES:
1. Answer IMMEDIATELY - don't ask follow-up questions
2. Give complete information with details
3. Include costs in USD where possible
4. Mention processing times
5. List documents needed

If question is NOT about travel/visa/immigration:
- Briefly acknowledge
- Ask how you can help with travel or visa

QUESTION: {$userMessage}

ANSWER:
PROMPT;

            $response = Http::timeout(60)->withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4o-mini',
                'messages' => [
                    ['role' => 'user', 'content' => $prompt]
                ],
                'temperature' => 0.3,
                'max_tokens' => 500,
            ]);

            Log::info('OpenAI response status', ['status' => $response->status(), 'body' => $response->body()]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['choices'][0]['message']['content'] ?? null;
            }

            Log::error('OpenAI API error', ['status' => $response->status(), 'body' => $response->body()]);
            return null;

        } catch (\Exception $e) {
            Log::error('OpenAI exception', ['error' => $e->getMessage()]);
            return null;
        }
    }
}