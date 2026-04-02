<?php

/*
 * =========================================================================
 * © 2026 Erebus Development Team
 * Author: AnonymousUser9183
 * =========================================================================
 * TrustProxies Middleware
 * =========================================================================
 */

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustProxies as Middleware;
use Illuminate\Http\Request;
use Closure;

class TrustProxies extends Middleware
{
    protected $proxies = '*';

    protected $headers =
        Request::HEADER_X_FORWARDED_FOR |
        Request::HEADER_X_FORWARDED_HOST |
        Request::HEADER_X_FORWARDED_PORT |
        Request::HEADER_X_FORWARDED_PROTO |
        Request::HEADER_X_FORWARDED_AWS_ELB;

    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Trust all proxies (Tor nodes are typically proxies)
        $this->proxies = '*';

        // Additional security headers (CSP is handled by AddCspHeaders middleware)
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('X-XSS-Protection', '0');
        $response->headers->set('Referrer-Policy', 'no-referrer');
        
        // Strict Transport Security
        $response->headers->set('Strict-Transport-Security', 'max-age=63072000; includeSubDomains; preload');

        // Cross-Origin Policies
        $response->headers->set('Cross-Origin-Opener-Policy', 'same-origin');
        $response->headers->set('Cross-Origin-Resource-Policy', 'same-origin');
        $response->headers->set('Cross-Origin-Embedder-Policy', 'require-corp');

        // DNS Prefetch Control
        $response->headers->set('X-DNS-Prefetch-Control', 'off');

        // Cache control for sensitive pages
        if ($this->isSensitivePage($request)) {
            $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0, private');
            $response->headers->set('Pragma', 'no-cache');
            $response->headers->set('Expires', '0');
        }

        return $response;
    }

    /**
     * Determine if the current request is to a sensitive page
     * that should not be cached
     *
     * @param  Request  $request
     * @return bool
     */
    private function isSensitivePage(Request $request): bool
    {
        $sensitivePaths = [
            '/account',
            '/dashboard',
            '/checkout',
            '/payment',
            '/profile',
            '/settings',
            '/orders',
            '/admin',
        ];

        foreach ($sensitivePaths as $path) {
            if ($request->is($path) || $request->is($path . '/*')) {
                return true;
            }
        }

        return false;
    }
}
