<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ChatMessage as ChatMessageModel;
use App\Models\ChatSession;
use App\Models\VisaArticle;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class VisaChatController extends Controller
{
    public function start(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
        ]);

        $sessionToken = Str::random(60);

        $session = ChatSession::create([
            'user_id' => Auth::id(),
            'session_token' => $sessionToken,
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'last_activity' => now(),
        ]);

        return response()->json([
            'success' => true,
            'session' => [
                'id' => $session->id,
                'token' => $session->session_token,
                'name' => $session->name,
            ],
        ]);
    }

    public function sendMessage(Request $request): JsonResponse
    {
        $request->validate([
            'session_token' => 'required|string|exists:chat_sessions,session_token',
            'message' => 'required|string|max:4000',
        ]);

        $session = ChatSession::where('session_token', $request->session_token)->first();

        ChatMessageModel::create([
            'chat_session_id' => $session->id,
            'role' => 'user',
            'content' => $request->message,
        ]);

        $reply = $this->buildReply($request->message, $session);

        ChatMessageModel::create([
            'chat_session_id' => $session->id,
            'role' => 'assistant',
            'content' => $reply,
        ]);

        $session->update(['last_activity' => now()]);

        return response()->json([
            'success' => true,
            'reply' => $reply,
        ]);
    }

    public function history(Request $request): JsonResponse
    {
        $request->validate([
            'session_token' => 'required|string',
        ]);

        $session = ChatSession::where('session_token', $request->session_token)->firstOrFail();

        $messages = $session->messages()
            ->orderBy('created_at', 'asc')
            ->get(['role', 'content', 'created_at']);

        return response()->json([
            'success' => true,
            'messages' => $messages,
        ]);
    }

    public function endSession(Request $request, $token): JsonResponse
    {
        $session = ChatSession::where('session_token', $token)->firstOrFail();
        $session->messages()->delete();
        $session->delete();

        return response()->json(['success' => true]);
    }

    private function buildReply(string $userMessage, ChatSession $session): string
    {
        $articles = VisaArticle::active()
            ->byCountry('Global')
            ->orWhere(function ($q) use ($userMessage) {
                $q->where('is_active', true)
                    ->where(function ($sub) use ($userMessage) {
                        $sub->where('title', 'like', '%'.$userMessage.'%')
                            ->orWhere('content', 'like', '%'.$userMessage.'%');
                    });
            })
            ->limit(3)
            ->get();

        $context = '';
        if ($articles->isNotEmpty()) {
            $context = "\n\nReference Knowledge:\n";
            foreach ($articles as $article) {
                $context .= "- [{$article->country}] {$article->title}: {$article->content}\n";
            }
        }

        $name = $session->name ?: 'there';

        $systemPrompt = <<<PROMPT
You are a professional Visa Education Assistant.
Rules:
- ONLY answer questions about visas, travel requirements, immigration procedures, visa interviews, required documents, processing timelines, and travel preparation.
- If a question is NOT about visas or immigration, politely decline and steer the user back to visa topics.
- NEVER answer unrelated topics (math, coding, general knowledge, entertainment, sports, etc.).
- Be helpful, educational, and friendly.
- Address user by name ({$name}) once if known.
- If they are asking about a specific country, ask which country first if not mentioned.
- Never guarantee visa approval.
- Never provide legal advice.
- Always remind users to verify information through official government sources.
- Use bullet points and numbered steps.
- If you reference knowledge, keep it concise.
{$context}
PROMPT;

        $geminiKey = config('services.gemini.api_key');

        if ($geminiKey) {
            try {
                $response = Http::timeout(20)
                    ->post('https://generativelanguage.googleapis.com/v1beta/models/'.config('services.gemini.model', 'gemini-2.0-flash').':generateContent?key='.$geminiKey, [
                        'contents' => [
                            [
                                'parts' => [
                                    ['text' => $systemPrompt],
                                ],
                            ],
                            [
                                'parts' => [
                                    ['text' => $userMessage],
                                ],
                            ],
                        ],
                        'generationConfig' => [
                            'temperature' => 0.3,
                            'maxOutputTokens' => 600,
                        ],
                    ]);

                if ($response->failed()) {
                    $errorBody = $response->json('error.message') ?: $response->body();
                    Log::warning('Gemini request failed', [
                        'status' => $response->status(),
                        'error' => $errorBody,
                    ]);

                    return $this->fallbackReply($userMessage, 'Gemini error: '.$errorBody);
                }

                $text = $response->json('candidates.0.content.parts.0.text');
                if ($text) {
                    return $text;
                }

                Log::warning('Gemini empty response', ['body' => $response->body()]);

                return $this->fallbackReply($userMessage, 'Empty response from Gemini');
            } catch (\Exception $e) {
                Log::error('Gemini exception', [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);

                return $this->fallbackReply($userMessage, 'Exception: '.$e->getMessage());
            }
        }

        return $this->fallbackReply($userMessage, 'No Gemini key configured');
    }

    private function fallbackReply(string $userMessage, string $debug = ''): string
    {
        $msg = strtolower(trim($userMessage));

        if (preg_match('/^(hi|hello|hey|good morning|good afternoon|good evening|howdy|greetings|yo|what\'?s up|whats up|sup)$/i', $msg)) {
            return "Hello! I'm your Visa Assistant AI — I'm here to help you with visa applications, travel requirements, interviews, required documents, and immigration procedures. Which country or visa type are you interested in?";
        }

        if ($debug) {
            Log::info('VisaChat fallback triggered', ['debug' => $debug, 'message' => substr($userMessage, 0, 100)]);
        }

        return 'AI is temporarily unavailable. Please try again shortly.';
    }
}
