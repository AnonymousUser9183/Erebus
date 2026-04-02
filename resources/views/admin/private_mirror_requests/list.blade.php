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
        max-width: 1200px;
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

    .mirror-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: var(--spacing-lg);
    }

    .mirror-table th {
        background-color: var(--color-bg-primary);
        color: var(--color-text-primary);
        padding: var(--spacing-md);
        text-align: left;
        font-weight: 600;
        border-bottom: 2px solid var(--color-border);
        font-size: 14px;
    }

    .mirror-table td {
        padding: var(--spacing-md);
        border-bottom: 1px solid var(--color-border);
        font-size: 14px;
    }

    .mirror-table tbody tr:hover {
        background-color: var(--color-bg-primary);
    }

    .status-badge {
        display: inline-block;
        padding: var(--spacing-xs) var(--spacing-sm);
        border-radius: var(--radius);
        font-size: 12px;
        font-weight: 500;
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

    .action-button {
        display: inline-block;
        background-color: var(--color-accent);
        color: #ffffff;
        padding: var(--spacing-sm) var(--spacing-md);
        border-radius: var(--radius);
        text-decoration: none;
        font-size: 13px;
        font-weight: 500;
        border: none;
        cursor: pointer;
        transition: background-color 0.3s ease;
        margin-right: 5px;
    }

    .action-button:hover {
        background-color: var(--color-accent-light);
    }

    .action-button.deny {
        background-color: #c62828;
    }

    .action-button.deny:hover {
        background-color: #b71c1c;
    }

    .empty-message {
        text-align: center;
        padding: var(--spacing-2xl);
        color: var(--color-text-secondary);
        background-color: var(--color-bg-primary);
        border-radius: var(--radius);
    }

    .pagination {
        margin-top: var(--spacing-xl);
        text-align: center;
    }

    .pagination a,
    .pagination span {
        display: inline-block;
        padding: var(--spacing-xs) var(--spacing-sm);
        margin: 0 2px;
        border-radius: var(--radius);
        background-color: var(--color-bg-primary);
        color: var(--color-text-primary);
        text-decoration: none;
        font-size: 14px;
    }

    .pagination .active {
        background-color: var(--color-accent);
        color: #ffffff;
    }

    .pagination a:hover {
        background-color: var(--color-accent-light);
        color: #ffffff;
    }
</style>

<div class="admin-container">
    <div class="admin-card">
        <h1 class="admin-title">Private Mirror Requests</h1>

        @if ($requests->count() > 0)
            <table class="mirror-table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Requested</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($requests as $req)
                        <tr>
                            <td>
                                @if ($req->user)
                                    <strong>{{ $req->user->username }}</strong>
                                @else
                                    <span style="color: #999;">User Not Found</span>
                                @endif
                            </td>
                            <td>
                                @if ($req->user)
                                    {{ $req->user->email }}
                                @else
                                    <span style="color: #999;">N/A</span>
                                @endif
                            </td>
                            <td>
                                <span class="status-badge status-{{ strtolower($req->status) }}">
                                    {{ ucfirst($req->status) }}
                                </span>
                            </td>
                            <td>
                                {{ $req->created_at->format('M d, Y H:i') }}
                            </td>
                            <td>
                                <a href="{{ route('admin.private-mirror-requests.show', $req->id) }}" class="action-button">
                                    View
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="pagination">
                {{ $requests->links() }}
            </div>
        @else
            <div class="empty-message">
                No private mirror requests yet.
            </div>
        @endif
    </div>
</div>

@endsection
