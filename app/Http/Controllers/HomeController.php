<?php

/*
 * =========================================================================
 * © 2026 Erebus Development Team
 * Author: AnonymousUser9183
 * =========================================================================
 * Home Controller - Dashboard with XMR Pricing & Advertisements
 * =========================================================================
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Popup;
use App\Models\Advertisement;
use App\Models\FeaturedProduct;
use App\Models\Product;
use App\Http\Controllers\XmrPriceController;

class HomeController extends Controller
{
    /**
     * Show the home page with advertisements and featured products
     *
     * @param XmrPriceController $xmrPriceController
     * @return View
     */
    public function index(XmrPriceController $xmrPriceController): View
    {
        try {
            Log::debug('Loading home page', ['user_id' => Auth::id()]);

            // Get active popup (always show if available)
            $popup = Popup::getActive();

            // Get active advertisements
            $advertisements = Advertisement::getActiveAdvertisements();

            // Get current XMR price for conversion
            $xmrPrice = $xmrPriceController->getXmrPrice();

            Log::debug('XMR price retrieved', ['price' => $xmrPrice]);

            // Organize advertisements by slot, skipping ads with deleted products
            $adSlots = $this->organizeAdvertisements($advertisements, (float) $xmrPrice);

            // Get featured products
            $featuredProducts = FeaturedProduct::getAllFeaturedProducts();

            // Format featured products similar to advertisements
            $formattedFeaturedProducts = $this->formatFeaturedProducts($featuredProducts, (float) $xmrPrice);

            Log::debug('Home page data loaded', [
                'ad_slots' => count($adSlots),
                'featured_products' => count($formattedFeaturedProducts),
            ]);

            return view('home', [
                'username' => Auth::user()->username,
                'popup' => $popup,
                'adSlots' => $adSlots,
                'featuredProducts' => $formattedFeaturedProducts,
            ]);

        } catch (\Exception $e) {
            Log::error('Error loading home page', [
                'user_id' => Auth::id(),
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Return home view with fallback data
            return view('home', [
                'username' => Auth::user()->username ?? 'Guest',
                'popup' => null,
                'adSlots' => [],
                'featuredProducts' => [],
            ])->with('error', 'Some homepage content could not be loaded.');
        }
    }

    /**
     * Organize advertisements by slot number, filtering out deleted products
     *
     * @param \Illuminate\Database\Eloquent\Collection $advertisements
     * @param float|int|null $xmrPrice
     * @return array<int, array>
     */
    private function organizeAdvertisements($advertisements, float|int|null $xmrPrice): array
    {
        $adSlots = [];
        $measurementUnits = Product::getMeasurementUnits();

        foreach ($advertisements as $ad) {
            // Skip advertisements where product is soft-deleted
            if (!$ad->product || $ad->product->trashed()) {
                Log::warning('Skipping advertisement with deleted product', [
                    'ad_id' => $ad->id,
                    'product_id' => $ad->product_id ?? null,
                ]);
                continue;
            }

            // Get the formatted measurement unit
            $formattedMeasurementUnit = $measurementUnits[$ad->product->measurement_unit] 
                ?? $ad->product->measurement_unit;

            // Format product price in XMR
            $productXmrPrice = $this->calculateXmrPrice($ad->product->price, $xmrPrice);

            // Get formatted options with XMR price
            $formattedBulkOptions = $ad->product->getFormattedBulkOptions($xmrPrice);
            $formattedDeliveryOptions = $ad->product->getFormattedDeliveryOptions($xmrPrice);

            $adSlots[$ad->slot_number] = [
                'product' => $ad->product,
                'vendor' => $ad->product->user,
                'ends_at' => $ad->ends_at,
                'measurement_unit' => $formattedMeasurementUnit,
                'xmr_price' => $productXmrPrice,
                'bulk_options' => $formattedBulkOptions,
                'delivery_options' => $formattedDeliveryOptions,
            ];

            Log::debug('Advertisement organized', [
                'slot' => $ad->slot_number,
                'product_id' => $ad->product_id,
            ]);
        }

        return $adSlots;
    }

    /**
     * Format featured products with XMR pricing
     *
     * @param \Illuminate\Database\Eloquent\Collection $featuredProducts
     * @param float|int|null $xmrPrice
     * @return array<int, array>
     */
    private function formatFeaturedProducts($featuredProducts, float|int|null $xmrPrice): array
    {
        $formattedFeaturedProducts = [];
        $measurementUnits = Product::getMeasurementUnits();

        foreach ($featuredProducts as $featured) {
            // Skip featured products where product is soft-deleted
            if (!$featured->product || $featured->product->trashed()) {
                Log::warning('Skipping featured product with deleted product', [
                    'featured_id' => $featured->id,
                    'product_id' => $featured->product_id ?? null,
                ]);
                continue;
            }

            // Get the formatted measurement unit
            $formattedMeasurementUnit = $measurementUnits[$featured->product->measurement_unit] 
                ?? $featured->product->measurement_unit;

            // Format product price in XMR
            $productXmrPrice = $this->calculateXmrPrice($featured->product->price, $xmrPrice);

            // Get formatted options with XMR price
            $formattedBulkOptions = $featured->product->getFormattedBulkOptions($xmrPrice);
            $formattedDeliveryOptions = $featured->product->getFormattedDeliveryOptions($xmrPrice);

            $formattedFeaturedProducts[] = [
                'product' => $featured->product,
                'vendor' => $featured->product->user,
                'measurement_unit' => $formattedMeasurementUnit,
                'xmr_price' => $productXmrPrice,
                'bulk_options' => $formattedBulkOptions,
                'delivery_options' => $formattedDeliveryOptions,
            ];

            Log::debug('Featured product formatted', [
                'product_id' => $featured->product_id,
            ]);
        }

        return $formattedFeaturedProducts;
    }

    /**
     * Calculate XMR price from fiat price
     *
     * @param float|int $price
     * @param float|int|null $xmrPrice
     * @return float|null
     */
    private function calculateXmrPrice(float|int $price, float|int|null $xmrPrice): float|null
    {
        if (is_numeric($xmrPrice) && $xmrPrice > 0) {
            return (float)($price / $xmrPrice);
        }

        return null;
    }
}
