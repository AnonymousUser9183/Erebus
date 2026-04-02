<?php

/*
 * =========================================================================
 * © 2026 Erebus Development Team
 * Author: AnonymousUser9183
 * =========================================================================
 * Cart Controller - Shopping Cart Management
 * =========================================================================
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;

class CartController extends Controller
{
    /**
     * Show cart items
     */
    public function index(): View
    {
        $cartItems = Cart::where('user_id', auth()->id())
            ->with('product')
            ->get();

        $total = $cartItems->sum(fn($item) => $item->product->price * $item->quantity);

        return view('cart.index', compact('cartItems', 'total'));
    }

    /**
     * Add item to cart
     */
    public function store(Request $request, Product $product): RedirectResponse
    {
        try {
            $validated = $request->validate([
                'quantity' => 'required|integer|min:1|max:100',
            ]);

            // Check if product is available
            if ($product->stock < $validated['quantity']) {
                return redirect()->back()
                    ->with('error', 'Not enough stock available.');
            }

            $cartItem = Cart::where('user_id', auth()->id())
                ->where('product_id', $product->id)
                ->first();

            if ($cartItem) {
                $cartItem->increment('quantity', $validated['quantity']);
            } else {
                Cart::create([
                    'user_id' => auth()->id(),
                    'product_id' => $product->id,
                    'quantity' => $validated['quantity'],
                ]);
            }

            Log::info('Item added to cart', [
                'user_id' => auth()->id(),
                'product_id' => $product->id,
                'quantity' => $validated['quantity'],
            ]);

            return redirect()->back()
                ->with('success', 'Item added to cart.');

        } catch (ValidationException $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors($e->validator->errors());
        }
    }

    /**
     * Update cart item
     */
    public function update(Request $request, Cart $cart): RedirectResponse
    {
        try {
            // Verify ownership
            if ($cart->user_id !== auth()->id()) {
                return redirect()->back()
                    ->with('error', 'Unauthorized access.');
            }

            $validated = $request->validate([
                'quantity' => 'required|integer|min:1|max:100',
            ]);

            $cart->update(['quantity' => $validated['quantity']]);

            Log::info('Cart item updated', [
                'user_id' => auth()->id(),
                'cart_id' => $cart->id,
                'quantity' => $validated['quantity'],
            ]);

            return redirect()->back()
                ->with('success', 'Cart updated.');

        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator->errors());
        }
    }

    /**
     * Remove item from cart
     */
    public function destroy(Cart $cart): RedirectResponse
    {
        // Verify ownership
        if ($cart->user_id !== auth()->id()) {
            return redirect()->back()
                ->with('error', 'Unauthorized access.');
        }

        $cart->delete();

        Log::info('Item removed from cart', [
            'user_id' => auth()->id(),
            'cart_id' => $cart->id,
        ]);

        return redirect()->back()
            ->with('success', 'Item removed from cart.');
    }

    /**
     * Clear entire cart
     */
    public function clear(): RedirectResponse
    {
        Cart::where('user_id', auth()->id())->delete();

        Log::info('Cart cleared', ['user_id' => auth()->id()]);

        return redirect()->route('cart.index')
            ->with('success', 'Cart cleared.');
    }

    /**
     * Save message for vendor
     */
    public function saveMessage(Request $request, Cart $cart): RedirectResponse
    {
        try {
            if ($cart->user_id !== auth()->id()) {
                return redirect()->back()
                    ->with('error', 'Unauthorized access.');
            }

            $validated = $request->validate([
                'message' => 'required|string|max:500',
            ]);

            $cart->update(['message' => $validated['message']]);

            Log::info('Cart message saved', [
                'user_id' => auth()->id(),
                'cart_id' => $cart->id(),
            ]);

            return redirect()->back()
                ->with('success', 'Message saved.');

        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator->errors());
        }
    }

    /**
     * Proceed to checkout
     */
    public function checkout(Request $request): View|RedirectResponse
    {
        try {
            $cartItems = Cart::where('user_id', auth()->id())
                ->with('product')
                ->get();

            if ($cartItems->isEmpty()) {
                return redirect()->route('cart.index')
                    ->with('error', 'Cart is empty.');
            }

            // Verify all items are in stock
            foreach ($cartItems as $item) {
                if ($item->product->stock < $item->quantity) {
                    Log::warning('Insufficient stock for checkout', [
                        'product_id' => $item->product_id,
                        'required' => $item->quantity,
                        'available' => $item->product->stock,
                    ]);

                    return redirect()->route('cart.index')
                        ->with('error', 'Some items are no longer available in the required quantity.');
                }
            }

            $total = $cartItems->sum(fn($item) => $item->product->price * $item->quantity);

            Log::debug('Checkout initiated', [
                'user_id' => auth()->id(),
                'items' => $cartItems->count(),
                'total' => $total,
            ]);

            return view('cart.checkout', compact('cartItems', 'total'));

        } catch (\Exception $e) {
            Log::error('Error during checkout', [
                'user_id' => auth()->id(),
                'message' => $e->getMessage(),
            ]);

            return redirect()->route('cart.index')
                ->with('error', 'An error occurred during checkout.');
        }
    }
}
