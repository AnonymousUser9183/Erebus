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

    .bulk-message-list-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: var(--spacing-xl);
    }

    .bulk-message-list-card {
        background-color: var(--color-bg-secondary);
        border: 1px solid var(--color-border);
        border-radius: var(--radius);
        padding: var(--spacing-lg);
    }

    .bulk-message-list-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: var(--spacing-2xl);
        padding-bottom: var(--spacing-lg);
        border-bottom: 2px solid var(--color-accent-light);
    }

    .bulk-message-list-title {
        font-size: 32px;
        font-weight: 600;
        color: var(--color-text-primary);
        margin: 0;
    }

    .bulk-message-list-new-btn {
        display: inline-block;
        background-color: var(--color-accent);
        color: #ffffff;
        padding: var(--spacing-md) var(--spacing-lg);
        border: none;
        border-radius: var(--radius);
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        text-decoration: none;
        transition: background-color 0.3s ease;
    }

    .bulk-message-list-new-btn:hover {
        background-color: var(--color-accent-light);
    }

    .bulk-message-list-table-container {
        overflow-x: auto;
    }

    .bulk-message-list-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }

    .bulk-message-list-table thead {
        background-color: var(--color-bg-primary);
        border-bottom: 2px solid var(--color-border);
    }

    .bulk-message-list-table th {
        padding: var(--spacing-md);
        text-align: left;
        font-weight: 600;
        color: var(--color-text-primary);
    }

    .bulk-message-list-table td {
        padding: var(--spacing-md);
        border-bottom: 1px solid var(--color-border);
        color: var(--color-text-primary);
    }

    .bulk-message-list-table tbody tr:hover {
        background-color: var(--color-bg-primary);
    }

    .bulk-message-list-title-text {
        font-weight: 500;
    }

    .bulk-message-list-target {
        font-size: 13px;
        color: var(--color-accent);
    }

    .bulk-message-list-date {
        font-size: 13px;
        color: var(--color-text-secondary);
    }

    .bulk-message-list-delete-btn {
        display: inline-block;
        background-color: #ef4444;
        color: #ffffff;
        padding: var(--spacing-xs) var(--spacing-sm);
        border: none;
        border-radius: var(--radius);
        font-size: 12px;
        font-weight: 500;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .bulk-message-list-delete-btn:hover {
        background-color: #dc2626;
    }

    .bulk-message-list-empty {
        background-color: var(--color-bg-primary);
        border: 1px solid var(--color-border);
        border-radius: var(--radius);
        padding: var(--spacing-lg);
        text-align: center;
        color: var(--color-text-secondary);
        font-size: 14px;
    }

    .mt-4 {
        margin-top: var(--spacing-xl);
    }

    .inline {
        display: inline;
    }

    @media (max-width: 768px) {
        .bulk-message-list-container {
            padding: var(--spacing-md);
        }

        .bulk-message-list-header {
            flex-direction: column;
            gap: var(--spacing-lg);
            align-items: flex-start;
        }

        .bulk-message-list-title {
            font-size: 24px;
        }

        .bulk-message-list-new-btn {
            width: 100%;
        }

        .bulk-message-list-table {
            font-size: 13px;
        }

        .bulk-message-list-table th,
        .bulk-message-list-table td {
            padding: var(--spacing-sm);
        }
    }
</style>

<div class="bulk-message-list-container">
    <div class="bulk-message-list-card">
        <div class="bulk-message-list-header">
            <h1 class="bulk-message-list-title">Sent Bulk Messages</h1>
            <a href="{{ route('admin.bulk-message.create') }}" class="bulk-message-list-new-btn">
                Send New Message
            </a>
        </div>

        @if($notifications->count() > 0)
            <div class="bulk-message-list-table-container">
                <table class="bulk-message-list-table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Target Group</th>
                            <th>Sent Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($notifications as $notification)
                            @if($notification->type === 'bulk')
                                <tr>
                                    <td>
                                        <div class="bulk-message-list-title-text">
                                            {{ $notification->title }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="bulk-message-list-target">
                                            @if($notification->target_role)
                                                {{ $notification->translated_role }}
                                            @else
                                                All Users
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="bulk-message-list-date">
                                            {{ $notification->created_at->format('Y-m-d / H:i') }}
                                        </div>
                                    </td>
                                    <td>
                                        <form action="{{ route('admin.bulk-message.delete', $notification) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bulk-message-list-delete-btn">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $notifications->links() }}
            </div>
        @else
            <div class="bulk-message-list-empty">
                <p>No bulk messages have been sent yet.</p>
            </div>
        @endif
    </div>
</div>

@endsection
