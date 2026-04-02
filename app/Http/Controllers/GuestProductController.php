<?php

/*
 * =========================================================================
 * © 2026 Erebus Development Team
 * Author: AnonymousUser9183
 * =========================================================================
 * Guest Product Controller - Public Product Listing & Details
 * =========================================================================
 */

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class GuestProductController extends Controller
{
    /**
     * Display a listing of guest-accessible products with filtering
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        try {
            Log::debug('Guest product index accessed', [
                'ip' => $request->ip(),
                'filters' => $request->query(),
            ]);

            // Get all categories
            $categories = Category::whereNull('parent_id')
                ->with('children.children')
                ->get();

            // Base query - only active products from available vendors
            $query = Product::active()
                ->whereHas('user', function ($q) {
                    $q->where(function ($subQuery) {
                        $subQuery->whereDoesntHave('vendorProfile', function ($vendorQuery) {
                            $vendorQuery->where('vacation_mode', true);
                        })->orWhereDoesntHave('vendorProfile');
                    });
                })
                ->whereHas('user', function ($q) {
                    $q->where(function ($subQuery) {
                        $subQuery->whereDoesntHave('vendorProfile', function ($vendorQuery) {
                            $vendorQuery->where('private_shop_mode', true);
                        })->orWhereDoesntHave('vendorProfile');
                    });
                })
                ->with('user', 'category');

            // Build filters array
            $filters = $this->applyFilters($query, $request);
            $currentType = $filters['type'] ?? null;

            // Apply sorting
            $this->applySorting($query, $request, $filters);

            $products = $query->paginate(20);

            Log::debug('Products fetched', [
                'count' => $products->count(),
                'filters' => $filters,
            ]);

            return view('auth.guest-products-index', [
                'products' => $products,
                'categories' => $categories,
                'filters' => $filters,
                'currentType' => $currentType,
            ]);

        } catch (\Exception $e) {
            Log::error('Error loading guest products', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'ip' => $request->ip(),
            ]);

            return view('auth.guest-products-index', [
                'products' => collect(),
                'categories' => Category::whereNull('parent_id')
                    ->with('children.children')
                    ->get(),
                'filters' => [],
                'currentType' => null,
            ])->with('error', 'An error occurred while loading products.');
        }
    }

    /**
     * Apply filters to product query
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param Request $request
     * @return array<string, mixed>
     */
    private function applyFilters($query, Request $request): array
    {
        $filters = [];

        // Search by product name
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%{$search}%");
            $filters['search'] = $search;
            Log::debug('Applied search filter', ['search' => $search]);
        }

        // Filter by vendor username
        if ($request->filled('vendor')) {
            $vendor = $request->input('vendor');
            $query->whereHas('user', function ($q) use ($vendor) {
                $q->where('username', 'like', "%{$vendor}%");
            });
            $filters['vendor'] = $vendor;
            Log::debug('Applied vendor filter', ['vendor' => $vendor]);
        }

        // Filter by product type
        if ($request->filled('type')) {
            $type = $request->input('type');
            if (in_array($type, ['digital', 'cargo', 'deaddrop'])) {
                $query->where('type', $type);
                $filters['type'] = $type;
                Log::debug('Applied type filter', ['type' => $type]);
            }
        }

        // Filter by category
        if ($request->filled('category')) {
            $category = $request->input('category');
            $query->where('category_id', $category);
            $filters['category'] = $category;
            Log::debug('Applied category filter', ['category' => $category]);
        }

        return $filters;
    }

    /**
     * Apply sorting to product query
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param Request $request
     * @param array $filters
     * @return void
     */
    private function applySorting($query, Request $request, array &$filters): void
    {
        if ($request->filled('sort_price')) {
            $sort = $request->input('sort_price');
            if ($sort === 'asc') {
                $query->orderBy('price', 'asc');
                $filters['sort_price'] = 'asc';
                Log::debug('Applied price sort', ['direction' => 'ascending']);
            } elseif ($sort === 'desc') {
                $query->orderBy('price', 'desc');
                $filters['sort_price'] = 'desc';
                Log::debug('Applied price sort', ['direction' => 'descending']);
            }
        } else {
            // Default: most recent first
            $query->orderBy('created_at', 'desc');
        }
    }

    /**
     * Display the specified product details
     *
     * @param string $id
     * @return View
     */
    public function show(string $id): View
    {
        try {
            Log::debug('Guest accessing product', [
                'product_id' => $id,
                'ip' => request()->ip(),
            ]);

            // Fetch product by ID
            $product = Product::with('user.profile', 'category', 'user.vendorProfile')
                ->findOrFail($id);

            // Check vendor status
            $vendor_on_vacation = $product->user->vendorProfile?->vacation_mode ?? false;
            $vendor_shop_private = $product->user->vendorProfile?->private_shop_mode ?? false;

            // Get product reviews
            $reviews = $product->reviews()
                ->with('user.profile')
                ->get();

            // Calculate review statistics
            $reviewStats = $this->calculateReviewStats($product, $reviews);

            // Get XMR price
            $xmrPrice = $this->calculateXmrPrice($product);

            Log::debug('Product loaded successfully', [
                'product_id' => $product->id,
                'reviews_count' => $reviews->count(),
            ]);

            return view('auth.guest-products-show', [
                'product' => $product,
                'reviews' => $reviews,
                'totalReviews' => $reviewStats['total'],
                'positiveCount' => $reviewStats['positive'],
                'mixedCount' => $reviewStats['mixed'],
                'negativeCount' => $reviewStats['negative'],
                'positivePercentage' => $reviewStats['positive_percentage'],
                'xmrPrice' => $xmrPrice,
                'vendor_on_vacation' => $vendor_on_vacation,
                'vendor_shop_private' => $vendor_shop_private,
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::warning('Product not found', [
                'product_id' => $id,
                'ip' => request()->ip(),
            ]);
            abort(404, 'Product not found');

        } catch (\Exception $e) {
            Log::error('Error loading product details', [
                'product_id' => $id,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'ip' => request()->ip(),
            ]);
            abort(500, 'An error occurred while loading the product');
        }
    }

    /**
     * Calculate review statistics for product
     *
     * @param Product $product
     * @param Collection $reviews
     * @return array<string, int>
     */
    private function calculateReviewStats(Product $product, Collection $reviews): array
    {
        $totalReviews = $reviews->count();
        $positiveCount = $product->getPositiveReviewsCount();
        $mixedCount = $product->getMixedReviewsCount();
        $negativeCount = $product->getNegativeReviewsCount();

        $positivePercentage = $totalReviews > 0
            ? round(($positiveCount / $totalReviews) * 100)
            : 0;

        return [
            'total' => $totalReviews,
            'positive' => $positiveCount,
            'mixed' => $mixedCount,
            'negative' => $negativeCount,
            'positive_percentage' => $positivePercentage,
        ];
    }

    /**
     * Calculate XMR price for product
     *
     * @param Product $product
     * @return string|null
     */
    private function calculateXmrPrice(Product $product): string|null
    {
        try {
            $xmrPrice = Cache::get('xmr_price');
            if ($xmrPrice) {
                return number_format($product->price / $xmrPrice, 4);
            }
            return null;
        } catch (\Exception $e) {
            Log::warning('Error calculating XMR price', [
                'message' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Serve product picture to guests
     *
     * @param string $filename
     * @return \Illuminate\Http\Response
     */
    public function showPicture(string $filename): \Illuminate\Http\Response
    {
        try {
            Log::debug('Guest accessing product picture', [
                'filename' => substr($filename, 0, 20) . '...',
                'ip' => request()->ip(),
            ]);

            // Security: prevent directory traversal
            if (strpos($filename, '..') !== false || strpos($filename, '/') !== false) {
                Log::warning('Directory traversal attempt detected', [
                    'filename' => $filename,
                    'ip' => request()->ip(),
                ]);
                abort(403, 'Forbidden');
            }

            // Default image
            if ($filename === 'default-product-picture.png') {
                return response()->file(public_path('images/default-product-picture.png'));
            }

            // Check if file exists in private storage
            $path = storage_path('app/private/product_pictures/' . $filename);

            if (!file_exists($path)) {
                Log::debug('Product picture not found, serving default', [
                    'filename' => $filename,
                ]);
                return response()->file(public_path('images/default-product-picture.png'));
            }

            // Get MIME type
            $mimeType = mime_content_type($path);

            // Validate MIME type (only allow image types)
            $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            if (!in_array($mimeType, $allowedMimes)) {
                Log::warning('Invalid MIME type for product picture', [
                    'filename' => $filename,
                    'mime_type' => $mimeType,
                    'ip' => request()->ip(),
                ]);
                abort(403, 'Invalid file type');
            }

            Log::debug('Product picture served', [
                'filename' => substr($filename, 0, 20) . '...',
                'mime_type' => $mimeType,
            ]);

            return response()->file($path, [
                'Content-Type' => $mimeType,
                'Cache-Control' => 'public, max-age=31536000',
            ]);

        } catch (\Exception $e) {
            Log::error('Error serving product picture', [
                'filename' => $filename,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'ip' => request()->ip(),
            ]);
            return response()->file(public_path('images/default-product-picture.png'));
        }
    }
}
