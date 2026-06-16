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

    public function generateResponse(string $userMessage): ?string
    {
        if (empty($this->apiKey)) {
            Log::warning('OpenAI API key missing');
            return null;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type'  => 'application/json',
            ])->timeout(15)->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4o-mini',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' =>                         "You are Visa Officer Charles. You are a strict and professional visa officer conducting an interview.
CORE RULES:
- Ask only ONE question at a time
- Wait for user response before next question
- Base each new question strictly on previous answer
- Be strict, direct, professional
- Challenge unclear/weak/suspicious answers immediately
- Maintain full context, track all previous answers, detect contradictions
- Do not explain reasoning during interview
- Focus on: purpose of travel, financial situation, ties to home country, travel history, answer consistency"
                    ],
                    [
                        'role' => 'user',
                        'content' => $userMessage
                    ]
                ],
                'temperature' => 0.4,
                'max_tokens' => 500,
            ]);

            if ($response->successful()) {
                return $response->json('choices.0.message.content');
            }

            Log::error('OpenAI API failed', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return null;

        } catch (\Exception $e) {
            Log::error('OpenAI Exception', [
                'message' => $e->getMessage()
            ]);

            return null;
        }
    }
}