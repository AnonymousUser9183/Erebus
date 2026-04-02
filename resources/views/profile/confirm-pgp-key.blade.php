@extends('layouts.app')

@section('content')

<style>
/* CSS Variables */
:root {
--color-accent: #208088;
--color-text-primary: #134252;
--color-text-secondary: #62746e;
--color-card-bg: #ffffff;
--color-border: #d4d8d6;
--color-input-bg: #f5f7f6;
--color-success: #22c55e;
--color-warning: #f59e61;
--radius-base: 8px;
--radius-lg: 12px;
--spacing-md: 12px;
--spacing-lg: 16px;
--spacing-xl: 24px;
}

/* Reset inherited styles */
.pgp-container * {
box-sizing: border-box;
}

.pgp-container {
max-width: 600px;
margin: 0 auto;
padding: var(--spacing-xl);
}

.pgp-card {
background: var(--color-card-bg);
border-radius: var(--radius-lg);
border: 1px solid var(--color-border);
padding: var(--spacing-xl);
box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
}

.pgp-title {
font-size: 28px;
margin: 0 0 var(--spacing-xl) 0;
color: var(--color-text-primary);
font-weight: 600;
letter-spacing: -0.3px;
}

.pgp-expiry-notice {
background: linear-gradient(135deg, rgba(245, 158, 11, 0.08) 0%, rgba(245, 158, 11, 0.04) 100%);
border-left: 4px solid var(--color-warning);
border-radius: var(--radius-base);
padding: var(--spacing-lg);
margin: 0 0 var(--spacing-lg) 0;
font-size: 13px;
color: #92400e;
line-height: 1.6;
}

.pgp-expiry-notice strong {
font-weight: 600;
}

.pgp-card-title {
font-size: 16px;
font-weight: 600;
color: var(--color-text-primary);
text-align: center;
margin: 0 0 var(--spacing-lg) 0;
}

.pgp-encrypted-message {
background: var(--color-input-bg);
padding: var(--spacing-lg);
border-radius: var(--radius-base);
border: 1px solid var(--color-border);
font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
font-size: 12px;
word-wrap: break-word;
white-space: pre-wrap;
max-height: 300px;
overflow-y: auto;
margin: 0 0 var(--spacing-lg) 0;
color: var(--color-text-primary);
line-height: 1.5;
}

.pgp-instruction {
color: var(--color-text-secondary);
margin: 0 0 var(--spacing-lg) 0;
line-height: 1.6;
font-size: 14px;
}

.pgp-form-group {
margin-bottom: var(--spacing-lg);
}

.pgp-label {
display: block;
text-align: center;
margin: 0 0 var(--spacing-md) 0;
color: var(--color-text-primary);
font-weight: 500;
font-size: 14px;
}

.pgp-textarea {
width: 100%;
padding: var(--spacing-md);
border: 1px solid var(--color-border);
border-radius: var(--radius-base);
font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
font-size: 14px;
color: var(--color-text-primary);
background: var(--color-input-bg);
resize: vertical;
min-height: 100px;
transition: all 0.2s ease;
}

.pgp-textarea::placeholder {
color: var(--color-text-secondary);
}

.pgp-textarea:focus {
outline: none;
border-color: var(--color-accent);
background: var(--color-card-bg);
box-shadow: 0 0 0 3px rgba(32, 128, 136, 0.1);
}

.pgp-textarea:disabled {
background: var(--color-input-bg);
opacity: 0.6;
cursor: not-allowed;
}

.pgp-submit-wrapper {
text-align: center;
margin-top: var(--spacing-xl);
}

.pgp-submit-btn {
background: var(--color-accent);
color: white;
padding: 12px 32px;
border: none;
border-radius: var(--radius-base);
font-weight: 600;
cursor: pointer;
font-size: 14px;
transition: all 0.2s ease;
text-transform: uppercase;
letter-spacing: 0.5px;
}

.pgp-submit-btn:hover {
background: #1a6f77;
box-shadow: 0 4px 12px rgba(32, 128, 136, 0.2);
}

.pgp-submit-btn:active {
transform: scale(0.98);
}

.pgp-submit-btn:disabled {
background: #cccccc;
cursor: not-allowed;
opacity: 0.6;
transform: none;
}

.pgp-back-link {
text-align: center;
margin-top: var(--spacing-lg);
}

.pgp-back-link a {
color: var(--color-accent);
text-decoration: none;
font-size: 13px;
font-weight: 500;
transition: color 0.2s ease;
}

.pgp-back-link a:hover {
color: #1a6f77;
text-decoration: underline;
}

/* Scrollbar styling for encrypted message */
.pgp-encrypted-message::-webkit-scrollbar {
width: 6px;
}

.pgp-encrypted-message::-webkit-scrollbar-track {
background: var(--color-input-bg);
border-radius: 3px;
}

.pgp-encrypted-message::-webkit-scrollbar-thumb {
background: var(--color-border);
border-radius: 3px;
}

.pgp-encrypted-message::-webkit-scrollbar-thumb:hover {
background: var(--color-accent);
}

/* Firefox scrollbar */
.pgp-encrypted-message {
scrollbar-color: var(--color-border) var(--color-input-bg);
scrollbar-width: thin;
}

/* Responsive design */
@media (max-width: 768px) {
.pgp-container {
padding: var(--spacing-lg);
}

.pgp-card {
padding: var(--spacing-lg);
}

.pgp-title {
font-size: 24px;
margin-bottom: var(--spacing-lg);
}

.pgp-encrypted-message {
font-size: 11px;
max-height: 250px;
}

.pgp-textarea {
min-height: 80px;
font-size: 13px;
}
}

@media (max-width: 480px) {
.pgp-container {
padding: var(--spacing-md);
}

.pgp-card {
padding: var(--spacing-lg);
}

.pgp-title {
font-size: 20px;
margin-bottom: var(--spacing-lg);
}

.pgp-card-title {
font-size: 14px;
}

.pgp-encrypted-message {
font-size: 10px;
padding: var(--spacing-md);
max-height: 200px;
}

.pgp-textarea {
min-height: 70px;
font-size: 12px;
padding: var(--spacing-md);
}

.pgp-submit-btn {
padding: 10px 20px;
font-size: 13px;
}
}
</style>

<div class="pgp-container">
<div class="pgp-card">
<h1 class="pgp-title">🔐 Verify PGP Public Key</h1>

<div class="pgp-expiry-notice">
<strong>⏱ Verification Expiry:</strong> This verification code will expire on {{ $expirationTime->format('M d, Y \a\t H:i') }}. Please complete verification within this time.
</div>

<h5 class="pgp-card-title">Encrypted Message</h5>
<pre class="pgp-encrypted-message">{{ $encryptedMessage }}</pre>

<p class="pgp-instruction">Please decrypt this message using your private key and enter the decrypted message below. The message format is: <code style="background: var(--color-input-bg); padding: 2px 6px; border-radius: 3px; font-family: monospace;">EREBUS-{10-digit number}-MARKETPLACE</code></p>

<form method="POST" action="{{ route('pgp.confirm.submit') }}">
@csrf
<div class="pgp-form-group">
<label for="decrypted_message" class="pgp-label">Decrypted Message</label>
<textarea
name="decrypted_message"
id="decrypted_message"
class="pgp-textarea"
required
minlength="16"
maxlength="40"
autocomplete="off"
spellcheck="false"
placeholder="Paste your decrypted message here"
></textarea>
</div>
<div class="pgp-submit-wrapper">
<button type="submit" class="pgp-submit-btn">✓ Verify PGP Key</button>
</div>
</form>

<div class="pgp-back-link">
<a href="{{ route('settings') }}">← Back to Settings</a>
</div>
</div>
</div>

@endsection
