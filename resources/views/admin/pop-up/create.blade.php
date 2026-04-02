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

    .popup-create-container {
        max-width: 800px;
        margin: 0 auto;
        padding: var(--spacing-xl);
    }

    .popup-create-card {
        background-color: var(--color-bg-secondary);
        border: 1px solid var(--color-border);
        border-radius: var(--radius);
        padding: var(--spacing-lg);
    }

    .popup-create-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: var(--spacing-lg);
        padding-bottom: var(--spacing-lg);
        border-bottom: 2px solid var(--color-accent-light);
    }

    .popup-create-title {
        font-size: 28px;
        font-weight: 600;
        color: var(--color-text-primary);
        margin: 0;
    }

    .popup-create-back-btn {
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

    .popup-create-back-btn:hover {
        background-color: #4b5563;
    }

    .popup-create-body {
        display: flex;
        flex-direction: column;
        gap: var(--spacing-lg);
    }

    .popup-create-form-group {
        display: flex;
        flex-direction: column;
        gap: var(--spacing-sm);
    }

    .popup-create-label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: var(--color-text-primary);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .popup-create-input {
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

    .popup-create-input:focus {
        outline: none;
        border-color: var(--color-accent);
        box-shadow: 0 0 0 2px rgba(32, 128, 136, 0.1);
    }

    .popup-create-textarea {
        resize: vertical;
        min-height: 150px;
    }

    .popup-create-toggle-group {
        display: flex;
        align-items: center;
        gap: var(--spacing-md);
        margin: var(--spacing-md) 0;
    }

    .popup-create-switch {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 28px;
    }

    .popup-create-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .popup-create-slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: 0.3s;
        border-radius: 28px;
    }

    .popup-create-slider:before {
        position: absolute;
        content: "";
        height: 22px;
        width: 22px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: 0.3s;
        border-radius: 50%;
    }

    .popup-create-switch input:checked + .popup-create-slider {
        background-color: var(--color-accent);
    }

    .popup-create-switch input:checked + .popup-create-slider:before {
        transform: translateX(22px);
    }

    .popup-create-label-toggle {
        font-size: 14px;
        color: var(--color-text-primary);
        margin: 0;
    }

    .popup-create-note {
        font-size: 12px;
        color: var(--color-text-secondary);
        margin: 0;
        font-style: italic;
    }

    .popup-create-btn-container {
        margin-top: var(--spacing-lg);
    }

    .popup-create-submit {
        width: 100%;
        background-color: var(--color-accent);
        color: #ffffff;
        padding: var(--spacing-md) var(--spacing-lg);
        border: none;
        border-radius: var(--radius);
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .popup-create-submit:hover {
        background-color: var(--color-accent-light);
    }

    @media (max-width: 768px) {
        .popup-create-container {
            padding: var(--spacing-md);
        }

        .popup-create-header {
            flex-direction: column;
            gap: var(--spacing-lg);
            align-items: flex-start;
        }

        .popup-create-title {
            font-size: 22px;
        }

        .popup-create-back-btn {
            width: 100%;
        }
    }
</style>

<div class="popup-create-container">
    <div class="popup-create-card">
        <div class="popup-create-header">
            <h2 class="popup-create-title">Create New Pop-up</h2>
            <a href="{{ route('admin.popup.index') }}" class="popup-create-back-btn">Back To List</a>
        </div>

        <div class="popup-create-body">
            <form action="{{ route('admin.popup.store') }}" method="POST">
                @csrf

                <div class="popup-create-form-group">
                    <label class="popup-create-label">Pop-up Title</label>
                    <input type="text" 
                           class="popup-create-input" 
                           name="title" 
                           value="{{ old('title') }}"
                           placeholder="Enter pop-up title"
                           required>
                </div>

                <div class="popup-create-form-group">
                    <label class="popup-create-label">Message Content</label>
                    <textarea class="popup-create-input popup-create-textarea" 
                              name="message" 
                              placeholder="Write your pop-up message here..."
                              required>{{ old('message') }}</textarea>
                </div>

                <div class="popup-create-toggle-group">
                    <label class="popup-create-switch">
                        <input type="checkbox" 
                               id="active" 
                               name="active" 
                               value="1" 
                               {{ old('active') ? 'checked' : '' }}>
                        <span class="popup-create-slider"></span>
                    </label>
                    <label for="active" class="popup-create-label-toggle">Activate This Pop-up</label>
                </div>
                <p class="popup-create-note">
                    Only one pop-up can be active at a time. Activating this will automatically deactivate others.
                </p>

                <div class="popup-create-btn-container">
                    <button type="submit" class="popup-create-submit">Publish Pop-up</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
