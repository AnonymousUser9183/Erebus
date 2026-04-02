@extends('layouts.app')

@section('content')
<div class="support-show-container">
    <div class="support-show-card">
        <div class="support-show-header">
            <div class="support-show-title-group">
                <h1 class="support-show-username">Support Request from {{ $supportRequest->user->username }}</h1>
                <div class="support-show-subject">{{ $supportRequest->subject ?? $supportRequest->title }}</div>
                <div class="support-show-meta">
                    <span>Created at {{ $supportRequest->created_at->format('Y-m-d H:i') }}</span>
                    <span>Category: <strong>{{ ucfirst($supportRequest->category ?? 'N/A') }}</strong></span>
                </div>
            </div>

            <!-- Status Update Form -->
            <form action="{{ route('admin.support.status', [$supportRequest, $supportRequest->ticket_id]) }}" method="POST" class="support-show-status-form">
                @csrf
                @method('PUT')
                <select name="status" class="support-show-status-select">
                    <option value="open" @if($supportRequest->status === 'open') selected @endif>Open</option>
                    <option value="in_progress" @if($supportRequest->status === 'in_progress') selected @endif>In Progress</option>
                    <option value="closed" @if($supportRequest->status === 'closed') selected @endif>Closed</option>
                </select>
                <button type="submit" class="support-show-status-btn">Update Status</button>
            </form>
        </div>

        <!-- Alert Messages -->
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <!-- Messages Thread -->
        <div class="support-show-messages">
            <!-- Original Request -->
            <div class="support-show-message">
                <div class="support-show-message-header">
                    <a href="{{ route('dashboard', $supportRequest->user->username) }}" class="support-show-message-user">
                        {{ $supportRequest->user->username }}
                    </a>
                    <div class="support-show-message-time">{{ $supportRequest->created_at->format('Y-m-d H:i') }}</div>
                </div>
                <div class="support-show-message-content">
                    {{ $supportRequest->message }}
                </div>
            </div>

            <!-- All Replies -->
            @forelse ($messages as $message)
                <div class="support-show-message @if($message->is_admin_reply) admin-reply @endif">
                    <div class="support-show-message-header">
                        <div>
                            <a href="{{ route('dashboard', $message->user->username) }}" class="support-show-message-user">
                                {{ $message->user->username }}
                            </a>
                            @if ($message->is_admin_reply)
                                <span class="support-show-admin-badge">Admin</span>
                            @endif
                        </div>
                        <div class="support-show-message-time">{{ $message->created_at->format('Y-m-d H:i') }}</div>
                    </div>
                    <div class="support-show-message-content">
                        {{ $message->message }}
                    </div>
                </div>
            @empty
                <p style="text-align: center; color: 999">No replies yet.</p>
            @endforelse
        </div>

        <!-- Reply Section -->
        @if ($supportRequest->status !== 'closed')
            <div class="support-show-reply-section">
                <!-- Form with correct route parameters: [$supportRequest, $supportRequest->ticket_id] -->
                <form action="{{ route('admin.support.reply', [$supportRequest, $supportRequest->ticket_id]) }}" method="POST" class="support-show-form">
                    @csrf
                    <div class="support-show-form-group">
                        <label for="message" class="support-show-label">Admin Reply</label>
                        <textarea name="message" id="message" class="support-show-textarea @error('message') is-invalid @enderror" required rows="4" placeholder="Write your reply here...">{{ old('message') }}</textarea>
                        @error('message')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="support-show-submit">
                        <button type="submit" class="support-show-submit-btn">Send Reply</button>
                    </div>
                </form>
            </div>
        @else
            <div class="support-show-closed-message">
                This request is closed. Please change the status to open or in progress to reply.
            </div>
        @endif

        <!-- Back Button -->
        <a href="{{ route('admin.support.requests') }}" class="support-show-back-btn">Return to Requests</a>
    </div>
</div>

<style>
    :root {
        --color-bg-primary: #fcfcf9;
        --color-bg-secondary: #ffffff;
        --color-text-primary: #134252;
        --color-text-secondary: #626c71;
        --color-border: #e8e8e6;
        --color-accent: #208088;
        --color-accent-light: #32b8c6;
        --spacing-xs: 8px;
        --spacing-sm: 12px;
        --spacing-md: 16px;
        --spacing-lg: 20px;
        --spacing-xl: 24px;
        --spacing-2xl: 32px;
        --radius: 8px;
    }

    .support-show-container {
        max-width: 1000px;
        margin: 0 auto;
        padding: var(--spacing-xl);
    }

    .support-show-card {
        background-color: var(--color-bg-secondary);
        border: 1px solid var(--color-border);
        border-radius: var(--radius);
        padding: var(--spacing-lg);
    }

    .support-show-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: var(--spacing-lg);
        padding-bottom: var(--spacing-lg);
        border-bottom: 2px solid var(--color-accent-light);
    }

    .support-show-title-group {
        flex: 1;
    }

    .support-show-username {
        font-size: 28px;
        font-weight: 600;
        color: var(--color-text-primary);
        margin: 0 0 var(--spacing-sm) 0;
    }

    .support-show-subject {
        font-size: 16px;
        font-weight: 500;
        color: var(--color-accent);
        margin: var(--spacing-sm) 0;
    }

    .support-show-meta {
        font-size: 13px;
        color: var(--color-text-secondary);
        display: flex;
        align-items: center;
        gap: var(--spacing-md);
        flex-wrap: wrap;
    }

    .support-show-status-form {
        display: flex;
        gap: var(--spacing-sm);
        align-items: center;
    }

    .support-show-status-select {
        padding: var(--spacing-xs) var(--spacing-sm);
        border: 1px solid var(--color-border);
        border-radius: var(--radius);
        font-size: 13px;
        color: var(--color-text-primary);
        background-color: var(--color-bg-secondary);
        cursor: pointer;
    }

    .support-show-status-btn {
        padding: var(--spacing-xs) var(--spacing-sm);
        background-color: var(--color-accent);
        color: #ffffff;
        border: none;
        border-radius: var(--radius);
        font-size: 12px;
        font-weight: 500;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .support-show-status-btn:hover {
        background-color: var(--color-accent-light);
    }

    .alert {
        padding: var(--spacing-lg);
        border-radius: var(--radius);
        margin-bottom: var(--spacing-lg);
    }

    .alert-success {
        background-color: #d4edda;
        border: 1px solid #c3e6cb;
        color: #155724;
    }

    .alert-danger {
        background-color: #f8d7da;
        border: 1px solid #f5c6cb;
        color: #721c24;
    }

    .support-show-back-btn {
        display: inline-block;
        background-color: var(--color-text-secondary);
        color: #ffffff;
        padding: var(--spacing-xs) var(--spacing-sm);
        border: none;
        border-radius: var(--radius);
        font-size: 12px;
        font-weight: 500;
        cursor: pointer;
        text-decoration: none;
        transition: background-color 0.3s ease;
        margin-bottom: var(--spacing-lg);
        margin-top: var(--spacing-lg);
    }

    .support-show-back-btn:hover {
        background-color: #4b5563;
    }

    .support-show-messages {
        margin: var(--spacing-2xl) 0;
        display: flex;
        flex-direction: column;
        gap: var(--spacing-lg);
    }

    .support-show-message {
        background-color: var(--color-bg-primary);
        border: 1px solid var(--color-border);
        border-radius: var(--radius);
        padding: var(--spacing-lg);
    }

    .support-show-message.admin-reply {
        background-color: #fef3c7;
        border-left: 4px solid #f59e0b;
    }

    .support-show-message-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: var(--spacing-md);
        padding-bottom: var(--spacing-md);
        border-bottom: 1px solid var(--color-border);
    }

    .support-show-message-user {
        color: var(--color-accent);
        text-decoration: none;
        font-weight: 500;
    }

    .support-show-message-user:hover {
        text-decoration: underline;
    }

    .support-show-admin-badge {
        display: inline-block;
        background-color: #f59e0b;
        color: #ffffff;
        padding: var(--spacing-xs) var(--spacing-sm);
        border-radius: var(--radius);
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        margin-left: var(--spacing-sm);
    }

    .support-show-message-time {
        font-size: 12px;
        color: var(--color-text-secondary);
    }

    .support-show-message-content {
        font-size: 14px;
        color: var(--color-text-primary);
        line-height: 1.6;
        white-space: pre-wrap;
        word-wrap: break-word;
    }

    .support-show-reply-section {
        background-color: var(--color-bg-primary);
        border: 1px solid var(--color-border);
        border-radius: var(--radius);
        padding: var(--spacing-lg);
        margin-top: var(--spacing-lg);
    }

    .support-show-form-group {
        margin-bottom: var(--spacing-lg);
    }

    .support-show-label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: var(--color-text-primary);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: var(--spacing-sm);
    }

    .support-show-textarea {
        width: 100%;
        padding: var(--spacing-md);
        border: 1px solid var(--color-border);
        border-radius: var(--radius);
        font-size: 14px;
        color: var(--color-text-primary);
        background-color: var(--color-bg-secondary);
        font-family: inherit;
        box-sizing: border-box;
        resize: vertical;
        transition: border-color 0.3s ease;
    }

    .support-show-textarea:focus {
        outline: none;
        border-color: var(--color-accent);
        box-shadow: 0 0 0 2px rgba(32, 128, 136, 0.1);
    }

    .support-show-textarea.is-invalid {
        border-color: #dc3545;
        background-color: #fee;
    }

    .invalid-feedback {
        color: #dc3545;
        font-size: 12px;
        margin-top: 5px;
        display: block;
    }

    .support-show-submit {
        text-align: center;
    }

    .support-show-submit-btn {
        background-color: var(--color-accent);
        color: #ffffff;
        padding: var(--spacing-md) var(--spacing-lg);
        border: none;
        border-radius: var(--radius);
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .support-show-submit-btn:hover {
        background-color: var(--color-accent-light);
    }

    .support-show-closed-message {
        background-color: #fee2e2;
        border: 1px solid #fecaca;
        border-radius: var(--radius);
        padding: var(--spacing-lg);
        color: #991b1b;
        text-align: center;
        font-size: 14px;
    }

    @media (max-width: 768px) {
        .support-show-container {
            padding: var(--spacing-md);
        }

        .support-show-header {
            flex-direction: column;
            gap: var(--spacing-lg);
        }

        .support-show-username {
            font-size: 22px;
        }

        .support-show-meta {
            flex-direction: column;
            align-items: flex-start;
        }

        .support-show-status-form {
            flex-direction: column;
        }

        .support-show-status-select,
        .support-show-status-btn {
            width: 100%;
        }
    }
</style>
@endsection
