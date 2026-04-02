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

    .admin-statistics-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: var(--spacing-xl);
    }

    .admin-statistics-header {
        margin-bottom: var(--spacing-2xl);
        padding-bottom: var(--spacing-lg);
        border-bottom: 2px solid var(--color-accent-light);
    }

    .admin-statistics-title {
        font-size: 32px;
        font-weight: 600;
        color: var(--color-text-primary);
        margin: 0;
    }

    .admin-statistics-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(380px, 1fr));
        gap: var(--spacing-xl);
    }

    .admin-statistics-card {
        background-color: var(--color-bg-secondary);
        border: 1px solid var(--color-border);
        border-radius: var(--radius);
        padding: var(--spacing-lg);
    }

    .admin-statistics-card h2 {
        font-size: 18px;
        font-weight: 600;
        color: var(--color-accent);
        margin: 0 0 var(--spacing-md) 0;
        padding-bottom: var(--spacing-md);
        border-bottom: 2px solid var(--color-accent-light);
    }

    .admin-statistics-card p {
        font-size: 14px;
        color: var(--color-text-primary);
        margin: var(--spacing-md) 0;
        line-height: 1.6;
    }

    .admin-statistics-card strong {
        color: var(--color-accent);
        font-weight: 600;
    }

    .admin-statistics-card ul {
        list-style: none;
        padding: 0;
        margin: var(--spacing-md) 0;
    }

    .admin-statistics-card li {
        font-size: 14px;
        color: var(--color-text-primary);
        padding: var(--spacing-sm) 0;
        line-height: 1.6;
    }

    .admin-statistics-card li strong {
        color: var(--color-accent);
        font-weight: 600;
    }

    .admin-statistics-card li:first-child {
        border-top: 1px solid var(--color-border);
        padding-top: var(--spacing-md);
    }

    @media (max-width: 768px) {
        .admin-statistics-container {
            padding: var(--spacing-md);
        }

        .admin-statistics-grid {
            grid-template-columns: 1fr;
        }

        .admin-statistics-title {
            font-size: 24px;
        }
    }
</style>

<div class="admin-statistics-container">
    <div class="admin-statistics-header">
        <h1 class="admin-statistics-title">Erebus Market Statistics</h1>
    </div>
    <div class="admin-statistics-grid">
        <!-- User Statistics Card -->
        <div class="admin-statistics-card">
            <h2>User Statistics</h2>
            <p><strong>Total Users:</strong> {{ number_format($totalUsers) }}</p>
            <ul>
                @foreach($usersByRole as $role)
                    <li>
                        <strong>{{ ucfirst($role->name) }}s:</strong> {{ number_format($role->users_count) }} 
                        @if($totalUsers > 0)
                            ({{ number_format(($role->users_count / $totalUsers) * 100, 1) }}%)
                        @endif
                    </li>
                @endforeach
            </ul>
            <p><strong>Currently Banned Users:</strong> {{ number_format($bannedUsersCount) }}
                @if($totalUsers > 0)
                    ({{ number_format(($bannedUsersCount / $totalUsers) * 100, 1) }}%)
                @endif
            </p>
        </div>
        <!-- Security Statistics Card -->
        <div class="admin-statistics-card">
            <h2>Security Statistics</h2>
            <ul>
                <li>
                    <strong>Total PGP Keys:</strong> {{ number_format($totalPgpKeys) }}
                    @if($totalUsers > 0)
                        ({{ number_format(($totalPgpKeys / $totalUsers) * 100, 1) }}% of users)
                    @endif
                </li>
                <li>
                    <strong>Verified PGP Keys:</strong> {{ number_format($verifiedPgpKeys) }}
                    @if($totalPgpKeys > 0)
                        ({{ number_format($pgpVerificationRate, 1) }}% verification rate)
                    @endif
                </li>
                <li>
                    <strong>Non-Verified PGP Keys:</strong> {{ number_format($totalPgpKeys - $verifiedPgpKeys) }}
                    @if($totalPgpKeys > 0)
                        ({{ number_format(100 - $pgpVerificationRate, 1) }}% of total)
                    @endif
                </li>
            </ul>
            <p><strong>2FA Enabled Users:</strong> {{ number_format($twoFaEnabled) }}
                @if($totalUsers > 0)
                    ({{ number_format($twoFaAdoptionRate, 1) }}% adoption rate)
                @endif
            </p>
        </div>
        <!-- Product Statistics Card -->
        <div class="admin-statistics-card">
            <h2>Product Statistics</h2>
            <p><strong>Total Products:</strong> {{ number_format($totalProducts) }}</p>
            <ul>
                <li>
                    <strong>Digital Products:</strong> {{ number_format($productsByType['digital']) }}
                    @if($totalProducts > 0)
                        ({{ number_format(($productsByType['digital'] / $totalProducts) * 100, 1) }}%)
                    @endif
                </li>
                <li>
                    <strong>Cargo Products:</strong> {{ number_format($productsByType['cargo']) }}
                    @if($totalProducts > 0)
                        ({{ number_format(($productsByType['cargo'] / $totalProducts) * 100, 1) }}%)
                    @endif
                </li>
                <li>
                    <strong>Dead Drop Products:</strong> {{ number_format($productsByType['deaddrop']) }}
                    @if($totalProducts > 0)
                        ({{ number_format(($productsByType['deaddrop'] / $totalProducts) * 100, 1) }}%)
                    @endif
                </li>
            </ul>
        </div>
    </div>
</div>
@endsection
