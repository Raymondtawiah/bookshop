<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OpenAiService
{
    protected string $apiKey;

    protected array $fallbackQuestions = [
        [
            'question' => 'What are the visa requirements for Ghana?',
            'answer' => "🇬🇭 GHANA VISA REQUIREMENTS:\n\n- Valid passport (6+ months)\n- Completed application form\n- 2 passport photos\n- Yellow fever certificate\n- Proof of accommodation\n- Return ticket\n- Bank statement (3 months)\n\nFEES: $60 - $200\nPROCESSING: 5 - 14 business days",
        ],
        [
            'question' => 'How do I apply for a US visa?',
            'answer' => "🇺🇸 US VISA PROCESS:\n\n1. Fill DS-160 form online\n2. Pay visa fee ($185)\n3. Book interview\n4. Attend interview with documents\n\nPROCESSING: 2 - 8 weeks",
        ],
        [
            'question' => 'Can I work on a tourist visa?',
            'answer' => "❌ No, you cannot work on a tourist visa in most countries.\n\nYou need a work visa sponsored by an employer.",
        ],
    ];

    public function __construct()
    {
        $this->apiKey = config('services.openai.api_key') ?? env('OPENAI_API_KEY');

        if (! $this->apiKey) {
            Log::warning('OpenAI API key is missing');
        }
    }

    public function generateResponse(string $userMessage): string
    {
        if (! $this->apiKey) {
            return $this->getFallbackResponse($userMessage);
        }

        try {
            $response = Http::timeout(15)
                ->withHeaders([
                    'Authorization' => 'Bearer '.$this->apiKey,
                    'Content-Type' => 'application/json',
                ])
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => 'gpt-4o-mini',
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'You are Visa Officer Charles, a professional visa and travel consultant.

Rules:
- Use simple English
- No markdown, no **bold**, no asterisks
- Short paragraphs only
- Give direct answers
- Include requirements, costs, and processing time when relevant',
                        ],
                        [
                            'role' => 'user',
                            'content' => $userMessage,
                        ],
                    ],
                    'temperature' => 0.4,
                    'max_tokens' => 500,
                ]);

            if ($response->successful()) {
                return $response->json('choices.0.message.content')
                    ?? $this->getFallbackResponse($userMessage);
            }

            Log::warning('OpenAI request failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return $this->getFallbackResponse($userMessage);

        } catch (\Exception $e) {
            Log::error('OpenAI exception', [
                'message' => $e->getMessage(),
            ]);

            return $this->getFallbackResponse($userMessage);
        }
    }

    protected function getFallbackResponse(string $userMessage): string
    {
        $message = strtolower($userMessage);

        foreach ($this->fallbackQuestions as $faq) {
            if (str_contains($message, strtolower($faq['question']))) {
                return $faq['answer'];
            }
        }

        return '🛫 I can help you with visa and travel questions like:
- Visa requirements
- Passport processing
- Travel documents
- Embassy procedures
- Work or study visas

Please ask a specific question.';
    }

    public function getFallbackQuestions(): array
    {
        return $this->fallbackQuestions;
    }
}
