<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Webinar extends Model
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
        return $this->hasMany(WebinarRegistration::class);
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

    public function notifications()
    {
        return $this->hasMany(WebinarNotification::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
