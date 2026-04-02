@extends('layouts.auth')

@section('title', '2-Factor Authentication - Erebus Marketplace Script')
@section('breadcrumb', '2-Factor Authentication')

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

    .auth-2fa-container {
        width: 100%;
        max-width: 650px;
    }

    .auth-2fa-card {
        background-color: var(--color-bg-secondary);
        border: 1px solid var(--color-border);
        border-radius: var(--radius-lg);
        padding: var(--spacing-2xl);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    }

    .auth-2fa-title {
        font-size: 22px;
        font-weight: 600;
        color: var(--color-text-primary);
        margin: 0 0 var(--spacing-xl) 0;
        text-align: center;
    }

    .auth-2fa-description {
        font-size: 13px;
        color: var(--color-text-secondary);
        margin: 0 0 var(--spacing-lg) 0;
        text-align: center;
        line-height: 1.5;
    }

    .auth-2fa-message-box {
        background-color: var(--color-bg-primary);
        border: 1px solid var(--color-border);
        border-radius: var(--radius-md);
        padding: var(--spacing-lg);
        margin-bottom: var(--spacing-xl);
    }

    .auth-2fa-message-label {
        font-size: 11px;
        font-weight: 600;
        color: var(--color-primary);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: var(--spacing-md);
    }

    .auth-2fa-message-content {
        background-color: var(--color-bg-secondary);
        border: 1px solid var(--color-primary);
        border-radius: var(--radius-md);
        padding: var(--spacing-lg);
        font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
        font-size: 11px;
        color: var(--color-text-primary);
        line-height: 1.6;
        white-space: pre-wrap;
        word-wrap: break-word;
        max-height: 250px;
        overflow-y: auto;
        margin-bottom: var(--spacing-lg);
    }

    .auth-2fa-message-instruction {
        font-size: 12px;
        color: var(--color-text-secondary);
        margin: 0;
    }

    .auth-2fa-form {
        display: flex;
        flex-direction: column;
        gap: var(--spacing-lg);
    }

    .auth-2fa-form-group {
        display: flex;
        flex-direction: column;
        gap: var(--spacing-sm);
    }

    .auth-2fa-label {
        font-size: 12px;
        font-weight: 600;
        color: var(--color-text-primary);
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .auth-2fa-textarea {
        padding: var(--spacing-md) var(--spacing-lg);
        border: 1px solid var(--color-border);
        border-radius: var(--radius-md);
        font-size: 14px;
        color: var(--color-text-primary);
        background-color: var(--color-bg-secondary);
        font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
        transition: all 0.2s ease;
        min-height: 120px;
        resize: vertical;
    }

    .auth-2fa-textarea::placeholder {
        color: var(--color-text-secondary);
    }

    .auth-2fa-textarea:focus {
        outline: none;
        border-color: var(--color-primary);
        box-shadow: 0 0 0 2px rgba(26, 122, 153, 0.1);
    }

    .auth-2fa-button {
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
        margin-top: var(--spacing-md);
    }

    .auth-2fa-button:hover {
        background-color: var(--color-primary-light);
    }

    .auth-2fa-button:active {
        transform: scale(0.98);
    }

    .auth-2fa-links {
        text-align: center;
        margin-top: var(--spacing-xl);
        font-size: 12px;
    }

    .auth-2fa-link {
        color: var(--color-primary);
        text-decoration: none;
        font-weight: 500;
        transition: color 0.2s ease;
    }

    .auth-2fa-link:hover {
        color: var(--color-primary-light);
        text-decoration: underline;
    }

    .auth-2fa-warning-box {
        background-color: #fef3c7;
        border: 1px solid #fcd34d;
        border-radius: var(--radius-md);
        padding: var(--spacing-lg);
        margin-top: var(--spacing-lg);
        font-size: 12px;
        color: #78350f;
        line-height: 1.5;
    }

    @media (max-width: 640px) {
        .auth-2fa-card {
            padding: var(--spacing-xl);
        }

        .auth-2fa-title {
            font-size: 18px;
        }

        .auth-2fa-message-content {
            font-size: 10px;
        }
    }
</style>

<div class="auth-2fa-container">
    <div class="auth-2fa-card">
        <h1 class="auth-2fa-title">2-STEP PGP VERIFICATION</h1>
        
        <p class="auth-2fa-description">
            A security verification is required to complete your login. Please decrypt the encrypted message below using your private PGP key.
        </p>

        <div class="auth-2fa-message-box">
            <div class="auth-2fa-message-label">Encrypted Message</div>
            <pre class="auth-2fa-message-content">{{ $encryptedMessage }}</pre>
            <p class="auth-2fa-message-instruction">
                Please decrypt this message using your private PGP key and paste the decrypted message in the field below.
            </p>
        </div>
        
        <form method="POST" action="{{ route('pgp.2fa.verify') }}" class="auth-2fa-form">
            @csrf
            
            <div class="auth-2fa-form-group">
                <label for="decrypted_message" class="auth-2fa-label">Decrypted Message:</label>
                <textarea 
                    id="decrypted_message" 
                    name="decrypted_message" 
                    class="auth-2fa-textarea" 
                    placeholder="Paste your decrypted message here"
                    required 
                    autocomplete="off"
                ></textarea>
            </div>
            
            <button type="submit" class="auth-2fa-button">Complete Verification</button>
        </form>

        <div class="auth-2fa-warning-box">
            🔒 <strong>Security Note:</strong> Never share your private key or decrypted messages with anyone. This is a secure verification process to protect your account.
        </div>
        
        <div class="auth-2fa-links">
            <a href="{{ route('login') }}" class="auth-2fa-link">Back to Login</a>
        </div>
    </div>
</div>

@endsection
