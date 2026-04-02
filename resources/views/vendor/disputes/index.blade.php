@extends('layouts.app')
@section('content')

<style>
    :root {
        --color-accent: #208088;
        --color-accent-light: #32b8c6;
        --color-text-primary: #134252;
        --color-text-secondary: #62746e;
        --color-card-bg: #ffffff;
        --color-border: #d4d8d6;
        --color-input-bg: #f5f7f6;
        --spacing-md: 12px;
        --spacing-lg: 16px;
        --spacing-xl: 24px;
        --radius-base: 8px;
        --radius-lg: 12px;
        --color-success: #4caf50;
        --color-warning: #ffc107;
        --color-error: #f44336;
        --color-info: #2196f3;
    }

    .disputes-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: var(--spacing-xl);
    }

    .disputes-card {
        background: var(--color-card-bg);
        border: 1px solid var(--color-border);
        border-radius: var(--radius-lg);
        padding: var(--spacing-xl);
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }

    .disputes-title {
        font-size: 28px;
        font-weight: 600;
        color: var(--color-text-primary);
        margin: 0 0 var(--spacing-xl) 0;
    }

    .disputes-empty {
        text-align: center;
        padding: var(--spacing-xl) 0;
        color: var(--color-text-secondary);
    }

    .disputes-empty a {
        display: inline-block;
        margin-top: var(--spacing-lg);
        background: var(--color-accent);
        color: white;
        padding: 10px 16px;
        border-radius: var(--radius-base);
        text-decoration: none;
        font-weight: 500;
    }

    .disputes-empty a:hover {
        background: var(--color-accent-light);
    }

    .disputes-table-container {
        overflow-x: auto;
    }

    .disputes-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }

    .disputes-table thead {
        background: var(--color-input-bg);
        border-bottom: 1px solid var(--color-border);
    }

    .disputes-table th {
        padding: var(--spacing-lg);
        text-align: left;
        font-weight: 600;
        color: var(--color-text-primary);
    }

    .disputes-table td {
        padding: var(--spacing-lg);
        border-bottom: 1px solid var(--color-border);
        color: var(--color-text-secondary);
    }

    .disputes-table tbody tr:hover {
        background: rgba(32, 128, 136, 0.02);
    }

    .disputes-status {
        display: inline-block;
        padding: 4px 12px;
        border-radius: var(--radius-base);
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .disputes-status-open {
        background: rgba(33, 150, 243, 0.1);
        color: #1976d2;
    }

    .disputes-status-in_progress {
        background: rgba(255, 193, 7, 0.1);
        color: #f57f17;
    }

    .disputes-status-closed {
        background: rgba(76, 175, 80, 0.1);
        color: #388e3c;
    }

    .disputes-action-btn {
        display: inline-block;
        background: var(--color-accent);
        color: white;
        padding: 6px 12px;
        border-radius: var(--radius-base);
        text-decoration: none;
        font-size: 12px;
        font-weight: 500;
        margin-right: var(--spacing-md);
        transition: background 0.2s ease;
    }

    .disputes-action-btn:hover {
        background: var(--color-accent-light);
    }

    .disputes-resolution {
        font-size: 12px;
        color: var(--color-text-secondary);
        margin-top: 4px;
    }

    @media (max-width: 768px) {
        .disputes-container {
            padding: var(--spacing-lg);
        }

        .disputes-title {
            font-size: 24px;
        }

        .disputes-table {
            font-size: 12px;
        }

        .disputes-table th, .disputes-table td {
            padding: var(--spacing-md);
        }
    }
</style>

<div class="disputes-container">
    <div class="disputes-card">
        <h1 class="disputes-title">My Disputes</h1>
        
        @if($disputes->isEmpty())
            <div class="disputes-empty">
                <p>You don't have any disputes at the moment.</p>
                <a href="{{ route('vendor.sales') }}">Return to Sales</a>
            </div>
        @else
            <div class="disputes-table-container">
                <table class="disputes-table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Date</th>
                            <th>Buyer</th>
                            <th>Reason</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($disputes as $dispute)
                            <tr>
                                <td>{{ substr($dispute->order->id, 0, 8) }}</td>
                                <td>{{ $dispute->created_at->format('Y-m-d / H:i') }}</td>
                                <td>{{ $dispute->order->user->username }}</td>
                                <td>{{ \Str::limit($dispute->reason, 30) }}</td>
                                <td>
                                    <span class="disputes-status disputes-status-{{ $dispute->status }}">
                                        {{ $dispute->getFormattedStatus() }}
                                    </span>
                                    @if($dispute->resolved_at)
                                        <div class="disputes-resolution">
                                            {{ $dispute->resolved_at->format('Y-m-d / H:i') }}
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('vendor.disputes.show', $dispute->id) }}" class="disputes-action-btn">
                                        View Dispute
                                    </a>
                                    <a href="{{ route('vendor.sales.show', $dispute->order->unique_url) }}" class="disputes-action-btn">
                                        View Order
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

@endsection