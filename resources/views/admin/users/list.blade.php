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

    .users-list-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: var(--spacing-xl);
    }

    .users-list-header {
        margin-bottom: var(--spacing-2xl);
        padding-bottom: var(--spacing-lg);
        border-bottom: 2px solid var(--color-accent-light);
    }

    .users-list-title {
        font-size: 32px;
        font-weight: 600;
        color: var(--color-text-primary);
        margin: 0;
    }

    .users-list-card {
        background-color: var(--color-bg-secondary);
        border: 1px solid var(--color-border);
        border-radius: var(--radius);
        padding: var(--spacing-lg);
    }

    .users-list-table-container {
        overflow-x: auto;
    }

    .users-list-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }

    .users-list-table thead {
        background-color: var(--color-bg-primary);
        border-bottom: 2px solid var(--color-border);
    }

    .users-list-table th {
        padding: var(--spacing-md);
        text-align: left;
        font-weight: 600;
        color: var(--color-text-primary);
    }

    .users-list-table td {
        padding: var(--spacing-md);
        border-bottom: 1px solid var(--color-border);
        color: var(--color-text-primary);
    }

    .users-list-table tbody tr:hover {
        background-color: var(--color-bg-primary);
    }

    .users-list-btn {
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

    .users-list-btn:hover {
        background-color: var(--color-accent-light);
    }

    .users-list-pagination {
        margin-top: var(--spacing-xl);
        display: flex;
        justify-content: center;
    }

    @media (max-width: 768px) {
        .users-list-container {
            padding: var(--spacing-md);
        }

        .users-list-title {
            font-size: 24px;
        }

        .users-list-table {
            font-size: 13px;
        }

        .users-list-table th,
        .users-list-table td {
            padding: var(--spacing-sm);
        }
    }
</style>

<div class="users-list-container">
    <div class="users-list-header">
        <h1 class="users-list-title">Marketplace Users</h1>
    </div>

    <div class="users-list-card">
        <div class="users-list-table-container">
            <table class="users-list-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Last Login</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->username }}</td>
                            <td>{{ $user->last_login ? $user->last_login->format('Y-m-d / H:i') : 'N/A' }}</td>
                            <td>
                                <a href="{{ route('admin.users.details', $user->id) }}" class="users-list-btn">User Details</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="users-list-pagination">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>

@endsection
