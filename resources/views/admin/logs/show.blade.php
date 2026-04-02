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

    .logs-show-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: var(--spacing-xl);
    }

    .logs-show-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: var(--spacing-2xl);
        padding-bottom: var(--spacing-lg);
        border-bottom: 2px solid var(--color-accent-light);
    }

    .logs-show-title {
        font-size: 32px;
        font-weight: 600;
        color: var(--color-text-primary);
        margin: 0;
    }

    .logs-show-back-btn {
        display: inline-block;
        background-color: var(--color-text-secondary);
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

    .logs-show-back-btn:hover {
        background-color: #4b5563;
    }

    .logs-show-card {
        background-color: var(--color-bg-secondary);
        border: 1px solid var(--color-border);
        border-radius: var(--radius);
        padding: var(--spacing-lg);
    }

    .logs-show-entries {
        display: flex;
        flex-direction: column;
        gap: var(--spacing-md);
    }

    .logs-show-entry {
        background-color: var(--color-bg-primary);
        border: 1px solid var(--color-border);
        border-left: 4px solid var(--color-accent);
        border-radius: var(--radius);
        padding: var(--spacing-md);
        font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
        font-size: 12px;
        color: var(--color-text-primary);
        line-height: 1.6;
        white-space: pre-wrap;
        word-wrap: break-word;
        overflow-x: auto;
    }

    .logs-show-entry-error {
        border-left-color: #dc2626;
        background-color: rgba(220, 38, 38, 0.05);
    }

    .logs-show-entry-warning {
        border-left-color: #f59e0b;
        background-color: rgba(245, 158, 11, 0.05);
    }

    .logs-show-entry-info {
        border-left-color: #3b82f6;
        background-color: rgba(59, 130, 246, 0.05);
    }

    .logs-show-empty {
        background-color: var(--color-bg-primary);
        border: 1px solid var(--color-border);
        border-radius: var(--radius);
        padding: var(--spacing-lg);
        text-align: center;
        color: var(--color-text-secondary);
        font-size: 14px;
    }

    .logs-show-pagination {
        margin-top: var(--spacing-xl);
        display: flex;
        justify-content: center;
    }

    @media (max-width: 768px) {
        .logs-show-container {
            padding: var(--spacing-md);
        }

        .logs-show-header {
            flex-direction: column;
            gap: var(--spacing-lg);
            align-items: flex-start;
        }

        .logs-show-title {
            font-size: 24px;
        }

        .logs-show-back-btn {
            width: 100%;
        }

        .logs-show-entry {
            font-size: 11px;
            padding: var(--spacing-sm);
        }
    }
</style>

<div class="logs-show-container">
    <div class="logs-show-header">
        <h1 class="logs-show-title">
            @if($type === 'error')
                Error Logs
            @elseif($type === 'warning')
                Warning Logs
            @else
                Information Logs
            @endif
        </h1>
        <a href="{{ route('admin.logs.index') }}" class="logs-show-back-btn">Back to Logs</a>
    </div>

    <div class="logs-show-card">
        @if(count($logs) > 0)
            <div class="logs-show-entries">
                @foreach($logs as $log)
                    <div class="logs-show-entry logs-show-entry-{{ $type }}">
                        {!! $log !!}
                    </div>
                @endforeach
            </div>

            @if(method_exists($logs, 'hasPages') && $logs->hasPages())
                <div class="logs-show-pagination">
                    {{ $logs->links() }}
                </div>
            @endif
        @else
            <div class="logs-show-empty">
                <p>No logs found for this category.</p>
            </div>
        @endif
    </div>
</div>

@endsection
