@extends('layouts.app')

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
        --color-warning: #ea580c;
        --color-success: #16a34a;
        --color-info: #0284c7;
        --radius-base: 8px;
        --radius-lg: 12px;
        --spacing-sm: 8px;
        --spacing-md: 12px;
        --spacing-lg: 16px;
        --spacing-xl: 24px;
    }
    
    .orders-index-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: var(--spacing-xl);
        background-color: var(--color-bg-primary);
    }
    
    .orders-index-card {
        background: var(--color-bg-secondary);
        border: 1px solid var(--color-border);
        border-radius: var(--radius-lg);
        padding: var(--spacing-xl);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    }
    
    .orders-index-title {
        font-size: 28px;
        font-weight: 600;
        color: var(--color-text-primary);
        margin: 0 0 var(--spacing-xl) 0;
    }
    
    .orders-index-empty {
        text-align: center;
        padding: var(--spacing-xl);
        color: var(--color-text-secondary);
    }
    
    .orders-index-empty p {
        font-size: 16px;
        margin: 0 0 var(--spacing-lg) 0;
    }
    
    .orders-index-browse-btn {
        display: inline-block;
        padding: var(--spacing-md) var(--spacing-lg);
        background-color: var(--color-primary);
        color: white;
        border: none;
        border-radius: var(--radius-base);
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-size: 14px;
    }
    
    .orders-index-browse-btn:hover {
        background-color: var(--color-primary-light);
    }
    
    .orders-index-table-container {
        overflow-x: auto;
        margin-top: var(--spacing-lg);
    }
    
    .orders-index-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }
    
    .orders-index-table thead {
        background-color: var(--color-bg-primary);
        border-bottom: 2px solid var(--color-border);
    }
    
    .orders-index-table th {
        padding: var(--spacing-lg);
        text-align: left;
        color: var(--color-text-primary);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        font-size: 12px;
    }
    
    .orders-index-table tbody tr {
        border-bottom: 1px solid var(--color-border);
        transition: background-color 0.3s ease;
    }
    
    .orders-index-table tbody tr:hover {
        background-color: var(--color-bg-primary);
    }
    
    .orders-index-table td {
        padding: var(--spacing-lg);
        color: var(--color-text-primary);
    }
    
    .orders-index-status {
        display: inline-block;
        padding: 6px 12px;
        border-radius: var(--radius-base);
        font-weight: 600;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    
    .orders-index-status-waiting_payment {
        background-color: rgba(26, 122, 153, 0.15);
        color: var(--color-primary);
        border: 1px solid rgba(26, 122, 153, 0.3);
    }
    
    .orders-index-status-payment_received {
        background-color: rgba(22, 163, 74, 0.15);
        color: var(--color-success);
        border: 1px solid rgba(22, 163, 74, 0.3);
    }
    
    .orders-index-status-product_sent {
        background-color: rgba(234, 88, 12, 0.15);
        color: var(--color-warning);
        border: 1px solid rgba(234, 88, 12, 0.3);
    }
    
    .orders-index-status-completed {
        background-color: rgba(22, 163, 74, 0.15);
        color: var(--color-success);
        border: 1px solid rgba(22, 163, 74, 0.3);
    }
    
    .orders-index-status-cancelled {
        background-color: rgba(220, 38, 38, 0.15);
        color: var(--color-danger);
        border: 1px solid rgba(220, 38, 38, 0.3);
    }
    
    .orders-index-status-disputed {
        background-color: rgba(234, 88, 12, 0.15);
        color: var(--color-warning);
        border: 1px solid rgba(234, 88, 12, 0.3);
    }
    
    .orders-index-action-btn {
        display: inline-block;
        padding: 6px 16px;
        background-color: var(--color-primary);
        color: white;
        border: none;
        border-radius: var(--radius-base);
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        font-size: 12px;
    }
    
    .orders-index-action-btn:hover {
        background-color: var(--color-primary-light);
    }
    
    .orders-index-action-btn:active {
        transform: scale(0.98);
    }

    .orders-index-pagination-wrapper {
        margin-top: var(--spacing-xl);
        display: flex;
        justify-content: center;
    }
    
    @media (max-width: 1024px) {
        .orders-index-table {
            font-size: 13px;
        }
        
        .orders-index-table th,
        .orders-index-table td {
            padding: var(--spacing-md);
        }
    }
    
    @media (max-width: 768px) {
        .orders-index-container {
            padding: var(--spacing-lg);
        }
        
        .orders-index-card {
            padding: var(--spacing-lg);
            margin-left: 0;
            margin-right: 0;
        }
        
        .orders-index-title {
            font-size: 22px;
        }
        
        .orders-index-table-container {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        
        .orders-index-table {
            font-size: 12px;
            min-width: 600px;
        }
        
        .orders-index-table th,
        .orders-index-table td {
            padding: 10px;
        }
    }
</style>

<div class="orders-index-container">
    <div class="orders-index-card">
        <h1 class="orders-index-title">My Orders</h1>

        {{-- Orders List --}}
        <div>
            @if($orders->isEmpty())
                <div class="orders-index-empty">
                    <p>You don't have any orders yet.</p>
                    <a href="{{ route('products.index') }}" class="orders-index-browse-btn">Browse Products</a>
                </div>
            @else
                <div class="orders-index-table-container">
                    <table class="orders-index-table">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Date</th>
                                <th>Vendor</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                                <tr>
                                    <td>{{ substr($order->id, 0, 8) }}</td>
                                    <td>{{ $order->created_at->format('Y-m-d / H:i') }}</td>
                                    <td>{{ $order->vendor->username }}</td>
                                    <td>${{ number_format($order->total, 2) }}</td>
                                    <td>
                                        <span class="orders-index-status orders-index-status-{{ strtolower($order->status) }}">
                                            {{ $order->getFormattedStatus() }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('orders.show', $order->unique_url) }}" class="orders-index-action-btn">
                                            View Details
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination (only if it's a Paginator instance) --}}
                @if($orders instanceof \Illuminate\Pagination\Paginator)
                    <div class="orders-index-pagination-wrapper">
                        {{ $orders->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>

@endsection
