<?php

/*
 * =========================================================================
 * © 2026 Erebus Development Team
 * Author: AnonymousUser9183
 * =========================================================================
 * Guides Controller - Educational Guides Display
 * =========================================================================
 */

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Log;

class GuidesController extends Controller
{
    /**
     * Display guides index page
     *
     * @return View
     */
    public function index(): View
    {
        try {
            Log::debug('Displaying guides index');
            return view('guides.index');
        } catch (\Exception $e) {
            Log::error('Error loading guides index', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return view('guides.index')
                ->with('error', 'An error occurred while loading guides.');
        }
    }

    /**
     * Display KeePassXC guide
     *
     * @return View
     */
    public function keepassxc(): View
    {
        try {
            Log::debug('Displaying KeePassXC guide');
            return view('guides.keepassxc-guide');
        } catch (\Exception $e) {
            Log::error('Error loading KeePassXC guide', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return view('guides.keepassxc-guide')
                ->with('error', 'An error occurred while loading this guide.');
        }
    }

    /**
     * Display Monero guide
     *
     * @return View
     */
    public function monero(): View
    {
        try {
            Log::debug('Displaying Monero guide');
            return view('guides.monero-guide');
        } catch (\Exception $e) {
            Log::error('Error loading Monero guide', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return view('guides.monero-guide')
                ->with('error', 'An error occurred while loading this guide.');
        }
    }

    /**
     * Display Tor guide
     *
     * @return View
     */
    public function tor(): View
    {
        try {
            Log::debug('Displaying Tor guide');
            return view('guides.tor-guide');
        } catch (\Exception $e) {
            Log::error('Error loading Tor guide', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return view('guides.tor-guide')
                ->with('error', 'An error occurred while loading this guide.');
        }
    }

    /**
     * Display Kleopatra guide
     *
     * @return View
     */
    public function kleopatra(): View
    {
        try {
            Log::debug('Displaying Kleopatra guide');
            return view('guides.kleopatra-guide');
        } catch (\Exception $e) {
            Log::error('Error loading Kleopatra guide', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return view('guides.kleopatra-guide')
                ->with('error', 'An error occurred while loading this guide.');
        }
    }
}
