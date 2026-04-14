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

    protected function cleanupOldMessages()
    {
        Chat::where('created_at', '<', now()->subHours(12))->delete();
    }

    public function store(Request $request)
    {
        // Clean up messages older than 24 hours
        $this->cleanupOldMessages();
        \Illuminate\Support\Facades\Log::info('Chat store called', [
            'message' => $request->input('message'),
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'unique_id' => $request->input('unique_id'),
            'ip' => $request->ip(),
            'all_params' => $request->all(),
        ]);
        
        $request->validate([
            'message' => 'required|string|max:5000',
        ]);

        $ipAddress = $request->ip();
        $user = Auth::user();
        $uniqueId = $request->input('unique_id');

        \Illuminate\Support\Facades\Log::info('Processing chat', ['uniqueId' => $uniqueId, 'ipAddress' => $ipAddress]);

        // Check if unique_id exists, if not create one
        if (!$uniqueId) {
            $existingChat = Chat::where('ip_address', $ipAddress)->whereNotNull('unique_id')->first();
            $uniqueId = $existingChat?->unique_id ?? uniqid('chat_', true);
            \Illuminate\Support\Facades\Log::info('Generated new uniqueId', ['uniqueId' => $uniqueId]);
        }

        // Get existing customer info if this is a returning customer
        $existingChat = Chat::where('unique_id', $uniqueId)->first();
        
        $chat = Chat::create([
            'user_id' => $user?->id,
            'name' => $request->input('name') ?? ($existingChat?->name ?? 'Guest'),
            'email' => $request->input('email') ?? $existingChat?->email,
            'message' => $request->message,
            'ip_address' => $ipAddress,
            'unique_id' => $uniqueId,
            'sender_type' => 'customer',
            'is_read' => false,
            'replied_message_id' => $request->input('replied_message_id'),
        ]);

        // Generate AI reply using OpenAI
        $aiReplyText = $this->openAiService->generateResponse($request->message);
        
        // Fallback responses if OpenAI fails
        if (!$aiReplyText) {
            $fallbackResponses = [
                "I'd be happy to help with your visa application. Could you please clarify which type of visa you need?",
                "For visa applications, please check the embassy website for required documents. Common documents include passport, photos, and application form.",
                "Visa processing times vary by country. Standard tourist visas typically take 5-14 business days.",
                "Please provide more details about your travel plans so I can assist better.",
            ];
            $aiReplyText = $fallbackResponses[array_rand($fallbackResponses)];
            Log::info('Using fallback response');
        }
        
        if ($aiReplyText) {
            Chat::create([
                'user_id' => null,
                'name' => 'AI Assistant',
                'email' => 'ai@bookshop.test',
                'message' => $aiReplyText,
                'ip_address' => $ipAddress,
                'unique_id' => $uniqueId,
                'sender_type' => 'admin',
                'is_read' => true,
                'replied_message_id' => $chat->id,
            ]);
        }

        \Illuminate\Support\Facades\Log::info('Chat created', ['chat_id' => $chat->id, 'unique_id' => $chat->unique_id]);

        $aiReply = null;
        if ($aiReplyText) {
            $aiReply = Chat::where('unique_id', $uniqueId)
                ->where('sender_type', 'admin')
                ->orderByDesc('id')
                ->first();
        }

        return response()->json([
            'success' => true,
            'chat' => $chat,
            'ai_reply' => $aiReply,
        ]);
    }

    public function customerChats(Request $request)
    {
        $uniqueId = $request->query('unique_id');
        
        if (!$uniqueId) {
            return response()->json([
                'success' => true,
                'chats' => [],
            ]);
        }

        $chats = Chat::where('unique_id', $uniqueId)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'chats' => $chats,
        ]);
    }

    public function getUnreadCount(Request $request)
    {
        $uniqueId = $request->query('unique_id');
        
        if (!$uniqueId) {
            return response()->json(['success' => true, 'count' => 0]);
        }
        
        $count = Chat::where('unique_id', $uniqueId)
            ->where('sender_type', 'admin')
            ->where('is_read', false)
            ->count();

        return response()->json([
            'success' => true,
            'count' => $count,
        ]);
    }

    public function markAsRead(Request $request)
    {
        $uniqueId = $request->query('unique_id');
        
        if ($uniqueId) {
            Chat::where('unique_id', $uniqueId)
                ->where('sender_type', 'admin')
                ->where('is_read', false)
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

    public function index()
    {
        $chats = Chat::select('unique_id', DB::raw('MAX(id) as latest_id'))
            ->whereNotNull('unique_id')
            ->groupBy('unique_id')
            ->orderByDesc('latest_id')
            ->get()
            ->map(function ($chat) {
                $chatData = Chat::where('unique_id', $chat->unique_id)->latest()->first();
                $firstChat = Chat::where('unique_id', $chat->unique_id)->oldest()->first();
                $unreadCount = Chat::where('unique_id', $chat->unique_id)
                    ->where('sender_type', 'customer')
                    ->where('is_read', false)
                    ->count();
                
                $email = $firstChat?->email;
                if (!$email) {
                    $emailChat = Chat::where('unique_id', $chat->unique_id)->whereNotNull('email')->first();
                    $email = $emailChat?->email;
                }
                
                return (object) [
                    'id' => $chat->id,
                    'ip_address' => $chatData?->ip_address,
                    'unique_id' => $chat->unique_id,
                    'latest_message' => $chatData?->message,
                    'latest_time' => $chatData?->created_at,
                    'name' => $firstChat?->name,
                    'email' => $email,
                    'unread_count' => $unreadCount,
                ];
            });

        return view('admin.chat.index', compact('chats'));
    }

    public function showByUniqueId($uniqueId)
    {
        $chats = Chat::where('unique_id', $uniqueId)
            ->orderBy('created_at', 'asc')
            ->get();

        if ($chats->isEmpty()) {
            return redirect()->route('admin.chat.index')->with('error', 'Chat not found');
        }

        $firstChat = $chats->first();
        $userName = $firstChat?->name ?? 'Guest';
        $userEmail = $firstChat?->email;
        $ipAddress = $firstChat?->ip_address;

        Chat::where('unique_id', $uniqueId)
            ->where('sender_type', 'customer')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('admin.chat.show', compact('chats', 'ipAddress', 'userName', 'userEmail', 'uniqueId'));
    }

    public function replyByUniqueId(Request $request, $uniqueId)
    {
        $request->validate([
            'message' => 'required|string|max:5000',
        ]);

        $chat = Chat::where('unique_id', $uniqueId)->first();
        if (!$chat) {
            return redirect()->route('admin.chat.index')->with('error', 'Chat not found');
        }

        Chat::create([
            'user_id' => Auth::id(),
            'name' => Auth::user()->name,
            'email' => Auth::user()->email,
            'message' => $request->message,
            'ip_address' => $chat->ip_address,
            'unique_id' => $uniqueId,
            'sender_type' => 'admin',
            'is_read' => true,
            'replied_message_id' => $request->input('replied_message_id'),
        ]);

        return redirect()->route('admin.chat.show', $uniqueId)->with('success', 'Reply sent successfully!');
    }

    public function getTotalUnreadCount()
    {
        $count = Chat::where('sender_type', 'customer')
            ->where('is_read', false)
            ->count();

        return response()->json(['success' => true, 'count' => $count]);
    }

    public function clearChatMessage($id)
    {
        $chat = Chat::find($id);
        if ($chat) {
            $chat->delete();
        }

        return response()->json(['success' => true]);
    }

    public function deleteConversation($uniqueId)
    {
        Chat::where('unique_id', $uniqueId)->delete();
        return redirect()->route('admin.chat.index')->with('success', 'Conversation deleted');
    }
}