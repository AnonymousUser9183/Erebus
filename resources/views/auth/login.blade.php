@extends('layouts.auth')

@section('title', 'Login - Erebus Marketplace Script')
@section('breadcrumb', 'Login')

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
        --color-success: #059669;
        --spacing-sm: 8px;
        --spacing-md: 12px;
        --spacing-lg: 16px;
        --spacing-xl: 24px;
        --spacing-2xl: 32px;
        --radius-md: 6px;
        --radius-lg: 8px;
    }

    .auth-login-container {
        width: 100%;
        max-width: 500px;
    }

    .auth-login-card {
        background-color: var(--color-bg-secondary);
        border: 1px solid var(--color-border);
        border-radius: var(--radius-lg);
        padding: var(--spacing-2xl);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    }

    .auth-login-title {
        font-size: 22px;
        font-weight: 600;
        color: var(--color-text-primary);
        margin: 0 0 var(--spacing-xl) 0;
        text-align: center;
    }

    .auth-login-description {
        font-size: 13px;
        color: var(--color-text-secondary);
        margin: 0 0 var(--spacing-xl) 0;
        text-align: center;
    }

    .auth-login-error-message {
        background-color: #fee2e2;
        border: 1px solid #fecaca;
        border-radius: var(--radius-md);
        padding: var(--spacing-lg);
        margin-bottom: var(--spacing-xl);
        font-size: 14px;
        color: #991b1b;
        text-align: center;
        font-weight: 500;
    }

    .auth-login-success-message {
        background-color: #dcfce7;
        border: 1px solid #86efac;
        border-radius: var(--radius-md);
        padding: var(--spacing-lg);
        margin-bottom: var(--spacing-xl);
        font-size: 14px;
        color: #166534;
        text-align: center;
        font-weight: 500;
    }

    .auth-login-form {
        display: flex;
        flex-direction: column;
        gap: var(--spacing-lg);
    }

    .auth-login-form-group {
        display: flex;
        flex-direction: column;
        gap: var(--spacing-sm);
    }

    .auth-login-label {
        font-size: 12px;
        font-weight: 600;
        color: var(--color-text-primary);
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .auth-login-input {
        padding: var(--spacing-md) var(--spacing-lg);
        border: 1px solid var(--color-border);
        border-radius: var(--radius-md);
        font-size: 14px;
        color: var(--color-text-primary);
        background-color: var(--color-bg-secondary);
        font-family: inherit;
        transition: all 0.2s ease;
    }

    .auth-login-input::placeholder {
        color: var(--color-text-secondary);
    }

    .auth-login-input:focus {
        outline: none;
        border-color: var(--color-primary);
        box-shadow: 0 0 0 2px rgba(26, 122, 153, 0.1);
    }

    .auth-login-input.error {
        border-color: var(--color-danger);
        background-color: #fef2f2;
    }

    .form-error-text {
        color: var(--color-danger);
        font-size: 12px;
        margin-top: var(--spacing-sm);
    }

    .captcha-section {
        background: #f9f9f9;
        border: 1px solid var(--color-border);
        border-radius: var(--radius-md);
        padding: var(--spacing-lg);
        margin: var(--spacing-lg) 0;
    }

    .captcha-title {
        font-size: 13px;
        font-weight: 600;
        color: var(--color-text-primary);
        margin-bottom: var(--spacing-md);
        text-transform: uppercase;
    }

    .captcha-description {
        font-size: 11px;
        color: var(--color-text-secondary);
        margin-bottom: var(--spacing-lg);
        line-height: 1.6;
    }

    .captcha-challenge {
        background: white;
        border: 1px solid var(--color-border);
        border-radius: var(--radius-md);
        padding: var(--spacing-md);
        font-family: monospace;
        font-size: 11px;
        word-break: break-all;
        color: #666;
        text-align: center;
        margin-bottom: var(--spacing-md);
        min-height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .captcha-info {
        font-size: 11px;
        color: var(--color-text-secondary);
        text-align: center;
        margin-top: var(--spacing-md);
    }

    .auth-login-button {
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
        width: 100%;
    }

    .auth-login-button:hover {
        background-color: var(--color-primary-light);
    }

    .auth-login-button:active {
        opacity: 0.9;
    }

    .auth-login-links {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: var(--spacing-md);
        margin-top: var(--spacing-xl);
        font-size: 12px;
        flex-wrap: wrap;
    }

    .auth-login-link {
        color: var(--color-primary);
        text-decoration: none;
        font-weight: 500;
    }

    .auth-login-link:hover {
        text-decoration: underline;
    }

    .auth-login-separator {
        color: var(--color-border);
    }

    @media (max-width: 640px) {
        .auth-login-card {
            padding: var(--spacing-xl);
        }

        .auth-login-title {
            font-size: 18px;
        }

        .auth-login-links {
            flex-direction: column;
            gap: var(--spacing-sm);
        }

        .auth-login-separator {
            display: none;
        }
    }
</style>

<div class="auth-login-container">
    <div class="auth-login-card">
        <h1 class="auth-login-title">LOGIN</h1>
        <p class="auth-login-description">Access your Erebus Marketplace Script account</p>

        @if ($errors->any())
            <div class="auth-login-error-message">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST" class="auth-login-form">
            @csrf

            <!-- Username Field -->
            <div class="auth-login-form-group">
                <label for="username" class="auth-login-label">Username</label>
                <input 
                    type="text" 
                    id="username" 
                    name="username" 
                    value="{{ old('username') }}"
                    class="auth-login-input @error('username') error @enderror"
                    placeholder="Enter your username (case-sensitive)"
                    required 
                    minlength="4" 
                    maxlength="16"
                    autocomplete="username"
                >
                @error('username')
                    <div class="form-error-text">{{ $message }}</div>
                @enderror
            </div>

            <!-- Password Field -->
            <div class="auth-login-form-group">
                <label for="password" class="auth-login-label">Password</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    class="auth-login-input @error('password') error @enderror"
                    placeholder="Enter your password (case-sensitive)"
                    required 
                    minlength="8" 
                    maxlength="40"
                    autocomplete="current-password"
                >
                @error('password')
                    <div class="form-error-text">{{ $message }}</div>
                @enderror
            </div>

            <!-- PoW Captcha Section -->
            <div class="captcha-section">
                <div class="captcha-title">PoWeRebus - Proof of Work Challenge</div>
                <p class="captcha-description">
                    This system uses a javascript-less computational challenge to prevent automated attacks.
                    The challenge will be solved using secure server-side logic.
                </p>

                <!-- Challenge Display -->
                @if (session()->has('pow_challenge'))
                    <div class="captcha-challenge">
                        {{ substr(session('pow_challenge'), 0, 16) }}...
                    </div>
                    <div class="captcha-info">
                        Challenge difficulty: {{ session('pow_difficulty', 4) }}
                        <br>
                        Click "Compute PoW & Login" to solve and proceed
                    </div>
                @else
                    <div class="captcha-challenge" style="color: #999;">
                        Challenge will be generated when you submit
                    </div>
                @endif
            </div>

            <!-- Submit Button -->
            <button type="submit" class="auth-login-button">
                Compute PoW & Login
            </button>
        </form>

        <!-- Links -->
        <div class="auth-login-links">
            <a href="{{ route('register') }}" class="auth-login-link">Create Account</a>
            <span class="auth-login-separator">|</span>
            <a href="{{ route('password.request') }}" class="auth-login-link">Forgot Password?</a>
        </div>
    </div>
</div>

@endsection
