<?php

namespace App\Services;

use App\Models\ExchangeRate;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ExchangeRateService
{
    protected const CACHE_KEY = 'exchange_rate_usd_ghs';

    protected const CACHE_TTL_MINUTES = 1440;

    public function getUsdToGhsRate(): float
    {
        return ExchangeRate::getUsdToGhsRate();
    }

    public function convertUsdToGhs(float $amountUsd): float
    {
        $rate = $this->getUsdToGhsRate();

        return round($amountUsd * $rate, 2);
    }

    public function lockRateForOrder(float $amountUsd): array
    {
        return ExchangeRate::lockRateForOrder($amountUsd);
    }

    public function refreshRate(): ?float
    {
        $newRate = $this->fetchLatestRate();

        if ($newRate && $newRate > 0) {
            ExchangeRate::updateOrCreate(
                ['from_currency' => 'USD', 'to_currency' => 'GHS'],
                [
                    'exchange_rate' => $newRate,
                    'last_updated' => now(),
                ]
            );

            Cache::put(self::CACHE_KEY, $newRate, now()->addMinutes(self::CACHE_TTL_MINUTES));

            return $newRate;
        }

        return null;
    }

    protected function fetchLatestRate(): ?float
    {
        try {
            $response = Http::timeout(10)->get('https://api.exchangerate.host/latest', [
                'base' => 'USD',
                'symbols' => 'GHS',
            ]);

            if ($response->successful() && $response->json('success')) {
                $rate = $response->json('rates.GHS');
                if ($rate && $rate > 0) {
                    return (float) $rate;
                }
            }
        } catch (\Exception $e) {
            Log::error('Failed to fetch exchange rate', ['error' => $e->getMessage()]);
        }

        return null;
    }
}
