<?php

/*
 * =========================================================================
 * © 2026 Erebus Development Team
 * Author: AnonymousUser9183
 * =========================================================================
 * VendorMiddleware Middleware
 * =========================================================================
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VendorMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->user() || !$request->user()->isVendor()) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
