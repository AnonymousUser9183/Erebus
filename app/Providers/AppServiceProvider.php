<?php

/*
* =========================================================================
* © 2026 Erebus Development Team
* Author: AnonymousUser9183
* =========================================================================
* Application Service Provider
* =========================================================================
*/

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Carbon\Carbon;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
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
if ($this->app->environment('production')) {
URL::forceScheme('http');
// Use current request host instead of hardcoded APP_URL
$request = $this->app->make('request');
if ($request->getHost()) {
URL::forceRootUrl('http://' . $request->getHost());
}
}

Paginator::defaultView('components.pagination');
Paginator::defaultSimpleView('components.pagination');

// Set Carbon locale to English
Carbon::setLocale('en');
}
}
