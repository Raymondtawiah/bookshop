<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Services\OpenAiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    protected OpenAiService $openAiService;

    public function __construct(OpenAiService $openAiService)
    {
        $this->openAiService = $openAiService;
    }

    protected function cleanupOldMessages(): void
    {
        Chat::where('created_at', '<', now()->subHours(24))->delete();
    }

    public function store(Request $request)
    {
        $this->cleanupOldMessages();

        $request->validate([
            'message' => 'required|string|max:5000',
        ]);

        $user = Auth::user();
        $ipAddress = $request->ip();

        // stable unique ID
        $uniqueId = $request->input('unique_id');

        if (!$uniqueId) {
            $uniqueId = Chat::where('ip_address', $ipAddress)
                ->whereNotNull('unique_id')
                ->value('unique_id')
                ?? uniqid('chat_', true);
        }

        // save user message
        $chat = Chat::create([
            'user_id' => $user?->id,
            'name' => $request->input('name', 'Guest'),
            'email' => $request->input('email'),
            'message' => $request->message,
            'ip_address' => $ipAddress,
            'unique_id' => $uniqueId,
            'sender_type' => 'customer',
            'is_read' => false,
        ]);

        // AI response
        $aiReplyText = $this->openAiService->generateResponse($request->message);

        if (!$aiReplyText) {
            $aiReplyText = "I'm having trouble responding right now. Please try again shortly.";
        }

        $aiChat = Chat::create([
            'user_id' => null,
            'name' => 'AI Assistant',
            'email' => 'ai@system.local',
            'message' => $aiReplyText,
            'ip_address' => $ipAddress,
            'unique_id' => $uniqueId,
            'sender_type' => 'admin',
            'is_read' => true,
            'replied_message_id' => $chat->id,
        ]);

        return response()->json([
            'success' => true,
            'chat' => $chat,
            'ai_reply' => $aiChat,
        ]);
    }

    public function customerChats(Request $request)
    {
        $uniqueId = $request->query('unique_id');

        return response()->json([
            'success' => true,
            'chats' => $uniqueId
                ? Chat::where('unique_id', $uniqueId)->orderBy('id')->get()
                : [],
        ]);
    }

    public function markAsRead(Request $request)
    {
        $uniqueId = $request->query('unique_id');

        if ($uniqueId) {
            Chat::where('unique_id', $uniqueId)
                ->where('sender_type', 'admin')
                ->update(['is_read' => true]);
        }

        return response()->json(['success' => true]);
    }

    public function clearAllChats(Request $request)
    {
        $uniqueId = $request->query('unique_id');

        if ($uniqueId) {
            Chat::where('unique_id', $uniqueId)->delete();
        }

        return response()->json(['success' => true]);
    }
}