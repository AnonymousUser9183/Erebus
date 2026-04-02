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

    .logs-index-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: var(--spacing-xl);
    }

    .logs-index-badge {
        font-size: 32px;
        font-weight: 600;
        color: var(--color-text-primary);
        margin: 0 0 var(--spacing-2xl) 0;
        padding-bottom: var(--spacing-lg);
        border-bottom: 2px solid var(--color-accent-light);
    }

    .logs-index-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: var(--spacing-xl);
    }

    .logs-index-card {
        background-color: var(--color-bg-secondary);
        border: 1px solid var(--color-border);
        border-radius: var(--radius);
        padding: var(--spacing-lg);
    }

    .logs-index-card-title {
        font-size: 18px;
        font-weight: 600;
        color: var(--color-accent);
        margin: 0 0 var(--spacing-md) 0;
        padding-bottom: var(--spacing-md);
        border-bottom: 2px solid var(--color-accent-light);
    }

    .logs-index-card-description {
        font-size: 14px;
        color: var(--color-text-secondary);
        margin: var(--spacing-md) 0;
        line-height: 1.6;
    }

    .logs-index-card-actions {
        display: flex;
        gap: var(--spacing-md);
        margin-top: var(--spacing-lg);
    }

    .logs-index-btn {
        flex: 1;
        display: inline-block;
        padding: var(--spacing-md) var(--spacing-lg);
        border: none;
        border-radius: var(--radius);
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        text-decoration: none;
        text-align: center;
        transition: background-color 0.3s ease;
    }

    .logs-index-btn-view {
        background-color: var(--color-accent);
        color: #ffffff;
    }

    .logs-index-btn-view:hover {
        background-color: var(--color-accent-light);
    }

    .logs-index-btn-delete {
        background-color: #ef4444;
        color: #ffffff;
    }

    .logs-index-btn-delete:hover {
        background-color: #dc2626;
    }

    .logs-index-delete-form {
        flex: 1;
        display: flex;
    }

    .logs-index-delete-form button {
        flex: 1;
    }

    .logs-index-card-error .logs-index-card-title {
        color: #dc2626;
    }

    .logs-index-card-warning .logs-index-card-title {
        color: #f59e0b;
    }

    .logs-index-card-info .logs-index-card-title {
        color: #3b82f6;
    }

    @media (max-width: 768px) {
        .logs-index-container {
            padding: var(--spacing-md);
        }

        .logs-index-badge {
            font-size: 24px;
        }

        .logs-index-grid {
            grid-template-columns: 1fr;
        }

        .logs-index-card-actions {
            flex-direction: column;
        }

        .logs-index-btn,
        .logs-index-btn-delete {
            width: 100%;
        }
    }

    .text-center {
        text-align: center;
    }
</style>

<div class="logs-index-container">
    <div class="text-center">
        <h1 class="logs-index-badge">System Logs</h1>
    </div>
    
    <div class="logs-index-grid">
        <div class="logs-index-card logs-index-card-error">
            <h2 class="logs-index-card-title">Error Logs</h2>
            <p class="logs-index-card-description">View Error, Critical, Alert, Emergency logs</p>
            <div class="logs-index-card-actions">
                <a href="{{ route('admin.logs.show', 'error') }}" class="logs-index-btn logs-index-btn-view">View Logs</a>
                <form action="{{ route('admin.logs.delete', ['type' => 'error']) }}" method="POST" class="logs-index-delete-form">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="logs-index-btn logs-index-btn-delete">Delete</button>
                </form>
            </div>
        </div>

        <div class="logs-index-card logs-index-card-warning">
            <h2 class="logs-index-card-title">Warning Logs</h2>
            <p class="logs-index-card-description">View Warning and Notice logs</p>
            <div class="logs-index-card-actions">
                <a href="{{ route('admin.logs.show', 'warning') }}" class="logs-index-btn logs-index-btn-view">View Logs</a>
                <form action="{{ route('admin.logs.delete', ['type' => 'warning']) }}" method="POST" class="logs-index-delete-form">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="logs-index-btn logs-index-btn-delete">Delete</button>
                </form>
            </div>
        </div>

        <div class="logs-index-card logs-index-card-info">
            <h2 class="logs-index-card-title">Information Logs</h2>
            <p class="logs-index-card-description">View Info and Debug logs</p>
            <div class="logs-index-card-actions">
                <a href="{{ route('admin.logs.show', 'info') }}" class="logs-index-btn logs-index-btn-view">View Logs</a>
                <form action="{{ route('admin.logs.delete', ['type' => 'info']) }}" method="POST" class="logs-index-delete-form">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="logs-index-btn logs-index-btn-delete">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
