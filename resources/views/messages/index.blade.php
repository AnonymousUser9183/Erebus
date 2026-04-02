@extends('layouts.app')

@section('content')
<style>
    :root {
        --color-bg-primary: #fcfcf9;
        --color-bg-secondary: #ffffff;
        --color-text-primary: #134252;
        --color-text-secondary: #626c71;
        --color-border: #e8e8e6;
        --color-accent: #208088;
        --color-accent-light: #32b8c6;
        --spacing-md: 16px;
        --spacing-lg: 20px;
        --spacing-xl: 24px;
        --radius: 8px;
    }

    .messages-container {
        max-width: 800px;
        margin: var(--spacing-xl) auto;
        padding: var(--spacing-lg);
    }

    .messages-card {
        background: var(--color-bg-secondary);
        border-radius: var(--radius);
        border: 1px solid var(--color-border);
        padding: var(--spacing-xl);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    }

    .messages-new-btn {
        display: inline-block;
        background: var(--color-accent);
        color: white;
        padding: 10px 16px;
        border-radius: var(--radius);
        text-decoration: none;
        font-weight: 600;
        margin-bottom: var(--spacing-lg);
        transition: all 0.3s ease;
    }

    .messages-new-btn:hover {
        background: #1a6a73;
        transform: translateY(-1px);
    }

    .messages-empty {
        text-align: center;
        padding: var(--spacing-2xl) var(--spacing-lg);
        color: var(--color-text-secondary);
        font-size: 1.1em;
    }

    .messages-list {
        border-top: 1px solid var(--color-border);
    }

    .messages-item {
        border-bottom: 1px solid var(--color-border);
        padding: var(--spacing-lg);
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        transition: all 0.3s ease;
    }

    .messages-item:hover {
        background: var(--color-bg-primary);
    }

    .messages-item-link {
        flex: 1;
        text-decoration: none;
        color: inherit;
    }

    .messages-header {
        display: flex;
        justify-content: space-between;
        align-items: baseline;
        margin-bottom: 8px;
    }

    .messages-username {
        font-weight: 600;
        color: var(--color-text-primary);
        margin: 0;
        font-size: 1em;
    }

    .messages-time {
        color: var(--color-text-secondary);
        font-size: 0.85em;
    }

    .messages-preview {
        color: var(--color-text-secondary);
        font-size: 0.9em;
        margin: 0;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .messages-delete {
        background: #c01527;
        color: white;
        border: none;
        padding: 6px 12px;
        border-radius: 4px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s ease;
        margin-left: var(--spacing-md);
        flex-shrink: 0;
    }

    .messages-delete:hover {
        background: #a01020;
        transform: scale(1.05);
    }

    .messages-limit-warning {
        background: #fff3cd;
        border: 1px solid #ffc107;
        color: #856404;
        padding: var(--spacing-md);
        border-radius: var(--radius);
        margin-top: var(--spacing-lg);
        font-weight: 500;
    }

    @media (max-width: 768px) {
        .messages-container { padding: var(--spacing-md); }
        .messages-card { padding: var(--spacing-lg); }
        .messages-delete { padding: 4px 8px; font-size: 0.85em; }
    }
</style>

<div class="messages-container">
    <div class="messages-card">
        @if(!Auth::user()->hasReachedConversationLimit())
            <a href="{{ route('messages.create') }}" class="messages-new-btn">
                ➕ Start New Conversation
            </a>
        @endif
        
        @if($conversations->isEmpty())
            <div class="messages-empty">
                📭 You don't have any conversations yet.
            </div>
        @else
            <div class="messages-list">
                @foreach($conversations as $conversation)
                    <div class="messages-item">
                        <a href="{{ route('messages.show', $conversation) }}" class="messages-item-link">
                            <div class="messages-header">
                                <h5 class="messages-username">
                                    @if($conversation->user1->id == Auth::id())
                                        {{ $conversation->user2->username }}
                                    @else
                                        {{ $conversation->user1->username }}
                                    @endif
                                </h5>
                                <span class="messages-time">
                                    @if($conversation->last_message_at)
                                        {{ $conversation->last_message_at->format('M d, Y H:i') }}
                                    @else
                                        No messages yet
                                    @endif
                                </span>
                            </div>
                            <p class="messages-preview">
                                @if($conversation->messages->isNotEmpty())
                                    {{ Str::limit($conversation->messages->last()->content, 80) }}
                                @else
                                    No messages yet
                                @endif
                            </p>
                        </a>
                        <form action="{{ route('messages.destroy', $conversation) }}" method="POST" style="margin: 0;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="messages-delete" title="Delete conversation" onclick="return confirm('Delete this conversation?');">
                                Delete
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        @endif

        @if (Auth::user()->hasReachedConversationLimit())
            <div class="messages-limit-warning">
                ⚠️ Conversation limit of 16 reached. Please delete other conversations to create a new one.
            </div>
        @endif
    </div>
</div>
@endsection
