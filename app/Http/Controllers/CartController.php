<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Nationality;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Add item to cart
     */
    public function addToCart(Request $request)
    {
        $request->validate([
            'product_name' => 'required|string|max:255',
            'unit_price_usd' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'book_id' => 'nullable|exists:books,id',
        ]);

        // Check if item already exists in cart
        if ($request->book_id) {
            $existingItem = Cart::where('user_id', Auth::id())
                ->where('book_id', $request->book_id)
                ->first();

            if ($existingItem) {
                // Update quantity instead of creating new item
                $existingItem->quantity += $request->quantity;
                $existingItem->save();

                return redirect()->back()->with('success', 'Item quantity updated in cart!');
            }
        }

        $cartItem = Cart::create([
            'user_id' => Auth::id(),
            'book_id' => $request->book_id,
            'product_name' => $request->product_name,
            'unit_price_usd' => $request->unit_price_usd,
            'quantity' => $request->quantity,
        ]);

        return redirect()->back()->with('success', 'Item added to cart successfully!');
    }

    /**
     * View cart
     */
    public function viewCart()
    {
        $cartItems = Cart::where('user_id', Auth::id())->with('book')->get();
        $total = $cartItems->sum(function ($item) {
            return $item->unit_price_usd * $item->quantity;
        });

        return view('cart.index', compact('cartItems', 'total'));
    }

    /**
     * Update cart item quantity
     */
    public function updateQuantity(Request $request, $id)
    {
        $cartItem = Cart::where('id', $id)->where('user_id', Auth::id())->first();

        if ($cartItem) {
            $cartItem->quantity = $request->quantity;
            $cartItem->save();
        }

        return redirect()->back();
    }

    /**
     * Remove item from cart
     */
    public function removeFromCart($id)
    {
        $cartItem = Cart::where('id', $id)->where('user_id', Auth::id())->first();

        if ($cartItem) {
            $cartItem->delete();
        }

        return redirect()->back()->with('success', 'Item removed from cart');
    }

    /**
     * Proceed to checkout
     */
    public function checkout()
    {
        $cartItems = Cart::where('user_id', Auth::id())
            ->with('book')
            ->get();

        $total = $cartItems->sum(function ($item) {
            return $item->unit_price_usd * $item->quantity;
        });

        $nationalities = Nationality::orderBy('name')->get();

        return view('cart.checkout', compact('cartItems', 'total', 'nationalities'));
    }

    /**
     * Get cart count for the current user
     */
    public function getCartCount()
    {
        if (Auth::check()) {
            return Cart::where('user_id', Auth::id())->sum('quantity');
        }

        return 0;
    }
}
