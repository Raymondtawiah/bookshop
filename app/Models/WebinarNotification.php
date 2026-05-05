<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WebinarNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'webinar_id',
        'title',
        'message',
        'type',
        'is_active',
        'expires_at',
        'created_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'expires_at' => 'datetime',
    ];

    public function webinar()
    {
        return $this->belongsTo(Webinar::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                    ->where(function ($q) {
                        $q->whereNull('expires_at')
                          ->orWhere('expires_at', '>', now());
                    });
    }

    public function scopeForWebinar($query, $webinarId)
    {
        return $query->where('webinar_id', $webinarId);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function recipients()
    {
        return $this->hasMany(NotificationRecipient::class, 'webinar_notification_id');
    }

    public function sentRecipients()
    {
        return $this->recipients()->sent();
    }

    public function failedRecipients()
    {
        return $this->recipients()->failed();
    }
}
