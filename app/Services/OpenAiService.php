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
            'answer' => "🇬🇭 GHANA VISA REQUIREMENTS:\n\n📋 GENERAL REQUIREMENTS:\n• Valid passport (6+ months)\n• Completed application form\n• 2 passport photos\n• Yellow fever certificate\n• Proof of accommodation\n• Return ticket\n• Bank statement (3 months)\n\n💰 FEES:\n• Single entry: $60-$100\n• Multiple entry: $100-$200\n\n⏱️ PROCESSING: 5-14 business days\n\n📍 Apply at Ghana Embassy or online (e-visa available for some nationalities)."
        ],
        [
            'question' => 'How do I apply for a US visa?',
            'answer' => "🇺🇸 US VISA APPLICATION PROCESS:\n\n📋 STEP 1: Complete DS-160 Form\n• Online at ceac.state.gov\n• Fill all required information\n\n📋 STEP 2: Pay Visa Fee\n• MRV fee: $160 (non-refundable)\n\n📋 STEP 3: Schedule Interview\n• Wait times vary by location\n• Book online or by phone\n\n📋 STEP 4: Interview\n• Bring all supporting documents\n• Common questions: travel purpose, ties to home country, financial status\n\n⏱️ PROCESSING: 2-8 weeks typically\n\n💰 FEES:\n• Tourist/Business (B1/B2): $185\n• Student (F1): $200\n\n💡 TIP: Apply well in advance of your travel date!"
        ],
        [
            'question' => 'What documents do I need for UK visa?',
            'answer' => "🇬🇧 UK VISA REQUIREMENTS:\n\n📋 REQUIRED DOCUMENTS:\n• Valid passport\n• Completed application form\n• Proof of funds (bank statements)\n• Employment letter\n• Accommodation proof\n• Travel itinerary\n• TB test results (if staying 6+ months)\n\n💰 FEES:\n• Standard visitor: £100\n• Short-term study: £200\n• Work visa: £700+\n\n⏱️ PROCESSING: 3 weeks typically\n\n📍 Apply online at gov.uk/apply-uk-visa"
        ],
        [
            'question' => 'Can I work on a tourist visa?',
            'answer' => "⚠️ WORKING ON TOURIST VISA - IMPORTANT:\n\n❌ NO - Working on a tourist visa is ILLEGAL in most countries\n\n✅ ALTERNATIVES:\n\n1. WORK VISA\n   • Employer must sponsor you\n   • Requires job offer\n\n2. BUSINESS VISA\n   • For business activities only\n   • Cannot be employed\n\n3. DIGITAL NOMAD VISA\n   • Available in: Estonia, Croatia, Bali, Barbados\n   • Allows remote work\n\n4. FREELANCER/Consultant Visa\n   • Available in: Germany, UAE\n\n💡 ALWAYS check local laws before accepting any work. Overstaying or working illegally can result in:\n• Deportation\n• Entry bans\n• Fines\n• Future visa rejections"
        ],
        [
            'question' => 'How long does passport processing take?',
            'answer' => "⏱️ PASSPORT PROCESSING TIMES:\n\n🇺🇸 USA:\n• Routine: 8-11 weeks\n• Expedited: 5-7 weeks ($60 extra)\n• Emergency: 2-3 days (for life/death emergencies)\n\n🇬🇧 UK:\n• Standard: 3 weeks\n• Fast Track: 1 week\n• Premium: Same day\n\n🇨🇦 Canada:\n• Standard: 10-20 business days\n• Express: 2-9 business days\n\n🌍 GENERAL TIPS:\n• Apply 2-3 months before travel\n• Check embassy websites for current times\n• Some countries offer expedited services\n• Keep copies of all documents\n\n⚠️ Note: Times may be longer during peak seasons (summer, holidays)"
        ],
        [
            'question' => 'Do I need travel insurance?',
            'answer' => "✈️ TRAVEL INSURANCE - IS IT WORTH IT?\n\n✅ HIGHLY RECOMMENDED - Here's why:\n\n🏥 MEDICAL COVERAGE:\n• Emergency medical treatment abroad\n• Hospital stays can cost $10,000+/day\n• Medical evacuation: $50,000+\n\n🛡️ COVERS:\n• Trip cancellation\n• Lost baggage\n• Flight delays\n• Personal liability\n\n💰 COST:\n• Basic: $50-100/year\n• Comprehensive: $150-300/year\n• Premium: $500+\n\n📋 VISA REQUIREMENT:\n• Schengen: MUST have min €30,000 medical coverage\n• Some countries require proof\n\n💡 TIP: Compare multiple providers, check exclusions, read the fine print!"
        ],
        [
            'question' => 'What is a visa on arrival?',
            'answer' => "🇨🇳 VISA ON ARRIVAL (VOA):\n\n✅ What it means:\n• Get visa at destination airport/border\n• No pre-application needed\n\n⚠️ Requirements vary by country:\n• Valid passport\n• Return ticket\n• Proof of accommodation\n• Sufficient funds\n\n🌍 COUNTRIES OFFERING VOA:\n• Thailand (15-30 days)\n• Malaysia (30 days)\n• Nepal (15-90 days)\n• Maldives (30 days)\n• Kenya (90 days)\n• Tanzania (90 days)\n• Uganda (90 days)\n\n⏱️ Wait time: 15 min to 2 hours\n\n💡 TIP: Some nationalities excluded. Check before you fly!\n\n⚠️ Note: Not available at all ports. Check specific airports."
        ],
        [
            'question' => 'How can I extend my visa?',
            'answer' => "📝 VISA EXTENSION GUIDE:\n\n🇺🇸 USA:\n• Most visas cannot be extended\n• Must apply for new visa\n• Apply before current expires\n\n🇬🇧 UK:\n• Apply online before expiry\n• Fee: £400+\n• Must have valid reason\n\n🇨🇦 Canada:\n• Apply before expiry\n• Fee: $100-230\n• Maintain status while processing\n\n🌍 GENERAL PROCESS:\n1. Visit immigration office\n2. Fill extension form\n3. Pay fee\n4. Provide supporting documents\n5. Wait for decision\n\n💡 TIPS:\n• Apply 2-4 weeks before expiry\n• Show proof of funds\n• Explain reason for extension\n• Some countries don't allow extensions\n\n⚠️ Overstaying = fines, deportation, future bans!"
        ]
    ];

    public function __construct()
    {
        $this->apiKey = config('services.openai.api_key') ?: env('OPENAI_API_KEY', '');
        Log::info('OpenAI key loaded', ['key_prefix' => substr($this->apiKey, 0, 20)]);
    }

    public function generateResponse(string $userMessage): ?string
    {
        if (empty($this->apiKey)) {
            Log::warning('OpenAI API key not configured');
            return $this->getFallbackResponse($userMessage);
        }

try {
            $prompt = <<<PROMPT
You are "Visa Officer Charles", a professional TRAVEL and VISA consultant with years of experience. Your name is Visa Officer Charles.

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

            $response = Http::timeout(10)->withHeaders([
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

            if ($response->successful()) {
                $data = $response->json();
                return $data['choices'][0]['message']['content'] ?? null;
            }

            return $this->getFallbackResponse($userMessage);

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            return $this->getFallbackResponse($userMessage);
        } catch (\Illuminate\Http\Client\RequestException $e) {
            return $this->getFallbackResponse($userMessage);
        } catch (\Exception $e) {
            Log::error('OpenAI exception', ['error' => $e->getMessage()]);
            return $this->getFallbackResponse($userMessage);
        }
    }

    protected function getFallbackResponse(string $userMessage): string
    {
        $userMessageLower = strtolower($userMessage);

        foreach ($this->fallbackQuestions as $faq) {
            if (str_contains($userMessageLower, strtolower($faq['question']))) {
                return $faq['answer'];
            }
        }

        return <<<RESPONSE
🛫 Welcome! I'm Visa Officer Charles!

I'm having trouble connecting to my AI service right now, but here are some common visa questions I can help with:

Popular Topics:
• 🇬🇭 Ghana Visa Requirements
• 🇺🇸 US Visa Application Process  
• 🇬🇧 UK Visa Documents
• Can I work on tourist visa?
• Passport processing times
• Travel insurance requirements
• Visa on arrival countries
• How to extend visa

Please try asking one of these questions, or try again in a few moments when the connection is restored!

💡 For urgent questions, contact the nearest embassy or consulate.
RESPONSE;
    }

    public function getFallbackQuestions(): array
    {
        return $this->fallbackQuestions;
    }
}