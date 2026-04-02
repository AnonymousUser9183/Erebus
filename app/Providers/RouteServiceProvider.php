<?php

/*
 * =========================================================================
 * © 2026 Erebus Development Team
 * Author: AnonymousUser9183
 * =========================================================================
 * Route Service Provider
 * =========================================================================
 */

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path that users are redirected to after authentication.
     *
     * @var string
     */
    public const HOME = '/home';
    
    /**
     * The path that users are redirected to after guest-only routes.
     *
     * @var string
     */
    public const GUEST = '/guest-products';
    
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }
    
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
