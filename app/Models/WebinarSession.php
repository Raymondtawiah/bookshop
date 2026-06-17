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
        'is_registration_open',
        'is_visible',
        'created_by',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'scheduled_at' => 'datetime',
        'duration_minutes' => 'integer',
        'is_registration_open' => 'boolean',
        'is_visible' => 'boolean',
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

    public function scopeVisible($query)
    {
        return $query->where('is_visible', true);
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
     * Fixed price for all webinars: $9.99.
     */
    public function getCurrentPriceAttribute(): float
    {
        return 9.99;
    }

    /**
     * Price tier — fixed label for the new flat price.
     */
    public function getPriceTierAttribute(): string
    {
        return '$9.99 Webinar';
    }

    /**
     * No early-bird pricing anymore — always false.
     */
    public function isEarlyBirdPricing(): bool
    {
        return false;
    }

    /**
     * Get payment status message.
     */
    public function getPaymentStatusMessage(): string
    {
        if (! $this->is_registration_open) {
            return 'Registration is currently closed by the administrator.';
        }

        return 'Registration is currently open. Secure your spot now!';
    }

    /**
     * Check if webinar registration is open (manual toggle)
     */
    public function registrationIsOpen(): bool
    {
        return $this->is_registration_open === true;
    }
}
