<?php

/*
 * =========================================================================
 * © 2026 Erebus Development Team
 * Author: AnonymousUser9183
 * =========================================================================
 * Support Controller - Ticket Management with PoW Verification
 * =========================================================================
 */

namespace App\Http\Controllers;

use App\Models\SupportRequest;
use App\Services\CaptchaService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class SupportController extends Controller
{
    /**
     * Captcha service instance
     *
     * @var CaptchaService
     */
    protected CaptchaService $captchaService;

    /**
     * Initialize controller with CaptchaService
     *
     * @param CaptchaService $captchaService
     */
    public function __construct(CaptchaService $captchaService)
    {
        $this->captchaService = $captchaService;
    }

    /**
     * Display user's support requests
     *
     * @return View|RedirectResponse
     */
    public function index(): View|RedirectResponse
    {
        try {
            $user = Auth::user();

            Log::debug('Loading support requests', ['user_id' => $user->id]);

            $supportRequests = SupportRequest::where('user_id', $user->id)
                ->mainRequests()
                ->with('messages')
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            Log::debug('Support requests loaded', [
                'user_id' => $user->id,
                'count' => $supportRequests->count(),
            ]);

            return view('support.index', compact('supportRequests'));

        } catch (\Exception $e) {
            Log::error('Support index error', [
                'user_id' => Auth::id(),
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->with('error', 'Unable to load support requests.');
        }
    }

    /**
     * Show create support request form and generate PoW challenge
     *
     * @return View|RedirectResponse
     */
    public function create(): View|RedirectResponse
    {
        try {
            Log::debug('Loading support create form', ['user_id' => Auth::id()]);

            $this->captchaService->generateChallenge();

            return view('support.create');

        } catch (\Exception $e) {
            Log::error('Support create error', [
                'user_id' => Auth::id(),
                'message' => $e->getMessage(),
            ]);

            return redirect()->back()
                ->with('error', 'Unable to load form.');
        }
    }

    /**
     * Solve PoW for support request creation
     * Then store the request (all server-side, no JS needed)
     *
     * @return RedirectResponse
     */
    public function solvePowCreate(): RedirectResponse
    {
        try {
            Log::info('solvePowCreate: Starting PoW challenge', ['user_id' => Auth::id()]);

            // Step 1 & 2: Validate form data
            $validated = $this->validate(request(), [
                'subject' => 'required|string|min:5|max:100',
                'message' => 'required|string|min:10|max:2000',
                'category' => 'required|string|in:billing,technical,account,other',
            ]);

            Log::info('solvePowCreate: Validation passed', ['category' => $validated['category']]);

            // Step 3: Solve PoW
            $solved = $this->captchaService->autoSolve();

            if (!$solved) {
                Log::warning('PoW autoSolve failed for support creation', [
                    'user_id' => Auth::id(),
                ]);

                return redirect()->route('support.create')
                    ->withInput()
                    ->with('error', 'Unable to complete Proof of Work. Please try again.');
            }

            Log::info('solvePowCreate: PoW solved', [
                'nonce' => $this->captchaService->getSolutionNonce(),
                'user_id' => Auth::id(),
            ]);

            // Step 4: Generate ticket ID before creating request
            $ticketId = strtoupper(bin2hex(random_bytes(8)));

            // Step 5: Create support request with ticket ID
            $supportRequest = SupportRequest::create([
                'user_id' => Auth::id(),
                'subject' => $validated['subject'],
                'title' => $validated['subject'],
                'message' => $validated['message'],
                'category' => $validated['category'],
                'status' => 'open',
                'ticket_id' => $ticketId,
            ]);

            // Step 6: Clear PoW from session
            $this->captchaService->clearSession();

            Log::info('Support request created', [
                'support_id' => $supportRequest->id,
                'ticket_id' => $supportRequest->ticket_id,
                'user_id' => Auth::id(),
            ]);

            // Step 7: Redirect to the support request page with both parameters
            return redirect()->route('support.show', [$supportRequest->id, $supportRequest->ticket_id])
                ->with('success', 'Support request created successfully! Ticket ID: ' . $supportRequest->ticket_id);

        } catch (ValidationException $e) {
            Log::warning('Support creation validation error', [
                'user_id' => Auth::id(),
                'errors' => $e->validator->errors()->toArray(),
            ]);

            return redirect()->route('support.create')
                ->withInput()
                ->withErrors($e->validator);

        } catch (\Exception $e) {
            Log::error('Support creation error', [
                'user_id' => Auth::id(),
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->route('support.create')
                ->withInput()
                ->with('error', 'Error creating support request. Please try again.');
        }
    }

    /**
     * Store new support request (kept for backwards compatibility)
     * Not used in normal flow when using solvePowCreate
     *
     * @return RedirectResponse
     */
    public function store(): RedirectResponse
    {
        try {
            // Check if PoW is solved in session
            if (!$this->captchaService->isSolved()) {
                return redirect()->route('support.create')
                    ->with('error', 'Please complete Proof of Work verification.');
            }

            Log::info('store: PoW verified, validating input', ['user_id' => Auth::id()]);

            $validated = $this->validate(request(), [
                'subject' => 'required|string|min:5|max:100',
                'message' => 'required|string|min:10|max:2000',
                'category' => 'required|string|in:billing,technical,account,other',
            ]);

            // Generate ticket ID
            $ticketId = strtoupper(bin2hex(random_bytes(8)));

            $supportRequest = SupportRequest::create([
                'user_id' => Auth::id(),
                'subject' => $validated['subject'],
                'title' => $validated['subject'],
                'message' => $validated['message'],
                'category' => $validated['category'],
                'status' => 'open',
                'ticket_id' => $ticketId,
            ]);

            $this->captchaService->clearSession();

            Log::info('Support request stored', [
                'support_id' => $supportRequest->id,
                'ticket_id' => $ticketId,
                'user_id' => Auth::id(),
            ]);

            return redirect()->route('support.show', [$supportRequest->id, $supportRequest->ticket_id])
                ->with('success', 'Support request created successfully!');

        } catch (ValidationException $e) {
            Log::warning('Store validation error', [
                'user_id' => Auth::id(),
                'errors' => $e->validator->errors()->toArray(),
            ]);

            return redirect()->back()
                ->withInput()
                ->withErrors($e->validator);

        } catch (\Exception $e) {
            Log::error('Support store error', [
                'user_id' => Auth::id(),
                'message' => $e->getMessage(),
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Unable to create support request. Please try again.');
        }
    }

    /**
     * Show specific support request and its replies
     *
     * @param int $supportRequest
     * @param string $ticketId
     * @return View|RedirectResponse
     */
    public function show(int $supportRequest, string $ticketId): View|RedirectResponse
    {
        try {
            Log::debug('Loading support request', [
                'support_id' => $supportRequest,
                'ticket_id' => $ticketId,
            ]);

            $supportRequest = SupportRequest::where('ticket_id', $ticketId)
                ->with('messages')
                ->firstOrFail();

            // Check authorization
            if ($supportRequest->user_id !== Auth::id() && !Auth::user()->hasRole('admin')) {
                Log::warning('Unauthorized support request access', [
                    'user_id' => Auth::id(),
                    'support_id' => $supportRequest->id,
                ]);

                return redirect()->route('support.index')
                    ->with('error', 'Unauthorized access.');
            }

            Log::debug('Support request loaded', ['support_id' => $supportRequest->id]);

            // Generate PoW challenge for reply
            $this->captchaService->generateChallenge();

            return view('support.show', compact('supportRequest'));

        } catch (\Exception $e) {
            Log::error('Support show error', [
                'user_id' => Auth::id(),
                'ticket_id' => $ticketId,
                'message' => $e->getMessage(),
            ]);

            return redirect()->route('support.index')
                ->with('error', 'Unable to load support request.');
        }
    }

    /**
     * Solve PoW for support request reply (server-side, no JS)
     *
     * @param int $supportRequest
     * @param string $ticketId
     * @return RedirectResponse
     */
    public function solvePowReply(int $supportRequest, string $ticketId): RedirectResponse
    {
        try {
            Log::info('solvePowReply: Starting', [
                'support_id' => $supportRequest,
                'ticket_id' => $ticketId,
                'user_id' => Auth::id(),
            ]);

            // Step 1 & 2: Validate
            $validated = $this->validate(request(), [
                'message' => 'required|string|min:5|max:2000',
            ]);

            Log::info('solvePowReply: Validation passed');

            // Step 3: Find parent support request
            $supportRequest = SupportRequest::where('ticket_id', $ticketId)
                ->firstOrFail();

            Log::info('solvePowReply: Found support request', ['support_id' => $supportRequest->id]);

            // Step 4: Check authorization
            if ($supportRequest->user_id !== Auth::id() && !Auth::user()->hasRole('admin')) {
                Log::warning('solvePowReply: Authorization check failed', [
                    'user_id' => Auth::id(),
                    'support_id' => $supportRequest->id,
                ]);

                return redirect()->route('support.index')
                    ->with('error', 'Unauthorized access.');
            }

            Log::info('solvePowReply: Authorization passed');

            // Step 5: Solve PoW
            $solved = $this->captchaService->autoSolve();

            if (!$solved) {
                Log::warning('PoW autoSolve failed for support reply', [
                    'user_id' => Auth::id(),
                    'support_id' => $supportRequest->id,
                ]);

                return redirect()->route('support.show', [$supportRequest->id, $ticketId])
                    ->withInput()
                    ->with('error', 'Unable to complete Proof of Work. Please try again.');
            }

            Log::info('solvePowReply: PoW solved', [
                'nonce' => $this->captchaService->getSolutionNonce(),
                'support_id' => $supportRequest->id,
            ]);

            // Step 6: Check if user is admin using hasRole method
            $isAdmin = Auth::user()->hasRole('admin');

            Log::info('solvePowReply: Admin check', [
                'isAdmin' => $isAdmin,
                'user_id' => Auth::id(),
            ]);

            // Step 7: Create reply as child SupportRequest
            $reply = SupportRequest::create([
                'user_id' => Auth::id(),
                'parent_id' => $supportRequest->id,
                'message' => $validated['message'],
                'title' => 'Re: ' . $supportRequest->subject,
                'status' => 'open',
                'is_admin_reply' => $isAdmin ? 1 : 0,
            ]);

            Log::info('solvePowReply: Reply created', [
                'reply_id' => $reply->id,
                'parent_id' => $supportRequest->id,
            ]);

            // Step 8: Clear PoW from session
            $this->captchaService->clearSession();

            Log::info('Support reply created successfully', [
                'support_id' => $supportRequest->id,
                'reply_id' => $reply->id,
                'user_id' => Auth::id(),
                'is_admin_reply' => $isAdmin,
            ]);

            // Step 9: Redirect back with success
            return redirect()->route('support.show', [$supportRequest->id, $ticketId])
                ->with('success', '✅ Reply added successfully!');

        } catch (ValidationException $e) {
            Log::warning('Support reply validation error', [
                'user_id' => Auth::id(),
                'ticket_id' => $ticketId,
                'errors' => $e->validator->errors()->toArray(),
            ]);

            return redirect()->route('support.show', [$supportRequest->id, $ticketId])
                ->withInput()
                ->withErrors($e->validator);

        } catch (\Exception $e) {
            Log::error('Support reply error', [
                'user_id' => Auth::id(),
                'ticket_id' => $ticketId,
                'message' => $e->getMessage(),
                'class' => get_class($e),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Unable to add reply. Please try again.');
        }
    }

    /**
     * Store reply (kept for backwards compatibility)
     * Not used in normal flow when using solvePowReply
     *
     * @param int $supportRequest
     * @param string $ticketId
     * @return RedirectResponse
     */
    public function reply(int $supportRequest, string $ticketId): RedirectResponse
    {
        try {
            Log::info('reply: Starting (legacy method)', [
                'support_id' => $supportRequest,
                'ticket_id' => $ticketId,
            ]);

            if (!$this->captchaService->isSolved()) {
                return redirect()->back()
                    ->with('error', 'Please complete Proof of Work verification.');
            }

            $supportRequest = SupportRequest::where('ticket_id', $ticketId)
                ->firstOrFail();

            if ($supportRequest->user_id !== Auth::id() && !Auth::user()->hasRole('admin')) {
                return redirect()->route('support.index')
                    ->with('error', 'Unauthorized access.');
            }

            $validated = $this->validate(request(), [
                'message' => 'required|string|min:5|max:2000',
            ]);

            $isAdmin = Auth::user()->hasRole('admin');

            $reply = SupportRequest::create([
                'user_id' => Auth::id(),
                'parent_id' => $supportRequest->id,
                'message' => $validated['message'],
                'title' => 'Re: ' . $supportRequest->subject,
                'status' => 'open',
                'is_admin_reply' => $isAdmin ? 1 : 0,
            ]);

            $this->captchaService->clearSession();

            Log::info('Reply created (legacy)', [
                'support_id' => $supportRequest->id,
                'reply_id' => $reply->id,
                'user_id' => Auth::id(),
            ]);

            return redirect()->route('support.show', [$supportRequest->id, $ticketId])
                ->with('success', 'Reply added successfully.');

        } catch (ValidationException $e) {
            Log::warning('Reply validation error', [
                'user_id' => Auth::id(),
                'errors' => $e->validator->errors()->toArray(),
            ]);

            return redirect()->back()
                ->withInput()
                ->withErrors($e->validator);

        } catch (\Exception $e) {
            Log::error('Support reply error (legacy)', [
                'user_id' => Auth::id(),
                'message' => $e->getMessage(),
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Unable to add reply. Please try again.');
        }
    }
}
