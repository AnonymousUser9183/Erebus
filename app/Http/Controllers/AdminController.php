<?php

/*
 * =========================================================================
 * © 2026 Erebus Development Team
 * Author: AnonymousUser9183
 * =========================================================================
 * Admin Controller - Panel Management
 * =========================================================================
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\User;
use App\Models\Role;
use App\Models\BannedUser;
use App\Models\Product;
use App\Models\Category;
use App\Models\Notification;
use App\Models\Popup;
use App\Models\SupportRequest;
use App\Models\VendorPayment;
use App\Models\PrivateMirrorRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\GdDriver;
use Intervention\Image\Encoders\PngEncoder;
use Intervention\Image\Encoders\WebpEncoder;
use Intervention\Image\Encoders\GifEncoder;
use Intervention\Image\Encoders\JpegEncoder;
use Intervention\Image\Exceptions\NotReadableException;
use MoneroIntegrations\MoneroPhp\walletRPC;

class AdminController extends Controller
{
    /**
     * Show admin dashboard
     */
    public function index(): View
    {
        return view('admin.index');
    }

    // ========================================================================
    // CANARY METHODS
    // ========================================================================

    /**
     * Show canary update form
     */
    public function showUpdateCanary(): View
    {
        $currentCanary = file_get_contents(public_path('canary.txt'));
        return view('admin.canary', compact('currentCanary'));
    }

    /**
     * Update canary file
     */
    public function updateCanary(Request $request): RedirectResponse
    {
        try {
            $validated = $request->validate([
                'canary' => 'required|string|max:3200',
            ], [
                'canary.required' => 'Canary content is required.',
                'canary.max' => 'Canary content cannot exceed 3200 characters.',
            ]);

            file_put_contents(public_path('canary.txt'), $validated['canary']);

            Log::info('Canary updated', ['user_id' => auth()->id()]);

            return redirect()->route('admin.canary')
                ->with('success', 'Canary updated successfully.');
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors($e->validator->errors()->first());
        }
    }

    // ========================================================================
    // LOGS METHODS
    // ========================================================================

    /**
     * Show logs index
     */
    public function showLogs(): View
    {
        return view('admin.logs.index');
    }

    /**
     * Get filtered logs from laravel.log file
     *
     * @param array<string> $logTypes
     * @param string|null $searchQuery
     * @return array<int, array>
     */
    private function getFilteredLogs(array $logTypes, ?string $searchQuery = null): array
    {
        $logPath = storage_path('logs/laravel.log');
        $logs = [];

        if (file_exists($logPath)) {
            $content = file_get_contents($logPath);
            $pattern = '/\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\] (\w+)\.(\w+): (.*?)(?=\[\d{4}-\d{2}-\d{2}|\Z)/s';

            preg_match_all($pattern, $content, $matches, PREG_SET_ORDER);

            foreach ($matches as $match) {
                $logType = strtolower($match[2]);

                if (!in_array($logType, $logTypes)) {
                    continue;
                }

                if ($searchQuery && stripos($match[0], $searchQuery) === false) {
                    continue;
                }

                $logs[] = [
                    'datetime' => $match[1],
                    'type' => $match[2],
                    'message' => $match[4],
                    'id' => md5($match[1] . $match[2] . $match[4]),
                ];
            }
        }

        return array_reverse($logs);
    }

    /**
     * Show logs by type
     */
    public function showLogsByType(string $type, Request $request): View
    {
        $logTypes = match ($type) {
            'error' => ['error', 'critical', 'alert', 'emergency'],
            'warning' => ['warning', 'notice'],
            'info' => ['info', 'debug'],
            default => abort(404),
        };

        $searchQuery = $request->input('search');
        $logs = $this->getFilteredLogs($logTypes, $searchQuery);

        return view('admin.logs.show', compact('logs', 'type', 'searchQuery'));
    }

    /**
     * Delete all logs of a specific type
     */
    public function deleteLogs(string $type): RedirectResponse
    {
        $logPath = storage_path('logs/laravel.log');

        if (file_exists($logPath)) {
            $content = file_get_contents($logPath);
            $pattern = '/\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\] (\w+)\.(\w+): (.*?)(?=\[\d{4}-\d{2}-\d{2}|\Z)/s';

            preg_match_all($pattern, $content, $matches, PREG_SET_ORDER);

            $newContent = '';
            foreach ($matches as $match) {
                $logType = strtolower($match[2]);

                if ($type === 'error' && !in_array($logType, ['error', 'critical', 'alert', 'emergency'])) {
                    $newContent .= $match[0];
                } elseif ($type === 'warning' && !in_array($logType, ['warning', 'notice'])) {
                    $newContent .= $match[0];
                } elseif ($type === 'info' && !in_array($logType, ['info', 'debug'])) {
                    $newContent .= $match[0];
                }
            }

            file_put_contents($logPath, $newContent);
            Log::info('Logs deleted', ['type' => $type, 'user_id' => auth()->id()]);
        }

        return redirect()->route('admin.logs')
            ->with('success', ucfirst($type) . ' logs deleted successfully.');
    }

    /**
     * Delete selected logs
     */
    public function deleteSelectedLogs(Request $request, string $type): RedirectResponse
    {
        $selectedLogs = $request->input('selectedlogs', []);

        if (empty($selectedLogs)) {
            return redirect()->route('admin.logs.show', $type)
                ->with('error', 'No logs selected for deletion.');
        }

        $logPath = storage_path('logs/laravel.log');

        if (file_exists($logPath)) {
            $content = file_get_contents($logPath);
            $pattern = '/\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\] (\w+)\.(\w+): (.*?)(?=\[\d{4}-\d{2}-\d{2}|\Z)/s';

            preg_match_all($pattern, $content, $matches, PREG_SET_ORDER);

            $newContent = '';
            foreach ($matches as $match) {
                $logId = md5($match[1] . $match[2] . $match[4]);

                if (in_array($logId, $selectedLogs)) {
                    continue;
                }

                $newContent .= $match[0];
            }

            file_put_contents($logPath, $newContent);
        }

        Log::info('Selected logs deleted', ['count' => count($selectedLogs), 'user_id' => auth()->id()]);

        return redirect()->route('admin.logs.show', $type)
            ->with('success', count($selectedLogs) . ' log(s) deleted successfully.');
    }

    // ========================================================================
    // USER MANAGEMENT METHODS
    // ========================================================================

    /**
     * List all users with pagination
     */
    public function userList(): View
    {
        $users = User::orderBy('username')->paginate(32);
        return view('admin.users.list', compact('users'));
    }

    /**
     * Show user details
     */
    public function userDetails(User $user): View
    {
        $user->load('referrer');
        return view('admin.users.details', compact('user'));
    }

    /**
     * Update user roles
     */
    public function updateUserRoles(Request $request, User $user): RedirectResponse
    {
        try {
            $validated = $request->validate([
                'roles' => 'array',
                'roles.*' => 'in:admin,vendor',
            ], [
                'roles.*.in' => 'Invalid role selected.',
            ]);

            $roles = $validated['roles'] ?? [];
            $roleIds = Role::whereIn('name', $roles)->pluck('id');
            $user->roles()->sync($roleIds);

            Log::info('User roles updated', ['user_id' => $user->id, 'admin_id' => auth()->id()]);

            return redirect()->route('admin.users.details', $user)
                ->with('success', 'User roles updated successfully.');
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator->errors()->first());
        }
    }

    /**
     * Ban a user
     */
    public function banUser(Request $request, User $user): RedirectResponse
    {
        try {
            $validated = $request->validate([
                'reason' => 'required|string|max:255',
                'duration' => 'required|integer|min:1',
            ], [
                'reason.required' => 'Ban reason is required.',
                'reason.max' => 'Reason cannot exceed 255 characters.',
                'duration.required' => 'Ban duration is required.',
                'duration.min' => 'Duration must be at least 1 day.',
            ]);

            $bannedUntil = now()->addDays((int)$validated['duration']);

            BannedUser::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'reason' => $validated['reason'],
                    'banned_until' => $bannedUntil,
                ]
            );

            Log::info('User banned', [
                'user_id' => $user->id,
                'banned_until' => $bannedUntil,
                'reason' => $validated['reason'],
                'admin_id' => auth()->id(),
            ]);

            return redirect()->route('admin.users.details', $user)
                ->with('success', 'User banned successfully for ' . $validated['duration'] . ' day(s).');
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors($e->validator->errors()->first());
        }
    }

    /**
     * Unban a user
     */
    public function unbanUser(User $user): RedirectResponse
    {
        $user->bannedUser()->delete();
        Log::info('User unbanned', ['user_id' => $user->id, 'admin_id' => auth()->id()]);

        return redirect()->route('admin.users.details', $user)
            ->with('success', 'User unbanned successfully.');
    }

    // ========================================================================
    // POPUP MANAGEMENT METHODS
    // ========================================================================

    /**
     * Display a listing of all popups
     */
    public function popupIndex(): View
    {
        $popups = Popup::orderBy('created_at', 'desc')->paginate(15);

        return view('admin.pop-up.list', [
            'popups' => $popups,
        ]);
    }

    /**
     * Show the form for creating a new popup
     */
    public function popupCreate(): View
    {
        return view('admin.pop-up.create');
    }

    /**
     * Store a newly created popup in storage
     */
    public function popupStore(Request $request): RedirectResponse
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'message' => 'required|string|max:5000',
                'active' => 'sometimes|boolean',
            ], [
                'title.required' => 'Popup title is required.',
                'title.max' => 'Title cannot exceed 255 characters.',
                'message.required' => 'Popup message is required.',
                'message.max' => 'Message cannot exceed 5000 characters.',
            ]);

            Popup::create($validated);

            Log::info('Popup created', ['user_id' => auth()->id()]);

            return redirect()->route('admin.popup.index')
                ->with('success', 'Popup created successfully.');

        } catch (ValidationException $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors($e->validator->errors()->first());
        }
    }

    /**
     * Activate a specific popup (deactivate all others)
     */
    public function popupActivate(Popup $popup): RedirectResponse
    {
        try {
            $popup->update(['active' => true]);

            Log::info('Popup activated', [
                'popup_id' => $popup->id,
                'user_id' => auth()->id(),
            ]);

            return redirect()->route('admin.pop-up.list')
                ->with('success', 'Popup activated successfully.');

        } catch (\Exception $e) {
            Log::error('Error activating popup', [
                'popup_id' => $popup->id,
                'message' => $e->getMessage(),
            ]);

            return redirect()->route('admin.pop-up.list')
                ->with('error', 'Unable to activate popup. Please try again.');
        }
    }

    /**
     * Delete a popup
     */
    public function popupDestroy(Popup $popup): RedirectResponse
    {
        try {
            $popupId = $popup->id;
            $popup->delete();

            Log::info('Popup deleted', [
                'popup_id' => $popupId,
                'user_id' => auth()->id(),
            ]);

            return redirect()->route('admin.pop-up.list')
                ->with('success', 'Popup deleted successfully.');

        } catch (\Exception $e) {
            Log::error('Error deleting popup', [
                'popup_id' => $popup->id,
                'message' => $e->getMessage(),
            ]);

            return redirect()->route('admin.pop-up.list')
                ->with('error', 'Unable to delete popup. Please try again.');
        }
    }

    // ========================================================================
    // SUPPORT REQUEST METHODS
    // ========================================================================

    /**
     * List support requests
     */
    public function supportRequests(): View
    {
        try {
            Log::debug('Fetching support requests for admin', ['admin_id' => auth()->id()]);

            $requests = SupportRequest::mainRequests()
                ->with(['user', 'messages' => fn($query) => $query->latest('created_at')->limit(1)])
                ->orderBy('created_at', 'desc')
                ->paginate(16);

            Log::debug('Support requests fetched', ['count' => $requests->count()]);

            return view('admin.support.list', compact('requests'));

        } catch (\Exception $e) {
            Log::error('Error fetching support requests', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->route('admin.index')
                ->with('error', 'Unable to load support requests. Please try again.');
        }
    }

    /**
     * Show a specific support request
     */
    public function showSupportRequest(SupportRequest $supportRequest, string $ticketId): View|RedirectResponse
    {
        try {
            Log::debug('Showing support request for admin', [
                'support_request_id' => $supportRequest->id,
                'ticket_id' => $ticketId,
            ]);

            if ($supportRequest->ticket_id !== $ticketId) {
                Log::warning('Ticket ID mismatch', [
                    'expected' => $supportRequest->ticket_id,
                    'provided' => $ticketId,
                ]);
                return redirect()->route('admin.support.requests')
                    ->with('error', 'Invalid support request.');
            }

            $supportRequest->load(['user', 'messages']);

            return view('admin.support.show', compact('supportRequest'));

        } catch (\Exception $e) {
            Log::error('Error showing support request', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->route('admin.support.requests')
                ->with('error', 'Unable to load support request. Please try again.');
        }
    }

    /**
     * Reply to support request
     */
    public function replySupportRequest(Request $request, SupportRequest $supportRequest, string $ticketId): RedirectResponse
    {
        try {
            $validated = $request->validate([
                'message' => 'required|string|max:2000',
            ], [
                'message.required' => 'Message is required.',
                'message.max' => 'Message cannot exceed 2000 characters.',
            ]);

            if ($supportRequest->ticket_id !== $ticketId) {
                return redirect()->route('admin.support.requests')
                    ->with('error', 'Invalid support request.');
            }

            $supportRequest->messages()->create([
                'user_id' => auth()->id(),
                'message' => $validated['message'],
            ]);

            Log::info('Admin replied to support request', [
                'support_request_id' => $supportRequest->id,
                'admin_id' => auth()->id(),
            ]);

            return redirect()->route('admin.support.show', [$supportRequest, $ticketId])
                ->with('success', 'Reply sent successfully.');

        } catch (ValidationException $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors($e->validator->errors()->first());
        }
    }

    /**
     * Update support request status
     */
    public function updateSupportStatus(Request $request, SupportRequest $supportRequest, string $ticketId): RedirectResponse
    {
        try {
            $validated = $request->validate([
                'status' => 'required|in:open,in_progress,resolved,closed',
            ], [
                'status.required' => 'Status is required.',
                'status.in' => 'Invalid status selected.',
            ]);

            if ($supportRequest->ticket_id !== $ticketId) {
                return redirect()->route('admin.support.requests')
                    ->with('error', 'Invalid support request.');
            }

            $supportRequest->update(['status' => $validated['status']]);

            Log::info('Support request status updated', [
                'support_request_id' => $supportRequest->id,
                'status' => $validated['status'],
                'admin_id' => auth()->id(),
            ]);

            return redirect()->route('admin.support.show', [$supportRequest, $ticketId])
                ->with('success', 'Status updated to ' . str_replace('_', ' ', $validated['status']) . '.');

        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator->errors()->first());
        }
    }
}
