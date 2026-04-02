<?php

/*
 * =========================================================================
 * © 2026 Erebus Development Team
 * Author: AnonymousUser9183
 * =========================================================================
 * Vendors Controller - Vendor Listing & Profile Display
 * =========================================================================
 */

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Product;
use App\Models\ProductReviews;
use App\Models\Dispute;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Exception;

class VendorsController extends Controller
{
    /**
     * Products per page in vendor listings
     */
    private const PRODUCTS_PER_PAGE = 8;

    /**
     * Reviews per page on vendor profile
     */
    private const REVIEWS_PER_PAGE = 8;

    /**
     * Display listing of all vendors
     *
     * @return View
     */
    public function index(): View
    {
        try {
            Log::debug('Fetching vendors list');

            // Get all vendors with vendor role and profiles
            $vendors = User::whereHas('roles', function ($query): void {
                $query->where('name', 'vendor');
            })
                ->with('profile')
                ->get(['id', 'username']);

            Log::debug('Vendors list retrieved', ['vendor_count' => $vendors->count()]);

            return view('vendors.index', compact('vendors'));

        } catch (Exception $e) {
            Log::error('Error fetching vendors list', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return view('vendors.index', [
                'vendors' => collect(),
                'error' => 'Unable to load vendors at this time.',
            ]);
        }
    }

    /**
     * Display vendor profile with products and reviews
     *
     * @param string $username
     * @return View|RedirectResponse
     */
    public function show(string $username): View|RedirectResponse
    {
        try {
            Log::debug('Loading vendor profile', ['username' => $username]);

            // Get vendor with relationships
            $vendor = User::whereHas('roles', function ($query): void {
                $query->where('name', 'vendor');
            })
                ->with(['vendorProfile', 'profile', 'pgpKey'])
                ->where('username', $username)
                ->firstOrFail(['id', 'username']);

            Log::debug('Vendor found', ['vendor_id' => $vendor->id, 'username' => $username]);

            // Check if vendor is in vacation mode
            if ($vendor->vendorProfile?->vacation_mode) {
                Log::info('Vendor in vacation mode', ['vendor_id' => $vendor->id]);

                return view('vendors.show', [
                    'vendor' => $vendor,
                    'vacation_mode' => true,
                    'products' => collect(),
                ]);
            }

            // Get vendor's active products
            $products = Product::where('user_id', $vendor->id)
                ->active()
                ->latest()
                ->paginate(self::PRODUCTS_PER_PAGE);

            Log::debug('Vendor products retrieved', [
                'vendor_id' => $vendor->id,
                'product_count' => $products->count(),
            ]);

            // Calculate review statistics
            $reviewStats = $this->calculateVendorReviewStatistics($vendor->id);

            // Calculate dispute statistics
            $disputeStats = $this->calculateVendorDisputeStatistics($vendor->id);

            // Get product IDs for this vendor
            $productIds = Product::where('user_id', $vendor->id)
                ->pluck('id')
                ->toArray();

            // Get all reviews for vendor's products
            $allReviews = collect();
            if (!empty($productIds)) {
                $allReviews = ProductReviews::whereIn('product_id', $productIds)
                    ->with(['user:id,username', 'product:id,name,slug'])
                    ->orderBy('created_at', 'desc')
                    ->paginate(self::REVIEWS_PER_PAGE, ['*'], 'reviews_page');

                Log::debug('Vendor reviews retrieved', [
                    'vendor_id' => $vendor->id,
                    'review_count' => $allReviews->count(),
                ]);
            }

            return view('vendors.show', [
                'vendor' => $vendor,
                'vacation_mode' => false,
                'products' => $products,
                'positiveCount' => $reviewStats['positive'],
                'mixedCount' => $reviewStats['mixed'],
                'negativeCount' => $reviewStats['negative'],
                'totalReviews' => $reviewStats['total'],
                'positivePercentage' => $reviewStats['positivePercentage'],
                'allReviews' => $allReviews,
                'disputesWon' => $disputeStats['won'],
                'disputesOpen' => $disputeStats['open'],
                'disputesLost' => $disputeStats['lost'],
                'totalDisputes' => $disputeStats['total'],
            ]);

        } catch (Exception $e) {
            Log::error('Error fetching vendor details', [
                'username' => $username,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->route('vendors.index')
                ->with('error', 'Vendor not found or unavailable.');
        }
    }

    /**
     * Calculate review statistics for vendor's products
     *
     * @param int $vendorId
     * @return array<string, int|float|null>
     */
    private function calculateVendorReviewStatistics(int $vendorId): array
    {
        try {
            Log::debug('Calculating review statistics', ['vendor_id' => $vendorId]);

            // Get product IDs for this vendor
            $productIds = Product::where('user_id', $vendorId)
                ->pluck('id')
                ->toArray();

            // If no products, return zero statistics
            if (empty($productIds)) {
                Log::debug('No products found for vendor', ['vendor_id' => $vendorId]);

                return [
                    'positive' => 0,
                    'mixed' => 0,
                    'negative' => 0,
                    'total' => 0,
                    'positivePercentage' => null,
                ];
            }

            // Count reviews by sentiment
            $reviewCounts = ProductReviews::whereIn('product_id', $productIds)
                ->select('sentiment', DB::raw('count(*) as count'))
                ->groupBy('sentiment')
                ->pluck('count', 'sentiment')
                ->toArray();

            // Extract sentiment counts
            $positiveCount = $reviewCounts[ProductReviews::SENTIMENT_POSITIVE] ?? 0;
            $mixedCount = $reviewCounts[ProductReviews::SENTIMENT_MIXED] ?? 0;
            $negativeCount = $reviewCounts[ProductReviews::SENTIMENT_NEGATIVE] ?? 0;
            $totalReviews = $positiveCount + $mixedCount + $negativeCount;

            // Calculate positive percentage
            $positivePercentage = $totalReviews > 0
                ? ($positiveCount / $totalReviews) * 100
                : null;

            Log::debug('Review statistics calculated', [
                'vendor_id' => $vendorId,
                'positive' => $positiveCount,
                'mixed' => $mixedCount,
                'negative' => $negativeCount,
                'total' => $totalReviews,
                'positive_percentage' => $positivePercentage,
            ]);

            return [
                'positive' => $positiveCount,
                'mixed' => $mixedCount,
                'negative' => $negativeCount,
                'total' => $totalReviews,
                'positivePercentage' => $positivePercentage,
            ];

        } catch (Exception $e) {
            Log::error('Error calculating review statistics', [
                'vendor_id' => $vendorId,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'positive' => 0,
                'mixed' => 0,
                'negative' => 0,
                'total' => 0,
                'positivePercentage' => null,
            ];
        }
    }

    /**
     * Calculate dispute statistics for vendor
     *
     * @param int $vendorId
     * @return array<string, int>
     */
    private function calculateVendorDisputeStatistics(int $vendorId): array
    {
        try {
            Log::debug('Calculating dispute statistics', ['vendor_id' => $vendorId]);

            // Get all disputes for this vendor
            $disputes = Dispute::getVendorDisputes($vendorId);

            // If no disputes, return zero statistics
            if ($disputes->isEmpty()) {
                Log::debug('No disputes found for vendor', ['vendor_id' => $vendorId]);

                return [
                    'won' => 0,
                    'open' => 0,
                    'lost' => 0,
                    'total' => 0,
                ];
            }

            // Count disputes by status
            $wonCount = $disputes->where('status', Dispute::STATUS_VENDOR_PREVAILS)->count();
            $openCount = $disputes->where('status', Dispute::STATUS_ACTIVE)->count();
            $lostCount = $disputes->where('status', Dispute::STATUS_BUYER_PREVAILS)->count();
            $totalDisputes = $disputes->count();

            Log::debug('Dispute statistics calculated', [
                'vendor_id' => $vendorId,
                'won' => $wonCount,
                'open' => $openCount,
                'lost' => $lostCount,
                'total' => $totalDisputes,
            ]);

            return [
                'won' => $wonCount,
                'open' => $openCount,
                'lost' => $lostCount,
                'total' => $totalDisputes,
            ];

        } catch (Exception $e) {
            Log::error('Error calculating dispute statistics', [
                'vendor_id' => $vendorId,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'won' => 0,
                'open' => 0,
                'lost' => 0,
                'total' => 0,
            ];
        }
    }
}
