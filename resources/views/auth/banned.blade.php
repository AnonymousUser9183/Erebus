@extends('layouts.auth')

@section('title', 'Account Banned - Erebus Marketplace Script')
@section('breadcrumb', 'Account Banned')

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
        --color-danger: #dc2626;
        --spacing-sm: 8px;
        --spacing-md: 12px;
        --spacing-lg: 16px;
        --spacing-xl: 24px;
        --radius-md: 6px;
        --radius-lg: 8px;
    }

    .auth-banned-container {
        width: 100%;
        max-width: 550px;
    }

    .auth-banned-card {
        background-color: var(--color-bg-secondary);
        border: 1px solid var(--color-border);
        border-radius: var(--radius-lg);
        padding: var(--spacing-2xl);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        text-align: center;
    }

    .auth-banned-title {
        font-size: 22px;
        font-weight: 600;
        color: var(--color-danger);
        margin: 0 0 var(--spacing-lg) 0;
    }

    .auth-banned-message {
        font-size: 14px;
        color: var(--color-text-primary);
        margin: 0 0 var(--spacing-xl) 0;
        line-height: 1.6;
    }

    .auth-banned-details {
        background-color: #fee2e2;
        border: 1px solid #fecaca;
        border-radius: var(--radius-md);
        padding: var(--spacing-lg);
        margin-bottom: var(--spacing-xl);
        text-align: left;
    }

    .auth-banned-detail-item {
        font-size: 13px;
        color: #7f1d1d;
        margin: 0 0 var(--spacing-sm) 0;
        line-height: 1.5;
    }

    .auth-banned-detail-item:last-child {
        margin-bottom: 0;
    }

    .auth-banned-detail-label {
        font-weight: 600;
        color: #991b1b;
    }

    .auth-banned-contact-info {
        font-size: 13px;
        color: var(--color-text-secondary);
        margin: 0 0 var(--spacing-xl) 0;
        line-height: 1.6;
        background-color: var(--color-bg-primary);
        border-radius: var(--radius-md);
        padding: var(--spacing-lg);
    }

    .auth-banned-links {
        display: flex;
        justify-content: center;
    }

    .auth-banned-link {
        color: var(--color-primary);
        text-decoration: none;
        font-weight: 500;
        font-size: 12px;
        transition: color 0.2s ease;
    }

    .auth-banned-link:hover {
        color: var(--color-primary-light);
        text-decoration: underline;
    }

    .auth-banned-warning {
        background-color: #fef3c7;
        border: 1px solid #fcd34d;
        border-radius: var(--radius-md);
        padding: var(--spacing-lg);
        margin-top: var(--spacing-xl);
        font-size: 12px;
        color: #78350f;
        line-height: 1.5;
    }

    @media (max-width: 640px) {
        .auth-banned-card {
            padding: var(--spacing-xl);
        }

        .auth-banned-title {
            font-size: 18px;
        }
    }
</style>

<div class="auth-banned-container">
    <div class="auth-banned-card">
        <h1 class="auth-banned-title">⛔ YOUR ACCOUNT HAS BEEN BANNED</h1>
        
        <p class="auth-banned-message">
            Your account has been temporarily banned for violating our site rules and terms of service.
        </p>
        
        <div class="auth-banned-details">
            <div class="auth-banned-detail-item">
                <span class="auth-banned-detail-label">Reason:</span><br>
                {{ $bannedUser->bannedUser->reason }}
            </div>
            <div class="auth-banned-detail-item">
                <span class="auth-banned-detail-label">Ban Until:</span><br>
                {{ $bannedUser->bannedUser->banned_until->format('Y-m-d \a\t H:i:s') }}
            </div>
        </div>
        
        <div class="auth-banned-contact-info">
            💬 If you believe this is a mistake or want to appeal, you can create a new account and open a support ticket to contest the ban. Our moderators will review your case.
        </div>

        <div class="auth-banned-warning">
            ⚠️ <strong>Note:</strong> If you continue to violate our rules after your ban is lifted, your account will be permanently banned and all funds will be forfeited.
        </div>
        
        <div class="auth-banned-links" style="margin-top: var(--spacing-xl);">
            <a href="{{ route('login') }}" class="auth-banned-link">Return to Login</a>
        </div>
    </div>
</div>

@endsection
