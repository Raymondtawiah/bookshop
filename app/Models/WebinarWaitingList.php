<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WebinarWaitingList extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'webinar_session_id',
        'email',
        'full_name',
        'is_guest',
    ];

    protected $casts = [
        'is_guest' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function webinarSession(): BelongsTo
    {
        return $this->belongsTo(WebinarSession::class);
    }

    public function scopeForWebinar($query, $webinarId)
    {
        return $query->where('webinar_session_id', $webinarId);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}
