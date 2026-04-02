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

    .bulk-message-create-container {
        max-width: 800px;
        margin: 0 auto;
        padding: var(--spacing-xl);
    }

    .bulk-message-create-card {
        background-color: var(--color-bg-secondary);
        border: 1px solid var(--color-border);
        border-radius: var(--radius);
        padding: var(--spacing-lg);
    }

    .bulk-message-create-title {
        font-size: 28px;
        font-weight: 600;
        color: var(--color-text-primary);
        margin: 0 0 var(--spacing-2xl) 0;
        padding-bottom: var(--spacing-lg);
        border-bottom: 2px solid var(--color-accent-light);
        text-align: center;
    }

    .bulk-message-create-form {
        display: flex;
        flex-direction: column;
        gap: var(--spacing-lg);
    }

    .bulk-message-create-form-group {
        display: flex;
        flex-direction: column;
        gap: var(--spacing-sm);
    }

    .bulk-message-create-label {
        font-size: 13px;
        font-weight: 600;
        color: var(--color-text-primary);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .bulk-message-create-input {
        padding: var(--spacing-md);
        border: 1px solid var(--color-border);
        border-radius: var(--radius);
        font-size: 14px;
        color: var(--color-text-primary);
        background-color: var(--color-bg-secondary);
        font-family: inherit;
        box-sizing: border-box;
        transition: border-color 0.3s ease;
    }

    .bulk-message-create-input:focus {
        outline: none;
        border-color: var(--color-accent);
        box-shadow: 0 0 0 2px rgba(32, 128, 136, 0.1);
    }

    .bulk-message-create-textarea {
        padding: var(--spacing-md);
        border: 1px solid var(--color-border);
        border-radius: var(--radius);
        font-size: 14px;
        color: var(--color-text-primary);
        background-color: var(--color-bg-secondary);
        font-family: inherit;
        box-sizing: border-box;
        resize: vertical;
        min-height: 150px;
    }

    .bulk-message-create-textarea:focus {
        outline: none;
        border-color: var(--color-accent);
        box-shadow: 0 0 0 2px rgba(32, 128, 136, 0.1);
    }

    .bulk-message-create-select {
        padding: var(--spacing-md);
        border: 1px solid var(--color-border);
        border-radius: var(--radius);
        font-size: 14px;
        color: var(--color-text-primary);
        background-color: var(--color-bg-secondary);
        font-family: inherit;
        box-sizing: border-box;
        cursor: pointer;
        transition: border-color 0.3s ease;
    }

    .bulk-message-create-select:focus {
        outline: none;
        border-color: var(--color-accent);
        box-shadow: 0 0 0 2px rgba(32, 128, 136, 0.1);
    }

    .bulk-message-create-actions {
        display: flex;
        gap: var(--spacing-md);
        margin-top: var(--spacing-lg);
    }

    .bulk-message-create-button {
        flex: 1;
        padding: var(--spacing-md) var(--spacing-lg);
        border: none;
        border-radius: var(--radius);
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        text-decoration: none;
        transition: background-color 0.3s ease;
        text-align: center;
    }

    .bulk-message-create-button-submit {
        background-color: var(--color-accent);
        color: #ffffff;
    }

    .bulk-message-create-button-submit:hover {
        background-color: var(--color-accent-light);
    }

    .bulk-message-create-button-cancel {
        background-color: var(--color-text-secondary);
        color: #ffffff;
    }

    .bulk-message-create-button-cancel:hover {
        background-color: #4b5563;
    }

    @media (max-width: 768px) {
        .bulk-message-create-container {
            padding: var(--spacing-md);
        }

        .bulk-message-create-title {
            font-size: 22px;
        }

        .bulk-message-create-actions {
            flex-direction: column;
        }

        .bulk-message-create-button {
            width: 100%;
        }
    }
</style>

<div class="bulk-message-create-container">
    <div class="bulk-message-create-card">
        <h1 class="bulk-message-create-title">Send Bulk Message</h1>

        <form action="{{ route('admin.bulk-message.send') }}" method="POST" class="bulk-message-create-form">
            @csrf

            <div class="bulk-message-create-form-group">
                <label for="title" class="bulk-message-create-label">Message Title</label>
                <input type="text" name="title" id="title" class="bulk-message-create-input" required>
            </div>

            <div class="bulk-message-create-form-group">
                <label for="message" class="bulk-message-create-label">Message Content</label>
                <textarea name="message" id="message" class="bulk-message-create-textarea" required></textarea>
            </div>

            <div class="bulk-message-create-form-group">
                <label for="target_role" class="bulk-message-create-label">Target User Group</label>
                <select name="target_role" id="target_role" class="bulk-message-create-select">
                    <option value="">All Users</option>
                    <option value="admin">Only Administrators</option>
                    <option value="vendor">Only Vendors</option>
                </select>
            </div>

            <div class="bulk-message-create-actions">
                <a href="{{ route('admin.bulk-message.list') }}" class="bulk-message-create-button bulk-message-create-button-cancel">
                    Cancel
                </a>
                <button type="submit" class="bulk-message-create-button bulk-message-create-button-submit">
                    Send Message
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
