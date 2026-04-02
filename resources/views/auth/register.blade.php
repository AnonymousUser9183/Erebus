@extends('layouts.auth')

@section('title', 'Create Account - Erebus Marketplace Script')
@section('breadcrumb', 'Register')

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

    .auth-register-container {
        width: 100%;
        max-width: 550px;
    }

    .auth-register-card {
        background-color: var(--color-bg-secondary);
        border: 1px solid var(--color-border);
        border-radius: var(--radius-lg);
        padding: var(--spacing-2xl);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    }

    .auth-register-title {
        font-size: 22px;
        font-weight: 600;
        color: var(--color-text-primary);
        margin: 0 0 var(--spacing-xl) 0;
        text-align: center;
    }

    .auth-register-description {
        font-size: 13px;
        color: var(--color-text-secondary);
        margin: 0 0 var(--spacing-xl) 0;
        text-align: center;
    }

    .auth-register-error-message {
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

    .auth-register-form {
        display: flex;
        flex-direction: column;
        gap: var(--spacing-lg);
    }

    .auth-register-form-group {
        display: flex;
        flex-direction: column;
        gap: var(--spacing-sm);
    }

    .auth-register-label {
        font-size: 12px;
        font-weight: 600;
        color: var(--color-text-primary);
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .auth-register-input {
        padding: var(--spacing-md) var(--spacing-lg);
        border: 1px solid var(--color-border);
        border-radius: var(--radius-md);
        font-size: 14px;
        color: var(--color-text-primary);
        background-color: var(--color-bg-secondary);
        font-family: inherit;
        transition: all 0.2s ease;
    }

    .auth-register-input::placeholder {
        color: var(--color-text-secondary);
    }

    .auth-register-input:focus {
        outline: none;
        border-color: var(--color-primary);
        box-shadow: 0 0 0 2px rgba(26, 122, 153, 0.1);
    }

    .auth-register-input.error {
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

    .auth-register-button {
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

    .auth-register-button:hover {
        background-color: var(--color-primary-light);
    }

    .auth-register-button:active {
        opacity: 0.9;
    }

    .auth-register-links {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: var(--spacing-md);
        margin-top: var(--spacing-xl);
        font-size: 12px;
        flex-wrap: wrap;
    }

    .auth-register-link {
        color: var(--color-primary);
        text-decoration: none;
        font-weight: 500;
    }

    .auth-register-link:hover {
        text-decoration: underline;
    }

    .auth-register-separator {
        color: var(--color-border);
    }

    .password-requirements {
        background: #f0f9ff;
        border: 1px solid #bfdbfe;
        border-radius: var(--radius-md);
        padding: var(--spacing-md);
        font-size: 11px;
        color: #1e40af;
        margin-bottom: var(--spacing-md);
        line-height: 1.6;
    }

    .password-requirements li {
        margin-bottom: 4px;
    }

    @media (max-width: 640px) {
        .auth-register-card {
            padding: var(--spacing-xl);
        }

        .auth-register-title {
            font-size: 18px;
        }

        .auth-register-links {
            flex-direction: column;
            gap: var(--spacing-sm);
        }

        .auth-register-separator {
            display: none;
        }
    }
</style>

<div class="auth-register-container">
    <div class="auth-register-card">
        <h1 class="auth-register-title">CREATE ACCOUNT</h1>
        <p class="auth-register-description">Join Erebus Marketplace Script securely</p>

        @if ($errors->any())
            <div class="auth-register-error-message">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('register') }}" method="POST" class="auth-register-form">
            @csrf

            <!-- Username Field -->
            <div class="auth-register-form-group">
                <label for="username" class="auth-register-label">Username</label>
                <input 
                    type="text" 
                    id="username" 
                    name="username" 
                    value="{{ old('username') }}"
                    class="auth-register-input @error('username') error @enderror"
                    placeholder="Choose a username (4-16 chars, case-sensitive, alphanumeric)"
                    required 
                    minlength="4" 
                    maxlength="16"
                    pattern="[a-zA-Z0-9]+"
                    autocomplete="username"
                >
                @error('username')
                    <div class="form-error-text">{{ $message }}</div>
                @enderror
            </div>

            <!-- Password Field -->
            <div class="auth-register-form-group">
                <label for="password" class="auth-register-label">Password</label>
                <div class="password-requirements">
                    <strong>Password must contain:</strong>
                    <ul style="margin: 4px 0 0 20px; padding: 0;">
                        <li>✓ Lowercase letters (a-z)</li>
                        <li>✓ Uppercase letters (A-Z)</li>
                        <li>✓ Numbers (0-9)</li>
                        <li>✓ Special character (#$%&@^`~.,:;"'/|_-<>*+!?={}[])</li>
                        <li>✓ 8-40 characters total</li>
                    </ul>
                </div>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    class="auth-register-input @error('password') error @enderror"
                    placeholder="Create a strong password (case-sensitive)"
                    required 
                    minlength="8" 
                    maxlength="40"
                    autocomplete="new-password"
                >
                @error('password')
                    <div class="form-error-text">{{ $message }}</div>
                @enderror
            </div>

            <!-- Password Confirmation Field -->
            <div class="auth-register-form-group">
                <label for="password_confirmation" class="auth-register-label">Confirm Password</label>
                <input 
                    type="password" 
                    id="password_confirmation" 
                    name="password_confirmation" 
                    class="auth-register-input @error('password_confirmation') error @enderror"
                    placeholder="Re-enter your password"
                    required 
                    minlength="8" 
                    maxlength="40"
                    autocomplete="new-password"
                >
                @error('password_confirmation')
                    <div class="form-error-text">{{ $message }}</div>
                @enderror
            </div>

            <!-- Reference Code Field (Optional) -->
            <div class="auth-register-form-group">
                <label for="reference_code" class="auth-register-label">Reference Code (Optional)</label>
                <input 
                    type="text" 
                    id="reference_code" 
                    name="reference_code" 
                    value="{{ old('reference_code') }}"
                    class="auth-register-input @error('reference_code') error @enderror"
                    placeholder="Leave blank if you have no referral code"
                    maxlength="16"
                >
                @error('reference_code')
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
                        Click "Compute PoW & Register" to solve and proceed
                    </div>
                @else
                    <div class="captcha-challenge" style="color: #999;">
                        Challenge will be generated when you submit
                    </div>
                @endif
            </div>

            <!-- Register Button -->
            <button type="submit" class="auth-register-button">
                Compute PoW & Register
            </button>
        </form>

        <!-- Links -->
        <div class="auth-register-links">
            <a href="{{ route('login') }}" class="auth-register-link">Already have account?</a>
            <span class="auth-register-separator">|</span>
            <a href="{{ route('password.request') }}" class="auth-register-link">Forgot Password?</a>
        </div>
    </div>
</div>

@endsection
