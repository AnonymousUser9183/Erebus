@extends('layouts.app')

@section('content')
<div class="support-list-container">
    <div class="support-list-header">
        <h1 class="support-list-title">Support Requests</h1>
        <p class="support-list-subtitle">Manage all user support tickets</p>
    </div>

    @if ($requests->count() > 0)
        <div class="support-list-card">
            <div class="support-list-table-container">
                <table class="support-list-table">
                    <thead>
                        <tr>
                            <th>Ticket ID</th>
                            <th>User</th>
                            <th>Subject</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Last Update</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($requests as $request)
                            <tr>
                                <td>
                                    <span class="support-list-ticket-id">{{ substr($request->ticket_id, 0, 12) }}...</span>
                                </td>
                                <td>
                                    <span class="support-list-username">{{ $request->user->username }}</span>
                                </td>
                                <td>
                                    <span class="support-list-title-text">{{ Str::limit($request->subject ?? $request->title, 40) }}</span>
                                </td>
                                <td>
                                    <span class="support-list-category">
                                        {{ ucfirst($request->category ?? 'N/A') }}
                                    </span>
                                </td>
                                <td>
                                    <span class="support-list-status support-list-status-{{ $request->status }}">
                                        @if ($request->status === 'open')
                                            🔴 Open
                                        @elseif ($request->status === 'in_progress')
                                            🟡 In Progress
                                        @else
                                            ✅ Closed
                                        @endif
                                    </span>
                                </td>
                                <td>
                                    <span class="support-list-time">{{ $request->created_at->format('Y-m-d H:i') }}</span>
                                </td>
                                <td>
                                    <span class="support-list-time">
                                        @if ($request->messages && $request->messages->count() > 0)
                                            {{ $request->messages->last()->created_at->format('Y-m-d H:i') }}
                                        @else
                                            No updates
                                        @endif
                                    </span>
                                </td>
                                <td>
                                    <!-- Route expects: {supportRequest}/{ticketId} -->
                                    <a href="{{ route('admin.support.show', [$request, $request->ticket_id]) }}" class="support-list-action-btn">
                                        View
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if ($requests->hasPages())
                <div class="support-list-pagination">
                    {{ $requests->links() }}
                </div>
            @endif
        </div>
    @else
        <div class="support-list-empty">
            <p>No support requests found.</p>
        </div>
    @endif
</div>

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

    .support-list-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: var(--spacing-xl);
    }

    .support-list-header {
        margin-bottom: var(--spacing-2xl);
        padding-bottom: var(--spacing-lg);
        border-bottom: 2px solid var(--color-accent-light);
    }

    .support-list-title {
        font-size: 32px;
        font-weight: 600;
        color: var(--color-text-primary);
        margin: 0 0 var(--spacing-xs) 0;
    }

    .support-list-subtitle {
        font-size: 14px;
        color: var(--color-text-secondary);
        margin: 0;
    }

    .support-list-card {
        background-color: var(--color-bg-secondary);
        border: 1px solid var(--color-border);
        border-radius: var(--radius);
        padding: var(--spacing-lg);
    }

    .support-list-table-container {
        overflow-x: auto;
    }

    .support-list-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }

    .support-list-table thead {
        background-color: var(--color-bg-primary);
        border-bottom: 2px solid var(--color-border);
    }

    .support-list-table th {
        padding: var(--spacing-md);
        text-align: left;
        font-weight: 600;
        color: var(--color-text-primary);
        white-space: nowrap;
    }

    .support-list-table td {
        padding: var(--spacing-md);
        border-bottom: 1px solid var(--color-border);
        color: var(--color-text-primary);
    }

    .support-list-table tbody tr:hover {
        background-color: var(--color-bg-primary);
    }

    .support-list-ticket-id {
        font-family: monospace;
        font-size: 12px;
        color: #999;
    }

    .support-list-username {
        font-weight: 500;
        color: var(--color-accent);
    }

    .support-list-title-text {
        font-weight: 500;
    }

    .support-list-category {
        font-size: 13px;
        color: var(--color-text-secondary);
    }

    .support-list-status {
        display: inline-block;
        padding: var(--spacing-xs) var(--spacing-sm);
        border-radius: var(--radius);
        font-size: 12px;
        font-weight: 600;
        text-transform: capitalize;
        white-space: nowrap;
    }

    .support-list-status-open {
        background-color: #fee2e2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }

    .support-list-status-in_progress {
        background-color: #fef3c7;
        color: #854d0e;
        border: 1px solid #fbbf24;
    }

    .support-list-status-closed {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .support-list-time {
        font-size: 13px;
        color: var(--color-text-secondary);
        white-space: nowrap;
    }

    .support-list-action-btn {
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
        white-space: nowrap;
    }

    .support-list-action-btn:hover {
        background-color: var(--color-accent-light);
    }

    .support-list-empty {
        background-color: var(--color-bg-primary);
        border: 1px solid var(--color-border);
        border-radius: var(--radius);
        padding: var(--spacing-lg);
        text-align: center;
        color: var(--color-text-secondary);
        font-size: 14px;
    }

    .support-list-pagination {
        margin-top: var(--spacing-xl);
        display: flex;
        justify-content: center;
    }

    @media (max-width: 768px) {
        .support-list-container {
            padding: var(--spacing-md);
        }

        .support-list-title {
            font-size: 24px;
        }

        .support-list-table {
            font-size: 12px;
        }

        .support-list-table th,
        .support-list-table td {
            padding: var(--spacing-sm);
        }

        .support-list-title-text {
            max-width: 100px;
            display: inline-block;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    }
</style>
@endsection
