<?php

/*
 * =========================================================================
 * © 2026 Erebus Development Team
 * Author: AnonymousUser9183
 * =========================================================================
 * Server Side Proof of Work CaptchaService
 * =========================================================================
 */

namespace App\Services;

use Illuminate\Support\Facades\Session;

class ServerPowCaptchaService
{
    /**
     * PoW difficulty: leading zero bytes required
     * 3 bytes = ~3-5 clicks
     * 4 bytes = ~10-20 clicks (RECOMMENDED)
     * 5 bytes = ~50-100 clicks
     */
    private const POW_DIFFICULTY = 4;

    /**
     * Hashes computed per click
     */
    private const BATCH_SIZE = 10000;

    /**
     * Challenge expires after 15 minutes
     */
    private const CHALLENGE_EXPIRY_MINUTES = 15;

    /**
     * Generate a new PoW challenge
     */
    public function generateChallenge(): array
    {
        $challenge = bin2hex(random_bytes(16));

        Session::put('pow_challenge', $challenge);
        Session::put('pow_current_nonce', 0);
        Session::put('pow_challenge_created_at', now()->timestamp);
        Session::forget('pow_solution_nonce');

        return [
            'challenge' => $challenge,
            'difficulty' => self::POW_DIFFICULTY,
        ];
    }

    /**
     * Solve the next batch of hashes
     * Returns: ['solved' => bool, 'attempts_so_far' => int, 'nonce' => ?string]
     */
    public function solveNextBatch(): array
    {
        // Verify challenge exists and is not expired
        if (!Session::has('pow_challenge')) {
            return [
                'solved' => false,
                'attempts_so_far' => 0,
                'message' => 'Challenge expired. Please refresh the page.',
            ];
        }

        $challenge = Session::get('pow_challenge');
        $currentNonce = Session::get('pow_current_nonce', 0);
        $createdAt = Session::get('pow_challenge_created_at', now()->timestamp);

        // Check if challenge expired
        if (now()->timestamp - $createdAt > (self::CHALLENGE_EXPIRY_MINUTES * 60)) {
            Session::forget(['pow_challenge', 'pow_current_nonce', 'pow_solution_nonce']);
            return [
                'solved' => false,
                'attempts_so_far' => 0,
                'message' => 'Challenge expired. Please refresh the page.',
            ];
        }

        // Compute next batch
        $targetLeadingBytes = self::POW_DIFFICULTY;
        $solution = null;

        for ($nonce = $currentNonce; $nonce < $currentNonce + self::BATCH_SIZE; $nonce++) {
            $hash = hash('sha256', $challenge . ':' . $nonce, true);

            // Check leading zero bytes
            $leadingZeros = 0;
            for ($i = 0; $i < strlen($hash); $i++) {
                if (ord($hash[$i]) === 0) {
                    $leadingZeros++;
                } else {
                    break;
                }
            }

            if ($leadingZeros >= $targetLeadingBytes) {
                $solution = $nonce;
                break;
            }
        }

        // Update progress
        $newNonce = $solution !== null ? $nonce : $currentNonce + self::BATCH_SIZE;
        Session::put('pow_current_nonce', $newNonce);

        if ($solution !== null) {
            // Solution found!
            Session::put('pow_solution_nonce', $solution);
            return [
                'solved' => true,
                'attempts_so_far' => $newNonce,
                'nonce' => $solution,
            ];
        }

        // Not solved yet
        return [
            'solved' => false,
            'attempts_so_far' => $newNonce,
            'nonce' => null,
        ];
    }

    /**
     * Validate a PoW solution
     */
    public function validateSolution(int $nonce): array
    {
        if (!Session::has('pow_challenge')) {
            return [
                'valid' => false,
                'message' => 'No challenge found. Please refresh the page.',
            ];
        }

        $challenge = Session::get('pow_challenge');
        $hash = hash('sha256', $challenge . ':' . $nonce, true);

        // Check leading zero bytes
        $leadingZeros = 0;
        for ($i = 0; $i < strlen($hash); $i++) {
            if (ord($hash[$i]) === 0) {
                $leadingZeros++;
            } else {
                break;
            }
        }

        if ($leadingZeros >= self::POW_DIFFICULTY) {
            return ['valid' => true];
        }

        return [
            'valid' => false,
            'message' => 'Invalid PoW solution.',
        ];
    }

    /**
     * Check if PoW is solved in current session
     */
    public function isSolved(): bool
    {
        return Session::has('pow_solution_nonce') && Session::get('pow_solution_nonce') !== null;
    }

    /**
     * Get solved nonce from session
     */
    public function getSolvedNonce(): ?int
    {
        if ($this->isSolved()) {
            return Session::get('pow_solution_nonce');
        }
        return null;
    }

    /**
     * Clear PoW session data
     */
    public function clearSession(): void
    {
        Session::forget([
            'pow_challenge',
            'pow_current_nonce',
            'pow_solution_nonce',
            'pow_challenge_created_at',
        ]);
    }
}
