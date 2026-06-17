<?php

namespace App\Services;

use App\Models\Discount;

class DiscountService
{
    public function applyDiscount(float $originalPrice, string $code): array
    {
        $discount = Discount::findByCode($code);

        if (! $discount) {
            return [
                'success' => false,
                'message' => 'Invalid or expired discount code',
                'discounted_price' => $originalPrice,
            ];
        }

        if ($discount->type !== 'ebook') {
            return [
                'success' => false,
                'message' => 'Discount code is not valid for e-books',
                'discounted_price' => $originalPrice,
            ];
        }

        $discountAmount = $discount->calculateDiscount($originalPrice);
        $discountedPrice = max(0, $originalPrice - $discountAmount);

        return [
            'success' => true,
            'discount' => $discount,
            'discount_amount' => $discountAmount,
            'discounted_price' => $discountedPrice,
        ];
    }
}
