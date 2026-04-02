<?php

/*
 * =========================================================================
 * © 2026 Erebus Development Team
 * Author: AnonymousUser9183
 * =========================================================================
 * ValidatePostSize Middleware
 * =========================================================================
 */

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\ValidatePostSize as Middleware;

class ValidatePostSize extends Middleware
{
    /**
     * The maximum allowed POST data size.
     *
     * @var int
     */
    protected $max = null;
}
