<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Models\AdminNotification;
use App\Mail\ChatNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ChatController extends Controller
{
    protected function getChatIdentifier(Request $request): ?string
    {
        if (Auth::check()) {
            return 'user_'.Auth::id();
        }

        return $request->cookie('chat_session') ?: 'guest_'.Str::random(32);
    }

    protected function getChatSession(Request $request): ?string
    {
        if (Auth::check()) {
            return null;
        }

        return $request->cookie('chat_session') ?: Str::random(32);
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $chatSession = $this->getChatSession($request);

        $query = ChatMessage::query();

        if ($user) {
            $query->where('user_id', $user->id);
        } elseif ($chatSession) {
            $query->where('chat_session', $chatSession);
        }

        $messages = $query->orderBy('created_at', 'asc')->get();

        $response = response()->view('chat.index', compact('messages', 'user'));

        if (! $user && $chatSession && ! $request->cookie('chat_session')) {
            $response->withCookie(cookie('chat_session', $chatSession, 60 * 24 * 30)); // 30 days
        }

        return $response;
    }

    public function store(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:2000',
            'sender_name' => 'nullable|string|max:255',
            'replied_to_message_id' => 'nullable|exists:chat_messages,id',
        ]);

        $user = Auth::user();
        $chatSession = $this->getChatSession($request);

        if (! $user && ! $chatSession) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to identify chat session.',
            ], 400);
        }

        $message = ChatMessage::create([
            'user_id' => $user ? $user->id : null,
            'chat_session' => $user ? null : $chatSession,
            'sender_name' => $request->input('sender_name') ?: ($user ? $user->name : 'Guest'),
            'message' => $request->input('message'),
            'sender_type' => $user ? 'customer' : 'guest',
            'status' => 'unread',
            'replied_to_message_id' => $request->input('replied_to_message_id'),
        ]);

        if ($user) {
            AdminNotification::createNotification(
                'chat',
                'New Chat Message',
                $user->name . ': ' . $request->input('message'),
                route('admin.chat.index')
            );

            try {
                Mail::to('raymondtawiah23@gmail.com')->send(
                    new ChatNotification($user->name, $request->input('message'), route('admin.chat.index'))
                );
            } catch (\Throwable $e) {
                \Log::error('Chat notification email failed: '.$e->getMessage());
            }
        } else {
            $senderName = $request->input('sender_name') ?: 'Guest';
            AdminNotification::createNotification(
                'chat',
                'New Chat Message',
                $senderName . ': ' . $request->input('message'),
                route('admin.chat.index')
            );

            try {
                Mail::to('raymondtawiah23@gmail.com')->send(
                    new ChatNotification($senderName, $request->input('message'), route('admin.chat.index'))
                );
            } catch (\Throwable $e) {
                \Log::error('Chat notification email failed: '.$e->getMessage());
            }
        }

        $response = response()->json([
            'success' => true,
            'message' => $message,
        ]);

        if (! $user) {
            $response->withCookie(cookie('chat_session', $chatSession, 60 * 24 * 30)); // 30 days
        }

        return $response;
    }

    public function getMessages(Request $request)
    {
        $user = Auth::user();
        $chatSession = $this->getChatSession($request);

        $query = ChatMessage::query();

        if ($user) {
            $query->where('user_id', $user->id);
        } elseif ($chatSession) {
            $query->where('chat_session', $chatSession);
        }

        $messages = $query->orderBy('created_at', 'asc')->get()->map(function ($message) {
            $replyToMessage = null;
            if ($message->replied_to_message_id) {
                $replyToMessage = ChatMessage::find($message->replied_to_message_id);
            }

            return [
                'id' => $message->id,
                'message' => $message->message,
                'sender_type' => $message->sender_type,
                'sender_name' => $message->sender_name,
                'created_at' => $message->created_at->format('h:i A'),
                'is_customer' => in_array($message->sender_type, ['customer', 'guest']),
                'replied_to_message_id' => $message->replied_to_message_id,
                'reply_to_message' => $replyToMessage ? [
                    'id' => $replyToMessage->id,
                    'message' => $replyToMessage->message,
                    'sender_name' => $replyToMessage->sender_name,
                    'sender_type' => $replyToMessage->sender_type,
                ] : null,
            ];
        });

        $response = response()->json([
            'success' => true,
            'messages' => $messages,
        ]);

        if (! $user && $chatSession && ! $request->cookie('chat_session')) {
            $response->withCookie(cookie('chat_session', $chatSession, 60 * 24 * 30)); // 30 days
        }

        return $response;
    }

    public function getConversations(Request $request)
    {
        $admin = Auth::user();

        $conversations = ChatMessage::query()
            ->select('user_id', 'chat_session', 'sender_name', 'message', 'sender_type', 'created_at', 'status')
            ->orderByDesc('created_at')
            ->get()
            ->groupBy(function ($message) {
                if ($message->user_id) {
                    return 'user_'.$message->user_id;
                }

                return 'guest_'.$message->chat_session;
            })
            ->map(function ($messages) {
                $lastMessage = $messages->first();
                $allMessages = $messages->reverse()->values();
                $isCustomer = in_array($lastMessage->sender_type, ['customer', 'guest']);

                return [
                    'id' => $lastMessage->user_id ? 'user_'.$lastMessage->user_id : 'guest_'.$lastMessage->chat_session,
                    'user_id' => $lastMessage->user_id,
                    'chat_session' => $lastMessage->chat_session,
                    'sender_name' => $lastMessage->sender_name,
                    'last_message' => $lastMessage->message,
                    'last_message_at' => $lastMessage->created_at->format('M d, Y h:i A'),
                    'sender_type' => $lastMessage->sender_type,
                    'is_customer' => $isCustomer,
                    'unread_count' => $messages->where('status', 'unread')->whereIn('sender_type', ['customer', 'guest'])->count(),
                    'preview' => strlen($lastMessage->message) > 50 ? substr($lastMessage->message, 0, 50).'...' : $lastMessage->message,
                ];
            })
            ->values();

        return response()->json([
            'success' => true,
            'conversations' => $conversations,
        ]);
    }

    public function getConversationMessages(Request $request, $conversationId)
    {
        $admin = Auth::user();

        $query = ChatMessage::query();

        if (str_starts_with($conversationId, 'user_')) {
            $userId = (int) str_replace('user_', '', $conversationId);
            $query->where('user_id', $userId);
        } elseif (str_starts_with($conversationId, 'guest_')) {
            $chatSession = str_replace('guest_', '', $conversationId);
            $query->where('chat_session', $chatSession);
        }

        $messages = $query->orderBy('created_at', 'asc')->get()->map(function ($message) {
            $replyToMessage = null;
            if ($message->replied_to_message_id) {
                $replyToMessage = ChatMessage::find($message->replied_to_message_id);
            }

            return [
                'id' => $message->id,
                'message' => $message->message,
                'sender_type' => $message->sender_type,
                'sender_name' => $message->sender_name,
                'created_at' => $message->created_at->format('h:i A'),
                'is_customer' => in_array($message->sender_type, ['customer', 'guest']),
                'replied_to_message_id' => $message->replied_to_message_id,
                'reply_to_message' => $replyToMessage ? [
                    'id' => $replyToMessage->id,
                    'message' => $replyToMessage->message,
                    'sender_name' => $replyToMessage->sender_name,
                    'sender_type' => $replyToMessage->sender_type,
                ] : null,
            ];
        });

        return response()->json([
            'success' => true,
            'messages' => $messages,
        ]);
    }

    public function adminReply(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:2000',
            'conversation_id' => 'required|string',
            'replied_to_message_id' => 'nullable|exists:chat_messages,id',
        ]);

        $admin = Auth::user();

        if (! $admin) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 401);
        }

        $conversationId = $request->input('conversation_id');
        $userId = null;
        $chatSession = null;

        if (str_starts_with($conversationId, 'user_')) {
            $userId = (int) str_replace('user_', '', $conversationId);
        } elseif (str_starts_with($conversationId, 'guest_')) {
            $chatSession = str_replace('guest_', '', $conversationId);
        }

        if (! $userId && ! $chatSession) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid conversation',
            ], 400);
        }

        $message = ChatMessage::create([
            'user_id' => $userId,
            'chat_session' => $chatSession,
            'sender_name' => $admin->name,
            'message' => $request->input('message'),
            'sender_type' => 'admin',
            'status' => 'read',
            'replied_to_message_id' => $request->input('replied_to_message_id'),
        ]);

        return response()->json([
            'success' => true,
            'message' => $message,
        ]);
    }

    public function markAsRead(Request $request)
    {
        $user = Auth::user();
        $chatSession = $this->getChatSession($request);

        $query = ChatMessage::query();

        if ($user) {
            $query->where('user_id', $user->id);
        } elseif ($chatSession) {
            $query->where('chat_session', $chatSession);
        }

        $query->where('sender_type', 'customer')
            ->orWhere('sender_type', 'guest')
            ->where('status', 'unread')
            ->update(['status' => 'read', 'read_at' => now()]);

        return response()->json([
            'success' => true,
        ]);
    }
}
