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
        max-width: 600px;
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

    .messages-title {
        font-size: 1.8em;
        font-weight: 600;
        color: var(--color-text-primary);
        margin-bottom: var(--spacing-lg);
        text-align: center;
    }

    .messages-form {
        display: flex;
        flex-direction: column;
        gap: var(--spacing-lg);
    }

    .messages-form-group {
        display: flex;
        flex-direction: column;
    }

    .messages-label {
        font-weight: 600;
        color: var(--color-text-primary);
        margin-bottom: 8px;
        font-size: 0.95em;
    }

    .messages-input,
    .messages-textarea {
        padding: 10px 12px;
        border: 1px solid var(--color-border);
        border-radius: var(--radius);
        font-family: inherit;
        font-size: 0.95em;
        color: var(--color-text-primary);
        background: var(--color-bg-secondary);
        transition: all 0.3s ease;
    }

    .messages-input:focus,
    .messages-textarea:focus {
        outline: none;
        border-color: var(--color-accent);
        box-shadow: 0 0 0 3px rgba(32, 128, 136, 0.1);
    }

    .messages-textarea {
        resize: vertical;
        min-height: 120px;
    }

    .messages-button {
        background: var(--color-accent);
        color: white;
        border: none;
        padding: 12px 16px;
        border-radius: var(--radius);
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 0.95em;
    }

    .messages-button:hover {
        background: #1a6a73;
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(32, 128, 136, 0.2);
    }

    @media (max-width: 768px) {
        .messages-container { padding: var(--spacing-md); }
        .messages-card { padding: var(--spacing-lg); }
        .messages-title { font-size: 1.4em; }
    }
</style>

<div class="messages-container">
    <div class="messages-card">
        <h2 class="messages-title">Start New Conversation</h2>
        <form action="{{ route('messages.start') }}" method="POST" class="messages-form">
            @csrf
            <div class="messages-form-group">
                <label for="username" class="messages-label">Username</label>
                <input 
                    type="text" 
                    name="username" 
                    id="username" 
                    class="messages-input" 
                    required 
                    maxlength="16"
                    placeholder="Enter recipient's username" 
                    value="{{ old('username', $username ?? '') }}"
                >
                @error('username')
                    <span style="color: #c01527; font-size: 0.85em; margin-top: 4px;">{{ $message }}</span>
                @enderror
            </div>

            <div class="messages-form-group">
                <label for="content" class="messages-label">Message</label>
                <textarea 
                    name="content" 
                    id="content" 
                    class="messages-textarea" 
                    required 
                    minlength="4"
                    maxlength="1600"
                    placeholder="Type your message here..."
                >{{ old('content') }}</textarea>
                @error('content')
                    <span style="color: #c01527; font-size: 0.85em; margin-top: 4px;">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="messages-button">
                Start Conversation
            </button>
        </form>
    </div>
</div>
@endsection