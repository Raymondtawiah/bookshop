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
     * Get the current price based on day of the week
     * Sunday to Tuesday: ₵30 (Early Registration)
     * Wednesday onwards: ₵50 (Late Registration)
     */
    public function getCurrentPriceAttribute(): float
    {
        $now = now();
        $dayOfWeek = $now->dayOfWeek; // 0 = Sunday, 1 = Monday, ..., 6 = Saturday
        
        // Sunday (0) to Tuesday (2)
        if ($dayOfWeek >= 0 && $dayOfWeek <= 2) {
            return 30.00;
        }
        
        // Wednesday (3) onwards
        return 50.00;
    }

    /**
     * Get the price tier description
     */
    public function getPriceTierAttribute(): string
    {
        $currentPrice = $this->current_price;
        
        if ($currentPrice == 30.00) {
            return 'Early Registration';
        }
        
        return 'Late Registration';
    }

    /**
     * Check if current price is early bird pricing
     */
    public function isEarlyBirdPricing(): bool
    {
        return $this->current_price == 30.00;
    }

    /**
     * Check if payments are currently accepted
     * Payments only accepted Sunday (0) to Thursday (4)
     * Friday (5) and Saturday (6) - payments closed
     */
    public function arePaymentsOpen(): bool
    {
        $dayOfWeek = now()->dayOfWeek; // 0 = Sunday, 1 = Monday, ..., 6 = Saturday
        
        // Payments accepted Sunday (0) through Thursday (4)
        // Payments closed Friday (5) and Saturday (6)
        return $dayOfWeek >= 0 && $dayOfWeek <= 4;
    }

    /**
     * Get payment status message
     */
    public function getPaymentStatusMessage(): string
    {
        if ($this->arePaymentsOpen()) {
            return 'Registration is currently open. Secure your spot now!';
        }
        
        return 'Registration is closed for this week. Please check back on Sunday for next week\'s webinar.';
    }
}
