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

    .sales-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: var(--spacing-xl);
    }

    .sales-card {
        background: var(--color-card-bg);
        border: 1px solid var(--color-border);
        border-radius: var(--radius-lg);
        padding: var(--spacing-xl);
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }

    .sales-title {
        font-size: 28px;
        font-weight: 600;
        color: var(--color-text-primary);
        margin: 0 0 var(--spacing-xl) 0;
    }

    .sales-empty {
        text-align: center;
        padding: var(--spacing-xl) 0;
        color: var(--color-text-secondary);
    }

    .sales-table-container {
        overflow-x: auto;
    }

    .sales-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }

    .sales-table thead {
        background: var(--color-input-bg);
        border-bottom: 1px solid var(--color-border);
    }

    .sales-table th {
        padding: var(--spacing-lg);
        text-align: left;
        font-weight: 600;
        color: var(--color-text-primary);
    }

    .sales-table td {
        padding: var(--spacing-lg);
        border-bottom: 1px solid var(--color-border);
        color: var(--color-text-secondary);
    }

    .sales-table tbody tr:hover {
        background: rgba(32, 128, 136, 0.02);
    }

    .sales-status {
        display: inline-block;
        padding: 4px 12px;
        border-radius: var(--radius-base);
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .sales-status-waiting_payment {
        background: rgba(33, 150, 243, 0.1);
        color: #1976d2;
    }

    .sales-status-payment_received {
        background: rgba(255, 193, 7, 0.1);
        color: #f57f17;
    }

    .sales-status-product_sent {
        background: rgba(33, 150, 243, 0.1);
        color: #1976d2;
    }

    .sales-status-completed {
        background: rgba(76, 175, 80, 0.1);
        color: #388e3c;
    }

    .sales-status-cancelled {
        background: rgba(244, 67, 54, 0.1);
        color: #c62828;
    }

    .sales-action-btn {
        display: inline-block;
        background: var(--color-accent);
        color: white;
        padding: 6px 12px;
        border-radius: var(--radius-base);
        text-decoration: none;
        font-size: 12px;
        font-weight: 500;
        transition: background 0.2s ease;
    }

    .sales-action-btn:hover {
        background: var(--color-accent-light);
    }

    @media (max-width: 768px) {
        .sales-container {
            padding: var(--spacing-lg);
        }

        .sales-title {
            font-size: 24px;
        }

        .sales-table {
            font-size: 12px;
        }

        .sales-table th, .sales-table td {
            padding: var(--spacing-md);
        }
    }
</style>

<div class="sales-container">
    <div class="sales-card">
        <h1 class="sales-title">My Sales</h1>
        
        @if($sales->isEmpty())
            <div class="sales-empty">
                <p>You don't have any sales yet.</p>
            </div>
        @else
            <div class="sales-table-container">
                <table class="sales-table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Date</th>
                            <th>Buyer</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sales as $sale)
                            <tr>
                                <td>{{ substr($sale->id, 0, 8) }}</td>
                                <td>{{ $sale->created_at->format('Y-m-d / H:i') }}</td>
                                <td>{{ $sale->user->username }}</td>
                                <td>${{ number_format($sale->total, 2) }}</td>
                                <td>
                                    <span class="sales-status sales-status-{{ strtolower($sale->status) }}">
                                        {{ $sale->getFormattedStatus() }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('vendor.sales.show', $sale->unique_url) }}" class="sales-action-btn">
                                        View Details
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