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
            --spacing-md: 16px;
            --spacing-lg: 20px;
            --spacing-xl: 24px;
            --radius: 8px;
        }

        .disputes-index-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: var(--spacing-lg);
        }

        /* Navigation - Links Back to Support */
        .disputes-nav {
            display: flex;
            gap: var(--spacing-md);
            margin-bottom: var(--spacing-xl);
            border-bottom: 2px solid var(--color-border);
        }

        .disputes-nav-link {
            padding: var(--spacing-md) var(--spacing-lg);
            background: none;
            border: none;
            color: var(--color-text-secondary);
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            border-bottom: 3px solid transparent;
            transition: all 0.3s ease;
            margin-bottom: -2px;
            text-decoration: none;
            display: inline-block;
        }

        .disputes-nav-link:hover {
            color: var(--color-text-primary);
        }

        .disputes-nav-link.active {
            color: var(--color-accent);
            border-bottom-color: var(--color-accent);
        }

        .disputes-index-card {
            background-color: var(--color-bg-secondary);
            border: 1px solid var(--color-border);
            border-radius: var(--radius);
            padding: var(--spacing-xl);
        }

        .disputes-index-title {
            font-size: 28px;
            font-weight: 700;
            color: var(--color-text-primary);
            margin: 0 0 var(--spacing-xl) 0;
        }

        .disputes-index-empty {
            text-align: center;
            padding: var(--spacing-xl);
            color: var(--color-text-secondary);
        }

        .disputes-index-empty p {
            font-size: 16px;
            margin-bottom: var(--spacing-lg);
        }

        .disputes-index-back-btn {
            display: inline-block;
            padding: var(--spacing-md) var(--spacing-lg);
            background-color: var(--color-accent);
            color: #ffffff;
            text-decoration: none;
            border-radius: var(--radius);
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .disputes-index-back-btn:hover {
            background-color: #1a6f7a;
        }

        .disputes-index-table-container {
            overflow-x: auto;
        }

        .disputes-index-table {
            width: 100%;
            border-collapse: collapse;
            background-color: var(--color-bg-secondary);
        }

        .disputes-index-table thead {
            background-color: var(--color-bg-primary);
            border-bottom: 2px solid var(--color-border);
        }

        .disputes-index-table th {
            padding: var(--spacing-md) var(--spacing-lg);
            text-align: left;
            font-weight: 600;
            color: var(--color-text-primary);
            font-size: 14px;
        }

        .disputes-index-table td {
            padding: var(--spacing-md) var(--spacing-lg);
            border-bottom: 1px solid var(--color-border);
            color: var(--color-text-secondary);
            font-size: 14px;
        }

        .disputes-index-table tbody tr:hover {
            background-color: var(--color-bg-primary);
        }

        .disputes-index-status {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .disputes-index-status-active {
            background-color: #dbeafe;
            color: #1e3a8a;
        }

        .disputes-index-status-resolved {
            background-color: #dcfce7;
            color: #166534;
        }

        .disputes-index-status-pending {
            background-color: #fef08a;
            color: #713f12;
        }

        .disputes-index-action-btn {
            display: inline-block;
            padding: 6px 12px;
            background-color: var(--color-accent);
            color: #ffffff;
            text-decoration: none;
            border-radius: var(--radius);
            font-size: 12px;
            font-weight: 500;
            transition: all 0.3s ease;
            margin-right: 8px;
        }

        .disputes-index-action-btn:hover {
            background-color: #1a6f7a;
        }

        @media (max-width: 768px) {
            .disputes-index-container {
                padding: var(--spacing-md);
            }

            .disputes-index-card {
                padding: var(--spacing-lg);
            }

            .disputes-index-title {
                font-size: 20px;
            }

            .disputes-index-table th,
            .disputes-index-table td {
                padding: var(--spacing-sm) var(--spacing-md);
                font-size: 12px;
            }
        }
    </style>

    <div class="disputes-index-container">
        <!-- Navigation - Links Back to Support -->
        <div class="disputes-nav">
            <a href="{{ route('support.index') }}" class="disputes-nav-link">
                📝 Support Requests
            </a>
            <a href="{{ route('disputes.index') }}" class="disputes-nav-link active">
                ⚖️ Disputes
            </a>
        </div>

        <div class="disputes-index-card">
            <h1 class="disputes-index-title">My Disputes</h1>

            @if($disputes->isEmpty())
                <div class="disputes-index-empty">
                    <p>You don't have any disputes at the moment.</p>
                </div>
            @else
                <div class="disputes-index-table-container">
                    <table class="disputes-index-table">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Date</th>
                                <th>Vendor</th>
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
                                    <td>{{ $dispute->order->vendor->username }}</td>
                                    <td>{{ Str::limit($dispute->reason, 30) }}</td>
                                    <td>
                                        <span class="disputes-index-status disputes-index-status-{{ strtolower($dispute->status) }}">
                                            {{ $dispute->getFormattedStatus() }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('disputes.show', $dispute->id) }}" class="disputes-index-action-btn">
                                            View Dispute
                                        </a>
                                        <a href="{{ route('orders.show', $dispute->order->unique_url) }}" class="disputes-index-action-btn">
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
