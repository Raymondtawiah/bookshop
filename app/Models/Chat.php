<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Chat extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'email',
        'message',
        'ip_address',
        'sender_type',
        'is_read',
        'unique_id',
        'replied_message_id',
    ];

    protected function casts(): array
    {
        return [
            'is_read' => 'boolean',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($chat) {
            if (empty($chat->unique_id) && $chat->ip_address) {
                $chat->unique_id = static::generateUniqueId($chat->ip_address);
            }
        });
    }

    public static function generateUniqueId($ipAddress)
    {
        $existingChat = static::where('ip_address', $ipAddress)->first();
        if ($existingChat && $existingChat->unique_id) {
            return $existingChat->unique_id;
        }

        return uniqid('chat_', true);
    }

    public static function getUniqueIdByIp($ipAddress)
    {
        $chat = static::where('ip_address', $ipAddress)->first();

        return $chat?->unique_id;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function getUniqueVisitors()
    {
        return self::select('ip_address')
            ->distinct()
            ->where('sender_type', 'customer')
            ->orderByDesc('created_at')
            ->get();
    }

    public static function getChatsByIp($ipAddress)
    {
        return self::where('ip_address', $ipAddress)
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public static function getAllChatsWithUserInfo()
    {
        return self::select('chats.*')
            ->selectRaw('MIN(chats.id) as first_chat_id')
            ->groupBy('chats.ip_address')
            ->orderByDesc('chats.created_at')
            ->get()
            ->map(function ($chat) {
                $latestChat = self::where('ip_address', $chat->ip_address)->latest()->first();
                $chat->latestMessage = $latestChat?->message;
                $chat->latestTime = $latestChat?->created_at;
                $chat->unreadCount = self::where('ip_address', $chat->ip_address)
                    ->where('sender_type', 'customer')
                    ->where('is_read', false)
                    ->count();

                return $chat;
            });
    }
}
