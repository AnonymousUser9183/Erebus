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
        --color-success: #22c55e;
        --color-warning: #f59e61;
        --spacing-xs: 8px;
        --spacing-sm: 12px;
        --spacing-md: 16px;
        --spacing-lg: 20px;
        --spacing-xl: 24px;
        --spacing-2xl: 32px;
        --radius: 8px;
    }

    .dashboard-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: var(--spacing-xl);
    }

    .dashboard-grid {
        display: grid;
        grid-template-columns: 1fr 2fr;
        gap: var(--spacing-2xl);
        align-items: start;
    }

    .dashboard-card {
        background-color: var(--color-bg-secondary);
        border: 1px solid var(--color-border);
        border-radius: var(--radius);
        padding: var(--spacing-lg);
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    .dashboard-profile-card {
        text-align: center;
        display: flex;
        flex-direction: column;
    }

    .dashboard-profile-header {
        margin-bottom: var(--spacing-lg);
    }

    .dashboard-profile-image-container {
        margin-bottom: var(--spacing-md);
    }

    .dashboard-profile-image {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid var(--color-accent);
        display: block;
        margin: 0 auto;
    }

    .dashboard-profile-name {
        font-size: 20px;
        font-weight: 600;
        color: var(--color-text-primary);
        margin: var(--spacing-md) 0 var(--spacing-xs) 0;
        word-break: break-word;
    }

    .dashboard-profile-role {
        font-size: 14px;
        color: var(--color-text-secondary);
        margin: 0 0 var(--spacing-md) 0;
        text-transform: capitalize;
    }

    .dashboard-profile-last-login {
        font-size: 12px;
        color: var(--color-text-secondary);
        margin: 0;
        border-top: 1px solid var(--color-border);
        padding-top: var(--spacing-md);
    }

    .dashboard-card-title {
        font-size: 16px;
        font-weight: 600;
        color: var(--color-text-primary);
        margin: var(--spacing-lg) 0 var(--spacing-md) 0;
        border-bottom: 2px solid var(--color-accent-light);
        padding-bottom: var(--spacing-md);
    }

    .dashboard-pgp-status {
        display: flex;
        justify-content: center;
        gap: var(--spacing-md);
        padding: var(--spacing-md) 0;
    }

    .dashboard-pgp-badge {
        display: inline-block;
        padding: var(--spacing-xs) var(--spacing-sm);
        border-radius: var(--radius);
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .dashboard-pgp-verified {
        background-color: rgba(34, 197, 94, 0.1);
        color: var(--color-success);
        border: 1px solid rgba(34, 197, 94, 0.3);
    }

    .dashboard-pgp-unverified {
        background-color: rgba(245, 158, 11, 0.1);
        color: var(--color-warning);
        border: 1px solid rgba(245, 158, 11, 0.3);
    }

    .dashboard-pgp-none {
        background-color: rgba(98, 108, 113, 0.1);
        color: var(--color-text-secondary);
        border: 1px solid rgba(98, 108, 113, 0.3);
    }

    .dashboard-description {
        font-size: 14px;
        line-height: 1.6;
        color: var(--color-text-primary);
        background-color: #f9f9f7;
        padding: var(--spacing-md);
        border-radius: var(--radius);
        border: 1px solid var(--color-border);
    }

    .dashboard-description p {
        margin: 0;
    }

    .dashboard-pgp-key-container {
        background-color: #f9f9f7;
        border: 1px solid var(--color-border);
        border-radius: var(--radius);
        overflow: hidden;
    }

    .dashboard-pgp-key {
        padding: var(--spacing-md);
    }

    .dashboard-pgp-key pre {
        margin: 0;
        font-size: 11px;
        font-family: 'Courier New', monospace;
        color: var(--color-text-primary);
        overflow-x: auto;
        white-space: pre-wrap;
        word-wrap: break-word;
        background: transparent;
        border: none;
    }

    .dashboard-pgp-empty {
        padding: var(--spacing-lg);
        text-align: center;
        color: var(--color-text-secondary);
    }

    .dashboard-pgp-empty p {
        margin: 0;
        font-size: 14px;
    }

    @media (max-width: 768px) {
        .dashboard-grid {
            grid-template-columns: 1fr;
        }

        .dashboard-profile-image {
            width: 100px;
            height: 100px;
        }

        .dashboard-container {
            padding: var(--spacing-md);
        }
    }
</style>

<div class="dashboard-container">
    <div class="dashboard-grid">
        <!-- Profile Information Card -->
        <div class="dashboard-card dashboard-profile-card">
            <div class="dashboard-profile-header">
                <div class="dashboard-profile-image-container">
                    <img class="dashboard-profile-image" src="{{ $profile ? $profile->profile_picture_url : asset('images/default-profile-picture.png') }}" alt="Profile Picture">
                </div>
                <h2 class="dashboard-profile-name">{{ e($user->username) }}</h2>
                <p class="dashboard-profile-role">{{ $userRole }}</p>
                @if($showFullInfo)
                    <p class="dashboard-profile-last-login">Last Login: {{ $user->last_login ? $user->last_login->format('d-m-Y') : 'Never' }}</p>
                @endif
            </div>
            
            <h3 class="dashboard-card-title">PGP Key Status</h3>
            <div class="dashboard-pgp-status">
                @if($pgpKey)
                    @if($pgpKey->verified)
                        <span class="dashboard-pgp-badge dashboard-pgp-verified">Verified</span>
                    @else
                        <span class="dashboard-pgp-badge dashboard-pgp-unverified">Unverified</span>
                    @endif
                @else
                    <span class="dashboard-pgp-badge dashboard-pgp-none">No Key</span>
                @endif
            </div>
        </div>

        <!-- Profile Description and PGP Key Card -->
        <div>
            <!-- Profile Description -->
            <div class="dashboard-card">
                <h3 class="dashboard-card-title">Profile Description</h3>
                <div class="dashboard-description">
                    {!! $description !!}
                </div>
            </div>

            <!-- Current PGP Key -->
            <div class="dashboard-card" style="margin-top: var(--spacing-xl);">
                <h3 class="dashboard-card-title">Current PGP Key</h3>
                <div class="dashboard-pgp-key-container">
                    <div class="dashboard-pgp-key">
                        @if($pgpKey)
                            <pre>{{ $pgpKey->public_key }}</pre>
                        @else
                            <div class="dashboard-pgp-empty">
                                <p>No PGP key added yet.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
