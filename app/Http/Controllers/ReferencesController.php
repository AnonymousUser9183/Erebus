<?php

/*
 * =========================================================================
 * © 2026 Erebus Development Team
 * Author: AnonymousUser9183
 * =========================================================================
 * References Controller - Referral Management
 * =========================================================================
 */

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ReferencesController extends Controller
{
    /**
     * Display references page
     *
     * @return View
     */
    public function index(): View
    {
        try {
            Log::debug('Loading references page', ['user_id' => Auth::id()]);

            $user = Auth::user();

            // Get reference information
            $referenceId = $user->reference_id;
            $referrer = $user->referrer;
            $usedReferenceCode = $referrer !== null;
            $referrerUsername = $usedReferenceCode ? $referrer->username : null;

            // Get referrals
            $referrals = $user->referrals;

            // Get private shops
            $privateShops = DB::table('private_shops')
                ->where('user_id', $user->id)
                ->join('users', 'private_shops.vendor_id', '=', 'users.id')
                ->select('private_shops.id', 'private_shops.vendor_reference_id', 'users.username as vendor_username')
                ->get();

            Log::debug('References loaded', [
                'user_id' => Auth::id(),
                'referral_count' => $referrals->count(),
                'private_shops_count' => $privateShops->count(),
            ]);

            return view('references', compact(
                'referenceId',
                'usedReferenceCode',
                'referrerUsername',
                'referrals',
                'privateShops'
            ));

        } catch (\Exception $e) {
            Log::error('Error loading references', [
                'user_id' => Auth::id(),
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return view('references', [
                'referenceId' => Auth::user()->reference_id ?? null,
                'usedReferenceCode' => false,
                'referrerUsername' => null,
                'referrals' => collect(),
                'privateShops' => collect(),
            ])->with('error', 'An error occurred while loading references.');
        }
    }

    /**
     * Store a vendor reference ID
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function storeVendorReference(Request $request): RedirectResponse
    {
        try {
            Log::debug('Adding vendor reference', ['user_id' => Auth::id()]);

            // Validate input
            $validated = $request->validate([
                'vendor_reference_id' => 'required|string|min:12|max:20|regex:/^[a-zA-Z0-9-]+$/',
            ], [
                'vendor_reference_id.regex' => 'Vendor reference ID can only contain letters, numbers, and hyphens.',
            ]);

            $user = Auth::user();
            $vendorReferenceId = $validated['vendor_reference_id'];

            // Find vendor by reference ID
            $vendor = User::whereHas('roles', function($query) {
                $query->where('name', 'vendor');
            })->get()->filter(function($user) use ($vendorReferenceId) {
                return $user->reference_id === $vendorReferenceId;
            })->first();

            if (!$vendor) {
                Log::warning('Invalid vendor reference ID', [
                    'user_id' => Auth::id(),
                    'reference_id' => $vendorReferenceId,
                ]);

                return redirect()->back()
                    ->with('error', 'Invalid vendor reference ID or not a vendor account.');
            }

            // Check if already added
            $exists = DB::table('private_shops')
                ->where('user_id', $user->id)
                ->where('vendor_reference_id', $vendorReferenceId)
                ->exists();

            if ($exists) {
                Log::warning('Vendor reference already added', [
                    'user_id' => Auth::id(),
                    'vendor_id' => $vendor->id,
                ]);

                return redirect()->back()
                    ->with('error', 'You have already added this vendor reference ID.');
            }

            // Insert private shop record
            DB::table('private_shops')->insert([
                'id' => Str::uuid(),
                'user_id' => $user->id,
                'vendor_id' => $vendor->id,
                'vendor_reference_id' => $vendorReferenceId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            Log::info('Vendor reference added', [
                'user_id' => Auth::id(),
                'vendor_id' => $vendor->id,
            ]);

            return redirect()->back()
                ->with('success', 'Vendor reference ID added successfully.');

        } catch (\Exception $e) {
            Log::error('Error adding vendor reference', [
                'user_id' => Auth::id(),
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->with('error', 'An error occurred while adding the vendor reference. Please try again.');
        }
    }

    /**
     * Remove a vendor reference ID
     *
     * @param string $id
     * @return RedirectResponse
     */
    public function removeVendorReference(string $id): RedirectResponse
    {
        try {
            Log::debug('Removing vendor reference', [
                'user_id' => Auth::id(),
                'reference_id' => $id,
            ]);

            $user = Auth::user();

            // Delete reference (verify ownership)
            $deleted = DB::table('private_shops')
                ->where('id', $id)
                ->where('user_id', $user->id)
                ->delete();

            if ($deleted) {
                Log::info('Vendor reference removed', [
                    'user_id' => Auth::id(),
                    'reference_id' => $id,
                ]);

                return redirect()->back()
                    ->with('success', 'Vendor reference ID removed successfully.');
            } else {
                Log::warning('Vendor reference not found or unauthorized', [
                    'user_id' => Auth::id(),
                    'reference_id' => $id,
                ]);

                return redirect()->back()
                    ->with('error', 'Vendor reference not found or you do not have permission to delete it.');
            }

        } catch (\Exception $e) {
            Log::error('Error removing vendor reference', [
                'user_id' => Auth::id(),
                'reference_id' => $id ?? 'unknown',
                'message' => $e->getMessage(),
            ]);

            return redirect()->back()
                ->with('error', 'An error occurred while removing the vendor reference. Please try again.');
        }
    }
}
