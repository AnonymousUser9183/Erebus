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
        --color-success: #22c55e;
        --spacing-xs: 8px;
        --spacing-sm: 12px;
        --spacing-md: 16px;
        --spacing-lg: 20px;
        --spacing-xl: 24px;
        --spacing-2xl: 32px;
        --radius: 8px;
    }

    .notifications-container {
        max-width: 900px;
        margin: 0 auto;
        padding: var(--spacing-xl);
    }

    .notifications-card {
        background-color: var(--color-bg-secondary);
        border: 1px solid var(--color-border);
        border-radius: var(--radius);
        overflow: hidden;
    }

    .notifications-header {
        background-color: var(--color-bg-primary);
        border-bottom: 2px solid var(--color-accent-light);
        padding: var(--spacing-lg);
    }

    .notifications-title {
        font-size: 24px;
        font-weight: 600;
        color: var(--color-text-primary);
        margin: 0;
    }

    .notifications-empty {
        padding: var(--spacing-2xl) var(--spacing-lg);
        text-align: center;
        color: var(--color-text-secondary);
    }

    .notifications-empty p {
        margin: 0;
        font-size: 15px;
    }

    .notifications-list {
        display: flex;
        flex-direction: column;
        divide-y: 1px solid var(--color-border);
    }

    .notifications-item {
        padding: var(--spacing-lg);
        border-bottom: 1px solid var(--color-border);
        display: flex;
        flex-direction: column;
        gap: var(--spacing-md);
    }

    .notifications-item:last-child {
        border-bottom: none;
    }

    .notifications-item-title {
        font-size: 16px;
        font-weight: 600;
        color: var(--color-accent);
        margin: 0;
    }

    .notifications-item-message {
        font-size: 14px;
        color: var(--color-text-primary);
        line-height: 1.6;
        margin: 0;
    }

    .notifications-item-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: var(--spacing-md);
    }

    .notifications-item-status {
        display: flex;
        align-items: center;
        gap: var(--spacing-md);
    }

    .notifications-read-badge {
        display: inline-block;
        background-color: rgba(34, 197, 94, 0.1);
        color: var(--color-success);
        border: 1px solid rgba(34, 197, 94, 0.3);
        padding: var(--spacing-xs) var(--spacing-sm);
        border-radius: var(--radius);
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .notifications-time {
        font-size: 12px;
        color: var(--color-text-secondary);
    }

    .notifications-actions {
        display: flex;
        gap: var(--spacing-sm);
    }

    .notifications-btn {
        padding: var(--spacing-xs) var(--spacing-sm);
        border: none;
        border-radius: var(--radius);
        font-size: 12px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .notifications-btn-read {
        background-color: var(--color-accent);
        color: #ffffff;
    }

    .notifications-btn-read:hover {
        background-color: var(--color-accent-light);
    }

    .notifications-btn-delete {
        background-color: #ffffff;
        color: var(--color-text-primary);
        border: 1px solid var(--color-border);
    }

    .notifications-btn-delete:hover {
        background-color: #fecaca;
        border-color: #ef4444;
        color: #dc2626;
    }

    .notifications-pagination {
        padding: var(--spacing-lg);
        background-color: var(--color-bg-primary);
        border-top: 1px solid var(--color-border);
        display: flex;
        justify-content: center;
        gap: var(--spacing-sm);
        flex-wrap: wrap;
    }

    .notifications-pagination a,
    .notifications-pagination span {
        padding: var(--spacing-xs) var(--spacing-sm);
        border: 1px solid var(--color-border);
        border-radius: var(--radius);
        font-size: 13px;
        text-decoration: none;
        color: var(--color-text-primary);
        display: inline-block;
        transition: all 0.3s ease;
    }

    .notifications-pagination a:hover {
        background-color: var(--color-accent);
        color: #ffffff;
        border-color: var(--color-accent);
    }

    .notifications-pagination .active {
        background-color: var(--color-accent);
        color: #ffffff;
        border-color: var(--color-accent);
    }

    .notifications-pagination .disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    @media (max-width: 768px) {
        .notifications-container {
            padding: var(--spacing-md);
        }

        .notifications-item-footer {
            flex-direction: column;
            align-items: flex-start;
        }

        .notifications-item-status {
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>

<div class="notifications-container">
    <div class="notifications-card">
        <div class="notifications-header">
            <h1 class="notifications-title">Your Notifications from Erebus Marketplace</h1>
        </div>
        @if($notifications->isEmpty())
            <div class="notifications-empty">
                <p>You don't have any notifications yet.</p>
            </div>
        @else
            <div class="notifications-list">
                @foreach($notifications as $notification)
                    <div class="notifications-item">
                        <h3 class="notifications-item-title">
                            {{ $notification->title }}
                        </h3>
                        <p class="notifications-item-message">
                            {{ $notification->message }}
                        </p>
                        <div class="notifications-item-footer">
                            <div class="notifications-item-status">
                                @if($notification->pivot->read)
                                    <span class="notifications-read-badge">Read</span>
                                @else
                                    <form method="POST" action="{{ route('notifications.mark-read', ['notification' => $notification->id]) }}">
                                        @csrf
                                        <button type="submit" class="notifications-btn notifications-btn-read">Mark as Read</button>
                                    </form>
                                @endif
                                <span class="notifications-time">
                                    {{ $notification->created_at->format('d-m-Y / H:i') }}
                                </span>
                            </div>
                            <div class="notifications-actions">
                                <form method="POST" action="{{ route('notifications.destroy', ['notification' => $notification->id]) }}">
                                    @csrf
                                    <button type="submit" class="notifications-btn notifications-btn-delete">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="notifications-pagination">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
