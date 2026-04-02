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
        --spacing-lg: 20px;
        --spacing-xl: 24px;
        --spacing-2xl: 32px;
        --radius: 8px;
    }

    .rate-limit-wrapper {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
        background: var(--color-bg-primary);
        padding: var(--spacing-lg);
    }

    .rate-limit-card {
        background: var(--color-bg-secondary);
        border-radius: var(--radius);
        border: 1px solid var(--color-border);
        padding: var(--spacing-2xl);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
        max-width: 500px;
        text-align: center;
    }

    .rate-limit-icon {
        font-size: 4em;
        margin-bottom: var(--spacing-lg);
        display: inline-block;
    }

    .rate-limit-title {
        font-size: 1.8em;
        font-weight: 600;
        color: var(--color-text-primary);
        margin-bottom: var(--spacing-lg);
    }

    .rate-limit-alert {
        background: #fff3cd;
        border: 1px solid #ffc107;
        border-radius: var(--radius);
        padding: var(--spacing-lg);
        margin-bottom: var(--spacing-xl);
    }

    .rate-limit-alert-heading {
        font-size: 1.2em;
        font-weight: 600;
        color: #856404;
        margin-bottom: 8px;
    }

    .rate-limit-message {
        color: #856404;
        margin-bottom: 12px;
        font-size: 0.95em;
    }

    .rate-limit-divider {
        border: none;
        border-top: 1px solid #ffc107;
        margin: 12px 0;
    }

    .rate-limit-submessage {
        color: #856404;
        margin-bottom: 0;
        font-size: 0.9em;
        font-weight: 500;
    }

    .rate-limit-action {
        margin-top: var(--spacing-lg);
    }

    .rate-limit-button {
        display: inline-block;
        background: var(--color-accent);
        color: white;
        padding: 12px 32px;
        border-radius: var(--radius);
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }

    .rate-limit-button:hover {
        background: #1a6a73;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(32, 128, 136, 0.2);
    }

    @media (max-width: 768px) {
        .rate-limit-wrapper { padding: var(--spacing-md); }
        .rate-limit-card { padding: var(--spacing-lg); }
        .rate-limit-title { font-size: 1.4em; }
        .rate-limit-icon { font-size: 3em; }
    }
</style>

<div class="rate-limit-wrapper">
    <div class="rate-limit-card">
        <div class="rate-limit-icon">⏱️</div>
        <h2 class="rate-limit-title">Rate Limit Exceeded</h2>
        
        <div class="rate-limit-alert">
            <h4 class="rate-limit-alert-heading">Too Many Messages</h4>
            <p class="rate-limit-message">
                For security reasons, we have temporarily limited your message sending frequency.
            </p>
            <div class="rate-limit-divider"></div>
            <p class="rate-limit-submessage">
                Please wait a while before sending another message.
            </p>
        </div>

        <div class="rate-limit-action">
            <a href="{{ route('messages.index') }}" class="rate-limit-button">
                Return to Messages
            </a>
        </div>
    </div>
</div>
@endsection