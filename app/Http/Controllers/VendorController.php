<?php

/*
 * =========================================================================
 * © 2026 Erebus Development Team
 * Author: AnonymousUser9183
 * =========================================================================
 * Vendor Controller - Vendor Dashboard, Products, Orders, & Advertisements
 * =========================================================================
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Models\VendorProfile;
use App\Models\Product;
use App\Models\Category;
use App\Models\Advertisement;
use App\Models\Orders;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use MoneroIntegrations\MoneroPhp\walletRPC;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Writer\PngWriter;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Intervention\Image\Encoders\JpegEncoder;
use Intervention\Image\Encoders\PngEncoder;
use Intervention\Image\Encoders\WebpEncoder;
use Intervention\Image\Encoders\GifEncoder;
use Intervention\Image\Exceptions\NotReadableException;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Exception;
use finfo;

class VendorController extends Controller
{
    /**
     * Monero wallet RPC instance
     */
    protected walletRPC $walletRPC;

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
     * Products per page in vendor listings
     */
    private const PRODUCTS_PER_PAGE = 12;

    /**
     * Maximum file size for product pictures (in KB)
     */
    private const MAX_FILE_SIZE_KB = 800;

    /**
     * Maximum additional photos per product
     */
    private const MAX_ADDITIONAL_PHOTOS = 3;

    /**
     * Maximum delivery options per product
     */
    private const MAX_DELIVERY_OPTIONS = 4;

    /**
     * Maximum bulk options per product
     */
    private const MAX_BULK_OPTIONS = 8;

    /**
     * Initialize controller with Monero RPC connection
     */
    public function __construct()
    {
        $config = config('monero');

        try {
            $this->walletRPC = new walletRPC(
                $config['host'],
                $config['port'],
                $config['ssl']
            );

            Log::debug('Monero RPC connection initialized', [
                'host' => $config['host'],
                'port' => $config['port'],
            ]);

        } catch (Exception $e) {
            Log::error('Failed to initialize Monero RPC connection', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }

    /**
     * Display vendor dashboard
     *
     * @return View
     */
    public function index(): View
    {
        try {
            Log::debug('Loading vendor dashboard', ['vendor_id' => Auth::id()]);
            return view('vendor.index');

        } catch (Exception $e) {
            Log::error('Error loading vendor dashboard', [
                'vendor_id' => Auth::id(),
                'message' => $e->getMessage(),
            ]);
            return view('vendor.index');
        }
    }

    /**
     * Display vendor's sales (orders)
     *
     * @return View
     */
    public function sales(): View
    {
        try {
            Log::debug('Loading vendor sales', ['vendor_id' => Auth::id()]);

            $sales = Orders::getVendorOrders(Auth::id());

            Log::debug('Vendor sales retrieved', [
                'vendor_id' => Auth::id(),
                'order_count' => $sales->count(),
            ]);

            return view('vendor.sales.index', compact('sales'));

        } catch (Exception $e) {
            Log::error('Error loading vendor sales', [
                'vendor_id' => Auth::id(),
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return view('vendor.sales.index', [
                'sales' => collect(),
            ]);
        }
    }

    /**
     * Display sale (order) details
     *
     * @param string $uniqueUrl
     * @return View|RedirectResponse
     */
    public function showSale(string $uniqueUrl): View|RedirectResponse
    {
        try {
            Log::debug('Loading sale details', [
                'vendor_id' => Auth::id(),
                'unique_url' => $uniqueUrl,
            ]);

            // Process auto status changes
            Orders::processAllAutoStatusChanges();

            $sale = Orders::findByUrl($uniqueUrl);

            if (!$sale) {
                Log::warning('Sale not found', [
                    'vendor_id' => Auth::id(),
                    'unique_url' => $uniqueUrl,
                ]);
                abort(404);
            }

            // Verify ownership
            if ($sale->vendor_id !== Auth::id()) {
                Log::warning('Unauthorized access to sale', [
                    'vendor_id' => Auth::id(),
                    'sale_id' => $sale->id,
                    'owner_id' => $sale->vendor_id,
                ]);
                abort(403, 'Unauthorized access.');
            }

            // Check for auto-cancellation (96 hours without marking sent)
            if ($sale->shouldAutoCancelIfNotSent()) {
                $sale->autoCancelIfNotSent();
                $sale->refresh();

                if ($sale->status === Orders::STATUS_CANCELLED) {
                    Log::info('Order auto-cancelled', [
                        'sale_id' => $sale->id,
                        'vendor_id' => Auth::id(),
                    ]);

                    return redirect()->route('vendor.sales.show', $sale->unique_url)
                        ->with('info', 'This order has been automatically cancelled because it was not marked as sent within 96 hours (4 days) after payment.');
                }
            }

            // Check for auto-completion (192 hours without marking confirmed)
            if ($sale->shouldAutoCompleteIfNotConfirmed()) {
                $sale->autoCompleteIfNotConfirmed();
                $sale->refresh();

                if ($sale->status === Orders::STATUS_COMPLETED) {
                    Log::info('Order auto-completed', [
                        'sale_id' => $sale->id,
                        'vendor_id' => Auth::id(),
                    ]);

                    return redirect()->route('vendor.sales.show', $sale->unique_url)
                        ->with('info', 'This order has been automatically marked as completed because it was not confirmed within 192 hours (8 days) after being marked as sent.');
                }
            }

            // Calculate total items accounting for bulk options
            $totalItems = $this->calculateOrderTotalItems($sale);

            Log::debug('Sale details loaded', [
                'sale_id' => $sale->id,
                'total_items' => $totalItems,
            ]);

            return view('vendor.sales.show', [
                'sale' => $sale,
                'totalItems' => $totalItems,
            ]);

        } catch (Exception $e) {
            Log::error('Error loading sale details', [
                'vendor_id' => Auth::id(),
                'unique_url' => $uniqueUrl,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->route('vendor.sales')
                ->with('error', 'Failed to load sale details.');
        }
    }

    /**
     * Update delivery text for order items
     *
     * @param Request $request
     * @param string $uniqueUrl
     * @return RedirectResponse
     */
    public function updateDeliveryText(Request $request, string $uniqueUrl): RedirectResponse
    {
        try {
            Log::debug('Updating delivery text', [
                'vendor_id' => Auth::id(),
                'unique_url' => $uniqueUrl,
            ]);

            $sale = Orders::findByUrl($uniqueUrl);

            if (!$sale) {
                Log::warning('Sale not found for delivery text update', [
                    'vendor_id' => Auth::id(),
                    'unique_url' => $uniqueUrl,
                ]);
                abort(404);
            }

            // Verify ownership
            if ($sale->vendor_id !== Auth::id()) {
                Log::warning('Unauthorized delivery text update', [
                    'vendor_id' => Auth::id(),
                    'sale_id' => $sale->id,
                ]);
                abort(403, 'Unauthorized access.');
            }

            // Verify order status
            if ($sale->status !== Orders::STATUS_PAYMENT_RECEIVED) {
                return redirect()->route('vendor.sales.show', $sale->unique_url)
                    ->with('error', 'Delivery information can only be updated for orders with "Payment Received" status.');
            }

            // Validate request
            try {
                $request->validate([
                    'delivery_text.*' => 'required|string|min:8|max:800',
                ], [
                    'delivery_text.*.required' => 'Delivery information is required for each product.',
                    'delivery_text.*.min' => 'Delivery information must be at least 8 characters.',
                    'delivery_text.*.max' => 'Delivery information cannot exceed 800 characters.',
                ]);

            } catch (ValidationException $e) {
                Log::warning('Validation failed on delivery text update', [
                    'vendor_id' => Auth::id(),
                    'sale_id' => $sale->id,
                    'errors' => $e->validator->errors()->all(),
                ]);

                return redirect()->back()
                    ->withInput()
                    ->with('error', $e->validator->errors()->first());
            }

            // Update delivery text for each item
            foreach ($sale->items as $item) {
                $productId = $item->product_id;
                if (isset($request->delivery_text[$productId])) {
                    $item->update([
                        'delivery_text' => $request->delivery_text[$productId],
                    ]);
                }
            }

            Log::info('Delivery text updated', [
                'sale_id' => $sale->id,
                'vendor_id' => Auth::id(),
                'items_updated' => count($sale->items),
            ]);

            return redirect()->route('vendor.sales.show', $sale->unique_url)
                ->with('success', 'Delivery information has been updated successfully.');

        } catch (Exception $e) {
            Log::error('Error updating delivery text', [
                'vendor_id' => Auth::id(),
                'unique_url' => $uniqueUrl,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update delivery information.');
        }
    }

    /**
     * Display vendor appearance form
     *
     * @return View
     */
    public function showAppearance(): View
    {
        try {
            Log::debug('Loading vendor appearance form', ['vendor_id' => Auth::id()]);

            $user = Auth::user();
            $vendorProfile = $user->vendorProfile ?? new VendorProfile();

            return view('vendor.appearance', compact('vendorProfile'));

        } catch (Exception $e) {
            Log::error('Error loading vendor appearance form', [
                'vendor_id' => Auth::id(),
                'message' => $e->getMessage(),
            ]);

            return view('vendor.appearance', [
                'vendorProfile' => new VendorProfile(),
            ]);
        }
    }

    /**
     * Update vendor appearance settings
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function updateAppearance(Request $request): RedirectResponse
    {
        try {
            Log::debug('Updating vendor appearance', ['vendor_id' => Auth::id()]);

            // Validate request
            try {
                $request->validate([
                    'description' => 'required|string|min:8|max:800',
                    'vendor_policy' => 'nullable|string|min:8|max:1600',
                    'vacation_mode' => 'required|in:0,1',
                    'private_shop_mode' => 'required|in:0,1',
                ], [
                    'description.required' => 'A description is required.',
                    'description.min' => 'Description must be at least 8 characters.',
                    'description.max' => 'Description cannot exceed 800 characters.',
                    'vendor_policy.min' => 'Vendor policy must be at least 8 characters.',
                    'vendor_policy.max' => 'Vendor policy cannot exceed 1600 characters.',
                    'vacation_mode.in' => 'Invalid vacation mode value.',
                    'private_shop_mode.in' => 'Invalid private shop mode value.',
                ]);

            } catch (ValidationException $e) {
                Log::warning('Validation failed on appearance update', [
                    'vendor_id' => Auth::id(),
                    'errors' => $e->validator->errors()->all(),
                ]);

                return redirect()->back()
                    ->withInput()
                    ->with('error', $e->validator->errors()->first());
            }

            $user = Auth::user();
            $vendorProfile = $user->vendorProfile ?? new VendorProfile();

            if (!$user->vendorProfile) {
                $vendorProfile->user_id = $user->id;
            }

            $vendorProfile->description = $request->description;
            $vendorProfile->vendor_policy = $request->vendor_policy;
            $vendorProfile->vacation_mode = (bool) $request->vacation_mode;
            $vendorProfile->private_shop_mode = (bool) $request->private_shop_mode;
            $vendorProfile->save();

            Log::info('Vendor appearance updated', [
                'vendor_id' => Auth::id(),
                'vacation_mode' => $vendorProfile->vacation_mode,
                'private_shop_mode' => $vendorProfile->private_shop_mode,
            ]);

            return redirect()->route('vendor.appearance')
                ->with('success', 'Vendor settings updated successfully.');

        } catch (Exception $e) {
            Log::error('Error updating vendor appearance', [
                'vendor_id' => Auth::id(),
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update vendor settings.');
        }
    }

    /**
     * Display vendor's products
     *
     * @return View
     */
    public function myProducts(): View
    {
        try {
            Log::debug('Loading vendor products', ['vendor_id' => Auth::id()]);

            $products = Product::where('user_id', Auth::id())
                ->select('id', 'name', 'type', 'slug')
                ->get();

            // Check advertisement status for each product
            foreach ($products as $product) {
                $product->is_advertised = Advertisement::isProductAdvertised($product->id);
            }

            Log::debug('Vendor products retrieved', [
                'vendor_id' => Auth::id(),
                'product_count' => $products->count(),
            ]);

            return view('vendor.my-products', compact('products'));

        } catch (Exception $e) {
            Log::error('Error loading vendor products', [
                'vendor_id' => Auth::id(),
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return view('vendor.my-products', [
                'products' => collect(),
            ]);
        }
    }

    /**
     * Delete a product
     *
     * @param Product $product
     * @return RedirectResponse
     */
    public function destroy(Product $product): RedirectResponse
    {
        try {
            // Verify ownership
            if ($product->user_id !== Auth::id()) {
                Log::warning('Unauthorized product deletion attempt', [
                    'vendor_id' => Auth::id(),
                    'product_id' => $product->id,
                    'owner_id' => $product->user_id,
                ]);
                abort(403, 'Unauthorized action.');
            }

            Log::debug('Deleting product', [
                'vendor_id' => Auth::id(),
                'product_id' => $product->id,
            ]);

            $product->delete();

            Log::info('Product deleted', [
                'vendor_id' => Auth::id(),
                'product_id' => $product->id,
            ]);

            return redirect()->route('vendor.my-products')
                ->with('success', 'Product deleted successfully.');

        } catch (Exception $e) {
            Log::error('Error deleting product', [
                'vendor_id' => Auth::id(),
                'product_id' => $product->id ?? 'unknown',
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->with('error', 'Failed to delete product.');
        }
    }

    /**
     * Show product creation form
     *
     * @param string $type
     * @return View|RedirectResponse
     */
    public function create(string $type): View|RedirectResponse
    {
        try {
            // Validate product type
            if (!in_array($type, [Product::TYPE_CARGO, Product::TYPE_DIGITAL, Product::TYPE_DEADDROP])) {
                Log::warning('Invalid product type on creation', [
                    'vendor_id' => Auth::id(),
                    'type' => $type,
                ]);
                abort(404);
            }

            Log::debug('Loading product creation form', [
                'vendor_id' => Auth::id(),
                'type' => $type,
            ]);

            $categories = Category::with('children')->get();
            $measurementUnits = Product::getMeasurementUnits();
            $countries = $this->loadCountries();

            return view('vendor.products.create', compact('type', 'categories', 'measurementUnits', 'countries'));

        } catch (Exception $e) {
            Log::error('Error loading product creation form', [
                'vendor_id' => Auth::id(),
                'type' => $type,
                'message' => $e->getMessage(),
            ]);

            return redirect()->route('vendor.index')
                ->with('error', 'Failed to load product creation form.');
        }
    }

    /**
     * Store newly created product
     *
     * @param Request $request
     * @param string $type
     * @return RedirectResponse
     */
    public function store(Request $request, string $type): RedirectResponse
    {
        try {
            // Validate product type
            if (!in_array($type, [Product::TYPE_CARGO, Product::TYPE_DIGITAL, Product::TYPE_DEADDROP])) {
                Log::warning('Invalid product type on store', [
                    'vendor_id' => Auth::id(),
                    'type' => $type,
                ]);
                abort(404);
            }

            Log::debug('Creating product', [
                'vendor_id' => Auth::id(),
                'type' => $type,
            ]);

            // Validate request
            $validated = $this->validateProductData($request, $type);

            // Process delivery options
            $deliveryOptions = $this->processDeliveryOptions($request, $type);

            // Process bulk options
            $bulkOptions = $this->processBulkOptions($request);

            // Handle product picture
            $productPicture = 'default-product-picture.png';
            if ($request->hasFile('product_picture')) {
                $productPicture = $this->handleProductPictureUpload($request->file('product_picture'));
            }

            // Handle additional photos
            $additionalPhotos = $this->handleAdditionalPhotosUpload($request);

            // Prepare product data
            $productData = [
                'user_id' => Auth::id(),
                'name' => $validated['name'],
                'description' => $validated['description'],
                'price' => $validated['price'],
                'category_id' => $validated['category_id'],
                'active' => true,
                'product_picture' => $productPicture,
                'stock_amount' => $validated['stock_amount'],
                'measurement_unit' => $validated['measurement_unit'],
                'delivery_options' => $deliveryOptions,
                'bulk_options' => $bulkOptions,
                'ships_from' => $validated['ships_from'],
                'ships_to' => $validated['ships_to'],
                'additional_photos' => $additionalPhotos,
            ];

            // Create product based on type
            $product = match ($type) {
                Product::TYPE_CARGO => Product::createCargo($productData),
                Product::TYPE_DIGITAL => Product::createDigital($productData),
                Product::TYPE_DEADDROP => Product::createDeadDrop($productData),
            };

            $productTypeName = $this->getProductTypeName($type);

            Log::info('Product created', [
                'vendor_id' => Auth::id(),
                'product_id' => $product->id,
                'type' => $type,
            ]);

            return redirect()->route('vendor.index')
                ->with('success', "{$productTypeName} product created successfully.");

        } catch (ValidationException $e) {
            Log::warning('Validation failed on product creation', [
                'vendor_id' => Auth::id(),
                'type' => $type,
                'errors' => $e->validator->errors()->all(),
            ]);

            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('error', 'Please fix the errors below and try again.');

        } catch (Exception $e) {
            Log::error('Failed to create product', [
                'vendor_id' => Auth::id(),
                'type' => $type,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create product. Please try again.');
        }
    }

    /**
     * Show product edit form
     *
     * @param Product $product
     * @return View|RedirectResponse
     */
    public function edit(Product $product): View|RedirectResponse
    {
        try {
            // Verify ownership
            if ($product->user_id !== Auth::id()) {
                Log::warning('Unauthorized product edit attempt', [
                    'vendor_id' => Auth::id(),
                    'product_id' => $product->id,
                ]);
                abort(403, 'Unauthorized action.');
            }

            Log::debug('Loading product edit form', [
                'vendor_id' => Auth::id(),
                'product_id' => $product->id,
            ]);

            $categories = Category::with('children')->get();
            $measurementUnits = Product::getMeasurementUnits();
            $countries = $this->loadCountries();

            return view('vendor.products.edit', compact('product', 'categories', 'measurementUnits', 'countries'));

        } catch (Exception $e) {
            Log::error('Error loading product edit form', [
                'vendor_id' => Auth::id(),
                'product_id' => $product->id ?? 'unknown',
                'message' => $e->getMessage(),
            ]);

            return redirect()->route('vendor.my-products')
                ->with('error', 'Failed to load product edit form.');
        }
    }

    /**
     * Update product
     *
     * @param Request $request
     * @param Product $product
     * @return RedirectResponse
     */
    public function update(Request $request, Product $product): RedirectResponse
    {
        try {
            // Verify ownership
            if ($product->user_id !== Auth::id()) {
                Log::warning('Unauthorized product update attempt', [
                    'vendor_id' => Auth::id(),
                    'product_id' => $product->id,
                ]);
                abort(403, 'Unauthorized action.');
            }

            Log::debug('Updating product', [
                'vendor_id' => Auth::id(),
                'product_id' => $product->id,
            ]);

            // Validate request (no name change for updates)
            $validated = $this->validateProductUpdateData($request);

            // Process delivery options
            $deliveryOptions = $this->processDeliveryOptions($request, $product->type);

            // Process bulk options
            $bulkOptions = $this->processBulkOptions($request);

            // Update product
            $product->update([
                'description' => $validated['description'],
                'price' => $validated['price'],
                'category_id' => $validated['category_id'],
                'stock_amount' => $validated['stock_amount'],
                'measurement_unit' => $validated['measurement_unit'],
                'delivery_options' => $deliveryOptions,
                'bulk_options' => $bulkOptions,
                'ships_from' => $validated['ships_from'],
                'ships_to' => $validated['ships_to'],
                'active' => $request->has('active'),
            ]);

            $productTypeName = $this->getProductTypeName($product->type);

            Log::info('Product updated', [
                'vendor_id' => Auth::id(),
                'product_id' => $product->id,
            ]);

            return redirect()->route('vendor.my-products')
                ->with('success', "{$productTypeName} product updated successfully.");

        } catch (ValidationException $e) {
            Log::warning('Validation failed on product update', [
                'vendor_id' => Auth::id(),
                'product_id' => $product->id,
                'errors' => $e->validator->errors()->all(),
            ]);

            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('error', 'Please fix the errors and try again.');

        } catch (Exception $e) {
            Log::error('Failed to update product', [
                'vendor_id' => Auth::id(),
                'product_id' => $product->id,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update product. Please try again.');
        }
    }

    /**
     * Show advertisement rate limit page
     *
     * @return View
     */
    public function showRateLimit(): View
    {
        try {
            Log::debug('Loading advertisement rate limit page', ['vendor_id' => Auth::id()]);

            $cooldownEnds = Advertisement::getCooldownEndTime(Auth::id());

            return view('vendor.advertisement.rate-limit', compact('cooldownEnds'));

        } catch (Exception $e) {
            Log::error('Error loading rate limit page', [
                'vendor_id' => Auth::id(),
                'message' => $e->getMessage(),
            ]);

            return view('vendor.advertisement.rate-limit', [
                'cooldownEnds' => null,
            ]);
        }
    }

    /**
     * Show advertisement creation form
     *
     * @param Product $product
     * @return View|RedirectResponse
     */
    public function createAdvertisement(Product $product): View|RedirectResponse
    {
        try {
            // Verify ownership
            if ($product->user_id !== Auth::id()) {
                Log::warning('Unauthorized advertisement creation attempt', [
                    'vendor_id' => Auth::id(),
                    'product_id' => $product->id,
                ]);
                abort(403, 'Unauthorized action.');
            }

            Log::debug('Loading advertisement creation form', [
                'vendor_id' => Auth::id(),
                'product_id' => $product->id,
            ]);

            // Check daily limit
            if (Advertisement::hasReachedDailyLimit(Auth::id())) {
                Log::warning('Daily advertisement limit reached', ['vendor_id' => Auth::id()]);
                return redirect()->route('vendor.advertisement.rate-limit');
            }

            // Check if already advertised
            if (Advertisement::isProductAdvertised($product->id)) {
                Log::info('Product already advertised', [
                    'vendor_id' => Auth::id(),
                    'product_id' => $product->id,
                ]);

                return redirect()->route('vendor.my-products')
                    ->with('error', 'This product is already being advertised in another slot.');
            }

            $slots = $this->prepareAdvertisementSlots();

            return view('vendor.advertisement.create', compact('product', 'slots'));

        } catch (Exception $e) {
            Log::error('Error loading advertisement creation form', [
                'vendor_id' => Auth::id(),
                'product_id' => $product->id ?? 'unknown',
                'message' => $e->getMessage(),
            ]);

            return redirect()->route('vendor.my-products')
                ->with('error', 'Failed to load advertisement form.');
        }
    }

    /**
     * Store new advertisement
     *
     * @param Request $request
     * @param Product $product
     * @return RedirectResponse
     */
    public function storeAdvertisement(Request $request, Product $product): RedirectResponse
    {
        try {
            // Verify ownership
            if ($product->user_id !== Auth::id()) {
                Log::warning('Unauthorized advertisement store attempt', [
                    'vendor_id' => Auth::id(),
                    'product_id' => $product->id,
                ]);
                abort(403, 'Unauthorized action.');
            }

            Log::debug('Creating advertisement', [
                'vendor_id' => Auth::id(),
                'product_id' => $product->id,
            ]);

            // Check daily limit
            if (Advertisement::hasReachedDailyLimit(Auth::id())) {
                return redirect()->route('vendor.advertisement.rate-limit');
            }

            // Check if already advertised
            if (Advertisement::isProductAdvertised($product->id)) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'This product is already being advertised in another slot.');
            }

            // Validate request
            try {
                $validated = $request->validate([
                    'slot_number' => [
                        'required',
                        'integer',
                        'min:1',
                        'max:8',
                        function ($attribute, $value, $fail) use ($request): void {
                            $duration = (int) $request->duration_days;
                            if ($duration < 1) {
                                return;
                            }
                            if (!Advertisement::isSlotAvailable($value, now(), now()->addDays($duration))) {
                                $fail('This slot is currently occupied.');
                            }
                        },
                    ],
                    'duration_days' => [
                        'required',
                        'integer',
                        'min:' . config('monero.advertisement_min_duration', 1),
                        'max:' . config('monero.advertisement_max_duration', 30),
                    ],
                ]);

            } catch (ValidationException $e) {
                Log::warning('Validation failed on advertisement creation', [
                    'vendor_id' => Auth::id(),
                    'product_id' => $product->id,
                    'errors' => $e->validator->errors()->all(),
                ]);

                return redirect()->back()
                    ->withInput()
                    ->with('error', $e->validator->errors()->first());
            }

            // Calculate required amount
            $requiredAmount = Advertisement::calculateRequiredAmount(
                $validated['slot_number'],
                $validated['duration_days']
            );

            // Create Monero subaddress
            $result = $this->walletRPC->create_address(
                0,
                "Advertisement Payment " . Auth::id() . "_" . time()
            );

            // Create advertisement record
            $advertisement = new Advertisement([
                'product_id' => $product->id,
                'user_id' => Auth::id(),
                'slot_number' => $validated['slot_number'],
                'duration_days' => $validated['duration_days'],
                'payment_address' => $result['address'],
                'payment_address_index' => $result['address_index'],
                'required_amount' => $requiredAmount,
                'expires_at' => now()->addMinutes((int) config('monero.address_expiration_time')),
            ]);

            $advertisement->save();

            Log::info('Advertisement created', [
                'vendor_id' => Auth::id(),
                'advertisement_id' => $advertisement->id,
                'product_id' => $product->id,
            ]);

            return redirect()->route('vendor.advertisement.payment', $advertisement->payment_identifier);

        } catch (Exception $e) {
            Log::error('Failed to create advertisement', [
                'vendor_id' => Auth::id(),
                'product_id' => $product->id ?? 'unknown',
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create advertisement. Please try again.');
        }
    }

    /**
     * Show advertisement payment page
     *
     * @param string $identifier
     * @return View|RedirectResponse
     */
    public function showAdvertisementPayment(string $identifier): View|RedirectResponse
    {
        try {
            Log::debug('Loading advertisement payment page', [
                'vendor_id' => Auth::id(),
                'identifier' => $identifier,
            ]);

            $advertisement = Advertisement::where('payment_identifier', $identifier)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            // Check if expired
            if ($advertisement->isExpired()) {
                Log::info('Payment window expired', [
                    'vendor_id' => Auth::id(),
                    'advertisement_id' => $advertisement->id,
                ]);

                return redirect()->route('vendor.my-products')
                    ->with('error', 'Payment window has expired.');
            }

            try {
                // Check for new payments
                $transfers = $this->walletRPC->get_transfers([
                    'in' => true,
                    'pool' => true,
                    'subaddr_indices' => [$advertisement->payment_address_index],
                ]);

                // Calculate minimum accepted payment
                $minPaymentPercentage = config('monero.advertisement_minimum_payment_percentage');
                $minAcceptedAmount = $advertisement->required_amount * $minPaymentPercentage;

                $totalReceived = $this->calculateTotalReceived($transfers, $minAcceptedAmount);

                // Update received amount
                $advertisement->total_received = $totalReceived;

                // Check if payment completed
                if ($totalReceived >= $advertisement->required_amount && !$advertisement->payment_completed) {
                    $advertisement->payment_completed = true;
                    $advertisement->payment_completed_at = now();
                    $advertisement->starts_at = now();
                    $advertisement->ends_at = now()->addDays((int) $advertisement->duration_days);

                    Log::info('Advertisement payment completed', [
                        'vendor_id' => Auth::id(),
                        'advertisement_id' => $advertisement->id,
                    ]);
                }

                $advertisement->save();

                // Generate QR code
                $qrCode = null;
                if (!$advertisement->payment_completed) {
                    $qrCode = $this->generateQrCode($advertisement->payment_address);
                }

                return view('vendor.advertisement.payment', [
                    'advertisement' => $advertisement,
                    'qrCode' => $qrCode,
                ]);

            } catch (Exception $e) {
                Log::error('Error checking advertisement payment', [
                    'vendor_id' => Auth::id(),
                    'advertisement_id' => $advertisement->id,
                    'message' => $e->getMessage(),
                ]);

                return view('vendor.advertisement.payment', [
                    'advertisement' => $advertisement,
                    'qrCode' => null,
                    'error' => 'Error checking payment status. Please try refreshing the page.',
                ]);
            }

        } catch (Exception $e) {
            Log::error('Error loading advertisement payment page', [
                'vendor_id' => Auth::id(),
                'identifier' => $identifier,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->route('vendor.my-products')
                ->with('error', 'Failed to load advertisement payment page.');
        }
    }

    // ==================== PRIVATE HELPER METHODS ====================

    /**
     * Handle product picture upload
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @return string
     * @throws Exception
     */
    private function handleProductPictureUpload($file): string
    {
        try {
            Log::debug('Processing product picture upload', ['vendor_id' => Auth::id()]);

            // Verify MIME type using finfo
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mimeType = $finfo->file($file->getPathname());

            if (!in_array($mimeType, self::ALLOWED_MIME_TYPES)) {
                throw new Exception('Invalid file type. Allowed types are JPEG, PNG, GIF, and WebP.');
            }

            $extension = $this->getExtensionFromMimeType($mimeType);
            $filename = time() . '_' . Str::uuid() . '.' . $extension;

            // Process image
            $manager = new ImageManager(new GdDriver());
            $image = $manager->read($file)
                ->resize(800, 800, function ($constraint): void {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });

            // Encode image
            $encodedImage = $this->encodeImage($image, $mimeType);

            // Save to storage
            if (!Storage::disk('private')->put('product_pictures/' . $filename, $encodedImage)) {
                throw new Exception('Failed to save product picture to storage');
            }

            Log::debug('Product picture uploaded', [
                'vendor_id' => Auth::id(),
                'filename' => $filename,
            ]);

            return $filename;

        } catch (NotReadableException $e) {
            Log::error('Image processing failed', [
                'vendor_id' => Auth::id(),
                'message' => $e->getMessage(),
            ]);

            throw new Exception('Failed to process uploaded image. Please try a different image.');

        } catch (Exception $e) {
            Log::error('Product picture upload failed', [
                'vendor_id' => Auth::id(),
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw $e;
        }
    }

    /**
     * Handle additional photos upload
     *
     * @param Request $request
     * @return array
     */
    private function handleAdditionalPhotosUpload(Request $request): array
    {
        $additionalPhotos = [];

        if ($request->hasFile('additional_photos')) {
            foreach ($request->file('additional_photos') as $index => $photo) {
                if ($index >= self::MAX_ADDITIONAL_PHOTOS) {
                    break;
                }

                try {
                    $additionalPhotos[] = $this->handleProductPictureUpload($photo);

                } catch (Exception $e) {
                    Log::warning('Failed to upload additional photo', [
                        'vendor_id' => Auth::id(),
                        'photo_index' => $index,
                        'message' => $e->getMessage(),
                    ]);
                    continue;
                }
            }
        }

        return $additionalPhotos;
    }

    /**
     * Get file extension from MIME type
     *
     * @param string $mimeType
     * @return string
     */
    private function getExtensionFromMimeType(string $mimeType): string
    {
        return match ($mimeType) {
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
            'image/webp' => 'webp',
            default => 'jpg',
        };
    }

    /**
     * Encode image based on MIME type
     *
     * @param mixed $image
     * @param string $mimeType
     * @return mixed
     */
    private function encodeImage($image, string $mimeType)
    {
        return match ($mimeType) {
            'image/png' => $image->encode(new PngEncoder()),
            'image/webp' => $image->encode(new WebpEncoder()),
            'image/gif' => $image->encode(new GifEncoder()),
            default => $image->encode(new JpegEncoder(80)),
        };
    }

    /**
     * Generate QR code for address
     *
     * @param string $address
     * @return string|null
     */
    private function generateQrCode(string $address): string|null
    {
        try {
            Log::debug('Generating QR code', ['vendor_id' => Auth::id()]);

            $result = Builder::create()
                ->writer(new PngWriter())
                ->writerOptions([])
                ->data($address)
                ->encoding(new Encoding('UTF-8'))
                ->errorCorrectionLevel(ErrorCorrectionLevel::High)
                ->size(300)
                ->margin(10)
                ->build();

            return $result->getDataUri();

        } catch (Exception $e) {
            Log::error('Error generating QR code', [
                'vendor_id' => Auth::id(),
                'message' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Prepare advertisement slots with pricing
     *
     * @return array<array>
     */
    private function prepareAdvertisementSlots(): array
    {
        $basePrice = config('monero.advertisement_base_price');
        $slots = [];

        foreach (config('monero.advertisement_slot_multipliers') as $slot => $multiplier) {
            $price = $basePrice * $multiplier;
            $isAvailable = !Advertisement::where('slot_number', $slot)
                ->where('payment_completed', true)
                ->where('starts_at', '<=', now())
                ->where('ends_at', '>=', now())
                ->exists();

            $slots[] = [
                'number' => $slot,
                'price' => $price,
                'is_available' => $isAvailable,
            ];
        }

        return $slots;
    }

    /**
     * Calculate total items in order accounting for bulk options
     *
     * @param Orders $sale
     * @return int
     */
    private function calculateOrderTotalItems(Orders $sale): int
    {
        $totalItems = 0;

        foreach ($sale->items as $item) {
            if ($item->bulk_option && isset($item->bulk_option['amount'])) {
                $totalItems += $item->quantity * $item->bulk_option['amount'];
            } else {
                $totalItems += $item->quantity;
            }
        }

        return $totalItems;
    }

    /**
     * Calculate total amount received in payment
     *
     * @param array $transfers
     * @param float $minAcceptedAmount
     * @return float
     */
    private function calculateTotalReceived(array $transfers, float $minAcceptedAmount): float
    {
        $totalReceived = 0;

        foreach (['in', 'pool'] as $type) {
            if (isset($transfers[$type])) {
                foreach ($transfers[$type] as $transfer) {
                    $amount = $transfer['amount'] / 1e12;
                    if ($amount >= $minAcceptedAmount) {
                        $totalReceived += $amount;
                    }
                }
            }
        }

        return $totalReceived;
    }

    /**
     * Get product type name for display
     *
     * @param string $type
     * @return string
     */
    private function getProductTypeName(string $type): string
    {
        return match ($type) {
            Product::TYPE_CARGO => 'Cargo',
            Product::TYPE_DIGITAL => 'Digital',
            Product::TYPE_DEADDROP => 'Dead Drop',
            default => 'Product',
        };
    }

    /**
     * Load countries from JSON file
     *
     * @return array
     */
    private function loadCountries(): array
    {
        try {
            return json_decode(file_get_contents(storage_path('app/country.json')), true) ?? [];
        } catch (Exception $e) {
            Log::error('Failed to load countries', ['message' => $e->getMessage()]);
            return [];
        }
    }

    /**
     * Validate product data for creation
     *
     * @param Request $request
     * @param string $type
     * @return array
     * @throws ValidationException
     */
    private function validateProductData(Request $request, string $type): array
    {
        $countries = $this->loadCountries();

        return $request->validate([
            'name' => 'required|string|min:4|max:240',
            'description' => 'required|string|min:4|max:2400',
            'price' => 'required|numeric|min:0|max:80000',
            'category_id' => 'required|exists:categories,id',
            'product_picture' => [
                'nullable',
                'file',
                'max:' . self::MAX_FILE_SIZE_KB,
            ],
            'additional_photos.*' => [
                'nullable',
                'file',
                'max:' . self::MAX_FILE_SIZE_KB,
            ],
            'stock_amount' => 'required|integer|min:0|max:80000',
            'measurement_unit' => [
                'required',
                Rule::in(array_keys(Product::getMeasurementUnits())),
            ],
            'ships_from' => [
                'required',
                'string',
                Rule::in($countries),
            ],
            'ships_to' => [
                'required',
                'string',
                Rule::in($countries),
            ],
        ]);
    }

    /**
     * Validate product data for updates
     *
     * @param Request $request
     * @return array
     * @throws ValidationException
     */
    private function validateProductUpdateData(Request $request): array
    {
        $countries = $this->loadCountries();

        return $request->validate([
            'description' => 'required|string|min:4|max:2400',
            'price' => 'required|numeric|min:0|max:80000',
            'category_id' => 'required|exists:categories,id',
            'stock_amount' => 'required|integer|min:0|max:80000',
            'measurement_unit' => [
                'required',
                Rule::in(array_keys(Product::getMeasurementUnits())),
            ],
            'ships_from' => [
                'required',
                'string',
                Rule::in($countries),
            ],
            'ships_to' => [
                'required',
                'string',
                Rule::in($countries),
            ],
        ]);
    }

    /**
     * Process delivery options from request
     *
     * @param Request $request
     * @param string $type
     * @return array
     * @throws ValidationException
     */
    private function processDeliveryOptions(Request $request, string $type): array
    {
        $deliveryOptionName = $type === Product::TYPE_DEADDROP ? 'pickup window' : 'delivery';

        $deliveryOptions = collect($request->delivery_options ?? [])
            ->map(function ($option) {
                return [
                    'description' => trim($option['description'] ?? ''),
                    'price' => is_numeric($option['price']) ? (float) $option['price'] : null,
                ];
            })
            ->filter(function ($option) {
                return !empty($option['description']) && $option['price'] !== null;
            })
            ->values()
            ->all();

        if (empty($deliveryOptions)) {
            throw ValidationException::withMessages([
                'delivery_options' => "At least one {$deliveryOptionName} option is required.",
            ]);
        }

        if (count($deliveryOptions) > self::MAX_DELIVERY_OPTIONS) {
            throw ValidationException::withMessages([
                'delivery_options' => "No more than " . self::MAX_DELIVERY_OPTIONS . " {$deliveryOptionName} options are allowed.",
            ]);
        }

        foreach ($deliveryOptions as $option) {
            if (strlen($option['description']) < 4 || strlen($option['description']) > 160) {
                throw ValidationException::withMessages([
                    'delivery_options' => "{$deliveryOptionName} description must be between 4 and 160 characters.",
                ]);
            }

            if ($option['price'] < 0 || $option['price'] > 80000) {
                throw ValidationException::withMessages([
                    'delivery_options' => "{$deliveryOptionName} price must be between 0 and 80000.",
                ]);
            }
        }

        return $deliveryOptions;
    }

    /**
     * Process bulk options from request
     *
     * @param Request $request
     * @return array
     * @throws ValidationException
     */
    private function processBulkOptions(Request $request): array
    {
        $bulkOptions = collect($request->bulk_options ?? [])
            ->map(function ($option) {
                return [
                    'amount' => is_numeric($option['amount']) ? (float) $option['amount'] : null,
                    'price' => is_numeric($option['price']) ? (float) $option['price'] : null,
                ];
            })
            ->filter(function ($option) {
                return $option['amount'] !== null && $option['price'] !== null;
            })
            ->values()
            ->all();

        if (empty($bulkOptions)) {
            return [];
        }

        if (count($bulkOptions) > self::MAX_BULK_OPTIONS) {
            throw ValidationException::withMessages([
                'bulk_options' => 'No more than ' . self::MAX_BULK_OPTIONS . ' bulk options are allowed.',
            ]);
        }

        foreach ($bulkOptions as $option) {
            if ($option['amount'] < 0 || $option['amount'] > 80000) {
                throw ValidationException::withMessages([
                    'bulk_options' => 'Bulk option amount must be between 0 and 80000.',
                ]);
            }

            if ($option['price'] < 0 || $option['price'] > 80000) {
                throw ValidationException::withMessages([
                    'bulk_options' => 'Bulk option price must be between 0 and 80000.',
                ]);
            }
        }

        return $bulkOptions;
    }
}
