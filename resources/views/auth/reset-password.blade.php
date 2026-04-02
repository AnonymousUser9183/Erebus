@extends('layouts.auth')

@section('title', 'Reset Password - Erebus Marketplace Script')
@section('breadcrumb', 'Reset Password')

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
--color-error: #dc2626;
--spacing-sm: 8px;
--spacing-md: 12px;
--spacing-lg: 16px;
--spacing-xl: 24px;
--radius-md: 6px;
--radius-lg: 8px;
}

.auth-reset-container {
width: 100%;
max-width: 500px;
}

.auth-reset-card {
background-color: var(--color-bg-secondary);
border: 1px solid var(--color-border);
border-radius: var(--radius-lg);
padding: var(--spacing-2xl);
box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
}

.auth-reset-title {
font-size: 22px;
font-weight: 600;
color: var(--color-text-primary);
margin: 0 0 var(--spacing-xl) 0;
text-align: center;
}

.auth-reset-form {
display: flex;
flex-direction: column;
gap: var(--spacing-lg);
}

.auth-reset-form-group {
display: flex;
flex-direction: column;
}

.auth-reset-label {
font-size: 13px;
font-weight: 600;
color: var(--color-text-primary);
margin-bottom: var(--spacing-sm);
text-transform: uppercase;
letter-spacing: 0.5px;
}

.auth-reset-input {
padding: var(--spacing-md) var(--spacing-lg);
font-size: 14px;
border: 1px solid var(--color-border);
border-radius: var(--radius-md);
background-color: var(--color-bg-primary);
color: var(--color-text-primary);
transition: border-color 0.2s ease;
font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
}

.auth-reset-input:focus {
outline: none;
border-color: var(--color-primary);
background-color: var(--color-bg-secondary);
}

.auth-reset-button {
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

.auth-reset-button:hover {
background-color: var(--color-primary-light);
}

.auth-reset-button:active {
transform: scale(0.98);
}

.auth-reset-links {
display: flex;
justify-content: center;
gap: var(--spacing-lg);
margin-top: var(--spacing-xl);
font-size: 13px;
}

.auth-reset-link {
color: var(--color-primary);
text-decoration: none;
transition: color 0.2s ease;
}

.auth-reset-link:hover {
color: var(--color-primary-light);
text-decoration: underline;
}

.auth-reset-error {
background-color: #fee2e2;
border: 1px solid #fecaca;
border-left: 4px solid var(--color-error);
border-radius: var(--radius-md);
padding: var(--spacing-lg);
margin-bottom: var(--spacing-lg);
font-size: 13px;
color: #7f1d1d;
line-height: 1.6;
}

.auth-reset-success {
background-color: #dcfce7;
border: 1px solid #bbf7d0;
border-left: 4px solid #16a34a;
border-radius: var(--radius-md);
padding: var(--spacing-lg);
margin-bottom: var(--spacing-lg);
font-size: 13px;
color: #15803d;
line-height: 1.6;
}

@media (max-width: 640px) {
.auth-reset-card {
padding: var(--spacing-xl);
}

.auth-reset-title {
font-size: 18px;
}
}
</style>

<div class="auth-reset-container">
<div class="auth-reset-card">
<h1 class="auth-reset-title">Reset Your Password</h1>

@if ($errors->any())
<div class="auth-reset-error">
<strong>Error:</strong>
@foreach ($errors->all() as $error)
<div>{{ $error }}</div>
@endforeach
</div>
@endif

@if (session('success'))
<div class="auth-reset-success">
{{ session('success') }}
</div>
@endif

<form method="POST" action="{{ route('password.update') }}" class="auth-reset-form">
@csrf

<!-- Hidden token field -->
<input type="hidden" name="token" value="{{ $token }}">

<!-- Username field (REQUIRED) -->
<div class="auth-reset-form-group">
<label for="username" class="auth-reset-label">Username:</label>
<input
type="text"
id="username"
name="username"
class="auth-reset-input @error('username') is-invalid @enderror"
placeholder="Enter your username"
value="{{ old('username') }}"
minlength="3"
maxlength="20"
required
autofocus
>
@error('username')
<small style="color: var(--color-error); margin-top: 4px;">{{ $message }}</small>
@enderror
</div>

<!-- Password field -->
<div class="auth-reset-form-group">
<label for="password" class="auth-reset-label">New Password:</label>
<input
type="password"
id="password"
name="password"
class="auth-reset-input @error('password') is-invalid @enderror"
placeholder="Enter your new password"
minlength="8"
maxlength="40"
required
>
@error('password')
<small style="color: var(--color-error); margin-top: 4px;">{{ $message }}</small>
@enderror
</div>

<!-- Password confirmation field -->
<div class="auth-reset-form-group">
<label for="password_confirmation" class="auth-reset-label">Confirm New Password:</label>
<input
type="password"
id="password_confirmation"
name="password_confirmation"
class="auth-reset-input @error('password_confirmation') is-invalid @enderror"
placeholder="Confirm your new password"
minlength="8"
maxlength="40"
required
>
@error('password_confirmation')
<small style="color: var(--color-error); margin-top: 4px;">{{ $message }}</small>
@enderror
</div>

<button type="submit" class="auth-reset-button">Reset Password</button>
</form>

<div class="auth-reset-links">
<a href="{{ route('login') }}" class="auth-reset-link">Back to Login</a>
<a href="{{ route('password.request') }}" class="auth-reset-link">Forgot Password?</a>
</div>
</div>
</div>

@endsection
