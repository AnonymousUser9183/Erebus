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
    --spacing-xs: 8px;
    --spacing-sm: 12px;
    --spacing-md: 16px;
    --spacing-lg: 20px;
    --spacing-xl: 24px;
    --spacing-2xl: 32px;
    --radius: 8px;
}

.admin-container {
    max-width: 900px;
    margin: 0 auto;
    padding: var(--spacing-xl);
}

.admin-card {
    background-color: var(--color-bg-secondary);
    border: 1px solid var(--color-border);
    border-radius: var(--radius);
    padding: var(--spacing-2xl);
    margin-bottom: var(--spacing-xl);
}

.admin-title {
    font-size: 24px;
    font-weight: 600;
    color: var(--color-text-primary);
    margin: 0 0 var(--spacing-xl) 0;
    padding-bottom: var(--spacing-lg);
    border-bottom: 2px solid var(--color-accent-light);
}

.info-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: var(--spacing-xl);
    margin-bottom: var(--spacing-xl);
}

.info-item {
    display: flex;
    flex-direction: column;
}

.info-label {
    font-size: 12px;
    font-weight: 600;
    color: var(--color-text-secondary);
    text-transform: uppercase;
    margin-bottom: var(--spacing-xs);
}

.info-value {
    font-size: 16px;
    color: var(--color-text-primary);
    font-weight: 500;
}

.status-badge {
    display: inline-block;
    padding: var(--spacing-xs) var(--spacing-sm);
    border-radius: var(--radius);
    font-size: 12px;
    font-weight: 600;
    width: fit-content;
}

.status-pending {
    background-color: #fff3e0;
    color: #e65100;
}

.status-assigned {
    background-color: #e8f5e9;
    color: #2e7d32;
}

.status-denied {
    background-color: #ffebee;
    color: #c62828;
}

.form-group {
    margin-bottom: var(--spacing-xl);
}

.form-label {
    display: block;
    font-size: 12px;
    font-weight: 600;
    color: var(--color-text-secondary);
    text-transform: uppercase;
    margin-bottom: var(--spacing-sm);
}

.form-input {
    width: 100%;
    padding: var(--spacing-md);
    border: 1px solid var(--color-border);
    border-radius: var(--radius);
    font-size: 14px;
    font-family: inherit;
    color: var(--color-text-primary);
    box-sizing: border-box;
}

.form-input:focus {
    outline: none;
    border-color: var(--color-accent);
    box-shadow: 0 0 0 3px rgba(32, 128, 136, 0.1);
}

.form-input:disabled {
    background-color: var(--color-bg-primary);
    cursor: not-allowed;
    color: var(--color-text-secondary);
}

.action-buttons {
    display: flex;
    gap: var(--spacing-md);
    margin-top: var(--spacing-xl);
}

.button {
    padding: var(--spacing-sm) var(--spacing-lg);
    border-radius: var(--radius);
    font-size: 14px;
    font-weight: 600;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-block;
}

.button-primary {
    background-color: var(--color-accent);
    color: #ffffff;
}

.button-primary:hover {
    background-color: var(--color-accent-light);
}

.button-danger {
    background-color: #c62828;
    color: #ffffff;
}

.button-danger:hover {
    background-color: #b71c1c;
}

.button-secondary {
    background-color: var(--color-bg-primary);
    color: var(--color-text-primary);
    border: 1px solid var(--color-border);
}

.button-secondary:hover {
    background-color: #f0f0f0;
}

.back-link {
    display: inline-block;
    margin-bottom: var(--spacing-lg);
    color: var(--color-accent);
    text-decoration: none;
    font-size: 14px;
    font-weight: 500;
}

.back-link:hover {
    color: var(--color-accent-light);
}

.alert {
    padding: var(--spacing-lg);
    border-radius: var(--radius);
    margin-bottom: var(--spacing-xl);
    font-size: 14px;
}

.alert-info {
    background-color: #e3f2fd;
    color: #0d47a1;
    border-left: 4px solid #2196f3;
}

.alert-danger {
    background-color: #ffebee;
    color: #b71c1c;
    border-left: 4px solid #f44336;
}

.alert-success {
    background-color: #e8f5e9;
    color: #1b5e20;
    border-left: 4px solid #4caf50;
}

@media (max-width: 768px) {
    .info-grid {
        grid-template-columns: 1fr;
    }
    
    .action-buttons {
        flex-direction: column;
    }
    
    .button {
        width: 100%;
        text-align: center;
    }
}
</style>

<div class="admin-container">
<a href="{{ route('admin.private-mirror-requests.list') }}" class="back-link">← Back to Requests</a>

@if ($errors->any())
@foreach ($errors->all() as $error)
<div class="alert alert-danger">
{{ $error }}
</div>
@endforeach
@endif

<div class="admin-card">
<h1 class="admin-title">Mirror Request Details</h1>

<div class="info-grid">
<div class="info-item">
<span class="info-label">Username</span>
<span class="info-value">
@if ($mirrorRequest->user)
{{ $mirrorRequest->user->username }}
@else
<span style="color: #999;">User Not Found</span>
@endif
</span>
</div>

<div class="info-item">
<span class="info-label">Email</span>
<span class="info-value">
@if ($mirrorRequest->user)
{{ $mirrorRequest->user->email }}
@else
<span style="color: #999;">N/A</span>
@endif
</span>
</div>

<div class="info-item">
<span class="info-label">Status</span>
<span class="status-badge status-{{ strtolower($mirrorRequest->status) }}">
{{ ucfirst($mirrorRequest->status) }}
</span>
</div>

<div class="info-item">
<span class="info-label">Requested Date</span>
<span class="info-value">
@if ($mirrorRequest->created_at)
{{ $mirrorRequest->created_at->format('M d, Y H:i:s') }}
@else
<span style="color: #999;">N/A</span>
@endif
</span>
</div>

@if ($mirrorRequest->assigned_mirror)
<div class="info-item">
<span class="info-label">Assigned Mirror URL</span>
<span class="info-value">
<a href="{{ $mirrorRequest->assigned_mirror }}" target="_blank" rel="noopener noreferrer">
{{ $mirrorRequest->assigned_mirror }}
</a>
</span>
</div>

<div class="info-item">
<span class="info-label">Assigned Date</span>
<span class="info-value">
@if ($mirrorRequest->assigned_at)
{{ $mirrorRequest->assigned_at->format('M d, Y H:i:s') }}
@else
<span style="color: #999;">N/A</span>
@endif
</span>
</div>
@endif
</div>

@if ($mirrorRequest->status !== 'assigned' && $mirrorRequest->status !== 'denied')
<div style="border-top: 1px solid var(--color-border); padding-top: var(--spacing-xl); margin-top: var(--spacing-xl);">
<h3 style="font-size: 16px; font-weight: 600; margin: 0 0 var(--spacing-lg) 0;">Assign Mirror</h3>

<form method="POST" action="/admin/private-mirror-requests/{{ $mirrorRequest->id ?? $mirrorRequest }}/assign" novalidate>
@csrf

<div class="form-group">
<label class="form-label" for="mirror_url">Mirror URL *</label>
<input
type="url"
id="mirror_url"
name="mirror_url"
class="form-input"
placeholder="https://example.com/mirror"
required
value="{{ old('mirror_url') }}"
>
@error('mirror_url')
<span style="color: #c62828; font-size: 12px; margin-top: var(--spacing-xs); display: block;">
{{ $message }}
</span>
@enderror
</div>

<div class="action-buttons">
<button type="submit" class="button button-primary">Assign Mirror</button>
</div>
</form>

<form method="POST" action="/admin/private-mirror-requests/{{ $mirrorRequest->id ?? $mirrorRequest }}/deny" style="display: inline; margin-top: var(--spacing-md);">
@csrf
<button type="submit" class="button button-danger" onclick="return confirm('Are you sure you want to deny this request?');">Deny Request</button>
</form>
</div>
@elseif ($mirrorRequest->status === 'denied')
<div class="alert alert-danger">
This request has been denied.
</div>
@elseif ($mirrorRequest->status === 'assigned')
<div class="alert alert-success">
This request has been assigned a mirror.
</div>
@endif
</div>
</div>

@endsection
