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

    .vendor-apps-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: var(--spacing-xl);
    }

    .vendor-apps-header {
        margin-bottom: var(--spacing-2xl);
        padding-bottom: var(--spacing-lg);
        border-bottom: 2px solid var(--color-accent-light);
    }

    .vendor-apps-title {
        font-size: 32px;
        font-weight: 600;
        color: var(--color-text-primary);
        margin: 0;
    }

    .vendor-apps-card {
        background-color: var(--color-bg-secondary);
        border: 1px solid var(--color-border);
        border-radius: var(--radius);
        padding: var(--spacing-lg);
        margin-bottom: var(--spacing-xl);
    }

    .vendor-apps-table-container {
        overflow-x: auto;
    }

    .vendor-apps-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }

    .vendor-apps-table thead {
        background-color: var(--color-bg-primary);
        border-bottom: 2px solid var(--color-border);
    }

    .vendor-apps-table th {
        padding: var(--spacing-md);
        text-align: left;
        font-weight: 600;
        color: var(--color-text-primary);
    }

    .vendor-apps-table td {
        padding: var(--spacing-md);
        border-bottom: 1px solid var(--color-border);
        color: var(--color-text-primary);
    }

    .vendor-apps-table tbody tr:hover {
        background-color: var(--color-bg-primary);
    }

    .vendor-apps-status {
        display: inline-block;
        padding: var(--spacing-xs) var(--spacing-sm);
        border-radius: var(--radius);
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .vendor-apps-status-waiting {
        background-color: #fef3c7;
        color: #854d0e;
        border: 1px solid #fbbf24;
    }

    .vendor-apps-status-accepted {
        background-color: #dcfce7;
        color: #166534;
        border: 1px solid #86efac;
    }

    .vendor-apps-status-denied {
        background-color: #fee2e2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }

    .vendor-apps-action-btn {
        display: inline-block;
        background-color: var(--color-accent);
        color: #ffffff;
        padding: var(--spacing-xs) var(--spacing-sm);
        border: none;
        border-radius: var(--radius);
        font-size: 12px;
        font-weight: 500;
        cursor: pointer;
        text-decoration: none;
        transition: background-color 0.3s ease;
    }

    .vendor-apps-action-btn:hover {
        background-color: var(--color-accent-light);
    }

    .vendor-apps-empty {
        background-color: var(--color-bg-primary);
        border: 1px solid var(--color-border);
        border-radius: var(--radius);
        padding: var(--spacing-lg);
        text-align: center;
        color: var(--color-text-secondary);
        font-size: 14px;
    }

    .vendor-apps-pagination {
        margin-top: var(--spacing-xl);
        display: flex;
        justify-content: center;
    }

    @media (max-width: 768px) {
        .vendor-apps-container {
            padding: var(--spacing-md);
        }

        .vendor-apps-title {
            font-size: 24px;
        }

        .vendor-apps-table {
            font-size: 13px;
        }

        .vendor-apps-table th,
        .vendor-apps-table td {
            padding: var(--spacing-sm);
        }
    }
</style>

<div class="vendor-apps-container">
    <div class="vendor-apps-header">
        <h1 class="vendor-apps-title">Vendor Applications</h1>
    </div>

    <div class="vendor-apps-card">
        @if($applications->count() > 0)
            <div class="vendor-apps-table-container">
                <table class="vendor-apps-table">
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Status</th>
                            <th>Submitted</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($applications as $application)
                            <tr>
                                <td>{{ $application->user->username }}</td>
                                <td>
                                    <span class="vendor-apps-status vendor-apps-status-{{ $application->application_status }}">
                                        {{ ucfirst($application->application_status) }}
                                    </span>
                                </td>
                                <td>{{ $application->application_submitted_at->format('Y-m-d / H:i') }}</td>
                                <td>
                                    <a href="{{ route('admin.vendor-applications.show', $application) }}" class="vendor-apps-action-btn">
                                        Review
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($applications->hasPages())
                <div class="vendor-apps-pagination">
                    {{ $applications->links() }}
                </div>
            @endif
        @else
            <div class="vendor-apps-empty">
                <p>No vendor applications found.</p>
            </div>
        @endif
    </div>
</div>

@endsection
