<?php

/*
 * =========================================================================
 * © 2026 Erebus Development Team
 * Author: AnonymousUser9183
 * =========================================================================
 * Wishlist Controller - User Product Wishlist Management
 * =========================================================================
 */

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\LengthAwarePaginator;

class WishlistController extends Controller
{
    /**
     * Products per page in wishlist
     */
    private const PRODUCTS_PER_PAGE = 12;

    /**
     * Display user's wishlist with pagination
     *
     * @return View|RedirectResponse
     */
    public function index(): View|RedirectResponse
    {
        try {
            Log::debug('Loading user wishlist', ['user_id' => Auth::id()]);

            $user = Auth::user();

            // Get wishlisted products with eager loading
            $wishlistedProducts = $user->wishlist()
                ->with(['user:id,username', 'category:id,name'])
                ->paginate(self::PRODUCTS_PER_PAGE);

            Log::debug('Wishlist loaded', [
                'user_id' => Auth::id(),
                'product_count' => $wishlistedProducts->count(),
            ]);

            return view('wishlist', [
                'products' => $wishlistedProducts,
                'title' => 'My Wishlist',
            ]);

        } catch (\Exception $e) {
            Log::error('Error loading wishlist', [
                'user_id' => Auth::id(),
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->route('products.index')
                ->with('error', 'An error occurred while loading your wishlist.');
        }
    }

    /**
     * Add product to user's wishlist
     *
     * @param Product $product
     * @return RedirectResponse
     */
    public function store(Product $product): RedirectResponse
    {
        try {
            Log::debug('Adding product to wishlist', [
                'user_id' => Auth::id(),
                'product_id' => $product->id,
            ]);

            $user = Auth::user();

            // Check if product is active
            if (!$product->active) {
                Log::warning('Attempt to wishlist inactive product', [
                    'user_id' => Auth::id(),
                    'product_id' => $product->id,
                ]);

                return back()->with('error', 'This product is no longer available.');
            }

            // Check if already wishlisted
            if ($user->hasWishlisted($product->id)) {
                Log::info('Product already wishlisted', [
                    'user_id' => Auth::id(),
                    'product_id' => $product->id,
                ]);

                return back()->with('error', 'This product is already in your wishlist.');
            }

            // Add to wishlist
            $user->wishlist()->attach($product->id);

            Log::info('Product added to wishlist', [
                'user_id' => Auth::id(),
                'product_id' => $product->id,
            ]);

            return back()->with('success', 'Product added to your wishlist.');

        } catch (\Exception $e) {
            Log::error('Error adding product to wishlist', [
                'user_id' => Auth::id(),
                'product_id' => $product->id ?? 'unknown',
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->with('error', 'An error occurred while adding the product to your wishlist.');
        }
    }

    /**
     * Remove product from user's wishlist
     *
     * @param Product $product
     * @return RedirectResponse
     */
    public function destroy(Product $product): RedirectResponse
    {
        try {
            Log::debug('Removing product from wishlist', [
                'user_id' => Auth::id(),
                'product_id' => $product->id,
            ]);

            $user = Auth::user();

            // Verify ownership (user can only remove from their own wishlist)
            if (!$user->hasWishlisted($product->id)) {
                Log::warning('Attempt to remove non-wishlisted product', [
                    'user_id' => Auth::id(),
                    'product_id' => $product->id,
                ]);

                return back()->with('error', 'This product is not in your wishlist.');
            }

            // Remove from wishlist
            $user->wishlist()->detach($product->id);

            Log::info('Product removed from wishlist', [
                'user_id' => Auth::id(),
                'product_id' => $product->id,
            ]);

            return back()->with('success', 'Product removed from your wishlist.');

        } catch (\Exception $e) {
            Log::error('Error removing product from wishlist', [
                'user_id' => Auth::id(),
                'product_id' => $product->id ?? 'unknown',
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->with('error', 'An error occurred while removing the product from your wishlist.');
        }
    }

    /**
     * Clear all products from user's wishlist
     *
     * @return RedirectResponse
     */
    public function clearAll(): RedirectResponse
    {
        try {
            Log::debug('Clearing entire wishlist', ['user_id' => Auth::id()]);

            $user = Auth::user();

            // Get count before clearing
            $countBefore = $user->wishlist()->count();

            // Clear wishlist
            $user->wishlist()->detach();

            Log::info('Wishlist cleared', [
                'user_id' => Auth::id(),
                'products_removed' => $countBefore,
            ]);

            return back()->with('success', 'Your wishlist has been cleared.');

        } catch (\Exception $e) {
            Log::error('Error clearing wishlist', [
                'user_id' => Auth::id(),
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->with('error', 'An error occurred while clearing your wishlist.');
        }
    }
}
