<?php

/*
 * =========================================================================
 * © 2026 Erebus Development Team
 * Author: AnonymousUser9183
 * =========================================================================
 * Settings Controller - User Account Settings, Passwords, PGP Keys, & Security
 * =========================================================================
 */

namespace App\Http\Controllers;

use App\Models\PgpKey;
use App\Models\SecretPhrase;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;
use Exception;

class SettingsController extends Controller
{
    /**
     * Password validation regex pattern
     * Requires: uppercase, lowercase, number, special character
     */
    private const PASSWORD_REGEX = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[#$%&@^`~.,:;"\'\/|_\-<>*+!?={\[\]()\}\]])[A-Za-z\d#$%&@^`~.,:;"\'\/|_\-<>*+!?={\[\]()\}\]]{8,}$/';

    /**
     * PGP key validation regex pattern
     * Validates ASCII-armored PGP public key format
     */
    private const PGP_KEY_REGEX = '/^-----BEGIN PGP PUBLIC KEY BLOCK-----.*-----END PGP PUBLIC KEY BLOCK-----$/s';

    /**
     * Secret phrase validation regex pattern
     * Letters only, no numbers or special characters
     */
    private const SECRET_PHRASE_REGEX = '/^[a-zA-Z]+$/';

    /**
     * Minimum password length
     */
    private const MIN_PASSWORD_LENGTH = 8;

    /**
     * Maximum password length
     */
    private const MAX_PASSWORD_LENGTH = 40;

    /**
     * Minimum PGP key length
     */
    private const MIN_PGP_KEY_LENGTH = 100;

    /**
     * Maximum PGP key length
     */
    private const MAX_PGP_KEY_LENGTH = 8000;

    /**
     * Minimum secret phrase length
     */
    private const MIN_SECRET_PHRASE_LENGTH = 4;

    /**
     * Maximum secret phrase length
     */
    private const MAX_SECRET_PHRASE_LENGTH = 16;

    /**
     * Display user settings page
     *
     * @return View
     */
    public function index(): View
    {
        try {
            Log::debug('Loading user settings page', ['user_id' => Auth::id()]);

            $user = Auth::user();
            $returnAddresses = $user->returnAddresses;

            Log::debug('Settings page loaded', [
                'user_id' => Auth::id(),
                'return_addresses_count' => $returnAddresses->count(),
            ]);

            return view('settings', compact('user', 'returnAddresses'));

        } catch (Exception $e) {
            Log::error('Error loading settings page', [
                'user_id' => Auth::id(),
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return view('settings', [
                'user' => Auth::user(),
                'returnAddresses' => collect(),
            ]);
        }
    }

    /**
     * Change user password
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function changePassword(Request $request): RedirectResponse
    {
        try {
            if (!Auth::check()) {
                Log::warning('Unauthorized password change attempt');
                return back()->with('error', 'Unauthorized access.');
            }

            Log::debug('Attempting password change', ['user_id' => Auth::id()]);

            // Validate request
            try {
                $validated = $request->validate(
                    $this->getPasswordValidationRules(),
                    $this->getPasswordValidationMessages()
                );

            } catch (ValidationException $e) {
                Log::warning('Validation failed on password change', [
                    'user_id' => Auth::id(),
                    'errors' => $e->validator->errors()->all(),
                ]);

                return back()
                    ->withInput()
                    ->with('error', $e->validator->errors()->first());
            }

            $user = Auth::user();

            // Verify current password
            if (!Hash::check($request->current_password, $user->password)) {
                Log::warning('Invalid current password provided', [
                    'user_id' => Auth::id(),
                ]);

                return back()
                    ->withInput()
                    ->with('error', 'The entered password does not match your current password.');
            }

            // Update password
            try {
                $user->password = Hash::make($validated['password']);
                $user->save();

                Log::info('Password changed successfully', [
                    'user_id' => Auth::id(),
                ]);

                // Regenerate session token for security
                $request->session()->regenerate();

                return back()->with('status', 'Password successfully changed. Your session has been renewed for security purposes.');

            } catch (QueryException $e) {
                Log::error('Database error on password change', [
                    'user_id' => Auth::id(),
                    'message' => $e->getMessage(),
                ]);

                return back()
                    ->withInput()
                    ->with('error', 'An error occurred while changing your password. Please try again or contact support.');

            } catch (Exception $e) {
                Log::error('Unexpected error on password change', [
                    'user_id' => Auth::id(),
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);

                return back()
                    ->withInput()
                    ->with('error', 'An unexpected error occurred. Please try again or contact support.');
            }

        } catch (Exception $e) {
            Log::error('Unhandled error on password change', [
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
     * Update PGP public key
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function updatePgpKey(Request $request): RedirectResponse
    {
        try {
            if (!Auth::check()) {
                Log::warning('Unauthorized PGP key update attempt');
                return back()->with('error', 'Unauthorized access.');
            }

            Log::debug('Attempting PGP key update', ['user_id' => Auth::id()]);

            // Validate request
            try {
                $validated = $request->validate(
                    $this->getPgpKeyValidationRules(),
                    $this->getPgpKeyValidationMessages()
                );

            } catch (ValidationException $e) {
                Log::warning('Validation failed on PGP key update', [
                    'user_id' => Auth::id(),
                    'errors' => $e->validator->errors()->all(),
                ]);

                return back()
                    ->withInput()
                    ->with('error', $e->validator->errors()->first());
            }

            $user = Auth::user();
            $pgpKey = $user->pgpKey ?? new PgpKey();

            try {
                $pgpKey->user_id = $user->id;
                $pgpKey->public_key = $validated['public_key'];
                $pgpKey->verified = false; // Reset verification status
                $pgpKey->save();

                Log::info('PGP key updated successfully', [
                    'user_id' => Auth::id(),
                    'pgp_key_id' => $pgpKey->id,
                ]);

                return redirect()
                    ->route('pgp.confirm')
                    ->with('success', 'PGP key saved. Please verify it now to activate it.');

            } catch (QueryException $e) {
                Log::error('Database error on PGP key update', [
                    'user_id' => Auth::id(),
                    'message' => $e->getMessage(),
                ]);

                return back()
                    ->withInput()
                    ->with('error', 'An error occurred while updating your PGP key. Please try again or contact support.');

            } catch (Exception $e) {
                Log::error('Unexpected error on PGP key update', [
                    'user_id' => Auth::id(),
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);

                return back()
                    ->withInput()
                    ->with('error', 'An unexpected error occurred. Please try again or contact support.');
            }

        } catch (Exception $e) {
            Log::error('Unhandled error on PGP key update', [
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
     * Update user secret phrase
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function updateSecretPhrase(Request $request): RedirectResponse
    {
        try {
            if (!Auth::check()) {
                Log::warning('Unauthorized secret phrase update attempt');
                return back()->with('error', 'Unauthorized access.');
            }

            Log::debug('Attempting secret phrase update', ['user_id' => Auth::id()]);

            // Validate request
            try {
                $validated = $request->validate(
                    $this->getSecretPhraseValidationRules(),
                    $this->getSecretPhraseValidationMessages()
                );

            } catch (ValidationException $e) {
                Log::warning('Validation failed on secret phrase update', [
                    'user_id' => Auth::id(),
                    'errors' => $e->validator->errors()->all(),
                ]);

                return back()
                    ->withInput()
                    ->with('error', $e->validator->errors()->first());
            }

            $user = Auth::user();

            try {
                // Check if user already has a secret phrase
                if ($user->secretPhrase) {
                    Log::warning('User attempted to set secret phrase twice', [
                        'user_id' => Auth::id(),
                    ]);

                    return back()->with('error', 'You already have a secret phrase. This is a one-time setting for security purposes.');
                }

                // Create new secret phrase
                $secretPhrase = new SecretPhrase([
                    'user_id' => $user->id,
                    'phrase' => $validated['secret_phrase'],
                ]);

                $secretPhrase->save();

                Log::info('Secret phrase created successfully', [
                    'user_id' => Auth::id(),
                    'secret_phrase_id' => $secretPhrase->id,
                ]);

                return back()->with('status', 'Secret phrase successfully added. This phrase will be displayed on your settings page to help you identify genuine site access.');

            } catch (QueryException $e) {
                Log::error('Database error on secret phrase creation', [
                    'user_id' => Auth::id(),
                    'message' => $e->getMessage(),
                ]);

                return back()
                    ->withInput()
                    ->with('error', 'An error occurred while adding your secret phrase. Please try again or contact support.');

            } catch (Exception $e) {
                Log::error('Unexpected error on secret phrase creation', [
                    'user_id' => Auth::id(),
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);

                return back()
                    ->withInput()
                    ->with('error', 'An unexpected error occurred. Please try again or contact support.');
            }

        } catch (Exception $e) {
            Log::error('Unhandled error on secret phrase update', [
                'user_id' => Auth::id(),
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()
                ->withInput()
                ->with('error', 'An unexpected error occurred. Please try again or contact support.');
        }
    }

    // ==================== PRIVATE HELPER METHODS ====================

    /**
     * Get password validation rules
     *
     * @return array<string, array<int|string, string>>
     */
    private function getPasswordValidationRules(): array
    {
        return [
            'current_password' => [
                'required',
                'string',
                'min:' . self::MIN_PASSWORD_LENGTH,
                'max:' . self::MAX_PASSWORD_LENGTH,
            ],
            'password' => [
                'required',
                'string',
                'min:' . self::MIN_PASSWORD_LENGTH,
                'max:' . self::MAX_PASSWORD_LENGTH,
                'confirmed',
                'regex:' . self::PASSWORD_REGEX,
            ],
            'password_confirmation' => [
                'required',
                'string',
            ],
        ];
    }

    /**
     * Get password validation messages
     *
     * @return array<string, string>
     */
    private function getPasswordValidationMessages(): array
    {
        return [
            'current_password.required' => 'Current password is required.',
            'current_password.string' => 'Current password must be a string.',
            'current_password.min' => 'Current password must be at least ' . self::MIN_PASSWORD_LENGTH . ' characters.',
            'current_password.max' => 'Current password cannot exceed ' . self::MAX_PASSWORD_LENGTH . ' characters.',
            'password.required' => 'New password is required.',
            'password.string' => 'New password must be a string.',
            'password.min' => 'New password must be at least ' . self::MIN_PASSWORD_LENGTH . ' characters.',
            'password.max' => 'New password cannot exceed ' . self::MAX_PASSWORD_LENGTH . ' characters.',
            'password.confirmed' => 'New password confirmation does not match.',
            'password.regex' => 'New password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.',
            'password_confirmation.required' => 'Please confirm your new password.',
            'password_confirmation.string' => 'Password confirmation must be a string.',
        ];
    }

    /**
     * Get PGP key validation rules
     *
     * @return array<string, array<int|string, string>>
     */
    private function getPgpKeyValidationRules(): array
    {
        return [
            'public_key' => [
                'required',
                'string',
                'min:' . self::MIN_PGP_KEY_LENGTH,
                'max:' . self::MAX_PGP_KEY_LENGTH,
                'regex:' . self::PGP_KEY_REGEX,
            ],
        ];
    }

    /**
     * Get PGP key validation messages
     *
     * @return array<string, string>
     */
    private function getPgpKeyValidationMessages(): array
    {
        return [
            'public_key.required' => 'Your PGP public key is required.',
            'public_key.string' => 'PGP public key must be a string.',
            'public_key.min' => 'PGP public key must be at least ' . self::MIN_PGP_KEY_LENGTH . ' characters.',
            'public_key.max' => 'PGP public key must not exceed ' . self::MAX_PGP_KEY_LENGTH . ' characters.',
            'public_key.regex' => 'PGP public key must be in the correct format (ASCII-armored PGP public key block).',
        ];
    }

    /**
     * Get secret phrase validation rules
     *
     * @return array<string, array<int|string, string>>
     */
    private function getSecretPhraseValidationRules(): array
    {
        return [
            'secret_phrase' => [
                'required',
                'string',
                'min:' . self::MIN_SECRET_PHRASE_LENGTH,
                'max:' . self::MAX_SECRET_PHRASE_LENGTH,
                'regex:' . self::SECRET_PHRASE_REGEX,
            ],
        ];
    }

    /**
     * Get secret phrase validation messages
     *
     * @return array<string, string>
     */
    private function getSecretPhraseValidationMessages(): array
    {
        return [
            'secret_phrase.required' => 'Secret phrase is required.',
            'secret_phrase.string' => 'Secret phrase must be a string.',
            'secret_phrase.min' => 'Secret phrase must be at least ' . self::MIN_SECRET_PHRASE_LENGTH . ' characters.',
            'secret_phrase.max' => 'Secret phrase cannot exceed ' . self::MAX_SECRET_PHRASE_LENGTH . ' characters.',
            'secret_phrase.regex' => 'Secret phrase must contain only letters (no numbers or special characters).',
        ];
    }
}
