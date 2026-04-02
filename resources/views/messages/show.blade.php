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
        --spacing-2xl: 32px;
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
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        display: flex;
        flex-direction: column;
        height: 600px;
    }

    .messages-header {
        padding: var(--spacing-lg);
        border-bottom: 1px solid var(--color-border);
        background: var(--color-bg-primary);
        border-radius: var(--radius) var(--radius) 0 0;
    }

    .messages-header-title {
        font-size: 1.3em;
        font-weight: 600;
        color: var(--color-text-primary);
        margin: 0;
    }

    .messages-list {
        flex: 1;
        overflow-y: auto;
        padding: var(--spacing-lg);
        display: flex;
        flex-direction: column;
        gap: var(--spacing-md);
    }

    .message-item {
        display: flex;
        margin-bottom: var(--spacing-md);
    }

    .message-sent {
        justify-content: flex-end;
    }

    .message-bubble {
        max-width: 70%;
        padding: 10px 12px;
        border-radius: var(--radius);
        word-wrap: break-word;
    }

    .message-sent .message-bubble {
        background: var(--color-accent);
        color: white;
    }

    .message-received .message-bubble {
        background: var(--color-bg-primary);
        color: var(--color-text-primary);
        border: 1px solid var(--color-border);
    }

    .message-meta {
        font-size: 0.8em;
        color: var(--color-text-secondary);
        margin-top: 4px;
        padding: 0 12px;
    }

    .messages-form {
        padding: var(--spacing-lg);
        border-top: 1px solid var(--color-border);
        display: flex;
        gap: 8px;
    }

    .messages-textarea {
        flex: 1;
        padding: 10px 12px;
        border: 1px solid var(--color-border);
        border-radius: var(--radius);
        font-family: inherit;
        font-size: 0.95em;
        color: var(--color-text-primary);
        resize: none;
        min-height: 40px;
    }

    .messages-textarea:focus {
        outline: none;
        border-color: var(--color-accent);
        box-shadow: 0 0 0 3px rgba(32, 128, 136, 0.1);
    }

    .messages-button {
        background: var(--color-accent);
        color: white;
        border: none;
        padding: 10px 16px;
        border-radius: var(--radius);
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .messages-button:hover {
        background: #1a6a73;
    }

    .messages-limit-alert {
        background: #fff3cd;
        border: 1px solid #ffc107;
        color: #856404;
        padding: var(--spacing-md);
        border-radius: var(--radius);
        margin-bottom: var(--spacing-md);
        font-size: 0.9em;
    }

    @media (max-width: 768px) {
        .messages-container { padding: var(--spacing-md); }
        .messages-card { height: auto; max-height: 80vh; }
        .message-bubble { max-width: 100%; }
    }
</style>

<div class="messages-container">
    <div class="messages-card">
        <div class="messages-header">
            <h2 class="messages-header-title">Conversation</h2>
        </div>

        <div class="messages-list" id="messageContainer">
            @forelse($messages as $message)
                <div class="message-item {{ $message->sender_id == Auth::id() ? 'message-sent' : 'message-received' }}">
                    <div>
                        <div class="message-bubble">
                            {{ $message->content }}
                        </div>
                        <div class="message-meta">
                            <strong>{{ $message->sender->username }}</strong> • {{ $message->created_at->format('M d, Y H:i') }}
                        </div>
                    </div>
                </div>
            @empty
                <div style="text-align: center; color: var(--color-text-secondary); padding: var(--spacing-lg);">
                    <p>No messages in this conversation yet.</p>
                </div>
            @endforelse
        </div>

        @if ($conversation->hasReachedMessageLimit())
            <div class="messages-limit-alert">
                ⚠️ Message limit of 40 reached. Please delete this conversation to start a new one.
            </div>
        @else
            <form action="{{ route('messages.store', $conversation) }}" method="POST" class="messages-form">
                @csrf
                <textarea 
                    name="content" 
                    class="messages-textarea" 
                    placeholder="Type your message..." 
                    required 
                    minlength="4"
                    maxlength="1600"
                ></textarea>
                <button type="submit" class="messages-button">Send</button>
            </form>
        @endif
    </div>
</div>

<script>
    // Auto-scroll to bottom
    const container = document.getElementById('messageContainer');
    if (container) {
        container.scrollTop = container.scrollHeight;
    }
</script>
@endsection