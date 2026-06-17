<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class ExchangeRateService
{
    /**
     * Get the current GHS to USD exchange rate.
     *
     * Priority:
     * 1. Cached value (1 hour)
     * 2. Environment variable (fixed rate)
     * 3. Live API fetch (exchangerate.host)
     *
     * @return float Exchange rate (1 USD = X GHS)
     */
    public function getGhsToUsdRate(): float
    {
        // Check cache first (1 hour)
        $cached = Cache::get('exchange_rate_ghs_to_usd');
        if ($cached) {
            return (float) $cached;
        }

        // 1. Try env variable (fixed rate)
        $envRate = env('EXCHANGE_RATE_GHS_TO_USD');
        if ($envRate && is_numeric($envRate)) {
            $rate = (float) $envRate;
            Cache::put('exchange_rate_ghs_to_usd', $rate, 3600);

            return $rate;
        }

        // 2. Try live API (free: exchangerate.host)
        try {
            $response = Http::get('https://api.exchangerate.host/latest?base=GHS&symbols=USD');
            if ($response->ok()) {
                $data = $response->json();
                if (isset($data['rates']['USD'])) {
                    $rate = (float) $data['rates']['USD'];
                    Cache::put('exchange_rate_ghs_to_usd', $rate, 3600);

                    return $rate;
                }
            }
        } catch (\Exception $e) {
            // Ignore and fall back
        }

        // 3. Fallback to config default
        $default = config('settings.usd_to_ghs_rate', 12.50);
        // Since we want 1 USD = X GHS, we need reciprocal if config is USD→GHS
        // config('settings.usd_to_ghs_rate') = 12.50 means 1 USD = 12.50 GHS
        // So GHS→USD rate = 1/12.50 = 0.08
        // But we store rate as "GHS per USD" for clarity
        $rate = $default > 1 ? $default : 1 / $default;

        Cache::put('exchange_rate_ghs_to_usd', $rate, 3600);

        return $rate;
    }

    /**
     * Convert GHS amount to USD cents (smallest unit for Stripe)
     *
     * @param  float  $amountGhs  Amount in GHS
     * @return int Amount in USD cents
     */
    public function convertGhsToUsdCents(float $amountGhs): int
    {
        $rate = $this->getGhsToUsdRate(); // e.g., 12.50 GHS per USD
        $amountUsd = $amountGhs / $rate;   // e.g., 70 / 12.50 = 5.60 USD
        $cents = (int) round($amountUsd * 100); // 560 cents

        return max(100, $cents); // Minimum 1 USD (100 cents) to avoid zero
    }

    /**
     * Convert GHS amount to USD (rounded to 2 decimals)
     *
     * @return float Amount in USD
     */
    public function convertGhsToUsd(float $amountGhs): float
    {
        $rate = $this->getGhsToUsdRate();
        $amountUsd = $amountGhs / $rate;

        return round($amountUsd, 2);
    }

    /**
     * Get display format for currency
     */
    public function getCurrencySymbol(string $currency): string
    {
        return match ($currency) {
            'USD' => '$',
            'EUR' => '€',
            'GBP' => '£',
            default => '$',
        };
    }
}
