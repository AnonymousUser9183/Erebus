<?php

/*
 * =========================================================================
 * © 2026 Erebus Development Team
 * Author: AnonymousUser9183
 * =========================================================================
 * Message Controller - User Messaging System
 * =========================================================================
 */

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Log;
use HTMLPurifier;
use HTMLPurifier_Config;

class MessageController extends Controller
{
    /**
     * Initialize middleware for rate limiting
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (RateLimiter::tooManyAttempts('send-message:' . Auth::id(), $perMinute = 5)) {
                Log::warning('Rate limit exceeded for sending messages', [
                    'user_id' => Auth::id(),
                    'limit' => 5,
                    'window' => 'per minute',
                ]);
                return response()->view('messages.rate-limit', [], 429);
            }

            RateLimiter::hit('send-message:' . Auth::id());
            return $next($request);
        })->only(['store', 'startConversation']);
    }

    /**
     * Display conversations index
     *
     * @return View
     */
    public function index(): View
    {
        try {
            Log::debug('Loading conversations', ['user_id' => Auth::id()]);

            $user = Auth::user();
            $conversations = $user->conversations()
                ->with(['user1', 'user2'])
                ->orderBy('last_message_at', 'desc')
                ->paginate(4);

            Log::debug('Conversations loaded', [
                'user_id' => Auth::id(),
                'count' => $conversations->count(),
            ]);

            return view('messages.index', compact('conversations'));

        } catch (\Exception $e) {
            Log::error('Error loading conversations', [
                'user_id' => Auth::id(),
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return view('messages.index', ['conversations' => collect()])
                ->with('error', 'An error occurred while loading conversations.');
        }
    }

    /**
     * Display messages in a conversation
     *
     * @param string $id
     * @return View|RedirectResponse
     */
    public function show(string $id): View|RedirectResponse
    {
        try {
            Log::debug('Loading conversation', [
                'conversation_id' => $id,
                'user_id' => Auth::id(),
            ]);

            $conversation = Message::conversation()->findOrFail($id);

            // Check view rate limit
            if (RateLimiter::tooManyAttempts('view-conversation:' . Auth::id(), $perMinute = 60)) {
                Log::warning('Rate limit exceeded for viewing conversations', [
                    'user_id' => Auth::id(),
                ]);
                return response()->view('messages.rate-limit', [], 429);
            }
            RateLimiter::hit('view-conversation:' . Auth::id());

            // Authorize user
            $this->authorize('view', $conversation);

            // Get messages
            $messages = $conversation->messages()
                ->with('sender')
                ->orderBy('created_at', 'desc')
                ->paginate(40);

            // Mark unread messages as read
            $unreadMessages = $messages->where('is_read', false)
                ->where('sender_id', '!=', Auth::id());

            foreach ($unreadMessages as $message) {
                $message->is_read = true;
                $message->save();
            }

            Log::debug('Conversation loaded', [
                'conversation_id' => $conversation->id,
                'message_count' => $messages->count(),
            ]);

            return view('messages.show', compact('conversation', 'messages'));

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::warning('Conversation not found', [
                'conversation_id' => $id,
                'user_id' => Auth::id(),
            ]);
            return redirect()->route('messages.index')
                ->with('error', 'Conversation not found.');

        } catch (\Exception $e) {
            Log::error('Error loading conversation', [
                'conversation_id' => $id,
                'user_id' => Auth::id(),
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->route('messages.index')
                ->with('error', 'An error occurred while loading the conversation.');
        }
    }

    /**
     * Store a message in a conversation
     *
     * @param Request $request
     * @param string $id
     * @return RedirectResponse
     */
    public function store(Request $request, string $id): RedirectResponse
    {
        try {
            Log::debug('Storing message', [
                'conversation_id' => $id,
                'user_id' => Auth::id(),
            ]);

            $conversation = Message::conversation()->findOrFail($id);
            $this->authorize('sendMessage', $conversation);

            // Check message limit
            if ($conversation->hasReachedMessageLimit()) {
                Log::warning('Message limit reached', [
                    'conversation_id' => $conversation->id,
                    'user_id' => Auth::id(),
                ]);

                return redirect()->back()
                    ->with('error', 'Message limit of 40 has been reached for this chat. Please delete this chat and start a new one with the user.');
            }

            // Validate input
            $validator = Validator::make($request->all(), [
                'content' => 'required|string|min:4|max:1600',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->with('error', $validator->errors()->first())
                    ->withInput();
            }

            $validatedData = $validator->validated();

            // Purify HTML content
            $cleanContent = $this->purifyContent($validatedData['content']);

            // Create message
            $message = new Message([
                'conversation_id' => $conversation->id,
                'sender_id' => Auth::id(),
                'content' => $cleanContent,
            ]);

            if (!$message->save()) {
                Log::error('Failed to save message', [
                    'user_id' => Auth::id(),
                    'conversation_id' => $conversation->id,
                ]);

                return redirect()->back()
                    ->with('error', 'Failed to send message. Please try again.');
            }

            // Update conversation timestamp
            $conversation->last_message_at = now();
            $conversation->save();

            // Create notification for recipient
            $this->notifyRecipient($conversation, Auth::user());

            Log::info('Message sent successfully', [
                'user_id' => Auth::id(),
                'conversation_id' => $conversation->id,
            ]);

            return redirect()->route('messages.show', $conversation);

        } catch (\Exception $e) {
            Log::error('Error storing message', [
                'conversation_id' => $id,
                'user_id' => Auth::id(),
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->with('error', 'An error occurred while sending the message.');
        }
    }

    /**
     * Show create conversation form
     *
     * @param Request $request
     * @return View|RedirectResponse
     */
    public function create(Request $request): View|RedirectResponse
    {
        try {
            Log::debug('Loading create conversation form', ['user_id' => Auth::id()]);

            if (Auth::user()->hasReachedConversationLimit()) {
                Log::warning('User reached conversation limit', [
                    'user_id' => Auth::id(),
                    'limit' => 16,
                ]);

                return redirect()->route('messages.index')
                    ->with('error', 'Conversation limit of 16 reached. Please delete other conversations to create a new one.');
            }

            $username = $request->query('username');
            return view('messages.create', compact('username'));

        } catch (\Exception $e) {
            Log::error('Error loading create conversation form', [
                'user_id' => Auth::id(),
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->route('messages.index')
                ->with('error', 'An error occurred while loading the form.');
        }
    }

    /**
     * Start a new conversation
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function startConversation(Request $request): RedirectResponse
    {
        try {
            Log::debug('Starting new conversation', ['user_id' => Auth::id()]);

            // Validate input
            $validator = Validator::make($request->all(), [
                'username' => 'required|string|alpha_num|max:16',
                'content' => 'required|string|min:4|max:1600',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->with('error', $validator->errors()->first())
                    ->withInput();
            }

            $validatedData = $validator->validated();

            // Check if trying to message self
            if ($validatedData['username'] === Auth::user()->username) {
                Log::warning('User attempted to message themselves', [
                    'user_id' => Auth::id(),
                ]);

                return redirect()->back()
                    ->with('error', 'You cannot send messages to yourself.')
                    ->withInput();
            }

            // Find recipient
            $otherUser = User::where('username', $validatedData['username'])->first();

            if (!$otherUser) {
                Log::debug('User not found', [
                    'username' => $validatedData['username'],
                    'querier_id' => Auth::id(),
                ]);

                return redirect()->back()
                    ->with('error', 'The specified user does not exist.')
                    ->withInput();
            }

            if ($otherUser->id === Auth::id()) {
                Log::warning('User attempted to chat with themselves', [
                    'user_id' => Auth::id(),
                ]);

                return redirect()->back()
                    ->with('error', 'You cannot start a chat with yourself.')
                    ->withInput();
            }

            // Find or create conversation
            $existingConversation = Message::findConversation(Auth::id(), $otherUser->id);

            if ($existingConversation) {
                $conversation = $existingConversation;

                if ($conversation->hasReachedMessageLimit()) {
                    Log::warning('Existing conversation reached message limit', [
                        'conversation_id' => $conversation->id,
                        'user_id' => Auth::id(),
                    ]);

                    return redirect()->back()
                        ->with('error', 'Message limit of 40 has been reached for the existing chat. Please delete it and start a new one.');
                }
            } else {
                if (Auth::user()->hasReachedConversationLimit()) {
                    Log::warning('User reached conversation limit', [
                        'user_id' => Auth::id(),
                    ]);

                    return redirect()->back()
                        ->with('error', 'Chat limit of 16 has been reached. Please delete other chats to create a new one.');
                }

                $conversation = Message::createConversation(Auth::id(), $otherUser->id);
                Log::info('New conversation created', [
                    'conversation_id' => $conversation->id,
                    'user_id_1' => Auth::id(),
                    'user_id_2' => $otherUser->id,
                ]);
            }

            // Purify and save initial message
            $cleanContent = $this->purifyContent($validatedData['content']);

            $message = new Message([
                'conversation_id' => $conversation->id,
                'sender_id' => Auth::id(),
                'content' => $cleanContent,
            ]);

            if (!$message->save()) {
                Log::error('Failed to save initial message', [
                    'user_id' => Auth::id(),
                    'with_user_id' => $otherUser->id,
                ]);

                return redirect()->back()
                    ->with('error', 'Failed to start chat. Please try again.');
            }

            // Update conversation timestamp
            $conversation->last_message_at = now();
            $conversation->save();

            // Create notification
            $this->createConversationNotification($otherUser, Auth::user());

            Log::info('Conversation started successfully', [
                'conversation_id' => $conversation->id,
                'user_id' => Auth::id(),
                'recipient_id' => $otherUser->id,
            ]);

            return redirect()->route('messages.show', $conversation);

        } catch (\Exception $e) {
            Log::error('Error starting conversation', [
                'user_id' => Auth::id(),
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->with('error', 'An error occurred while starting the chat.');
        }
    }

    /**
     * Delete a conversation
     *
     * @param string $id
     * @return RedirectResponse
     */
    public function destroy(string $id): RedirectResponse
    {
        try {
            Log::debug('Deleting conversation', [
                'conversation_id' => $id,
                'user_id' => Auth::id(),
            ]);

            $conversation = Message::conversation()->findOrFail($id);
            $this->authorize('delete', $conversation);

            $conversation->delete(); // Soft delete

            Log::info('Conversation deleted', [
                'conversation_id' => $conversation->id,
                'user_id' => Auth::id(),
            ]);

            return redirect()->route('messages.index')
                ->with('success', 'Chat successfully deleted.');

        } catch (\Exception $e) {
            Log::error('Failed to delete conversation', [
                'conversation_id' => $id,
                'user_id' => Auth::id(),
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->with('error', 'Failed to delete chat. Please try again.');
        }
    }

    /**
     * Purify HTML content
     *
     * @param string $content
     * @return string
     */
    private function purifyContent(string $content): string
    {
        $config = HTMLPurifier_Config::createDefault();
        $config->set('HTML.Allowed', 'p,b,i,u,a[href],ul,ol,li');
        $purifier = new HTMLPurifier($config);
        return $purifier->purify($content);
    }

    /**
     * Notify recipient of new message
     *
     * @param Message $conversation
     * @param User $sender
     * @return void
     */
    private function notifyRecipient(Message $conversation, User $sender): void
    {
        try {
            $recipientId = $conversation->user_id_1 == Auth::id()
                ? $conversation->user_id_2
                : $conversation->user_id_1;

            $recipient = User::find($recipientId);

            if (!$recipient) {
                Log::warning('Recipient not found for notification', [
                    'recipient_id' => $recipientId,
                ]);
                return;
            }

            // Check if unread notification already exists
            $existingNotification = $recipient->notifications()
                ->where('title', 'LIKE', 'New message from ' . $sender->username)
                ->wherePivot('read', false)
                ->first();

            if ($existingNotification) {
                Log::debug('Unread notification already exists', [
                    'recipient_id' => $recipientId,
                    'sender_id' => $sender->id,
                ]);
                return;
            }

            // Create new notification
            $notification = new Notification([
                'title' => 'New message from ' . $sender->username,
                'message' => 'You have received a new message from ' . $sender->username,
                'type' => 'message',
            ]);
            $notification->save();
            $notification->users()->attach($recipientId, ['read' => false]);

            Log::debug('Notification created', [
                'notification_id' => $notification->id,
                'recipient_id' => $recipientId,
            ]);

        } catch (\Exception $e) {
            Log::error('Error creating message notification', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }

    /**
     * Create notification for new conversation
     *
     * @param User $recipient
     * @param User $sender
     * @return void
     */
    private function createConversationNotification(User $recipient, User $sender): void
    {
        try {
            $notification = new Notification([
                'title' => 'New message from ' . $sender->username,
                'message' => 'You have received a new message from ' . $sender->username,
                'type' => 'message',
            ]);
            $notification->save();
            $notification->users()->attach($recipient->id, ['read' => false]);

            Log::debug('Conversation notification created', [
                'notification_id' => $notification->id,
                'recipient_id' => $recipient->id,
                'sender_id' => $sender->id,
            ]);

        } catch (\Exception $e) {
            Log::error('Error creating conversation notification', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }
}
