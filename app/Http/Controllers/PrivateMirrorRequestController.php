<?php

/*
 * =========================================================================
 * © 2026 Erebus Development Team
 * Author: AnonymousUser9183
 * =========================================================================
 * Private Mirror Request Controller - Private Mirror Management
 * =========================================================================
 */

namespace App\Http\Controllers;

use App\Models\PrivateMirrorRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class PrivateMirrorRequestController extends Controller
{
    /**
     * Initialize middleware for authentication
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * User requests a private mirror
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            Log::debug('Creating private mirror request', ['user_id' => Auth::id()]);

            $user = Auth::user();

            // Check if user already has assigned mirror
            $existingRequest = PrivateMirrorRequest::where('user_id', $user->id)
                ->whereNotNull('assigned_mirror')
                ->first();

            if ($existingRequest) {
                Log::info('User already has assigned mirror', [
                    'user_id' => $user->id,
                ]);
                return redirect()->route('profile')
                    ->with('info', 'You already have a private mirror assigned.');
            }

            // Check if user has pending request
            $pendingRequest = PrivateMirrorRequest::where('user_id', $user->id)
                ->where('status', 'pending')
                ->first();

            if ($pendingRequest) {
                Log::info('User already has pending mirror request', [
                    'user_id' => $user->id,
                ]);
                return redirect()->route('profile')
                    ->with('info', 'You already have a pending mirror request.');
            }

            // Create request
            PrivateMirrorRequest::create([
                'user_id' => $user->id,
                'status' => 'pending',
            ]);

            Log::info('Private mirror request created', [
                'user_id' => $user->id,
            ]);

            return redirect()->route('profile')
                ->with('success', 'Your private mirror request has been submitted. An admin will review it shortly.');

        } catch (\Exception $e) {
            Log::error('Error creating mirror request', [
                'user_id' => Auth::id(),
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->route('profile')
                ->with('error', 'An error occurred while submitting your request. Please try again.');
        }
    }

    /**
     * Admin: List all mirror requests
     *
     * @return View
     */
    public function adminIndex(): View
    {
        try {
            Log::debug('Admin loading mirror requests', ['admin_id' => Auth::id()]);

            $requests = PrivateMirrorRequest::with('user')
                ->orderBy('created_at', 'desc')
                ->paginate(20);

            Log::debug('Mirror requests loaded', [
                'count' => $requests->count(),
            ]);

            return view('admin.private_mirror_requests.list', compact('requests'));

        } catch (\Exception $e) {
            Log::error('Error listing mirror requests', [
                'admin_id' => Auth::id(),
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->route('admin.index')
                ->with('error', 'Unable to load mirror requests.');
        }
    }

    /**
     * Admin: Show individual request for review
     *
     * @param PrivateMirrorRequest $request
     * @return View|RedirectResponse
     */
    public function adminShow(PrivateMirrorRequest $request): View|RedirectResponse
    {
        try {
            Log::debug('Admin viewing mirror request', [
                'request_id' => $request->id,
                'admin_id' => Auth::id(),
            ]);

            $request->load('user');

            return view('admin.private_mirror_requests.show', compact('request'));

        } catch (\Exception $e) {
            Log::error('Error showing mirror request', [
                'request_id' => $request->id,
                'admin_id' => Auth::id(),
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->route('admin.private-mirror-requests.list')
                ->with('error', 'Unable to load this request.');
        }
    }

    /**
     * Admin: Assign a mirror to a user
     *
     * @param Request $request
     * @param PrivateMirrorRequest $mirrorRequest
     * @return RedirectResponse
     */
    public function adminAssign(Request $request, PrivateMirrorRequest $mirrorRequest): RedirectResponse
    {
        try {
            Log::debug('Admin assigning mirror', [
                'request_id' => $mirrorRequest->id,
                'admin_id' => Auth::id(),
            ]);

            // Validate mirror URL
            try {
                $validated = $request->validate([
                    'mirror_url' => [
                        'required',
                        'string',
                        'max:500',
                        'url',
                    ],
                ], [
                    'mirror_url.required' => 'Mirror URL is required.',
                    'mirror_url.url' => 'Please enter a valid URL.',
                    'mirror_url.max' => 'Mirror URL cannot exceed 500 characters.',
                ]);

            } catch (ValidationException $e) {
                Log::warning('Mirror URL validation failed', [
                    'request_id' => $mirrorRequest->id,
                    'errors' => $e->validator->errors()->toArray(),
                ]);

                return redirect()->back()
                    ->withInput()
                    ->withErrors($e->validator);
            }

            // Check if already assigned
            if ($mirrorRequest->isAssigned()) {
                Log::info('Mirror request already assigned', [
                    'request_id' => $mirrorRequest->id,
                ]);

                return redirect()->back()
                    ->with('info', 'This request has already been assigned a mirror.');
            }

            // Assign mirror
            $mirrorRequest->update([
                'assigned_mirror' => $validated['mirror_url'],
                'status' => 'assigned',
                'assigned_at' => now(),
            ]);

            Log::info('Private mirror assigned', [
                'request_id' => $mirrorRequest->id,
                'user_id' => $mirrorRequest->user_id,
                'admin_id' => Auth::id(),
            ]);

            return redirect()->route('admin.private-mirror-requests.list')
                ->with('success', 'Mirror successfully assigned to user.');

        } catch (\Exception $e) {
            Log::error('Error assigning mirror', [
                'request_id' => $mirrorRequest->id,
                'admin_id' => Auth::id(),
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'An error occurred while assigning the mirror. Please try again.');
        }
    }

    /**
     * Admin: Deny a private mirror request
     *
     * @param PrivateMirrorRequest $mirrorRequest
     * @return RedirectResponse
     */
    public function adminDeny(PrivateMirrorRequest $mirrorRequest): RedirectResponse
    {
        try {
            Log::debug('Admin denying mirror request', [
                'request_id' => $mirrorRequest->id,
                'admin_id' => Auth::id(),
            ]);

            $mirrorRequest->update(['status' => 'denied']);

            Log::info('Private mirror request denied', [
                'request_id' => $mirrorRequest->id,
                'user_id' => $mirrorRequest->user_id,
                'admin_id' => Auth::id(),
            ]);

            return redirect()->route('admin.private-mirror-requests.list')
                ->with('success', 'Mirror request denied.');

        } catch (\Exception $e) {
            Log::error('Error denying private mirror request', [
                'request_id' => $mirrorRequest->id,
                'admin_id' => Auth::id(),
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->with('error', 'An error occurred while denying the request.');
        }
    }
}
