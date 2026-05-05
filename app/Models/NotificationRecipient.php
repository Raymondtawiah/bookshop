<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NotificationRecipient extends Model
{
    use HasFactory;

    protected $fillable = [
        'webinar_notification_id',
        'user_id',
        'webinar_registration_id',
        'status',
        'sent_at',
        'error_message',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    public function notification()
    {
        return $this->belongsTo(WebinarNotification::class, 'webinar_notification_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function webinarRegistration()
    {
        return $this->belongsTo(WebinarRegistration::class);
    }

    public function scopeSent($query)
    {
        return $query->where('status', 'sent');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}
