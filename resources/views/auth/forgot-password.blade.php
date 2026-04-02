@extends('layouts.auth')

@section('title', 'Forgot Password - Erebus Marketplace Script')
@section('breadcrumb', 'Forgot Password')

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
        --spacing-sm: 8px;
        --spacing-md: 12px;
        --spacing-lg: 16px;
        --spacing-xl: 24px;
        --radius-md: 6px;
        --radius-lg: 8px;
    }

    .auth-forgot-container {
        width: 100%;
        max-width: 550px;
    }

    .auth-forgot-card {
        background-color: var(--color-bg-secondary);
        border: 1px solid var(--color-border);
        border-radius: var(--radius-lg);
        padding: var(--spacing-2xl);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    }

    .auth-forgot-title {
        font-size: 22px;
        font-weight: 600;
        color: var(--color-text-primary);
        margin: 0 0 var(--spacing-xl) 0;
        text-align: center;
    }

    .auth-forgot-description {
        font-size: 13px;
        color: var(--color-text-secondary);
        margin: 0 0 var(--spacing-xl) 0;
        text-align: center;
        line-height: 1.5;
    }

    .auth-forgot-form {
        display: flex;
        flex-direction: column;
        gap: var(--spacing-lg);
    }

    .auth-forgot-form-group {
        display: flex;
        flex-direction: column;
        gap: var(--spacing-sm);
    }

    .auth-forgot-label {
        font-size: 12px;
        font-weight: 600;
        color: var(--color-text-primary);
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .auth-forgot-input {
        padding: var(--spacing-md) var(--spacing-lg);
        border: 1px solid var(--color-border);
        border-radius: var(--radius-md);
        font-size: 14px;
        color: var(--color-text-primary);
        background-color: var(--color-bg-secondary);
        font-family: inherit;
        transition: all 0.2s ease;
    }

    .auth-forgot-input::placeholder {
        color: var(--color-text-secondary);
    }

    .auth-forgot-input:focus {
        outline: none;
        border-color: var(--color-primary);
        box-shadow: 0 0 0 2px rgba(26, 122, 153, 0.1);
    }

    .auth-forgot-textarea {
        padding: var(--spacing-md) var(--spacing-lg);
        border: 1px solid var(--color-border);
        border-radius: var(--radius-md);
        font-size: 14px;
        color: var(--color-text-primary);
        background-color: var(--color-bg-secondary);
        font-family: inherit;
        transition: all 0.2s ease;
        min-height: 100px;
        resize: vertical;
    }

    .auth-forgot-textarea:focus {
        outline: none;
        border-color: var(--color-primary);
        box-shadow: 0 0 0 2px rgba(26, 122, 153, 0.1);
    }

    .auth-forgot-button {
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

    .auth-forgot-button:hover {
        background-color: var(--color-primary-light);
    }

    .auth-forgot-button:active {
        transform: scale(0.98);
    }

    .auth-forgot-links {
        text-align: center;
        margin-top: var(--spacing-xl);
        font-size: 12px;
    }

    .auth-forgot-link {
        color: var(--color-primary);
        text-decoration: none;
        font-weight: 500;
        transition: color 0.2s ease;
    }

    .auth-forgot-link:hover {
        color: var(--color-primary-light);
        text-decoration: underline;
    }

    .auth-forgot-info-box {
        background-color: #fef3c7;
        border: 1px solid #fcd34d;
        border-radius: var(--radius-md);
        padding: var(--spacing-lg);
        margin-bottom: var(--spacing-lg);
        font-size: 12px;
        color: #78350f;
        line-height: 1.5;
    }

    @media (max-width: 640px) {
        .auth-forgot-card {
            padding: var(--spacing-xl);
        }

        .auth-forgot-title {
            font-size: 18px;
        }
    }
</style>

<div class="auth-forgot-container">
    <div class="auth-forgot-card">
        <h1 class="auth-forgot-title">FORGOT PASSWORD</h1>
        
        <p class="auth-forgot-description">
            Lost your password? No problem! Enter your username and your 12-word mnemonic phrase to reset your password.
        </p>

        <div class="auth-forgot-info-box">
            ⚠️ <strong>Important:</strong> You will need your 12-word mnemonic phrase that was provided during registration to recover your account.
        </div>
        
        <form method="POST" action="{{ route('password.verify') }}" class="auth-forgot-form">
            @csrf
            
            <div class="auth-forgot-form-group">
                <label for="username" class="auth-forgot-label">Username:</label>
                <input 
                    type="text" 
                    id="username" 
                    name="username" 
                    class="auth-forgot-input" 
                    placeholder="Enter your username"
                    value="{{ old('username') }}" 
                    minlength="4" 
                    maxlength="16" 
                    required 
                    autofocus
                >
            </div>
            
            <div class="auth-forgot-form-group">
                <label for="mnemonic" class="auth-forgot-label">12-Word Mnemonic Phrase:</label>
                <textarea 
                    id="mnemonic" 
                    name="mnemonic" 
                    class="auth-forgot-textarea" 
                    placeholder="Enter your 12-word mnemonic phrase separated by spaces"
                    minlength="40" 
                    maxlength="512" 
                    required
                >{{ old('mnemonic') }}</textarea>
            </div>
            
            <button type="submit" class="auth-forgot-button">Verify Mnemonic Phrase</button>
        </form>
        
        <div class="auth-forgot-links">
            <a href="{{ route('login') }}" class="auth-forgot-link">Back to Login</a>
        </div>
    </div>
</div>

@endsection
