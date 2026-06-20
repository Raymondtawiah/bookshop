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

    private array $topicKeywords = [
        'purpose_of_visit' => 'travel, business, tourism, family, study',
        'travel_details' => 'dates, itinerary, duration, accommodation, flight, hotel',
        'family_friends_us' => 'family, friend, relationship, address, support',
        'employment' => 'job, employer, position, salary, income, stability',
        'finances' => 'funds, savings, sponsor, bank, support, proof',
        'home_country_ties' => 'family, job, property, return, obligations, roots',
        'travel_history' => 'previous visas, travel, countries, visits, compliance',
        'stress_questions' => 'plan, refused, alternative, return, preparation',
        'school_selection' => 'university, program, ranking, campus, reason, faculty',
        'academic_program' => 'degree, major, courses, skills, career, research',
        'academic_background' => 'grades, qualifications, degree, school, performance',
        'career_plans' => 'job, career, goals, industry, return, advancement',
        'university_knowledge' => 'facilities, ranking, program, professors, campus, support',
    ];

    public function __construct()
    {
        $this->apiKey = config('services.gemini.api_key') ?? env('GEMINI_API_KEY', '');
        $this->groqKey = config('services.groq.api_key') ?? env('GROQ_API_KEY', '');
    }

    public function getNextQuestion(array $conversationHistory, ?string $visaType = null): string
    {
        $visaType = $visaType ?? $this->detectVisaType($conversationHistory);
        $topics = $visaType === 'f1' ? $this->f1Topics : $this->b1b2Topics;
        $topicList = implode(', ', $topics);
        $visaName = $visaType === 'f1' ? 'F1 Student' : 'B1/B2 Visitor';

        $keywordList = collect($this->topicKeywords)
            ->map(fn($keywords, $topic) => "{$topic}: {$keywords}")
            ->implode("\n");

        $systemPrompt = "You are Visa Officer Charles, a strict but helpful visa officer conducting a {$visaName} visa interview simulation.

CRITICAL RULES:
1. Output ONLY one message per turn. Never output multiple messages.
2. Ask only ONE question per message.
3. Always start with coaching assessment of the applicant's last answer:
   - TIP: [brief coaching 1-2 sentences], then ask exactly one follow-up question on the same topic.
   - Good. [then ask one new question from the next topic in order].
4. Never skip coaching. Every turn MUST begin with either 'TIP:' or 'Good.'.
5. If the applicant's answer is not related to the previous question, do not proceed to the next topic.
   Instead respond with: 'Your answer is not related to the question. Please answer the previous question before I ask the next one.'
   Then repeat the same question.
6. Follow this exact topic order: {$topicList}.
7. For each question, expect the answer to include relevant keywords from the expected keyword list below.
   If the answer is missing those keywords or feels off-topic, treat it as unrelated or incomplete.
8. Never greet, never acknowledge, never add commentary beyond coaching and one question.

Expected keywords by topic:
{$keywordList}

Format examples:
TIP: Your sponsor's income is unclear. A stronger answer would state the exact amount and source. What is your sponsor's annual income?
Good. What is your passport number and expiry date?";

        $reply = $this->callGemini($systemPrompt, $conversationHistory, __FUNCTION__);

        if (! is_string($reply) || '' === trim($reply)) {
            $reply = $this->offlineReply($topicList, $conversationHistory);
        }

        return $reply;
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

        $reply = $this->callGemini($systemPrompt, $conversationHistory);

        if (! is_string($reply) || '' === trim($reply)) {
            $reply = $this->offlineEvaluation($visaType, $conversationHistory);
        }

        if (! is_string($reply) || stripos($reply, 'EVALUATION BLOCK:') === false) {
            $reply = "EVALUATION BLOCK:\n" . ($reply ?: '');
        }

        return $reply;
    }

    private function offlineReply(string $topicList, array $conversationHistory): string
    {
        $count = count(array_filter($conversationHistory, fn($m) => ($m['role'] ?? '') === 'user'));

        if ($count >= 12) {
            return $this->offlineEvaluation($this->detectVisaType($conversationHistory), $conversationHistory);
        }

        $topics = explode(', ', $topicList);
        $idx = min($count, max(0, count($topics) - 1));

        $prompts = [
            'purpose_of_visit' => 'What is the main purpose of your trip to the United States?',
            'travel_details' => 'What are your specific travel dates and itinerary?',
            'family_friends_us' => 'Do you have family or friends in the United States? If so, how are they related and where do they live?',
            'employment' => 'What is your current employment situation? Describe your role and income stability.',
            'finances' => 'How will you fund this trip? Who is paying and show proof of sufficient funds?',
            'home_country_ties' => 'What ties do you have to your home country that will ensure your return?',
            'travel_history' => 'Have you travelled internationally before? Mention previous visas if any.',
            'stress_questions' => 'What would you do if your visa is refused?',
            'school_selection' => 'Why did you choose this specific university and program?',
            'academic_program' => 'What will you study and how does it relate to your career goals?',
            'academic_background' => 'Tell me about your previous academic qualifications and grades.',
            'career_plans' => 'What are your career plans after completing your studies?',
            'university_knowledge' => 'Describe the university facilities, rankings, or key faculty that attracted you.',
        ];

        $question = $prompts[$topics[$idx]] ?? 'Tell me more about your background.';

        $line = ($count % 2 === 0) ? 'TIP: Keep answers specific and concise.' : 'Good.';

        return trim($line . ' ' . $question);
    }

    private function offlineEvaluation(string $visaType, array $conversationHistory): string
    {
        $answered = count(array_filter($conversationHistory, fn($m) => ($m['role'] ?? '') === 'user'));

        $decision = $answered >= 7 ? 'Approved' : 'Refused';
        $score = max(55, min(92, 72 + ($answered * 3) + random_int(-4, 4)));

        $decisionLabel = $decision === 'Approved' ? 'Approved' : 'Refused';

        return "EVALUATION BLOCK:
Decision: {$decisionLabel}
Score: {$score}
Risk Level: " . ($score >= 80 ? 'Low' : 'Medium') . "
Strengths: " . ($decision === 'Approved' ? 'Clear answers, strong home ties, consistent story' : 'Genuine travel purpose demonstrated') . "
Weaknesses: Limited detail in financial planning, accommodation plan could be stronger
Remarks: Offline assessment based on {$answered} user responses for {$visaType}. This is a simulated evaluation. Connect a live AI model for a real-time assessment.";
    }

    private function callGemini(string $systemPrompt, array $conversationHistory, string $functionName = 'unknown'): string
    {
        if (empty($this->apiKey)) {
            Log::warning('Gemini API key missing', ['function' => $functionName]);

            return '';
        }

        $contents = [];

        foreach ($conversationHistory as $msg) {
            $role = $msg['role'] === 'officer' ? 'model' : 'user';
            $contents[] = [
                'role' => $role,
                'parts' => [['text' => $msg['content']]],
            ];
        }

        $baseUrl = config('services.gemini.base_url', 'https://generativelanguage.googleapis.com/v1');
        $model = config('services.gemini.model', 'gemini-1.5-flash');

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

        return '';
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
