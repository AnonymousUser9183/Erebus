<?php

/*
 * =========================================================================
 * © 2026 Erebus Development Team
 * Author: AnonymousUser9183
 * =========================================================================
 * Disputes Controller - Order Disputes Management
 * =========================================================================
 */

namespace App\Http\Controllers;

use App\Models\Dispute;
use App\Models\DisputeMessage;
use App\Models\Orders;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Illuminate\Pagination\LengthAwarePaginator;

class DisputesController extends Controller
{
    /**
     * Display disputes for current user (buyer or vendor)
     * Shows disputes on orders where user is involved
     *
     * @return View
     */
    public function index(): View
    {
        try {
            Log::debug('Loading disputes index', ['user_id' => Auth::id()]);

            $user = Auth::user();
            $userId = $user->id;

            // Get disputes on orders where user is buyer OR vendor
            $disputes = Dispute::with(['order' => function ($query) use ($userId) {
                $query->where('user_id', $userId)
                    ->orWhere('vendor_id', $userId);
            }])
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            // Filter out disputes where user isn't involved
            $disputes->setCollection(
                $disputes->getCollection()->filter(function ($dispute) {
                    return $dispute->order !== null;
                })
            );

            Log::debug('Disputes loaded', [
                'user_id' => $userId,
                'count' => $disputes->count(),
            ]);

            return view('disputes.index', compact('disputes'));

        } catch (\Exception $e) {
            Log::error('Error loading disputes index', [
                'user_id' => Auth::id(),
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->route('home')
                ->with('error', 'Unable to load disputes. Please try again later.');
        }
    }

    /**
     * Show specific dispute details
     *
     * @param string $id
     * @return View|RedirectResponse
     */
    public function show(string $id): View|RedirectResponse
    {
        try {
            Log::debug('Loading dispute details', [
                'dispute_id' => $id,
                'user_id' => Auth::id(),
            ]);

            $dispute = Dispute::with(['order.vendor', 'order.user', 'messages.user'])
                ->find($id);

            if (!$dispute) {
                Log::warning('Dispute not found', ['dispute_id' => $id]);
                return redirect()->route('disputes.index')
                    ->with('error', 'Dispute not found.');
            }

            // Check if user is involved in the dispute
            $user = Auth::user();
            $userId = $user->id;

            if ($dispute->order->user_id !== $userId && $dispute->order->vendor_id !== $userId) {
                Log::warning('Unauthorized dispute access attempt', [
                    'dispute_id' => $id,
                    'user_id' => $userId,
                ]);
                return redirect()->route('disputes.index')
                    ->with('error', 'Unauthorized access.');
            }

            Log::debug('Dispute loaded successfully', ['dispute_id' => $id]);
            return view('disputes.show', compact('dispute'));

        } catch (\Exception $e) {
            Log::error('Error loading dispute', [
                'dispute_id' => $id,
                'user_id' => Auth::id(),
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->route('disputes.index')
                ->with('error', 'Unable to load dispute. Please try again later.');
        }
    }

    /**
     * Store a new dispute from order
     *
     * @param string $uniqueUrl
     * @return RedirectResponse
     */
    public function store(string $uniqueUrl): RedirectResponse
    {
        try {
            Log::info('Creating new dispute', [
                'unique_url' => substr($uniqueUrl, 0, 10) . '...',
                'user_id' => Auth::id(),
            ]);

            $order = Orders::where('unique_url', $uniqueUrl)->first();

            if (!$order) {
                Log::warning('Order not found for dispute creation', [
                    'unique_url' => $uniqueUrl,
                ]);
                return redirect()->back()
                    ->with('error', 'Order not found.');
            }

            // Check if user is buyer
            if ($order->user_id !== Auth::id()) {
                Log::warning('Non-buyer attempted to create dispute', [
                    'order_id' => $order->id,
                    'user_id' => Auth::id(),
                ]);
                return redirect()->back()
                    ->with('error', 'Only the buyer can open a dispute.');
            }

            // Check if dispute already exists
            if ($order->disputes()->exists()) {
                Log::info('Attempted to create duplicate dispute', [
                    'order_id' => $order->id,
                ]);
                return redirect()->back()
                    ->with('error', 'This order already has an open dispute.');
            }

            // Create dispute
            $dispute = new Dispute([
                'id' => Str::random(30),
                'order_id' => $order->id,
                'status' => 'active',
            ]);
            $dispute->save();

            Log::info('Dispute created successfully', [
                'dispute_id' => $dispute->id,
                'order_id' => $order->id,
                'user_id' => Auth::id(),
            ]);

            return redirect()->route('disputes.show', $dispute->id)
                ->with('success', 'Dispute created successfully.');

        } catch (\Exception $e) {
            Log::error('Error creating dispute', [
                'unique_url' => $uniqueUrl,
                'user_id' => Auth::id(),
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->with('error', 'Unable to create dispute. Please try again later.');
        }
    }

    /**
     * Add message to dispute
     *
     * @param string $id
     * @return RedirectResponse
     */
    public function addMessage(string $id): RedirectResponse
    {
        try {
            Log::debug('Adding message to dispute', [
                'dispute_id' => $id,
                'user_id' => Auth::id(),
            ]);

            $dispute = Dispute::find($id);

            if (!$dispute) {
                Log::warning('Dispute not found for message addition', ['dispute_id' => $id]);
                return redirect()->back()
                    ->with('error', 'Dispute not found.');
            }

            // Validate user is involved
            $user = Auth::user();
            if ($dispute->order->user_id !== $user->id && $dispute->order->vendor_id !== $user->id) {
                Log::warning('Unauthorized message addition attempt', [
                    'dispute_id' => $id,
                    'user_id' => $user->id,
                ]);
                return redirect()->back()
                    ->with('error', 'Unauthorized.');
            }

            // Validate and get message
            $validated = request()->validate([
                'message' => 'required|string|min:5|max:1000',
            ]);

            // Create message
            $disputeMessage = new DisputeMessage([
                'id' => Str::random(30),
                'dispute_id' => $dispute->id,
                'user_id' => $user->id,
                'message' => $validated['message'],
            ]);
            $disputeMessage->save();

            Log::info('Message added to dispute', [
                'dispute_id' => $dispute->id,
                'message_id' => $disputeMessage->id,
                'user_id' => $user->id,
            ]);

            return redirect()->back()
                ->with('success', 'Message added to dispute.');

        } catch (\Exception $e) {
            Log::error('Error adding message to dispute', [
                'dispute_id' => $id,
                'user_id' => Auth::id(),
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->with('error', 'Unable to add message. Please try again later.');
        }
    }

    /**
     * Admin: List all disputes
     *
     * @return View
     */
    public function adminIndex(): View
    {
        try {
            Log::debug('Admin loading all disputes', ['user_id' => Auth::id()]);

            $disputes = Dispute::with(['order.vendor', 'order.user'])
                ->orderBy('created_at', 'desc')
                ->paginate(15);

            Log::debug('Admin disputes loaded', [
                'count' => $disputes->count(),
            ]);

            return view('admin.disputes.index', compact('disputes'));

        } catch (\Exception $e) {
            Log::error('Error loading admin disputes', [
                'user_id' => Auth::id(),
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->with('error', 'Unable to load disputes.');
        }
    }

    /**
     * Admin: Show dispute details
     *
     * @param string $id
     * @return View|RedirectResponse
     */
    public function adminShow(string $id): View|RedirectResponse
    {
        try {
            Log::debug('Admin loading dispute details', [
                'dispute_id' => $id,
                'user_id' => Auth::id(),
            ]);

            $dispute = Dispute::with(['order.vendor', 'order.user', 'messages.user'])
                ->find($id);

            if (!$dispute) {
                Log::warning('Dispute not found for admin view', ['dispute_id' => $id]);
                return redirect()->route('admin.disputes.index')
                    ->with('error', 'Dispute not found.');
            }

            Log::debug('Admin dispute loaded', ['dispute_id' => $id]);
            return view('admin.disputes.show', compact('dispute'));

        } catch (\Exception $e) {
            Log::error('Error loading admin dispute', [
                'dispute_id' => $id,
                'user_id' => Auth::id(),
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->with('error', 'Unable to load dispute.');
        }
    }

    /**
     * Admin: Resolve dispute - Vendor prevails
     *
     * @param string $id
     * @return RedirectResponse
     */
    public function resolveVendorPrevails(string $id): RedirectResponse
    {
        try {
            Log::info('Admin resolving dispute - vendor prevails', [
                'dispute_id' => $id,
                'admin_id' => Auth::id(),
            ]);

            $dispute = Dispute::find($id);

            if (!$dispute) {
                Log::warning('Dispute not found for resolution', ['dispute_id' => $id]);
                return redirect()->back()
                    ->with('error', 'Dispute not found.');
            }

            $dispute->update([
                'status' => 'vendor_prevails',
                'resolved_by' => Auth::id(),
                'resolved_at' => now(),
            ]);

            Log::info('Dispute resolved - vendor prevails', [
                'dispute_id' => $dispute->id,
                'admin_id' => Auth::id(),
            ]);

            return redirect()->back()
                ->with('success', 'Dispute resolved - Vendor prevails.');

        } catch (\Exception $e) {
            Log::error('Error resolving dispute (vendor prevails)', [
                'dispute_id' => $id,
                'admin_id' => Auth::id(),
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->with('error', 'Unable to resolve dispute.');
        }
    }

    /**
     * Admin: Resolve dispute - Buyer prevails
     *
     * @param string $id
     * @return RedirectResponse
     */
    public function resolveBuyerPrevails(string $id): RedirectResponse
    {
        try {
            Log::info('Admin resolving dispute - buyer prevails', [
                'dispute_id' => $id,
                'admin_id' => Auth::id(),
            ]);

            $dispute = Dispute::find($id);

            if (!$dispute) {
                Log::warning('Dispute not found for resolution', ['dispute_id' => $id]);
                return redirect()->back()
                    ->with('error', 'Dispute not found.');
            }

            $dispute->update([
                'status' => 'buyer_prevails',
                'resolved_by' => Auth::id(),
                'resolved_at' => now(),
            ]);

            Log::info('Dispute resolved - buyer prevails', [
                'dispute_id' => $dispute->id,
                'admin_id' => Auth::id(),
            ]);

            return redirect()->back()
                ->with('success', 'Dispute resolved - Buyer prevails.');

        } catch (\Exception $e) {
            Log::error('Error resolving dispute (buyer prevails)', [
                'dispute_id' => $id,
                'admin_id' => Auth::id(),
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->with('error', 'Unable to resolve dispute.');
        }
    }

    /**
     * Vendor: List vendor's disputes
     *
     * @return View
     */
    public function vendorDisputes(): View
    {
        try {
            Log::debug('Vendor loading disputes', ['vendor_id' => Auth::id()]);

            $vendor = Auth::user();
            $vendorId = $vendor->id;

            // Get disputes on vendor's orders
            $disputes = Dispute::with(['order' => function ($query) use ($vendorId) {
                $query->where('vendor_id', $vendorId);
            }])
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            // Filter out disputes where order doesn't belong to vendor
            $disputes->setCollection(
                $disputes->getCollection()->filter(function ($dispute) {
                    return $dispute->order !== null;
                })
            );

            Log::debug('Vendor disputes loaded', [
                'vendor_id' => $vendorId,
                'count' => $disputes->count(),
            ]);

            return view('vendor.disputes.index', compact('disputes'));

        } catch (\Exception $e) {
            Log::error('Error loading vendor disputes', [
                'vendor_id' => Auth::id(),
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->with('error', 'Unable to load disputes.');
        }
    }

    /**
     * Vendor: Show vendor's specific dispute
     *
     * @param string $id
     * @return View|RedirectResponse
     */
    public function vendorShow(string $id): View|RedirectResponse
    {
        try {
            Log::debug('Vendor loading dispute details', [
                'dispute_id' => $id,
                'vendor_id' => Auth::id(),
            ]);

            $vendor = Auth::user();
            $dispute = Dispute::with(['order.vendor', 'order.user', 'messages.user'])
                ->find($id);

            if (!$dispute) {
                Log::warning('Dispute not found for vendor view', ['dispute_id' => $id]);
                return redirect()->route('vendor.disputes.index')
                    ->with('error', 'Dispute not found.');
            }

            // Check if this is vendor's dispute
            if ($dispute->order->vendor_id !== $vendor->id) {
                Log::warning('Unauthorized vendor dispute access', [
                    'dispute_id' => $id,
                    'vendor_id' => $vendor->id,
                ]);
                return redirect()->route('vendor.disputes.index')
                    ->with('error', 'Unauthorized access.');
            }

            Log::debug('Vendor dispute loaded', ['dispute_id' => $id]);
            return view('vendor.disputes.show', compact('dispute'));

        } catch (\Exception $e) {
            Log::error('Error loading vendor dispute', [
                'dispute_id' => $id,
                'vendor_id' => Auth::id(),
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->with('error', 'Unable to load dispute.');
        }
    }
}
