<?php

/*
 * =========================================================================
 * © 2026 Erebus Development Team
 * Author: AnonymousUser9183
 * =========================================================================
 * Orders Controller - Order & Payment Management
 * =========================================================================
 */

namespace App\Http\Controllers;

use App\Models\Orders;
use App\Models\Cart;
use App\Models\ProductReviews;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use MoneroIntegrations\MoneroPhp\walletRPC;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Writer\PngWriter;

class OrdersController extends Controller
{
    /**
     * Monero wallet RPC instance
     *
     * @var walletRPC|null
     */
    protected walletRPC|null $walletRPC;

    /**
     * Initialize controller with Monero RPC connection
     */
    public function __construct()
    {
        try {
            Log::debug('OrdersController constructor called');
            $config = config('monero');
            $this->walletRPC = new walletRPC(
                $config['host'],
                $config['port'],
                $config['ssl']
            );
        } catch (\Exception $e) {
            Log::error('Failed to initialize Monero RPC connection', [
                'message' => $e->getMessage(),
            ]);
            $this->walletRPC = null;
        }
    }

    /**
     * Display a listing of the user's orders
     *
     * @return View
     */
    public function index(): View
    {
        try {
            Log::debug('Loading user orders', ['user_id' => Auth::id()]);

            $orders = Orders::getUserOrders(Auth::id());

            Log::debug('Orders loaded', [
                'user_id' => Auth::id(),
                'count' => $orders->count(),
            ]);

            return view('orders.index', ['orders' => $orders]);

        } catch (\Exception $e) {
            Log::error('Error loading user orders', [
                'user_id' => Auth::id(),
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return view('orders.index', ['orders' => collect()])
                ->with('error', 'An error occurred while loading orders.');
        }
    }

    /**
     * Display the specified order with payment and status handling
     *
     * @param string $uniqueUrl
     * @return View|RedirectResponse
     */
    public function show(string $uniqueUrl): View|RedirectResponse
    {
        try {
            Log::debug('Loading order details', [
                'unique_url' => substr($uniqueUrl, 0, 10) . '...',
                'user_id' => Auth::id(),
            ]);

            // Process auto status changes
            Orders::processAllAutoStatusChanges();

            $order = Orders::findByUrl($uniqueUrl);

            if (!$order) {
                Log::warning('Order not found', ['unique_url' => $uniqueUrl]);
                abort(404);
            }

            // Verify user access
            if ($order->user_id !== Auth::id() && $order->vendor_id !== Auth::id()) {
                Log::warning('Unauthorized order access attempt', [
                    'order_id' => $order->id,
                    'user_id' => Auth::id(),
                ]);
                abort(403, 'Unauthorized access.');
            }

            $isBuyer = $order->user_id === Auth::id();

            // Handle payment processing for buyers
            $qrCode = null;
            if ($isBuyer && $order->status === Orders::STATUS_WAITING_PAYMENT) {
                $qrCode = $this->handlePaymentProcessing($order);
                if ($qrCode instanceof RedirectResponse) {
                    return $qrCode;
                }
            }

            // Load reviews for completed orders
            if ($isBuyer && $order->status === Orders::STATUS_COMPLETED) {
                $order->items->each(function ($item) {
                    $item->existingReview = ProductReviews::where('user_id', Auth::id())
                        ->where('order_item_id', $item->id)
                        ->first();
                });
            }

            // Get dispute if exists
            $dispute = $order->dispute;

            // Calculate total items
            $totalItems = $this->calculateTotalItems($order);

            Log::debug('Order loaded successfully', [
                'order_id' => $order->id,
                'status' => $order->status,
            ]);

            return view('orders.show', [
                'order' => $order,
                'isBuyer' => $isBuyer,
                'dispute' => $dispute,
                'qrCode' => $qrCode,
                'totalItems' => $totalItems,
            ]);

        } catch (\Exception $e) {
            Log::error('Error loading order', [
                'unique_url' => $uniqueUrl,
                'user_id' => Auth::id(),
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->route('orders.index')
                ->with('error', 'An error occurred while loading the order.');
        }
    }

    /**
     * Handle payment processing for orders
     *
     * @param Orders $order
     * @return string|RedirectResponse|null
     */
    private function handlePaymentProcessing(Orders $order): string|RedirectResponse|null
    {
        try {
            // Check for expired payment
            if ($order->isExpired() && !empty($order->payment_address)) {
                Log::warning('Order payment expired', ['order_id' => $order->id]);
                $order->handleExpiredPayment();
                $order->refresh();

                if ($order->status === Orders::STATUS_CANCELLED) {
                    return redirect()->route('orders.show', $order->unique_url)
                        ->with('info', 'This order has been automatically cancelled because the payment window has expired.');
                }
            }

            // Check for auto-cancel if not sent
            if ($order->shouldAutoCancelIfNotSent()) {
                Log::info('Auto-cancelling order not sent within 96 hours', [
                    'order_id' => $order->id,
                ]);
                $order->autoCancelIfNotSent();
                $order->refresh();

                if ($order->status === Orders::STATUS_CANCELLED) {
                    return redirect()->route('orders.show', $order->unique_url)
                        ->with('info', 'This order has been automatically cancelled because the vendor did not mark it as sent within 96 hours.');
                }
            }

            // Check for auto-complete
            if ($order->shouldAutoCompleteIfNotConfirmed()) {
                Log::info('Auto-completing order not confirmed within 192 hours', [
                    'order_id' => $order->id,
                ]);
                $order->autoCompleteIfNotConfirmed();
                $order->refresh();

                if ($order->status === Orders::STATUS_COMPLETED) {
                    return redirect()->route('orders.show', $order->unique_url)
                        ->with('info', 'This order has been automatically marked as completed.');
                }
            }

            // Generate payment address if needed
            if (empty($order->payment_address) && $order->status === Orders::STATUS_WAITING_PAYMENT) {
                return $this->generatePaymentAddress($order);
            }

            // Check for new payments
            try {
                $order->checkPayments($this->walletRPC);
            } catch (\Exception $e) {
                Log::error('Error checking payments', [
                    'order_id' => $order->id,
                    'message' => $e->getMessage(),
                ]);
            }

            $order->refresh();

            // Generate QR code if needed
            if (!$order->is_paid && $order->payment_address) {
                try {
                    return $this->generateQrCode($order->payment_address);
                } catch (\Exception $e) {
                    Log::error('Error generating QR code', [
                        'order_id' => $order->id,
                        'message' => $e->getMessage(),
                    ]);
                    return null;
                }
            }

            return null;

        } catch (\Exception $e) {
            Log::error('Error in payment processing', [
                'order_id' => $order->id,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return null;
        }
    }

    /**
     * Generate payment address for order
     *
     * @param Orders $order
     * @return string|RedirectResponse|null
     */
    private function generatePaymentAddress(Orders $order): string|RedirectResponse|null
    {
        try {
            Log::debug('Generating payment address', ['order_id' => $order->id]);

            // Get XMR price
            $xmrPriceController = new XmrPriceController();
            $xmrRate = $xmrPriceController->getXmrPrice();

            if ($xmrRate === 'UNAVAILABLE') {
                Log::error('Unable to get XMR price', ['order_id' => $order->id]);
                return redirect()->back()
                    ->with('error', 'Unable to get XMR price. Please try again later.');
            }

            // Calculate required XMR
            $requiredXmrAmount = $order->calculateRequiredXmrAmount($xmrRate);

            // Update order
            $order->update([
                'required_xmr_amount' => $requiredXmrAmount,
                'xmr_usd_rate' => $xmrRate,
            ]);

            // Generate address
            if (!$order->generatePaymentAddress($this->walletRPC)) {
                Log::error('Failed to generate payment address', ['order_id' => $order->id]);
                return redirect()->back()
                    ->with('error', 'Unable to generate payment address. Please try again.');
            }

            Log::info('Payment address generated', ['order_id' => $order->id]);
            return null;

        } catch (\Exception $e) {
            Log::error('Error generating payment address', [
                'order_id' => $order->id,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->back()
                ->with('error', 'Error setting up payment: ' . $e->getMessage());
        }
    }

    /**
     * Calculate total items in order accounting for bulk options
     *
     * @param Orders $order
     * @return int
     */
    private function calculateTotalItems(Orders $order): int
    {
        $totalItems = 0;

        foreach ($order->items as $item) {
            if ($item->bulk_option && isset($item->bulk_option['amount'])) {
                $totalItems += $item->quantity * $item->bulk_option['amount'];
            } else {
                $totalItems += $item->quantity;
            }
        }

        return $totalItems;
    }

    /**
     * Create a new order from cart items
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            Log::debug('Creating new order from cart', ['user_id' => Auth::id()]);

            $user = Auth::user();
            $cartItems = Cart::where('user_id', $user->id)
                ->with(['product', 'product.user'])
                ->get();

            if ($cartItems->isEmpty()) {
                Log::warning('User attempted to create order with empty cart', [
                    'user_id' => Auth::id(),
                ]);
                return redirect()->route('cart.index')
                    ->with('error', 'Your cart is empty.');
            }

            // Get vendor ID
            $vendorId = $cartItems->first()->product->user_id;

            // Check if can create order
            [$canCreate, $reason] = Orders::canCreateNewOrder($user->id, $vendorId);

            if (!$canCreate) {
                Log::warning('User cannot create order', [
                    'user_id' => Auth::id(),
                    'vendor_id' => $vendorId,
                    'reason' => $reason,
                ]);
                return redirect()->route('cart.checkout')
                    ->with('error', $reason);
            }

            // Calculate totals
            $subtotal = Cart::getCartTotal($user);
            $commissionPercentage = config('marketplace.commission_percentage');
            $commission = ($subtotal * $commissionPercentage) / 100;
            $total = $subtotal + $commission;

            // Create order
            $order = Orders::createFromCart($user, $cartItems, $subtotal, $commission, $total);

            // Clear cart
            Cart::where('user_id', $user->id)->delete();

            Log::info('Order created successfully', [
                'order_id' => $order->id,
                'user_id' => Auth::id(),
                'total' => $total,
            ]);

            return redirect()->route('orders.show', $order->unique_url)
                ->with('success', 'Order created successfully. Please complete the payment.');

        } catch (\Exception $e) {
            Log::error('Error creating order', [
                'user_id' => Auth::id(),
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->route('cart.checkout')
                ->with('error', 'Failed to create order. Please try again.');
        }
    }

    /**
     * Generate QR code for payment address
     *
     * @param string $address
     * @return string|null
     */
    private function generateQrCode(string $address): string|null
    {
        try {
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

        } catch (\Exception $e) {
            Log::error('Error generating QR code', [
                'message' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Mark order as sent (vendor action)
     *
     * @param string $uniqueUrl
     * @return RedirectResponse
     */
    public function markAsSent(string $uniqueUrl): RedirectResponse
    {
        try {
            Log::debug('Marking order as sent', [
                'unique_url' => substr($uniqueUrl, 0, 10) . '...',
                'user_id' => Auth::id(),
            ]);

            $order = Orders::findByUrl($uniqueUrl);

            if (!$order) {
                Log::warning('Order not found', ['unique_url' => $uniqueUrl]);
                abort(404);
            }

            // Verify vendor ownership
            if ($order->vendor_id !== Auth::id()) {
                Log::warning('Unauthorized attempt to mark order as sent', [
                    'order_id' => $order->id,
                    'user_id' => Auth::id(),
                ]);
                abort(403, 'Unauthorized action.');
            }

            if ($order->markAsSent()) {
                Log::info('Order marked as sent', [
                    'order_id' => $order->id,
                    'vendor_id' => Auth::id(),
                ]);

                return redirect()->route('vendor.sales.show', $order->unique_url)
                    ->with('success', 'Product marked as sent. The buyer has been notified.');
            }

            return redirect()->route('vendor.sales.show', $order->unique_url)
                ->with('error', 'Unable to mark as sent at this time.');

        } catch (\Exception $e) {
            Log::error('Error marking order as sent', [
                'unique_url' => $uniqueUrl,
                'user_id' => Auth::id(),
                'message' => $e->getMessage(),
            ]);

            return redirect()->back()
                ->with('error', 'An error occurred while marking the order as sent.');
        }
    }

    /**
     * Mark order as completed (buyer action)
     *
     * @param string $uniqueUrl
     * @return RedirectResponse
     */
    public function markAsCompleted(string $uniqueUrl): RedirectResponse
    {
        try {
            Log::debug('Marking order as completed', [
                'unique_url' => substr($uniqueUrl, 0, 10) . '...',
                'user_id' => Auth::id(),
            ]);

            $order = Orders::findByUrl($uniqueUrl);

            if (!$order) {
                Log::warning('Order not found', ['unique_url' => $uniqueUrl]);
                abort(404);
            }

            // Verify buyer ownership
            if ($order->user_id !== Auth::id()) {
                Log::warning('Unauthorized attempt to mark order as completed', [
                    'order_id' => $order->id,
                    'user_id' => Auth::id(),
                ]);
                abort(403, 'Unauthorized action.');
            }

            if ($order->markAsCompleted()) {
                Log::info('Order marked as completed', [
                    'order_id' => $order->id,
                    'buyer_id' => Auth::id(),
                ]);

                return redirect()->route('orders.show', $order->unique_url)
                    ->with('success', 'Order marked as completed and payment has been sent to the vendor. Thank you for your purchase.');
            }

            return redirect()->route('orders.show', $order->unique_url)
                ->with('error', 'Unable to mark as completed at this time.');

        } catch (\Exception $e) {
            Log::error('Error marking order as completed', [
                'unique_url' => $uniqueUrl,
                'user_id' => Auth::id(),
                'message' => $e->getMessage(),
            ]);

            return redirect()->back()
                ->with('error', 'An error occurred while marking the order as completed.');
        }
    }

    /**
     * Mark order as cancelled (buyer or vendor action)
     *
     * @param string $uniqueUrl
     * @return RedirectResponse
     */
    public function markAsCancelled(string $uniqueUrl): RedirectResponse
    {
        try {
            Log::debug('Marking order as cancelled', [
                'unique_url' => substr($uniqueUrl, 0, 10) . '...',
                'user_id' => Auth::id(),
            ]);

            $order = Orders::findByUrl($uniqueUrl);

            if (!$order) {
                Log::warning('Order not found', ['unique_url' => $uniqueUrl]);
                abort(404);
            }

            // Verify ownership
            if ($order->user_id !== Auth::id() && $order->vendor_id !== Auth::id()) {
                Log::warning('Unauthorized attempt to cancel order', [
                    'order_id' => $order->id,
                    'user_id' => Auth::id(),
                ]);
                abort(403, 'Unauthorized action.');
            }

            // Check if can be cancelled
            if ($order->status === Orders::STATUS_COMPLETED) {
                Log::warning('Attempt to cancel completed order', [
                    'order_id' => $order->id,
                ]);
                return redirect()->back()
                    ->with('error', 'Completed orders cannot be cancelled.');
            }

            if ($order->markAsCancelled()) {
                Log::info('Order cancelled', [
                    'order_id' => $order->id,
                    'cancelled_by' => Auth::id(),
                ]);

                // Determine redirect route
                $isBuyer = $order->user_id === Auth::id();
                $route = $isBuyer ? 'orders.show' : 'vendor.sales.show';

                return redirect()->route($route, $order->unique_url)
                    ->with('success', 'Order has been cancelled successfully.');
            }

            return redirect()->back()
                ->with('error', 'Unable to cancel the order at this time.');

        } catch (\Exception $e) {
            Log::error('Error cancelling order', [
                'unique_url' => $uniqueUrl,
                'user_id' => Auth::id(),
                'message' => $e->getMessage(),
            ]);

            return redirect()->back()
                ->with('error', 'An error occurred while cancelling the order.');
        }
    }

    /**
     * Submit a review for an order item
     *
     * @param Request $request
     * @param string $uniqueUrl
     * @param string $orderItemId
     * @return RedirectResponse
     */
    public function submitReview(Request $request, string $uniqueUrl, string $orderItemId): RedirectResponse
    {
        try {
            Log::debug('Submitting product review', [
                'unique_url' => substr($uniqueUrl, 0, 10) . '...',
                'order_item_id' => $orderItemId,
                'user_id' => Auth::id(),
            ]);

            // Find order
            $order = Orders::findByUrl($uniqueUrl);

            if (!$order) {
                Log::warning('Order not found for review', ['unique_url' => $uniqueUrl]);
                abort(404);
            }

            // Verify buyer
            if ($order->user_id !== Auth::id()) {
                Log::warning('Unauthorized review submission', [
                    'order_id' => $order->id,
                    'user_id' => Auth::id(),
                ]);
                abort(403, 'Unauthorized action.');
            }

            // Verify completed
            if ($order->status !== Orders::STATUS_COMPLETED) {
                Log::warning('Review submitted for non-completed order', [
                    'order_id' => $order->id,
                ]);
                return redirect()->route('orders.show', $order->unique_url)
                    ->with('error', 'You can only review products from completed orders.');
            }

            // Find order item
            $orderItem = $order->items()->where('id', $orderItemId)->first();

            if (!$orderItem) {
                Log::warning('Order item not found', ['order_item_id' => $orderItemId]);
                abort(404);
            }

            // Check existing review
            $existingReview = ProductReviews::where('user_id', Auth::id())
                ->where('order_item_id', $orderItem->id)
                ->first();

            if ($existingReview) {
                Log::warning('User already reviewed this product', [
                    'order_item_id' => $orderItem->id,
                    'user_id' => Auth::id(),
                ]);
                return redirect()->route('orders.show', $order->unique_url)
                    ->with('error', 'You have already reviewed this product.');
            }

            // Validate
            $validated = $request->validate([
                'review_text' => 'required|string|min:8|max:800',
                'sentiment' => 'required|in:positive,mixed,negative',
            ]);

            // Create review
            ProductReviews::create([
                'product_id' => $orderItem->product_id,
                'user_id' => Auth::id(),
                'order_id' => $order->id,
                'order_item_id' => $orderItem->id,
                'review_text' => $validated['review_text'],
                'sentiment' => $validated['sentiment'],
            ]);

            Log::info('Product review submitted', [
                'product_id' => $orderItem->product_id,
                'user_id' => Auth::id(),
                'sentiment' => $validated['sentiment'],
            ]);

            return redirect()->route('orders.show', $order->unique_url)
                ->with('success', 'Your review has been submitted successfully.');

        } catch (\Exception $e) {
            Log::error('Error submitting review', [
                'unique_url' => $uniqueUrl,
                'order_item_id' => $orderItemId,
                'user_id' => Auth::id(),
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->with('error', 'An error occurred while submitting your review.');
        }
    }
}
