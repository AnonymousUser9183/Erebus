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

    .canary-index-container {
        max-width: 900px;
        margin: 0 auto;
        padding: var(--spacing-xl);
    }

    .canary-index-card {
        background-color: var(--color-bg-secondary);
        border: 1px solid var(--color-border);
        border-radius: var(--radius);
        padding: var(--spacing-lg);
    }

    .canary-index-title {
        font-size: 24px;
        font-weight: 600;
        color: var(--color-accent);
        margin: 0 0 var(--spacing-lg) 0;
        padding-bottom: var(--spacing-md);
        border-bottom: 2px solid var(--color-accent-light);
        text-align: center;
    }

    .canary-index-form {
        display: flex;
        flex-direction: column;
        gap: var(--spacing-lg);
    }

    .canary-index-form-group {
        margin-bottom: var(--spacing-md);
    }

    .canary-index-form-group.text-center {
        text-align: center;
    }

    .canary-index-label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: var(--color-text-primary);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: var(--spacing-sm);
    }

    .canary-index-textarea {
        width: 100%;
        padding: var(--spacing-md);
        border: 1px solid var(--color-border);
        border-radius: var(--radius);
        font-size: 14px;
        color: var(--color-text-primary);
        background-color: var(--color-bg-secondary);
        font-family: 'Courier New', monospace;
        resize: vertical;
        box-sizing: border-box;
        transition: border-color 0.3s ease;
    }

    .canary-index-textarea:focus {
        outline: none;
        border-color: var(--color-accent);
        box-shadow: 0 0 0 2px rgba(32, 128, 136, 0.1);
    }

    .canary-index-btn {
        background-color: var(--color-accent);
        color: #ffffff;
        border: none;
        padding: var(--spacing-md) var(--spacing-lg);
        border-radius: var(--radius);
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .canary-index-btn:hover {
        background-color: var(--color-accent-light);
    }

    @media (max-width: 768px) {
        .canary-index-container {
            padding: var(--spacing-md);
        }

        .canary-index-title {
            font-size: 20px;
        }

        .canary-index-textarea {
            min-height: 200px;
        }
    }
</style>

<div class="canary-index-container">
    <div class="canary-index-card">
        <h2 class="canary-index-title">Erebus Marketplace Script Canary</h2>

        <form method="POST" action="{{ route('admin.canary.post') }}" class="canary-index-form">
            @csrf

            <div class="canary-index-form-group text-center">
                <label for="canary" class="canary-index-label">Current Canary</label>
                <textarea id="canary" class="canary-index-textarea" name="canary" required rows="5">{{ old('canary', $currentCanary) }}</textarea>
            </div>

            <div class="canary-index-form-group text-center">
                <button type="submit" class="canary-index-btn">
                    Update Canary
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
