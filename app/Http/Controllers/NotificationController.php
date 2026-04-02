<?php

/*
 * =========================================================================
 * © 2026 Erebus Development Team
 * Author: AnonymousUser9183
 * =========================================================================
 * Notification Controller - User Notifications Management
 * =========================================================================
 */

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
    /**
     * Display a listing of the user's notifications
     *
     * @return View
     */
    public function index(): View
    {
        try {
            Log::debug('Loading user notifications', ['user_id' => Auth::id()]);

            $user = Auth::user();
            $notifications = $user->notifications()
                ->orderBy('created_at', 'desc')
                ->paginate(16);

            Log::debug('Notifications loaded', [
                'user_id' => Auth::id(),
                'count' => $notifications->count(),
            ]);

            return view('notifications', compact('notifications'));

        } catch (\Exception $e) {
            Log::error('Error loading notifications', [
                'user_id' => Auth::id(),
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->with('error', 'An error occurred while loading notifications. Please try again later.');
        }
    }

    /**
     * Mark the specified notification as read
     *
     * @param Notification $notification
     * @return RedirectResponse
     */
    public function markAsRead(Notification $notification): RedirectResponse
    {
        try {
            Log::debug('Marking notification as read', [
                'user_id' => Auth::id(),
                'notification_id' => $notification->id,
            ]);

            $user = Auth::user();

            // Check if the notification belongs to the user
            $userNotification = $user->notifications()
                ->where('notifications.id', $notification->id)
                ->first();

            if (!$userNotification) {
                Log::warning('Unauthorized notification access attempt', [
                    'user_id' => Auth::id(),
                    'notification_id' => $notification->id,
                ]);

                return redirect()->route('notifications.index')
                    ->with('error', 'Notification not found.');
            }

            // Update the pivot table to mark as read
            $user->notifications()->updateExistingPivot($notification->id, ['read' => true]);

            Log::info('Notification marked as read', [
                'user_id' => Auth::id(),
                'notification_id' => $notification->id,
            ]);

            return redirect()->route('notifications.index')
                ->with('success', 'Notification marked as read.');

        } catch (\Exception $e) {
            Log::error('Failed to mark notification as read', [
                'user_id' => Auth::id(),
                'notification_id' => $notification->id,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->route('notifications.index')
                ->with('error', 'Failed to mark notification as read.');
        }
    }

    /**
     * Remove the specified notification from the user's list
     *
     * @param Notification $notification
     * @return RedirectResponse
     */
    public function destroy(Notification $notification): RedirectResponse
    {
        try {
            Log::debug('Deleting notification', [
                'user_id' => Auth::id(),
                'notification_id' => $notification->id,
            ]);

            $user = Auth::user();

            // Check if the notification belongs to the user
            $userNotification = $user->notifications()
                ->where('notifications.id', $notification->id)
                ->first();

            if (!$userNotification) {
                Log::warning('Unauthorized notification deletion attempt', [
                    'user_id' => Auth::id(),
                    'notification_id' => $notification->id,
                ]);

                return redirect()->route('notifications.index')
                    ->with('error', 'Notification not found.');
            }

            // Detach the notification from the user
            $user->notifications()->detach($notification->id);

            Log::info('Notification deleted', [
                'user_id' => Auth::id(),
                'notification_id' => $notification->id,
            ]);

            return redirect()->route('notifications.index')
                ->with('success', 'Notification deleted successfully.');

        } catch (\Exception $e) {
            Log::error('Failed to delete notification', [
                'user_id' => Auth::id(),
                'notification_id' => $notification->id,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->route('notifications.index')
                ->with('error', 'Failed to delete notification.');
        }
    }
}
