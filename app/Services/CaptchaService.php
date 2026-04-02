<?php

/*
 * =========================================================================
 * © 2026 Erebus Development Team
 * Author: AnonymousUser9183
 * =========================================================================
 * Proof of Work CaptchaService
 * =========================================================================
 */

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class CaptchaService
{
    /**
     * Difficulty: number of leading zeros required
     * 4 = ~65,536 average hashes needed (10-20 clicks)
     */
    protected int $difficulty = 4;

    /**
     * Generate a new PoW challenge
     * Stores in session
     */
    public function generateChallenge(): void
    {
        $challenge = Str::random(32);
        
        Session::put('pow_challenge', $challenge);
        Session::put('pow_difficulty', $this->difficulty);
        Session::put('pow_current_nonce', 0);
        Session::put('pow_solution_nonce', null);
        Session::put('pow_solved', false);

        Log::debug('PoW challenge generated', [
            'challenge' => substr($challenge, 0, 8) . '...',
            'difficulty' => $this->difficulty,
        ]);
    }

    /**
     * Generate a captcha code (for backwards compatibility)
     * Returns a random code for the challenge
     */
    public function generateCode(): string
    {
        $this->generateChallenge();
        return Session::get('pow_challenge', '');
    }

    /**
     * Check if PoW has been solved
     */
    public function isSolved(): bool
    {
        return Session::get('pow_solved', false) === true;
    }

    /**
     * Get solution nonce if solved
     */
    public function getSolutionNonce(): ?int
    {
        return Session::get('pow_solution_nonce');
    }

    /**
     * Get current progress (number of hashes attempted)
     */
    public function getCurrentProgress(): int
    {
        return Session::get('pow_current_nonce', 0);
    }

    /**
     * Get the challenge string
     */
    public function getChallenge(): ?string
    {
        return Session::get('pow_challenge');
    }

    /**
     * Get difficulty
     */
    public function getDifficulty(): int
    {
        return Session::get('pow_difficulty', $this->difficulty);
    }

    /**
     * Solve next batch of hashes (10,000 per call)
     * Returns true if solution found
     */
    public function solveBatch(): bool
    {
        $challenge = Session::get('pow_challenge');
        $currentNonce = Session::get('pow_current_nonce', 0);
        $targetDifficulty = Session::get('pow_difficulty', $this->difficulty);

        if (!$challenge) {
            Log::warning('No PoW challenge in session');
            return false;
        }

        // Solve next batch (10,000 hashes)
        $batchSize = 10000;

        for ($i = 0; $i < $batchSize; $i++) {
            $currentNonce++;
            $hash = hash('sha256', $challenge . strval($currentNonce));

            // Check if hash starts with required zeros (difficulty)
            if (strpos($hash, str_repeat('0', $targetDifficulty)) === 0) {
                // Solution found!
                Session::put('pow_solution_nonce', $currentNonce);
                Session::put('pow_solved', true);
                Session::put('pow_current_nonce', $currentNonce);

                Log::info('PoW solved', [
                    'nonce' => $currentNonce,
                    'attempts' => $currentNonce,
                    'difficulty' => $targetDifficulty,
                ]);

                return true;
            }
        }

        // Update progress
        Session::put('pow_current_nonce', $currentNonce);

        Log::debug('PoW batch completed', [
            'batch_size' => $batchSize,
            'total_nonce' => $currentNonce,
        ]);

        return false;
    }

    /**
     * Automatically solve PoW (called during form submission)
     * Returns success status
     */
    public function autoSolve(): bool
    {
        // Try to solve in one go (good for most cases)
        $challenge = Session::get('pow_challenge');
        $targetDifficulty = Session::get('pow_difficulty', $this->difficulty);

        if (!$challenge) {
            return false;
        }

        // Solve with timeout protection
        $nonce = 0;
        $maxAttempts = 1000000; // ~1 million max attempts

        for ($i = 0; $i < $maxAttempts; $i++) {
            $nonce++;
            $hash = hash('sha256', $challenge . strval($nonce));

            if (strpos($hash, str_repeat('0', $targetDifficulty)) === 0) {
                // Found!
                Session::put('pow_solution_nonce', $nonce);
                Session::put('pow_solved', true);
                Session::put('pow_current_nonce', $nonce);

                Log::info('PoW auto-solved', [
                    'nonce' => $nonce,
                    'attempts' => $nonce,
                    'difficulty' => $targetDifficulty,
                ]);

                return true;
            }
        }

        // Not found in max attempts
        Log::warning('PoW auto-solve timeout', [
            'attempts' => $maxAttempts,
            'difficulty' => $targetDifficulty,
        ]);

        return false;
    }

    /**
     * Get estimated time to solve based on current progress
     * NEW METHOD: Supports support request PoW solving flow
     */
    public function getEstimatedTimeRemaining(): string
    {
        $currentNonce = $this->getCurrentProgress();
        $difficulty = $this->getDifficulty();

        // Average hashes needed = 2^difficulty
        // For difficulty 4: realistically ~65,536 on average
        $averageHashes = pow(2, $difficulty + 12);

        if ($currentNonce >= $averageHashes) {
            return 'Should be complete soon...';
        }

        $remaining = $averageHashes - $currentNonce;
        $hashesPerSecond = 50000; // 10,000 hashes per request, ~5 requests per second
        $secondsNeeded = ceil($remaining / $hashesPerSecond);
        $requestsNeeded = ceil($remaining / 10000);

        if ($secondsNeeded < 60) {
            return 'About ' . $requestsNeeded . ' more clicks (' . $secondsNeeded . ' seconds)...';
        }

        $minutesNeeded = ceil($secondsNeeded / 60);
        return 'About ' . $minutesNeeded . ' more minutes...';
    }

    /**
     * Clear PoW session data
     */
    public function clearSession(): void
    {
        Session::forget([
            'pow_challenge',
            'pow_difficulty',
            'pow_current_nonce',
            'pow_solution_nonce',
            'pow_solution_found_at',
            'pow_solved',
        ]);

        Log::debug('PoW session cleared');
    }

    /**
     * Reset difficulty (if needed)
     */
    public function setDifficulty(int $difficulty): void
    {
        $this->difficulty = $difficulty;
        Session::put('pow_difficulty', $difficulty);
    }
}
