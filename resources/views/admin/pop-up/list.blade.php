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

    .popup-list-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: var(--spacing-xl);
    }

    .popup-list-header {
        margin-bottom: var(--spacing-2xl);
        padding-bottom: var(--spacing-lg);
        border-bottom: 2px solid var(--color-accent-light);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .popup-list-title {
        font-size: 32px;
        font-weight: 600;
        color: var(--color-text-primary);
        margin: 0;
    }

    .popup-list-create-btn {
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

    .popup-list-create-btn:hover {
        background-color: var(--color-accent-light);
    }

    .popup-list-card {
        background-color: var(--color-bg-secondary);
        border: 1px solid var(--color-border);
        border-radius: var(--radius);
        padding: var(--spacing-lg);
    }

    .popup-list-table-container {
        overflow-x: auto;
    }

    .popup-list-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }

    .popup-list-table thead {
        background-color: var(--color-bg-primary);
        border-bottom: 2px solid var(--color-border);
    }

    .popup-list-table th {
        padding: var(--spacing-md);
        text-align: left;
        font-weight: 600;
        color: var(--color-text-primary);
    }

    .popup-list-table td {
        padding: var(--spacing-md);
        border-bottom: 1px solid var(--color-border);
        color: var(--color-text-primary);
    }

    .popup-list-table tbody tr:hover {
        background-color: var(--color-bg-primary);
    }

    .popup-list-status-badge {
        display: inline-block;
        padding: var(--spacing-xs) var(--spacing-sm);
        border-radius: var(--radius);
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .popup-list-status-active {
        background-color: #dcfce7;
        color: #166534;
        border: 1px solid #86efac;
    }

    .popup-list-status-inactive {
        background-color: #e5e7eb;
        color: #374151;
        border: 1px solid #d1d5db;
    }

    .popup-list-actions {
        display: flex;
        gap: var(--spacing-sm);
    }

    .popup-list-action-btn {
        display: inline-block;
        padding: var(--spacing-xs) var(--spacing-sm);
        border: none;
        border-radius: var(--radius);
        font-size: 12px;
        font-weight: 500;
        cursor: pointer;
        text-decoration: none;
        transition: background-color 0.3s ease;
    }

    .popup-list-action-activate {
        background-color: var(--color-accent);
        color: #ffffff;
    }

    .popup-list-action-activate:hover {
        background-color: var(--color-accent-light);
    }

    .popup-list-action-delete {
        background-color: #ef4444;
        color: #ffffff;
    }

    .popup-list-action-delete:hover {
        background-color: #dc2626;
    }

    .popup-list-empty {
        background-color: var(--color-bg-primary);
        border: 1px solid var(--color-border);
        border-radius: var(--radius);
        padding: var(--spacing-lg);
        text-align: center;
        color: var(--color-text-secondary);
        font-size: 14px;
    }

    .popup-list-pagination {
        margin-top: var(--spacing-xl);
        display: flex;
        justify-content: center;
    }

    @media (max-width: 768px) {
        .popup-list-container {
            padding: var(--spacing-md);
        }

        .popup-list-header {
            flex-direction: column;
            gap: var(--spacing-lg);
            align-items: flex-start;
        }

        .popup-list-title {
            font-size: 24px;
        }

        .popup-list-create-btn {
            width: 100%;
        }

        .popup-list-table {
            font-size: 13px;
        }

        .popup-list-table th,
        .popup-list-table td {
            padding: var(--spacing-sm);
        }

        .popup-list-actions {
            flex-direction: column;
        }

        .popup-list-action-btn {
            width: 100%;
        }
    }
</style>

<div class="popup-list-container">
    <div class="popup-list-header">
        <h1 class="popup-list-title">Popup Management</h1>
        <a href="{{ route('admin.popup.create') }}" class="popup-list-create-btn">Create New</a>
    </div>

    <div class="popup-list-card">
        @if($popups->count())
            <div class="popup-list-table-container">
                <table class="popup-list-table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Preview</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($popups as $popup)
                            <tr>
                                <td>{{ $popup->title }}</td>
                                <td>{{ Str::limit($popup->message, 40) }}</td>
                                <td>
                                    <span class="popup-list-status-badge {{ $popup->active ? 'popup-list-status-active' : 'popup-list-status-inactive' }}">
                                        {{ $popup->active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>{{ $popup->created_at->format('Y-m-d / H:i') }}</td>
                                <td>
                                    <div class="popup-list-actions">
                                        @if(!$popup->active)
                                            <form action="{{ route('admin.popup.activate', $popup) }}" method="POST" style="display: inline;">
                                                @csrf
                                                <button type="submit" class="popup-list-action-btn popup-list-action-activate">Activate</button>
                                            </form>
                                        @endif
                                        <form action="{{ route('admin.popup.destroy', $popup) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="popup-list-action-btn popup-list-action-delete">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($popups->hasPages())
                <div class="popup-list-pagination">
                    {{ $popups->links() }}
                </div>
            @endif
        @else
            <div class="popup-list-empty">
                <p>No popup messages found</p>
            </div>
        @endif
    </div>
</div>

@endsection
