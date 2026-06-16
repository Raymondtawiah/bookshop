<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $fillable = ['code', 'type', 'percentage', 'starts_at', 'ends_at', 'is_active'];

    protected $casts = [
        'percentage' => 'decimal:2',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public static function findByCode(string $code): ?self
    {
        return static::where('code', $code)
            ->where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('starts_at')
                    ->orWhere('starts_at', '<=', now());
            })
            ->where(function ($query) {
                $query->whereNull('ends_at')
                    ->orWhere('ends_at', '>=', now());
            })
            ->first();
    }

    public function calculateDiscount(float $price): float
    {
        return round($price * ($this->percentage / 100), 2);
    }
}
