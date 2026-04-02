<?php

/*
 * =========================================================================
 * © 2026 Erebus Development Team
 * Author: AnonymousUser9183
 * =========================================================================
 * Return Address Controller - Monero Return Address Management
 * =========================================================================
 */

namespace App\Http\Controllers;

use App\Models\ReturnAddress;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;
use MoneroIntegrations\MoneroPhp\Cryptonote;
use Exception;

class ReturnAddressController extends Controller
{
    /**
     * Cryptonote instance for Monero address validation
     */
    private Cryptonote $cryptonote;

    /**
     * Minimum Monero address length
     */
    private const MIN_ADDRESS_LENGTH = 40;

    /**
     * Maximum Monero address length
     */
    private const MAX_ADDRESS_LENGTH = 160;

    /**
     * Maximum return addresses per user
     */
    private const MAX_ADDRESSES_PER_USER = 8;

    /**
     * Initialize controller with Cryptonote instance
     */
    public function __construct()
    {
        try {
            $this->cryptonote = new Cryptonote();

            Log::debug('Cryptonote instance initialized for ReturnAddressController');

        } catch (Exception $e) {
            Log::error('Failed to initialize Monero Cryptonote', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw new Exception('Failed to initialize Monero address validation service.');
        }
    }

    /**
     * Display return addresses page
     *
     * @return View
     */
    public function index(): View
    {
        try {
            Log::debug('Loading return addresses page', ['user_id' => Auth::id()]);

            $user = Auth::user();
            $returnAddresses = $user->returnAddresses;

            Log::debug('Return addresses retrieved', [
                'user_id' => Auth::id(),
                'address_count' => $returnAddresses->count(),
            ]);

            return view('return-addresses', compact('returnAddresses'));

        } catch (Exception $e) {
            Log::error('Error loading return addresses page', [
                'user_id' => Auth::id(),
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return view('return-addresses', [
                'returnAddresses' => collect(),
            ]);
        }
    }

    /**
     * Store a new return address
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            if (!Auth::check()) {
                Log::warning('Unauthorized return address creation attempt');
                return back()->with('error', 'Unauthorized access.');
            }

            Log::debug('Attempting to add return address', ['user_id' => Auth::id()]);

            // Validate request
            try {
                $validated = $request->validate(
                    $this->getAddressValidationRules(),
                    $this->getAddressValidationMessages()
                );

            } catch (ValidationException $e) {
                Log::warning('Validation failed on return address creation', [
                    'user_id' => Auth::id(),
                    'errors' => $e->validator->errors()->all(),
                ]);

                return back()
                    ->withInput()
                    ->with('error', $e->validator->errors()->first());
            }

            $user = Auth::user();

            // Check if user has reached the address limit
            if ($user->returnAddresses()->count() >= self::MAX_ADDRESSES_PER_USER) {
                Log::warning('User attempted to exceed return address limit', [
                    'user_id' => Auth::id(),
                    'current_count' => $user->returnAddresses()->count(),
                    'max_allowed' => self::MAX_ADDRESSES_PER_USER,
                ]);

                return back()
                    ->withInput()
                    ->with('error', 'You can add a maximum of ' . self::MAX_ADDRESSES_PER_USER . ' return addresses.');
            }

            // Validate Monero address using Cryptonote
            if (!$this->isValidMoneroAddress($validated['monero_address'])) {
                Log::warning('Invalid Monero address provided', [
                    'user_id' => Auth::id(),
                    'address_length' => strlen($validated['monero_address']),
                ]);

                return back()
                    ->withInput()
                    ->with('error', 'Invalid Monero address. Please verify and try again.');
            }

            // Create return address
            try {
                ReturnAddress::create([
                    'user_id' => $user->id,
                    'monero_address' => $validated['monero_address'],
                ]);

                Log::info('Return address added successfully', [
                    'user_id' => Auth::id(),
                    'address_count' => $user->returnAddresses()->count() + 1,
                ]);

                return back()->with('success', 'Return address successfully added.');

            } catch (QueryException $e) {
                Log::error('Database error on return address creation', [
                    'user_id' => Auth::id(),
                    'message' => $e->getMessage(),
                ]);

                return back()
                    ->withInput()
                    ->with('error', 'An error occurred while adding your return address. Please try again or contact support.');

            } catch (Exception $e) {
                Log::error('Unexpected error on return address creation', [
                    'user_id' => Auth::id(),
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);

                return back()
                    ->withInput()
                    ->with('error', 'An unexpected error occurred. Please try again or contact support.');
            }

        } catch (Exception $e) {
            Log::error('Unhandled error on return address store', [
                'user_id' => Auth::id(),
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()
                ->withInput()
                ->with('error', 'An unexpected error occurred. Please try again or contact support.');
        }
    }

    /**
     * Delete a return address
     *
     * @param ReturnAddress $returnAddress
     * @return RedirectResponse
     */
    public function destroy(ReturnAddress $returnAddress): RedirectResponse
    {
        try {
            if (!Auth::check()) {
                Log::warning('Unauthorized return address deletion attempt');
                return back()->with('error', 'Unauthorized access.');
            }

            Log::debug('Attempting to delete return address', [
                'user_id' => Auth::id(),
                'address_id' => $returnAddress->id,
            ]);

            // Verify ownership
            if ($returnAddress->user_id !== Auth::id()) {
                Log::warning('Unauthorized deletion attempt for return address', [
                    'user_id' => Auth::id(),
                    'address_id' => $returnAddress->id,
                    'owner_id' => $returnAddress->user_id,
                ]);

                abort(403, 'Unauthorized action.');
            }

            try {
                $returnAddress->delete();

                Log::info('Return address deleted successfully', [
                    'user_id' => Auth::id(),
                    'address_id' => $returnAddress->id,
                ]);

                return back()->with('success', 'Return address successfully deleted.');

            } catch (QueryException $e) {
                Log::error('Database error on return address deletion', [
                    'user_id' => Auth::id(),
                    'address_id' => $returnAddress->id,
                    'message' => $e->getMessage(),
                ]);

                return back()->with('error', 'An error occurred while deleting your return address. Please try again or contact support.');

            } catch (Exception $e) {
                Log::error('Unexpected error on return address deletion', [
                    'user_id' => Auth::id(),
                    'address_id' => $returnAddress->id,
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);

                return back()->with('error', 'An unexpected error occurred. Please try again or contact support.');
            }

        } catch (Exception $e) {
            Log::error('Unhandled error on return address destroy', [
                'user_id' => Auth::id(),
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->with('error', 'An unexpected error occurred. Please try again or contact support.');
        }
    }

    // ==================== PRIVATE HELPER METHODS ====================

    /**
     * Validate Monero address using Cryptonote
     *
     * @param string $address
     * @return bool
     */
    private function isValidMoneroAddress(string $address): bool
    {
        try {
            Log::debug('Validating Monero address', [
                'address_length' => strlen($address),
                'user_id' => Auth::id(),
            ]);

            // Check checksum validity
            if (!$this->cryptonote->verify_checksum($address)) {
                Log::warning('Monero address checksum validation failed', [
                    'user_id' => Auth::id(),
                    'address_length' => strlen($address),
                ]);

                return false;
            }

            // Attempt to decode address to validate format
            $decoded = $this->cryptonote->decode_address($address);

            // If decode succeeds, address is valid
            Log::debug('Monero address validation successful', [
                'user_id' => Auth::id(),
                'address_length' => strlen($address),
            ]);

            return true;

        } catch (Exception $e) {
            Log::warning('Monero address validation error', [
                'user_id' => Auth::id(),
                'address_length' => strlen($address),
                'message' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Get address validation rules
     *
     * @return array<string, array<int|string, mixed>>
     */
    private function getAddressValidationRules(): array
    {
        return [
            'monero_address' => [
                'required',
                'string',
                'min:' . self::MIN_ADDRESS_LENGTH,
                'max:' . self::MAX_ADDRESS_LENGTH,
                Rule::unique('return_addresses')->where(function ($query) {
                    return $query->where('user_id', Auth::id());
                }),
            ],
        ];
    }

    /**
     * Get address validation messages
     *
     * @return array<string, string>
     */
    private function getAddressValidationMessages(): array
    {
        return [
            'monero_address.required' => 'Monero address is required.',
            'monero_address.string' => 'Monero address must be a string.',
            'monero_address.min' => 'Monero address must be at least ' . self::MIN_ADDRESS_LENGTH . ' characters.',
            'monero_address.max' => 'Monero address cannot exceed ' . self::MAX_ADDRESS_LENGTH . ' characters.',
            'monero_address.unique' => 'This Monero address has already been added to your account.',
        ];
    }
}
