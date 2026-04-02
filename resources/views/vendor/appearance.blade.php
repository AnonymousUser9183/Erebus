@extends('layouts.app')
@section('content')

<style>
    :root {
        --color-accent: #208088;
        --color-accent-light: #32b8c6;
        --color-text-primary: #134252;
        --color-text-secondary: #62746e;
        --color-card-bg: #ffffff;
        --color-border: #d4d8d6;
        --color-input-bg: #f5f7f6;
        --color-danger: #dc2626;
        --spacing-md: 12px;
        --spacing-lg: 16px;
        --spacing-xl: 24px;
        --radius-base: 8px;
        --radius-lg: 12px;
        --color-success: #4caf50;
    }

    .appearance-container {
        max-width: 900px;
        margin: 0 auto;
        padding: var(--spacing-xl);
    }

    .appearance-card {
        background: var(--color-card-bg);
        border: 1px solid var(--color-border);
        border-radius: var(--radius-lg);
        padding: var(--spacing-xl);
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }

    .appearance-title {
        font-size: 24px;
        font-weight: 600;
        color: var(--color-text-primary);
        margin: 0 0 var(--spacing-xl) 0;
    }

    /* Error Alert */
    .appearance-error {
        background: #fee2e2;
        border: 1px solid #fecaca;
        padding: var(--spacing-lg);
        border-radius: var(--radius-base);
        color: #991b1b;
        margin-bottom: var(--spacing-lg);
        font-size: 14px;
    }

    .appearance-error-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .appearance-error-list li {
        padding: 4px 0;
        margin-bottom: 4px;
    }

    .appearance-error-list li:before {
        content: "⚠ ";
        font-weight: bold;
        margin-right: 4px;
    }

    .appearance-section {
        margin-bottom: var(--spacing-xl);
        padding-bottom: var(--spacing-xl);
        border-bottom: 1px solid var(--color-border);
    }

    .appearance-section:last-child {
        border-bottom: none;
    }

    .appearance-section-title {
        font-size: 16px;
        font-weight: 600;
        color: var(--color-text-primary);
        margin: 0 0 var(--spacing-lg) 0;
    }

    .appearance-section-description {
        font-size: 13px;
        color: var(--color-text-secondary);
        margin-bottom: var(--spacing-lg);
    }

    .toggle-group {
        background: var(--color-input-bg);
        padding: var(--spacing-lg);
        border-radius: var(--radius-base);
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: var(--spacing-lg);
    }

    .toggle-label {
        font-size: 14px;
        font-weight: 500;
        color: var(--color-text-primary);
    }

    .toggle-input {
        width: 50px;
        height: 28px;
        appearance: none;
        background: var(--color-border);
        border-radius: 14px;
        cursor: pointer;
        transition: background 0.3s ease;
        border: none;
        position: relative;
    }

    .toggle-input:checked {
        background: var(--color-accent);
    }

    .toggle-input:after {
        content: '';
        position: absolute;
        width: 24px;
        height: 24px;
        background: white;
        border-radius: 50%;
        top: 2px;
        left: 2px;
        transition: left 0.3s ease;
    }

    .toggle-input:checked:after {
        left: 24px;
    }

    .appearance-form-group {
        margin-bottom: var(--spacing-lg);
    }

    .appearance-form-group.has-error .appearance-textarea,
    .appearance-form-group.has-error .appearance-input {
        border-color: var(--color-danger);
        background-color: #fef2f2;
    }

    .appearance-form-label {
        display: block;
        font-size: 14px;
        font-weight: 500;
        color: var(--color-text-primary);
        margin-bottom: var(--spacing-md);
    }

    .appearance-textarea {
        width: 100%;
        padding: var(--spacing-md);
        border: 1px solid var(--color-border);
        border-radius: var(--radius-base);
        font-family: inherit;
        font-size: 14px;
        color: var(--color-text-primary);
        resize: vertical;
        min-height: 120px;
        box-sizing: border-box;
        transition: border-color 0.2s ease;
    }

    .appearance-textarea:focus {
        outline: none;
        border-color: var(--color-accent);
        box-shadow: 0 0 0 2px rgba(32, 128, 136, 0.1);
    }

    .appearance-input {
        width: 100%;
        padding: var(--spacing-md);
        border: 1px solid var(--color-border);
        border-radius: var(--radius-base);
        font-family: inherit;
        font-size: 14px;
        color: var(--color-text-primary);
        box-sizing: border-box;
        transition: border-color 0.2s ease;
    }

    .appearance-input:focus {
        outline: none;
        border-color: var(--color-accent);
        box-shadow: 0 0 0 2px rgba(32, 128, 136, 0.1);
    }

    .appearance-char-count {
        font-size: 12px;
        color: var(--color-text-secondary);
        margin-top: var(--spacing-md);
        text-align: right;
    }

    .appearance-char-count.warning {
        color: #ffc107;
    }

    .appearance-char-count.error {
        color: var(--color-danger);
    }

    .appearance-help-text {
        font-size: 12px;
        color: var(--color-text-secondary);
        margin-top: var(--spacing-md);
        line-height: 1.5;
    }

    .appearance-field-error {
        font-size: 12px;
        color: var(--color-danger);
        margin-top: 4px;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .appearance-field-error:before {
        content: "⚠";
        font-weight: bold;
    }

    .appearance-submit-btn {
        background: var(--color-accent);
        color: white;
        padding: 12px 24px;
        border: none;
        border-radius: var(--radius-base);
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s ease;
        width: 100%;
    }

    .appearance-submit-btn:hover {
        background: var(--color-accent-light);
    }

    .appearance-success {
        background: rgba(76, 175, 80, 0.1);
        border: 1px solid rgba(76, 175, 80, 0.2);
        padding: var(--spacing-lg);
        border-radius: var(--radius-base);
        color: var(--color-success);
        margin-bottom: var(--spacing-lg);
    }

    @media (max-width: 768px) {
        .appearance-container {
            padding: var(--spacing-lg);
        }

        .appearance-title {
            font-size: 20px;
        }

        .toggle-group {
            flex-direction: column;
            align-items: flex-start;
            gap: var(--spacing-md);
        }
    }
</style>

<div class="appearance-container">
    <div class="appearance-card">
        <h2 class="appearance-title">Store Appearance & Settings</h2>

        @if(session('success'))
            <div class="appearance-success">
                ✓ {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="appearance-error">
                <strong>Unable to save your changes:</strong>
                @if ($errors->count() === 1)
                    <div style="margin-top: 8px;">{{ $errors->first() }}</div>
                @else
                    <ul class="appearance-error-list">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
        @endif

        <form action="{{ route('vendor.appearance.update') }}" method="POST">
            @csrf

            <div class="appearance-section">
                <h3 class="appearance-section-title">Store Status</h3>
                <p class="appearance-section-description">Manage how your store appears to customers on the marketplace.</p>

                <div class="toggle-group">
                    <label class="toggle-label">Vacation Mode</label>
                    <input type="hidden" name="vacation_mode" value="0">
                    <input type="checkbox" name="vacation_mode" value="1" class="toggle-input" {{ old('vacation_mode', $vendorProfile->vacation_mode) ? 'checked' : '' }}>
                </div>
                <p class="appearance-help-text">
                    When enabled, your store will be hidden from the marketplace and customers won't be able to place new orders. Existing orders will still be visible.
                </p>

                <div class="toggle-group" style="margin-top: var(--spacing-lg);">
                    <label class="toggle-label">Private Shop Mode</label>
                    <input type="hidden" name="private_shop_mode" value="0">
                    <input type="checkbox" name="private_shop_mode" value="1" class="toggle-input" {{ old('private_shop_mode', $vendorProfile->private_shop_mode) ? 'checked' : '' }}>
                </div>
                <p class="appearance-help-text">
                    Enable this to make your store private. Only users with direct access links will be able to view your products.
                </p>
            </div>

            <div class="appearance-section">
                <h3 class="appearance-section-title">Store Information</h3>
                <p class="appearance-section-description">Customize how customers see your store.</p>

                <div class="appearance-form-group @error('description') has-error @enderror">
                    <label for="description" class="appearance-form-label">Store Description</label>
                    <textarea 
                        name="description" 
                        id="description" 
                        class="appearance-textarea"
                        minlength="8"
                        maxlength="800"
                        required
                        onkeyup="updateCharCount(this, 'description-count')">{{ old('description', $vendorProfile->description) }}</textarea>
                    <div class="appearance-char-count" id="description-count">
                        <span class="current-count">{{ strlen(old('description', $vendorProfile->description)) }}</span>/800 characters
                    </div>
                    @error('description')
                        <div class="appearance-field-error">{{ $message }}</div>
                    @enderror
                    <p class="appearance-help-text">
                        Write a brief description about your store. This appears on your store front and helps customers learn about what you offer. (8-800 characters)
                    </p>
                </div>

                <div class="appearance-form-group @error('vendor_policy') has-error @enderror">
                    <label for="vendor_policy" class="appearance-form-label">Vendor Policy & Terms</label>
                    <textarea 
                        name="vendor_policy" 
                        id="vendor_policy" 
                        class="appearance-textarea"
                        minlength="8"
                        maxlength="1600"
                        required
                        onkeyup="updateCharCount(this, 'policy-count')">{{ old('vendor_policy', $vendorProfile->vendor_policy) }}</textarea>
                    <div class="appearance-char-count" id="policy-count">
                        <span class="current-count">{{ strlen(old('vendor_policy', $vendorProfile->vendor_policy)) }}</span>/1600 characters
                    </div>
                    @error('vendor_policy')
                        <div class="appearance-field-error">{{ $message }}</div>
                    @enderror
                    <p class="appearance-help-text">
                        Outline your store policies, shipping times, return policies, or any other important information customers should know. (8-1600 characters)
                    </p>
                </div>
            </div>

            <button type="submit" class="appearance-submit-btn">Save Changes</button>
        </form>
    </div>
</div>

<script>
    function updateCharCount(textarea, countElementId) {
        const count = textarea.value.length;
        const maxLength = parseInt(textarea.getAttribute('maxlength'));
        const countElement = document.getElementById(countElementId);
        const currentCountSpan = countElement.querySelector('.current-count');

        currentCountSpan.textContent = count;

        if (count >= maxLength * 0.9) {
            countElement.classList.add('warning');
            if (count >= maxLength) {
                countElement.classList.add('error');
                countElement.classList.remove('warning');
            }
        } else {
            countElement.classList.remove('warning', 'error');
        }
    }

    // Initialize character counts on page load
    document.addEventListener('DOMContentLoaded', function() {
        const textareas = document.querySelectorAll('textarea');
        textareas.forEach(textarea => {
            if (textarea.id === 'description') {
                updateCharCount(textarea, 'description-count');
            } else if (textarea.id === 'vendor_policy') {
                updateCharCount(textarea, 'policy-count');
            }
        });
    });
</script>

@endsection
