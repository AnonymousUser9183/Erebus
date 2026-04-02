<?php

/*
 * =========================================================================
 * © 2026 The Erebus Development Team
 * Author: AnonymousUser9183
 * =========================================================================
 * Laravel 12 Bootstrap Configuration with Middleware & Exceptions
 * Kernel.php responsibilities are now here in Laravel 12
 * =========================================================================
 */

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

return Application::configure(basePath: dirname(__DIR__))
->withRouting(
    web: __DIR__.'/../routes/web.php',
    commands: __DIR__.'/../routes/console.php',
    health: '/up',
)
->withMiddleware(function (Middleware $middleware) {
    /**
     * Global HTTP Middleware
     * These middleware are run during every request
     */
    $middleware->use([
        // \App\Http\Middleware\TrustHosts::class,
        \App\Http\Middleware\LogRedirects::class,
        \App\Http\Middleware\TrustProxies::class,
        \Illuminate\Http\Middleware\HandleCors::class,
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ]);
    
    /**
     * Route middleware groups
     */
    $middleware->group('web', [
        \App\Http\Middleware\EncryptCookies::class,
        \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \App\Http\Middleware\VerifyCsrfToken::class,
        \Illuminate\Routing\Middleware\SubstituteBindings::class,
        \App\Http\Middleware\TrustProxies::class,
    ]);
    
    $middleware->group('api', [
        // \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        \Illuminate\Routing\Middleware\ThrottleRequests::class.':api',
        \Illuminate\Routing\Middleware\SubstituteBindings::class,
        \App\Http\Middleware\TrustProxies::class,
    ]);
    
    /**
     * Middleware aliases
     * Aliases may be used instead of class names to conveniently assign middleware to routes
     */
    $middleware->alias([
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'auth.session' => \Illuminate\Session\Middleware\AuthenticateSession::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'precognitive' => \Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests::class,
        'signed' => \App\Http\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        'admin' => \App\Http\Middleware\AdminMiddleware::class,
        'vendor' => \App\Http\Middleware\VendorMiddleware::class,
        'banned' => \App\Http\Middleware\CheckBanned::class,
        'csp' => \App\Http\Middleware\TrustProxies::class,
    ]);
})
->withExceptions(function (Exceptions $exceptions) {
    /**
     * Handle exceptions with proper security
     * Never expose debug information in production
     */
    
    // Register a custom render callback for all exceptions
    $exceptions->render(function (Throwable $e, $request) {
        // Log the exception
        \Illuminate\Support\Facades\Log::error('Exception occurred', [
            'exception' => get_class($e),
                                               'message' => $e->getMessage(),
                                               'status' => $e instanceof HttpException ? $e->getStatusCode() : 500,
        ]);
        
        // If debug mode is ON, use Laravel's default debug response
        if (config('app.debug')) {
            return null; // Let Laravel handle it with debug info
        }
        
        // In production, render safe error pages
        
        // Handle FileNotFoundException as 404
        if ($e instanceof FileNotFoundException) {
            $status = 404;
            $view = 'errors.404';
        } elseif ($e instanceof HttpException) {
            $status = $e->getStatusCode();
            $view = match ($status) {
                401 => 'errors.401',
                403 => 'errors.403',
                404 => 'errors.404',
                419 => 'errors.419',
                429 => 'errors.429',
                500 => 'errors.500',
                503 => 'errors.503',
                default => 'errors.500',
            };
        } else {
            // All other exceptions = 500
            $status = 500;
            $view = 'errors.500';
        }
        
        // Return the error view
        if (view()->exists($view)) {
            return response()->view($view, [
                'exception' => $e,
            ], $status);
        }
        
        // Fallback to generic error response
        return response()->view('errors.500', [
            'exception' => $e,
        ], 500);
    });
    
    // Stop reporting certain exceptions
    $exceptions->dontReport([
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Validation\ValidationException::class,
        FileNotFoundException::class,
    ]);
    
    // Don't report duplicates
    $exceptions->dontReportDuplicates();
    
})->create();
