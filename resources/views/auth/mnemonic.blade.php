@extends('layouts.auth')

@section('title', 'Your Mnemonic Phrase - Erebus Marketplace Script')
@section('breadcrumb', 'Mnemonic Phrase')

@section('content')

<style>
    :root {
        --color-primary: #1a7a99;
        --color-primary-light: #2a9db8;
        --color-text-primary: #333333;
        --color-text-secondary: #666666;
        --color-bg-primary: #f5f5f5;
        --color-bg-secondary: #ffffff;
        --color-border: #e0e0e0;
        --color-warning: #ea580c;
        --spacing-sm: 8px;
        --spacing-md: 12px;
        --spacing-lg: 16px;
        --spacing-xl: 24px;
        --radius-md: 6px;
        --radius-lg: 8px;
    }

    .auth-mnemonic-container {
        width: 100%;
        max-width: 650px;
    }

    .auth-mnemonic-card {
        background-color: var(--color-bg-secondary);
        border: 1px solid var(--color-border);
        border-radius: var(--radius-lg);
        padding: var(--spacing-2xl);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    }

    .auth-mnemonic-title {
        font-size: 22px;
        font-weight: 600;
        color: var(--color-text-primary);
        margin: 0 0 var(--spacing-xl) 0;
        text-align: center;
    }

    .auth-mnemonic-warning-box {
        background-color: #fef3c7;
        border: 1px solid #fcd34d;
        border-left: 4px solid var(--color-warning);
        border-radius: var(--radius-md);
        padding: var(--spacing-lg);
        margin-bottom: var(--spacing-xl);
        font-size: 13px;
        color: #78350f;
        line-height: 1.6;
    }

    .auth-mnemonic-warning-title {
        font-weight: 600;
        margin-bottom: var(--spacing-sm);
        font-size: 14px;
    }

    .auth-mnemonic-display-box {
        background-color: var(--color-bg-primary);
        border: 1px solid var(--color-border);
        border-radius: var(--radius-md);
        padding: var(--spacing-lg);
        margin-bottom: var(--spacing-xl);
    }

    .auth-mnemonic-words {
        background-color: var(--color-bg-secondary);
        border: 1px solid var(--color-primary);
        border-radius: var(--radius-md);
        padding: var(--spacing-lg);
        font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
        font-size: 16px;
        color: var(--color-text-primary);
        line-height: 1.8;
        white-space: pre-wrap;
        word-wrap: break-word;
        user-select: text;
        margin: 0;
    }

    .auth-mnemonic-description {
        font-size: 12px;
        color: var(--color-text-secondary);
        margin-top: var(--spacing-md);
        line-height: 1.5;
    }

    .auth-mnemonic-form {
        display: flex;
        flex-direction: column;
        gap: var(--spacing-lg);
    }

    .auth-mnemonic-button {
        padding: var(--spacing-md) var(--spacing-lg);
        background-color: var(--color-primary);
        color: white;
        border: none;
        border-radius: var(--radius-md);
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .auth-mnemonic-button:hover {
        background-color: var(--color-primary-light);
    }

    .auth-mnemonic-button:active {
        transform: scale(0.98);
    }

    .auth-mnemonic-checklist {
        background-color: #f0f9ff;
        border: 1px solid #bfdbfe;
        border-radius: var(--radius-md);
        padding: var(--spacing-lg);
        margin-top: var(--spacing-xl);
        font-size: 12px;
        color: #1e40af;
        line-height: 1.7;
    }

    .auth-mnemonic-checklist-item {
        margin-bottom: var(--spacing-sm);
    }

    .auth-mnemonic-checklist-item:last-child {
        margin-bottom: 0;
    }

    @media (max-width: 640px) {
        .auth-mnemonic-card {
            padding: var(--spacing-xl);
        }

        .auth-mnemonic-title {
            font-size: 18px;
        }

        .auth-mnemonic-words {
            font-size: 14px;
        }
    }
</style>

<div class="auth-mnemonic-container">
    <div class="auth-mnemonic-card">
        <h1 class="auth-mnemonic-title">YOUR MNEMONIC PHRASE</h1>
        
        <div class="auth-mnemonic-warning-box">
            <div class="auth-mnemonic-warning-title">⚠️ IMPORTANT - READ CAREFULLY</div>
            <p style="margin: 0;">
                This is the ONLY time you will see this 12-word mnemonic phrase. Please write it down and store it securely OFFLINE in a safe place. You will need this to recover your account if you forget your password. Anyone with access to this phrase can access your account.
            </p>
        </div>

        <div class="auth-mnemonic-display-box">
            <pre class="auth-mnemonic-words">{{ $mnemonic }}</pre>
            <p class="auth-mnemonic-description">
                ☝️ Your 12-word mnemonic phrase is displayed above. Make sure to store it safely.
            </p>
        </div>

        <div class="auth-mnemonic-checklist">
            <div class="auth-mnemonic-checklist-item">✓ I have written down my 12-word mnemonic phrase</div>
            <div class="auth-mnemonic-checklist-item">✓ I have stored it securely offline in a safe place</div>
            <div class="auth-mnemonic-checklist-item">✓ I understand that losing this phrase means losing access to my account</div>
            <div class="auth-mnemonic-checklist-item">✓ I understand that sharing this phrase gives others access to my account</div>
        </div>
        
        <form method="GET" action="{{ route('login') }}" class="auth-mnemonic-form">
            <button type="submit" class="auth-mnemonic-button">Continue to Login</button>
        </form>
    </div>
</div>

@endsection
