<?php

namespace App\Services;

use App\Models\Cart;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

/**
 * Cart Service - Handles all cart-related business logic
 *
 * Single Responsibility: This service is responsible only for cart operations
 * Following SOLID principles by separating cart logic from controllers and models
 */
class CartService
{
    /**
     * Get the count of unique items in the user's cart
     */
    public function getItemCount(): int
    {
        return $this->getUserCart()->count();
    }

    /**
     * Get the total quantity of all items in the user's cart
     */
    public function getTotalQuantity(): int
    {
        return (int) $this->getUserCart()->sum('quantity');
    }

    /**
     * Get all cart items for the current user
     *
     * @return Collection
     */
    public function getCartItems()
    {
        return $this->getUserCart()->get();
    }

    /**
     * Check if user has items in cart
     */
    public function hasItems(): bool
    {
        return $this->getItemCount() > 0;
    }

    /**
     * Get the total price of all items in cart
     */
    public function getTotalPrice(): float
    {
        return (float) $this->getUserCart()
            ->selectRaw('SUM(unit_price * quantity) as total')
            ->value('total') ?? 0;
    }

    /**
     * Get the user's cart query builder
     *
     * @return Builder
     */
    private function getUserCart()
    {
        return Cart::where('user_id', Auth::id());
    }
}
