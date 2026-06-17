<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class WebinarRegistration extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'webinar_id',
        'user_id',
        'full_name',
        'email',
        'phone',
        'registration_status',
        'payment_status',
        'transaction_reference',
        'amount_paid',
        'paid_at',
        'joined_at',
        'access_token',
        'access_token_expires_at',
        'last_reminder_sent',
        'reminder_count',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'joined_at' => 'datetime',
        'access_token_expires_at' => 'datetime',
        'last_reminder_sent' => 'datetime',
        'reminder_count' => 'integer',
        'deleted_at' => 'datetime',
    ];

    public const STATUS_PENDING = 'pending';

    public const STATUS_PAID = 'paid';

    public function webinar(): BelongsTo
    {
        return $this->belongsTo(WebinarSession::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isPaid(): bool
    {
        return $this->payment_status === self::STATUS_PAID;
    }

    /**
     * Get the attendance status based on joined_at and webinar time
     */
    public function getAttendanceStatusAttribute(): string
    {
        if (! $this->joined_at) {
            // Check if webinar has already passed
            if ($this->webinar && $this->webinar->scheduled_at && now()->greaterThan($this->webinar->scheduled_at)) {
                return 'absent';
            }

            return 'not_started';
        }

        return 'attended';
    }
}
