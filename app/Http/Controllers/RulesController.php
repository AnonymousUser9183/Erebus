<?php

/*
 * =========================================================================
 * © 2026 Erebus Development Team
 * Author: AnonymousUser9183
 * =========================================================================
 * Rules Controller - Site Rules & Policy Display with Pagination
 * =========================================================================
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;
use Exception;

class RulesController extends Controller
{
    /**
     * Total number of rules pages
     */
    private const TOTAL_PAGES = 5;

    /**
     * Items per page
     */
    private const ITEMS_PER_PAGE = 1;

    /**
     * Display rules page with pagination
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        try {
            Log::debug('Loading rules page', [
                'page' => $request->get('page', 1),
                'ip' => $request->ip(),
            ]);

            // Get current page from request
            $currentPage = max((int) $request->get('page', 1), 1);

            // Validate page number is within bounds
            if ($currentPage > self::TOTAL_PAGES) {
                Log::warning('Invalid page number requested for rules', [
                    'page' => $currentPage,
                    'total_pages' => self::TOTAL_PAGES,
                    'ip' => $request->ip(),
                ]);

                // Reset to last valid page
                $currentPage = self::TOTAL_PAGES;
            }

            // Create paginator instance
            // Note: Items array is empty because content is rendered in blade template
            $paginatedRules = $this->createPaginator($currentPage);

            Log::debug('Rules page loaded successfully', [
                'current_page' => $currentPage,
                'total_pages' => self::TOTAL_PAGES,
            ]);

            return view('rules', compact('paginatedRules'));

        } catch (Exception $e) {
            Log::error('Error loading rules page', [
                'page' => $request->get('page', 1),
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Create fallback paginator
            $paginatedRules = $this->createPaginator(1);

            return view('rules', compact('paginatedRules'));
        }
    }

    // ==================== PRIVATE HELPER METHODS ====================

    /**
     * Create LengthAwarePaginator instance
     *
     * @param int $currentPage
     * @return LengthAwarePaginator
     */
    private function createPaginator(int $currentPage): LengthAwarePaginator
    {
        try {
            Log::debug('Creating paginator instance', [
                'current_page' => $currentPage,
                'total_pages' => self::TOTAL_PAGES,
                'items_per_page' => self::ITEMS_PER_PAGE,
            ]);

            return new LengthAwarePaginator(
                [], // Empty items array - content is rendered in blade
                self::TOTAL_PAGES, // Total number of items
                self::ITEMS_PER_PAGE, // Items per page
                $currentPage, // Current page
                [
                    'path' => route('rules'), // Route path for pagination links
                    'query' => request()->query(), // Preserve query parameters
                ]
            );

        } catch (Exception $e) {
            Log::error('Error creating paginator', [
                'current_page' => $currentPage,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Return paginator for page 1 as fallback
            return new LengthAwarePaginator(
                [],
                self::TOTAL_PAGES,
                self::ITEMS_PER_PAGE,
                1,
                ['path' => route('rules')]
            );
        }
    }
}
