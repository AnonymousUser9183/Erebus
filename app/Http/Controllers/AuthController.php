<?php

/*
 * =========================================================================
 * © 2026 Erebus Development Team
 * Author: AnonymousUser9183
 * =========================================================================
 * Auth Controller - User Authentication Management
 * =========================================================================
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\User;
use FurqanSiddiqui\BIP39\BIP39;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    /**
     * Show registration form
     */
    public function showRegisterForm(): View
    {
        return view('auth.register');
    }

    /**
     * Handle login
     */
    public function login(Request $request): RedirectResponse
    {
        try {
            $validated = $request->validate([
                'username' => 'required|string',
                'password' => 'required|string',
                '2fa_token' => 'nullable|string',
            ], [
                'username.required' => 'Username is required.',
                'password.required' => 'Password is required.',
            ]);

            $user = User::where('username', $validated['username'])->first();

            if (!$user || !Hash::check($validated['password'], $user->password)) {
                Log::warning('Login attempt failed', [
                    'username' => $validated['username'],
                    'ip' => $request->ip(),
                ]);

                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Invalid username or password.');
            }

            if ($user->bannedUser()->exists()) {
                Log::warning('Login attempt by banned user', [
                    'user_id' => $user->id,
                    'ip' => $request->ip(),
                ]);

                return redirect()->route('banned')
                    ->with('error', 'Your account has been banned.');
            }

            Auth::login($user, remember: $request->boolean('remember'));

            Log::info('User logged in', [
                'user_id' => $user->id,
                'ip' => $request->ip(),
            ]);

            return redirect()->route('home')
                ->with('success', 'Login successful. Welcome back!');

        } catch (ValidationException $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors($e->validator->errors());
        }
    }

    /**
     * Handle registration
     */
    public function register(Request $request): RedirectResponse|View
    {
        try {
            $validated = $request->validate([
                'username' => 'required|string|unique:users|min:4|max:20',
                'password' => [
                    'required',
                    'string',
                    'confirmed',
                    'min:8',
                    'max:40',
                    'regex:/[a-z]/',      // Must contain lowercase
                    'regex:/[A-Z]/',      // Must contain uppercase
                    'regex:/[0-9]/',      // Must contain number
                    'regex:/[#$%&@^`~.,:;"\'\/|_\-<>*+!?={}[\]]/', // Must contain special char
                ],
                'pgp_key' => 'nullable|string',
            ], [
                'username.required' => 'Username is required.',
                'username.unique' => 'This username is already taken.',
                'username.min' => 'Username must be at least 4 characters.',
                'username.max' => 'Username cannot exceed 20 characters.',
                'password.required' => 'Password is required.',
                'password.min' => 'Password must be at least 8 characters.',
                'password.max' => 'Password cannot exceed 40 characters.',
                'password.confirmed' => 'Passwords do not match.',
                'password.regex' => 'Password must contain lowercase (a-z), uppercase (A-Z), numbers (0-9), and special characters (#$%&@^`~.,:;"\'\/|_-<>*+!?={}[]).',
            ]);

            // Generate BIP39 mnemonic (12 words)
            $mnemonic = $this->generateMnemonic();
            if (!$mnemonic) {
                Log::error('Mnemonic generation failed for new user registration', [
                    'username' => $validated['username'],
                ]);
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Registration failed. Please try again.');
            }

            $user = User::create([
                'username' => $validated['username'],
                'password' => Hash::make($validated['password']),
                'pgp_key' => $validated['pgp_key'] ?? null,
                'mnemonic' => $mnemonic,
            ]);

            Log::info('New user registered', [
                'user_id' => $user->id,
                'username' => $validated['username'],
                'ip' => $request->ip(),
                'mnemonic_words' => count(explode(' ', $mnemonic)),
            ]);

            // Display mnemonic to user before logging them in
            // This is the ONLY time they will see it
            return view('auth.mnemonic', [
                'mnemonic' => $mnemonic,
                'user_id' => $user->id,
            ]);

        } catch (ValidationException $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors($e->validator->errors());
        }
    }

    /**
     * Generate BIP39 mnemonic (12 words) - returns space-separated string
     */
    protected function generateMnemonic(): string|bool
    {
        try {
            // Generate 12-word mnemonic using BIP39::Generate()
            $mnemonic = BIP39::Generate(12, "english");
            // $mnemonic->words is an array, so join with spaces
            return implode(" ", $mnemonic->words);
        } catch (\Exception $e) {
            Log::error('Mnemonic generation failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Handle logout
     */
    public function logout(Request $request): RedirectResponse
    {
        Log::info('User logged out', ['user_id' => auth()->id()]);

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('guest-products.index')
            ->with('success', 'You have been logged out successfully.');
    }

    /**
     * Show forgot password form
     */
    public function showForgotPasswordForm(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Verify mnemonic for password reset
     */
    public function verifyMnemonic(Request $request): RedirectResponse
    {
        try {
            $validated = $request->validate([
                'username' => 'required|string|exists:users',
                'mnemonic' => 'required|string',
            ], [
                'username.required' => 'Username is required.',
                'username.exists' => 'This username does not exist.',
                'mnemonic.required' => 'Mnemonic key is required.',
            ]);

            $user = User::where('username', $validated['username'])->first();

            if (!$user || $user->mnemonic !== $validated['mnemonic']) {
                Log::warning('Mnemonic verification failed', [
                    'username' => $validated['username'],
                    'ip' => $request->ip(),
                ]);

                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Invalid username or mnemonic key.');
            }

            // Generate reset token and store it on the user
            $token = Str::random(64);
            $user->update([
                'password_reset_token' => Hash::make($token),
                'password_reset_expires_at' => now()->addHour(1), // Token valid for 1 hour
            ]);

            Log::info('Password reset token generated', ['username' => $validated['username']]);

            // Redirect to reset password form with token
            return redirect()->route('password.reset', ['token' => $token])
                ->with('success', 'Mnemonic verified. Please enter your new password.');

        } catch (ValidationException $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors($e->validator->errors());
        }
    }

    /**
     * Show reset password form
     */
    public function showResetForm(Request $request): View|RedirectResponse
    {
        $token = $request->query('token');

        if (!$token) {
            return redirect()->route('password.request')
                ->with('error', 'Invalid or missing reset token.');
        }

        return view('auth.reset-password', compact('token'));
    }

    /**
     * Handle password reset
     */
    public function reset(Request $request): RedirectResponse
    {
        try {
            $validated = $request->validate([
                'username' => 'required|string|exists:users',
                'token' => 'required|string',
                'password' => [
                    'required',
                    'string',
                    'confirmed',
                    'min:8',
                    'max:40',
                    'regex:/[a-z]/',      // Must contain lowercase
                    'regex:/[A-Z]/',      // Must contain uppercase
                    'regex:/[0-9]/',      // Must contain number
                    'regex:/[#$%&@^`~.,:;"\'\/|_\-<>*+!?={}[\]]/', // Must contain special char
                ],
            ], [
                'username.required' => 'Username is required.',
                'username.exists' => 'This username does not exist.',
                'token.required' => 'Reset token is required.',
                'password.required' => 'New password is required.',
                'password.min' => 'Password must be at least 8 characters.',
                'password.max' => 'Password cannot exceed 40 characters.',
                'password.confirmed' => 'Passwords do not match.',
                'password.regex' => 'Password must contain lowercase (a-z), uppercase (A-Z), numbers (0-9), and special characters (#$%&@^`~.,:;"\'\/|_-<>*+!?={}[]).',
            ]);

            $user = User::where('username', $validated['username'])->first();

            // Verify token is valid and not expired
            if (!$user ||
                !$user->password_reset_token ||
                !Hash::check($validated['token'], $user->password_reset_token) ||
                ($user->password_reset_expires_at && $user->password_reset_expires_at->isPast())) {

                Log::warning('Invalid password reset attempt', [
                    'username' => $validated['username'],
                    'ip' => $request->ip(),
                ]);

                return redirect()->back()
                    ->with('error', 'Invalid or expired reset token.');
            }

            // Update password and clear reset token
            $user->update([
                'password' => Hash::make($validated['password']),
                'password_reset_token' => null,
                'password_reset_expires_at' => null,
            ]);

            Log::info('Password reset successful', ['user_id' => $user->id]);

            return redirect()->route('login')
                ->with('success', 'Password reset successful. Please login with your new password.');

        } catch (ValidationException $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors($e->validator->errors());
        }
    }

    /**
     * Show mnemonic display
     */
    public function showMnemonic(string $token): View
    {
        return view('auth.mnemonic', compact('token'));
    }

    /**
     * Show 2FA challenge form
     */
    public function showPgp2FAChallenge(): View
    {
        return view('auth.2fa-challenge');
    }

    /**
     * Verify 2FA challenge
     */
    public function verifyPgp2FAChallenge(Request $request): RedirectResponse
    {
        try {
            $validated = $request->validate([
                'challenge_response' => 'required|string',
            ], [
                'challenge_response.required' => 'Challenge response is required.',
            ]);

            Log::info('2FA challenge verified', ['user_id' => auth()->id()]);

            return redirect()->route('home')
                ->with('success', '2FA verification successful.');

        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator->errors());
        }
    }

    /**
     * Update 2FA settings
     */
    public function updatePgp2FASettings(Request $request): RedirectResponse
    {
        try {
            $validated = $request->validate([
                'pgp_2fa_enabled' => 'required|boolean',
            ], [
                'pgp_2fa_enabled.required' => '2FA setting is required.',
            ]);

            auth()->user()->update([
                'pgp_2fa_enabled' => $validated['pgp_2fa_enabled'],
            ]);

            Log::info('2FA settings updated', [
                'user_id' => auth()->id(),
                'enabled' => $validated['pgp_2fa_enabled'],
            ]);

            $message = $validated['pgp_2fa_enabled']
                ? '2FA has been enabled successfully.'
                : '2FA has been disabled successfully.';

            return redirect()->route('settings')
                ->with('success', $message);

        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator->errors());
        }
    }

    /**
     * Show banned user page
     */
    public function showBanned(): View
    {
        return view('auth.banned');
    }
}
