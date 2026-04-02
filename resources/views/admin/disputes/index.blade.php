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

    .disputes-index-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: var(--spacing-xl);
    }

    .disputes-index-card {
        background-color: var(--color-bg-secondary);
        border: 1px solid var(--color-border);
        border-radius: var(--radius);
        padding: var(--spacing-lg);
    }

    .disputes-index-title {
        font-size: 32px;
        font-weight: 600;
        color: var(--color-text-primary);
        margin: 0 0 var(--spacing-2xl) 0;
        padding-bottom: var(--spacing-lg);
        border-bottom: 2px solid var(--color-accent-light);
    }

    .disputes-index-table-container {
        overflow-x: auto;
    }

    .disputes-index-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }

    .disputes-index-table thead {
        background-color: var(--color-bg-primary);
        border-bottom: 2px solid var(--color-border);
    }

    .disputes-index-table th {
        padding: var(--spacing-md);
        text-align: left;
        font-weight: 600;
        color: var(--color-text-primary);
    }

    .disputes-index-table td {
        padding: var(--spacing-md);
        border-bottom: 1px solid var(--color-border);
        color: var(--color-text-primary);
    }

    .disputes-index-table tbody tr:hover {
        background-color: var(--color-bg-primary);
    }

    .disputes-index-status {
        display: inline-block;
        padding: var(--spacing-xs) var(--spacing-sm);
        border-radius: var(--radius);
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .disputes-index-status-active {
        background-color: #dcfce7;
        color: #166534;
        border: 1px solid #86efac;
    }

    .disputes-index-status-resolved {
        background-color: #e5e7eb;
        color: #374151;
        border: 1px solid #d1d5db;
    }

    .admin-dispute-index-resolver {
        font-size: 12px;
        color: var(--color-text-secondary);
        margin-top: var(--spacing-xs);
        font-style: italic;
    }

    .disputes-index-action-btn {
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

    .disputes-index-action-btn:hover {
        background-color: var(--color-accent-light);
    }

    .disputes-index-empty {
        background-color: var(--color-bg-primary);
        border: 1px solid var(--color-border);
        border-radius: var(--radius);
        padding: var(--spacing-lg);
        text-align: center;
        color: var(--color-text-secondary);
        font-size: 14px;
    }

    @media (max-width: 768px) {
        .disputes-index-container {
            padding: var(--spacing-md);
        }

        .disputes-index-title {
            font-size: 24px;
        }

        .disputes-index-table {
            font-size: 13px;
        }

        .disputes-index-table th,
        .disputes-index-table td {
            padding: var(--spacing-sm);
        }
    }
</style>

<div class="disputes-index-container">
    <div class="disputes-index-card">
        <h1 class="disputes-index-title">Dispute Management</h1>

        @if($disputes->count() > 0)
            <div class="disputes-index-table-container">
                <table class="disputes-index-table">
                    <thead>
                        <tr>
                            <th>Dispute ID</th>
                            <th>Date Opened</th>
                            <th>Order ID</th>
                            <th>Buyer</th>
                            <th>Vendor</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($disputes as $dispute)
                            <tr>
                                <td>{{ substr($dispute->id, 0, 8) }}</td>
                                <td>{{ $dispute->created_at->format('Y-m-d / H:i') }}</td>
                                <td>{{ substr($dispute->order->id, 0, 8) }}</td>
                                <td>{{ $dispute->order->user->username }}</td>
                                <td>{{ $dispute->order->vendor->username }}</td>
                                <td>
                                    <span class="disputes-index-status disputes-index-status-{{ $dispute->status }}">
                                        {{ $dispute->getFormattedStatus() }}
                                    </span>
                                    @if($dispute->status !== \App\Models\Dispute::STATUS_ACTIVE)
                                        <div class="admin-dispute-index-resolver">
                                            by {{ $dispute->resolver->username }}
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    @if($dispute->status === \App\Models\Dispute::STATUS_ACTIVE)
                                        <a href="{{ route('admin.disputes.show', $dispute->id) }}" class="disputes-index-action-btn">
                                            Manage Dispute
                                        </a>
                                    @else
                                        <a href="{{ route('admin.disputes.show', $dispute->id) }}" class="disputes-index-action-btn">
                                            View Details
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="disputes-index-empty">
                <p>There are no disputes at the moment.</p>
            </div>
        @endif
    </div>
</div>

@endsection
