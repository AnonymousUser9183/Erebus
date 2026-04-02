<?php

/*
 * =========================================================================
 * © 2026 Erebus Development Team
 * Author: AnonymousUser9183
 * =========================================================================
 * Product Controller - Product Browsing, Search, Filtering, & Display
 * =========================================================================
 */

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\ProductReviews;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Exception;
use finfo;

class ProductController extends Controller
{
    /**
     * Allowed MIME types for product pictures
     */
    private const ALLOWED_MIME_TYPES = [
        'image/jpeg',
        'image/png',
        'image/gif',
        'image/webp',
    ];

    /**
     * Products per page
     */
    private const PRODUCTS_PER_PAGE = 12;

    /**
     * Maximum search term length
     */
    private const MAX_SEARCH_LENGTH = 80;

    /**
     * Maximum vendor name length
     */
    private const MAX_VENDOR_LENGTH = 16;

    /**
     * Default product picture filename
     */
    private const DEFAULT_PICTURE = 'default-product-picture.png';

    /**
     * Display paginated product listing with search and filters
     *
     * @param Request $request
     * @return View|Response
     */
    public function index(Request $request): View|Response
    {
        try {
            Log::debug('Loading products index', [
                'search' => $request->get('search'),
                'vendor' => $request->get('vendor'),
                'type' => $request->get('type'),
                'category' => $request->get('category'),
                'sort_price' => $request->get('sort_price'),
            ]);

            // Validate request inputs
            try {
                $validated = $request->validate($this->getProductIndexValidationRules());

            } catch (ValidationException $e) {
                Log::warning('Validation failed on products index', [
                    'errors' => $e->validator->errors()->all(),
                ]);

                return redirect()
                    ->route('products.index')
                    ->with('error', $e->validator->errors()->first())
                    ->withInput();
            }

            // Extract non-empty filters
            $filters = $this->extractFilters($validated);

            // Build query with relationships and visibility filters
            $query = $this->buildProductQuery($filters);

            // Apply sorting or randomization
            $query = $this->applySorting($query, $filters);

            // Get paginated results
            $products = $query->paginate(self::PRODUCTS_PER_PAGE)->withQueryString();

            // Load categories for filter dropdown
            $categories = $this->loadCategoryHierarchy();

            Log::debug('Products index loaded', [
                'total_products' => $products->total(),
                'current_page' => $products->currentPage(),
                'filters_applied' => count($filters),
            ]);

            return view('products.index', [
                'products' => $products,
                'categories' => $categories,
                'currentType' => $filters['type'] ?? null,
                'filters' => $filters,
            ]);

        } catch (Exception $e) {
            Log::error('Error loading products index', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()
                ->route('products.index')
                ->with('error', 'An error occurred while processing your request.');
        }
    }

    /**
     * Display single product with details and reviews
     *
     * @param Product $product
     * @param XmrPriceController $xmrPriceController
     * @return View|Response
     */
    public function show(Product $product, XmrPriceController $xmrPriceController): View|Response
    {
        try {
            Log::debug('Loading product details', [
                'product_id' => $product->id,
                'product_name' => $product->name,
            ]);

            // Verify product is active
            if (!$product->active) {
                Log::warning('Attempt to view inactive product', [
                    'product_id' => $product->id,
                    'user_id' => Auth::id(),
                ]);

                abort(404);
            }

            // Load necessary relationships
            $product->load([
                'user:id,username',
                'user.vendorProfile:id,user_id,vacation_mode,private_shop_mode,vendor_policy',
                'category:id,name',
            ]);

            // Check vendor vacation mode
            if ($this->isVendorOnVacation($product)) {
                Log::info('Viewing product from vendor on vacation', [
                    'product_id' => $product->id,
                    'vendor_id' => $product->user_id,
                ]);

                return view('products.show', [
                    'product' => $product,
                    'title' => $product->name,
                    'vendor_on_vacation' => true,
                    'vendor_shop_private' => false,
                ]);
            }

            // Check vendor private shop mode
            if ($this->isVendorShopPrivate($product)) {
                if (!$this->userHasPrivateShopAccess($product->user_id)) {
                    Log::info('Viewing product from private vendor without access', [
                        'product_id' => $product->id,
                        'vendor_id' => $product->user_id,
                        'user_id' => Auth::id(),
                    ]);

                    return view('products.show', [
                        'product' => $product,
                        'title' => $product->name,
                        'vendor_on_vacation' => false,
                        'vendor_shop_private' => true,
                    ]);
                }
            }

            // Get XMR price and calculate
            $xmrPrice = $this->calculateXmrPrice($product, $xmrPriceController);

            // Get measurement unit
            $measurementUnits = Product::getMeasurementUnits();
            $formattedMeasurementUnit = $measurementUnits[$product->measurement_unit] ?? $product->measurement_unit;

            // Format options with XMR pricing
            $formattedBulkOptions = $product->getFormattedBulkOptions($xmrPrice);
            $formattedDeliveryOptions = $product->getFormattedDeliveryOptions($xmrPrice);

            // Load reviews and statistics
            $reviews = ProductReviews::getProductReviews($product->id);
            $reviewStats = $this->getReviewStatistics($product);

            Log::debug('Product details loaded', [
                'product_id' => $product->id,
                'total_reviews' => $reviewStats['totalReviews'],
            ]);

            return view('products.show', [
                'product' => $product,
                'title' => $product->name,
                'vendor_on_vacation' => false,
                'vendor_shop_private' => false,
                'xmrPrice' => $xmrPrice,
                'formattedMeasurementUnit' => $formattedMeasurementUnit,
                'formattedBulkOptions' => $formattedBulkOptions,
                'formattedDeliveryOptions' => $formattedDeliveryOptions,
                'reviews' => $reviews,
                'positivePercentage' => $reviewStats['positivePercentage'],
                'positiveCount' => $reviewStats['positiveCount'],
                'mixedCount' => $reviewStats['mixedCount'],
                'negativeCount' => $reviewStats['negativeCount'],
                'totalReviews' => $reviewStats['totalReviews'],
            ]);

        } catch (Exception $e) {
            Log::error('Error loading product details', [
                'product_id' => $product->id ?? 'unknown',
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()
                ->route('products.index')
                ->with('error', 'An error occurred while loading the product.');
        }
    }

    /**
     * Display product picture
     *
     * @param string $filename
     * @return Response
     */
    public function showPicture(string $filename)
    {
        try {
            // Verify authentication
            if (!Auth::check()) {
                Log::warning('Unauthorized product picture access attempt', [
                    'filename' => $filename,
                    'ip' => request()->ip(),
                ]);

                abort(403, 'Unauthorized access.');
            }

            Log::debug('Retrieving product picture', [
                'filename' => $filename,
                'user_id' => Auth::id(),
            ]);

            // Serve default picture from public directory
            if ($filename === self::DEFAULT_PICTURE) {
                return response()->file(public_path('images/' . self::DEFAULT_PICTURE));
            }

            // Sanitize filename to prevent path traversal
            if ($this->containsPathTraversal($filename)) {
                Log::warning('Path traversal attempt detected', [
                    'filename' => $filename,
                    'user_id' => Auth::id(),
                ]);

                throw new Exception('Invalid filename.');
            }

            // Check file exists in storage
            $path = 'private/product_pictures/' . $filename;

            if (!Storage::disk('private')->exists($path)) {
                Log::warning('Product picture file not found', [
                    'filename' => $filename,
                    'user_id' => Auth::id(),
                ]);

                throw new Exception('Product picture not found.');
            }

            // Get file and validate MIME type
            $file = Storage::disk('private')->get($path);
            $mimeType = $this->getFileMimeType($file);

            if (!in_array($mimeType, self::ALLOWED_MIME_TYPES)) {
                Log::warning('Invalid MIME type for product picture', [
                    'filename' => $filename,
                    'mime_type' => $mimeType,
                    'user_id' => Auth::id(),
                ]);

                throw new Exception('Invalid file type.');
            }

            Log::debug('Product picture served successfully', [
                'filename' => $filename,
                'mime_type' => $mimeType,
                'user_id' => Auth::id(),
            ]);

            // Create response with proper headers
            return response($file, 200)
                ->header('Content-Type', $mimeType)
                ->header('Cache-Control', 'public, max-age=86400');

        } catch (Exception $e) {
            Log::error('Error retrieving product picture', [
                'filename' => $filename,
                'user_id' => Auth::id(),
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Fallback to default picture on error
            return response()->file(public_path('images/' . self::DEFAULT_PICTURE));
        }
    }

    // ==================== PRIVATE HELPER METHODS ====================

    /**
     * Get product index validation rules
     *
     * @return array<string, array<int|string, mixed>>
     */
    private function getProductIndexValidationRules(): array
    {
        return [
            'search' => ['nullable', 'string', 'min:1', 'max:' . self::MAX_SEARCH_LENGTH],
            'vendor' => ['nullable', 'string', 'min:1', 'max:' . self::MAX_VENDOR_LENGTH],
            'type' => ['nullable', Rule::in([Product::TYPE_DIGITAL, Product::TYPE_CARGO, Product::TYPE_DEADDROP])],
            'category' => ['nullable', 'integer', 'exists:categories,id'],
            'sort_price' => ['nullable', Rule::in(['asc', 'desc'])],
        ];
    }

    /**
     * Extract non-empty filters from validated data
     *
     * @param array<string, mixed> $validated
     * @return array<string, mixed>
     */
    private function extractFilters(array $validated): array
    {
        return collect($validated)
            ->filter(function ($value) {
                return $value !== null && $value !== '';
            })
            ->toArray();
    }

    /**
     * Build product query with relationships and visibility filters
     *
     * @param array<string, mixed> $filters
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function buildProductQuery(array $filters)
    {
        $query = Product::with(['user' => function ($query) {
            $query->select('id', 'username');
        }])
            ->select('products.*')
            ->active()
            ->whereHas('user', function ($query) {
                $query->whereDoesntHave('vendorProfile', function ($q) {
                    $q->where(function ($subQuery) {
                        $subQuery->where('vacation_mode', true)
                            ->orWhere(function ($privShop) {
                                $privShop->where('private_shop_mode', true)
                                    ->whereNotExists(function ($ref) {
                                        $ref->select(DB::raw(1))
                                            ->from('private_shops')
                                            ->whereColumn('private_shops.vendor_id', 'users.id')
                                            ->where('private_shops.user_id', Auth::id() ?? 0);
                                    });
                            });
                    });
                })
                    ->orWhereHas('vendorProfile', function ($q) {
                        $q->where('vacation_mode', false)
                            ->where(function ($subQuery) {
                                $subQuery->where('private_shop_mode', false)
                                    ->orWhereExists(function ($ref) {
                                        $ref->select(DB::raw(1))
                                            ->from('private_shops')
                                            ->whereColumn('private_shops.vendor_id', 'users.id')
                                            ->where('private_shops.user_id', Auth::id() ?? 0);
                                    });
                            });
                    });
            });

        // Apply search filter
        if (isset($filters['search'])) {
            $searchTerm = $this->sanitizeSearchTerm($filters['search']);
            $query->where('name', 'like', '%' . addcslashes($searchTerm, '%_') . '%');
        }

        // Apply vendor filter
        if (isset($filters['vendor'])) {
            $vendorTerm = $this->sanitizeSearchTerm($filters['vendor']);
            $query->whereHas('user', function ($q) use ($vendorTerm) {
                $q->where('username', 'like', '%' . addcslashes($vendorTerm, '%_') . '%');
            });
        }

        // Apply product type filter
        if (isset($filters['type'])) {
            $query->ofType($filters['type']);
        }

        // Apply category filter
        if (isset($filters['category'])) {
            $query->where('category_id', (int) $filters['category']);
        }

        return $query;
    }

    /**
     * Apply sorting to product query
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array<string, mixed> $filters
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function applySorting($query, array $filters)
    {
        if (isset($filters['sort_price'])) {
            return $query->orderBy('price', $filters['sort_price']);
        }

        // Default: randomize product order
        return $query->inRandomOrder();
    }

    /**
     * Load full category hierarchy
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function loadCategoryHierarchy()
    {
        return Category::where('level', 1)
            ->with(['children' => function ($query) {
                $query->with('children');
            }])
            ->orderBy('name')
            ->get();
    }

    /**
     * Check if vendor is on vacation
     *
     * @param Product $product
     * @return bool
     */
    private function isVendorOnVacation(Product $product): bool
    {
        return $product->user->vendorProfile && $product->user->vendorProfile->vacation_mode;
    }

    /**
     * Check if vendor shop is private
     *
     * @param Product $product
     * @return bool
     */
    private function isVendorShopPrivate(Product $product): bool
    {
        return $product->user->vendorProfile && $product->user->vendorProfile->private_shop_mode;
    }

    /**
     * Check if user has access to private shop
     *
     * @param int $vendorId
     * @return bool
     */
    private function userHasPrivateShopAccess(int $vendorId): bool
    {
        if (!Auth::check()) {
            return false;
        }

        return DB::table('private_shops')
            ->where('user_id', Auth::id())
            ->where('vendor_id', $vendorId)
            ->exists();
    }

    /**
     * Calculate XMR price for product
     *
     * @param Product $product
     * @param XmrPriceController $xmrPriceController
     * @return float|string
     */
    private function calculateXmrPrice(Product $product, XmrPriceController $xmrPriceController)
    {
        try {
            $xmrPrice = $xmrPriceController->getXmrPrice();

            if (is_numeric($xmrPrice) && $xmrPrice > 0) {
                return $product->price / $xmrPrice;
            }

            return $xmrPrice; // Return 'UNAVAILABLE' or error message
        } catch (Exception $e) {
            Log::warning('Error calculating XMR price', [
                'product_id' => $product->id,
                'message' => $e->getMessage(),
            ]);

            return 'UNAVAILABLE';
        }
    }

    /**
     * Get review statistics for product
     *
     * @param Product $product
     * @return array<string, int|float>
     */
    private function getReviewStatistics(Product $product): array
    {
        return [
            'positivePercentage' => $product->getPositiveReviewPercentage(),
            'positiveCount' => $product->getPositiveReviewsCount(),
            'mixedCount' => $product->getMixedReviewsCount(),
            'negativeCount' => $product->getNegativeReviewsCount(),
            'totalReviews' => $product->getPositiveReviewsCount() +
                              $product->getMixedReviewsCount() +
                              $product->getNegativeReviewsCount(),
        ];
    }

    /**
     * Sanitize search term by removing HTML tags
     *
     * @param string $term
     * @return string
     */
    private function sanitizeSearchTerm(string $term): string
    {
        return strip_tags($term);
    }

    /**
     * Check for path traversal attempts
     *
     * @param string $filename
     * @return bool
     */
    private function containsPathTraversal(string $filename): bool
    {
        return strpos($filename, '..') !== false || strpos($filename, '/') !== false;
    }

    /**
     * Get file MIME type using finfo
     *
     * @param string $fileContent
     * @return string
     */
    private function getFileMimeType(string $fileContent): string
    {
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        return $finfo->buffer($fileContent);
    }
}
