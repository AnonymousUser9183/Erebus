<?php

/*
 * =========================================================================
 * © 2026 Erebus Development Team
 * Author: AnonymousUser9183
 * =========================================================================
 * Captcha Controller - Proof of Work Challenge Management
 * =========================================================================
 */

namespace App\Http\Controllers;

use App\Services\CaptchaService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class CaptchaController extends Controller
{
    /**
     * Captcha service instance
     *
     * @var CaptchaService
     */
    protected CaptchaService $captchaService;

    /**
     * Initialize controller with CaptchaService
     */
    public function __construct(CaptchaService $captchaService)
    {
        Log::debug('CaptchaController constructor called');
        $this->captchaService = $captchaService;
    }

    /**
     * Handle PoW challenge generation - Server-side only
     * Called when user first visits login/register page
     *
     * @return bool
     */
    public function generateChallenge(): bool
    {
        try {
            Log::debug('Generating PoW challenge', ['user_id' => auth()->id()]);

            // Clear any previous PoW session data
            $this->captchaService->clearSession();

            // Generate a new challenge
            $this->captchaService->generateChallenge();

            $challenge = $this->captchaService->getChallenge();
            $difficulty = $this->captchaService->getDifficulty();

            if (!$challenge) {
                Log::error('Failed to generate challenge - no challenge returned');
                return false;
            }

            Log::debug('PoW challenge generated successfully', [
                'challenge' => substr($challenge, 0, 8) . '...',
                'difficulty' => $difficulty,
                'user_id' => auth()->id(),
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error('Error generating challenge', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return false;
        }
    }

    /**
     * Solve the current PoW challenge - Server-side only
     * Called when user clicks "Compute PoW & Login/Register"
     *
     * @return bool
     */
    public function solveChallenge(): bool
    {
        try {
            Log::debug('Attempting to solve PoW challenge', ['user_id' => auth()->id()]);

            // Check if there's an active challenge
            if (!Session::has('pow_challenge')) {
                Log::warning('PoW solve attempted without active challenge');
                return false;
            }

            // Auto-solve the challenge using server-side logic
            $solved = $this->captchaService->autoSolve();

            if ($solved) {
                // Get the solution nonce
                $nonce = $this->captchaService->getSolutionNonce();

                // Generate token and store in session
                $token = $this->generateToken();

                Log::info('PoW solved server-side successfully', [
                    'nonce' => substr($nonce, 0, 8) . '...',
                    'difficulty' => $this->captchaService->getDifficulty(),
                    'token' => substr($token, 0, 20) . '...',
                    'user_id' => auth()->id(),
                ]);

                return true;
            } else {
                Log::warning('PoW auto-solve failed - max attempts reached', [
                    'user_id' => auth()->id(),
                ]);
                return false;
            }

        } catch (\Exception $e) {
            Log::error('Error solving challenge', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => auth()->id(),
            ]);
            return false;
        }
    }

    /**
     * Generate a NoctiPoW token and store in session
     * Format: nocti_[9_random_chars]_[timestamp]
     *
     * @return string
     */
    private function generateToken(): string
    {
        $randomPart = strtolower(Str::random(9));
        $timestamp = now()->timestamp;
        $token = "nocti_{$randomPart}_{$timestamp}";

        // Store token in session for validation on next request
        Session::put('pow_token_valid', $token);
        Session::put('pow_token_timestamp', $timestamp);

        Log::debug('PoW token generated', [
            'token_prefix' => substr($token, 0, 20) . '...',
            'user_id' => auth()->id(),
        ]);

        return $token;
    }
}
