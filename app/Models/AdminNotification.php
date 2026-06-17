<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminNotification extends Model
{
    use HasFactory;

    protected $table = 'notifications';

    protected $fillable = [
        'type',
        'title',
        'message',
        'link',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    public static function createNotification(string $type, string $title, ?string $message = null, ?string $link = null): self
    {
        return self::create([
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'link' => $link,
            'is_read' => false,
        ]);
    }

    public static function getUnreadCount(): int
    {
        return self::where('is_read', false)->count();
    }

    public static function getLatest(int $limit = 10): Collection
    {
        return self::latest()->limit($limit)->get();
    }

    public function markAsRead(): void
    {
        $this->update(['is_read' => true]);
    }

    public static function markAllAsRead(): void
    {
        self::where('is_read', false)->update(['is_read' => true]);
    }

    public static function deleteOlderThan(int $hours = 12): int
    {
        return self::where('created_at', '<', now()->subHours($hours))->delete();
    }
}
