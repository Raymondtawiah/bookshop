<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WebinarSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'webinar_link',
        'price',
        'scheduled_at',
        'duration_minutes',
        'status',
        'created_by',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'scheduled_at' => 'datetime',
        'duration_minutes' => 'integer',
    ];

    public function registrations(): HasMany
    {
        return $this->hasMany(WebinarRegistration::class, 'webinar_id');
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(WebinarNotification::class, 'webinar_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('scheduled_at', '>', now())->orderBy('scheduled_at', 'asc');
    }

    public function getTotalPaidRegistrationsAttribute(): int
    {
        return $this->registrations()->where('payment_status', 'paid')->count();
    }

    public function getTotalRegistrationsAttribute(): int
    {
        return $this->registrations()->count();
    }

    /**
     * Fixed price for all webinars: ₵100.
     */
    public function getCurrentPriceAttribute(): float
    {
        return 100.00;
    }

    /**
     * Price tier — fixed label for the new flat price.
     */
    public function getPriceTierAttribute(): string
    {
        return '₵100 Webinar';
    }

    /**
     * No early-bird pricing anymore — always false.
     */
    public function isEarlyBirdPricing(): bool
    {
        return false;
    }

    /**
     * Check if payments are currently accepted.
     * Open: Sunday through Wednesday (any hour), Thursday before noon.
     * Closed: Thursday from 12:00 (noon), Friday all day, Saturday all day.
     */
    public function arePaymentsOpen(): bool
    {
        $dayOfWeek = now()->dayOfWeek; // 0 = Sunday, 1 = Monday, ..., 6 = Saturday

        // Friday (5) and Saturday (6) — always closed
        if ($dayOfWeek >= 5) {
            return false;
        }

        // Thursday (4) — closed from 12:00 (noon) onwards
        if ($dayOfWeek === 4 && now()->hour >= 12) {
            return false;
        }

        // Sunday (0) to Wednesday (3) and Thursday morning — open
        return true;
    }

    /**
     * Get payment status message.
     */
    public function getPaymentStatusMessage(): string
    {
        if ($this->arePaymentsOpen()) {
            return 'Registration is currently open. Secure your spot now!';
        }

        $dayOfWeek = now()->dayOfWeek;

        // Thursday afternoon or later in the week
        if ($dayOfWeek === 4) {
            return 'Registration is closed. The webinar takes place every Friday. Registration will reopen on Sunday.';
        }

        // Friday or Saturday
        if ($dayOfWeek >= 5) {
            return 'Registration is closed for this weekend. Registration reopens on Sunday.';
        }

        return 'Registration is currently closed. Please check back on Sunday for next week\'s webinar.';
    }
}
