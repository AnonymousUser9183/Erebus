<?php

/*
 * =========================================================================
 * © 2026 The Erebus Development Team
 * Author: AnonymousUser9183
 * =========================================================================
 * PoWeRebus Proof of Work Configuration File
 * =========================================================================
 */

return [
    // PoW Captcha Settings
    'pow_difficulty' => env('POW_DIFFICULTY', 4), // Number of leading zeros required (difficulty 4 = ~65,536 average hashes)
    'pow_batch_size' => env('POW_BATCH_SIZE', 10000), // Hashes per batch solve request
    'pow_max_attempts' => env('POW_MAX_ATTEMPTS', 1000000), // Maximum hashing attempts before timeout
    'pow_token_expiry_minutes' => env('POW_TOKEN_EXPIRY_MINUTES', 30), // Token validity duration
];
