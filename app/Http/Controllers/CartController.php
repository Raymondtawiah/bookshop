<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Book;
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
            'product_price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'book_id' => 'nullable|exists:books,id',
        ]);

        $cartItem = Cart::create([
            'user_id' => Auth::id(),
            'book_id' => $request->book_id,
            'product_name' => $request->product_name,
            'product_price' => $request->product_price,
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
        $total = $cartItems->sum(function($item) {
            return $item->product_price * $item->quantity;
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
            
        $total = $cartItems->sum(function($item) {
            return $item->product_price * $item->quantity;
        });

        return view('cart.checkout', compact('cartItems', 'total'));
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
