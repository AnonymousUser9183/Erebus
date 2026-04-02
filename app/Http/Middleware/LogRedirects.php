<?php

/*
 * =========================================================================
 * © 2026 Erebus Development Team
 * Author: AnonymousUser9183
 * =========================================================================
 * LogRedirects Middleware
 * =========================================================================
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LogRedirects
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Log all redirects (3xx status codes)
        if ($response instanceof \Illuminate\Http\RedirectResponse || (isset($response->status) && $response->status >= 300 && $response->status < 400)) {
            Log::channel('single')->warning('REDIRECT DETECTED', [
                'from_path' => $request->path(),
                'from_url' => $request->url(),
                'to_url' => $response->headers->get('location') ?? 'unknown',
                'status' => $response->status ?? 'unknown',
                'auth_check' => auth()->check(),
                'route_name' => $request->route()?->getName(),
                'timestamp' => now()->toDateTimeString(),
                'referer' => $request->headers->get('referer')
            ]);
        }

        return $response;
    }
}
