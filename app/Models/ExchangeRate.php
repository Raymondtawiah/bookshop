<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExchangeRate extends Model
{
    use HasFactory;

    protected $fillable = [
        'from_currency',
        'to_currency',
        'exchange_rate',
        'last_updated',
    ];

    protected $casts = [
        'exchange_rate' => 'decimal:4',
        'last_updated' => 'datetime',
    ];

    public static function getUsdToGhsRate(): float
    {
        $rate = self::where('from_currency', 'USD')
            ->where('to_currency', 'GHS')
            ->first();

        if ($rate) {
            return (float) $rate->exchange_rate;
        }

        return (float) config('settings.usd_to_ghs_rate', 12.50);
    }

    public static function lockRateForOrder(float $amountUsd): array
    {
        $rate = self::getUsdToGhsRate();
        $amountGhs = round($amountUsd * $rate, 2);

        return [
            'exchange_rate' => $rate,
            'total_usd' => $amountUsd,
            'total_ghs' => $amountGhs,
            'locked_at' => now(),
        ];
    }
}
