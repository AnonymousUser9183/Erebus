<?php

/*
 * =========================================================================
 * © 2026 Erebus Development Team
 * Author: AnonymousUser9183
 * =========================================================================
 * Dashboard Controller - User Profile & Dashboard
 * =========================================================================
 */

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Crypt;
use App\Models\User;
use Exception;

class DashboardController extends Controller
{
    /**
     * Show user dashboard or profile
     *
     * @param string|null $username
     * @return View|RedirectResponse
     */
    public function index(?string $username = null): View|RedirectResponse
    {
        try {
            Log::debug('Loading dashboard', [
                'user_id' => Auth::id(),
                'requested_username' => $username,
            ]);

            $loggedInUser = Auth::user();

            if (!$loggedInUser) {
                Log::warning('Unauthenticated user tried to access dashboard');
                return redirect()->route('login')
                    ->with('error', 'Please login to access the dashboard.');
            }

            // Get requested user or default to logged-in user
            $user = $username
                ? User::where('username', $username)->firstOrFail()
                : $loggedInUser;

            // Get or create user profile
            $profile = $user->profile ?? $user->profile()->create();

            // Get PGP key
            $pgpKey = $user->pgpKey;

            // Determine user role
            $userRole = $this->determineUserRole($user);

            // Check if viewing own profile
            $isOwnProfile = $user->id === $loggedInUser->id;

            // Determine visibility based on permissions
            $showFullInfo = $isOwnProfile || $loggedInUser->isAdmin();

            // Decrypt description with fallback
            $description = $profile->description
                ? $this->decryptDescription($profile->description)
                : "This user hasn't added a description yet.";

            Log::debug('Dashboard loaded successfully', [
                'user_id' => $user->id,
                'role' => $userRole,
                'is_own_profile' => $isOwnProfile,
            ]);

            return view('dashboard', compact(
                'user',
                'profile',
                'pgpKey',
                'userRole',
                'isOwnProfile',
                'showFullInfo',
                'description'
            ));

        } catch (Exception $e) {
            Log::error('Error loading dashboard', [
                'user_id' => Auth::id(),
                'requested_username' => $username,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->route('home')
                ->with('error', 'An error occurred while loading the dashboard. Please try again.');
        }
    }

    /**
     * Determine user role string
     *
     * @param User $user
     * @return string
     */
    private function determineUserRole(User $user): string
    {
        $hasAdmin = $user->hasRole('admin');
        $hasVendor = $user->hasRole('vendor');

        return match (true) {
            $hasAdmin && $hasVendor => 'Admin & Vendor',
            $hasAdmin => 'Administrator',
            $hasVendor => 'Vendor',
            default => 'Buyer',
        };
    }

    /**
     * Safely decrypt user description
     *
     * @param string $encryptedDescription
     * @return string
     */
    private function decryptDescription(string $encryptedDescription): string
    {
        try {
            return Crypt::decryptString($encryptedDescription);
        } catch (Exception $e) {
            Log::warning('Failed to decrypt user description', [
                'user_id' => Auth::id(),
                'message' => $e->getMessage(),
            ]);

            return "This user hasn't added a description yet.";
        }
    }
}
