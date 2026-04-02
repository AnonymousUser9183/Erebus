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

    .become-vendor-application-container {
        max-width: 900px;
        margin: 0 auto;
        padding: var(--spacing-xl);
    }

    .become-vendor-application-card {
        background-color: var(--color-bg-secondary);
        border: 1px solid var(--color-border);
        border-radius: var(--radius);
        padding: var(--spacing-2xl);
    }

    .become-vendor-application-card h1 {
        font-size: 32px;
        font-weight: 600;
        color: var(--color-text-primary);
        margin: 0 0 var(--spacing-2xl) 0;
        padding-bottom: var(--spacing-lg);
        border-bottom: 2px solid var(--color-accent-light);
    }

    .become-vendor-application-alert {
        background-color: #fee2e2;
        border: 1px solid #fecaca;
        border-radius: var(--radius);
        padding: var(--spacing-lg);
        margin-bottom: var(--spacing-xl);
    }

    .become-vendor-application-alert ul {
        margin: 0;
        padding: 0 0 0 var(--spacing-lg);
        list-style: none;
    }

    .become-vendor-application-alert li {
        font-size: 14px;
        color: #7f1d1d;
        margin-bottom: var(--spacing-sm);
    }

    .become-vendor-application-alert li:before {
        content: "× ";
        color: #dc2626;
        font-weight: bold;
        margin-right: var(--spacing-sm);
    }

    .become-vendor-application-alert li:last-child {
        margin-bottom: 0;
    }

    .become-vendor-application-form-group {
        margin-bottom: var(--spacing-lg);
        display: flex;
        flex-direction: column;
        gap: var(--spacing-sm);
    }

    .become-vendor-application-form-group label {
        font-size: 14px;
        font-weight: 600;
        color: var(--color-text-primary);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .become-vendor-application-textarea {
        padding: var(--spacing-md);
        border: 1px solid var(--color-border);
        border-radius: var(--radius);
        font-size: 14px;
        color: var(--color-text-primary);
        background-color: var(--color-bg-secondary);
        font-family: inherit;
        box-sizing: border-box;
        transition: border-color 0.3s ease;
        min-height: 180px;
        resize: vertical;
    }

    .become-vendor-application-textarea:focus {
        outline: none;
        border-color: var(--color-accent);
        box-shadow: 0 0 0 2px rgba(32, 128, 136, 0.1);
    }

    .become-vendor-application-image-upload {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: var(--spacing-lg);
    }

    .become-vendor-application-image-slot {
        position: relative;
        border: 2px solid var(--color-border);
        border-radius: var(--radius);
        overflow: hidden;
        background-color: var(--color-bg-primary);
        transition: all 0.3s ease;
    }

    .become-vendor-application-image-slot:hover {
        border-color: var(--color-accent);
        background-color: #f5fafb;
    }

    .become-vendor-application-image-input {
        width: 100%;
        padding: var(--spacing-lg);
        border: none;
        cursor: pointer;
        font-size: 13px;
        color: var(--color-text-primary);
    }

    .become-vendor-application-image-input::file-selector-button {
        background-color: var(--color-accent);
        color: white;
        padding: var(--spacing-md) var(--spacing-lg);
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-weight: 600;
        font-size: 13px;
        margin-right: var(--spacing-md);
    }

    .become-vendor-application-image-input::file-selector-button:hover {
        background-color: var(--color-accent-light);
    }

    .become-vendor-application-image-hint {
        font-size: 13px;
        color: var(--color-text-secondary);
        line-height: 1.6;
        display: block;
        padding: var(--spacing-lg) var(--spacing-md);
    }

    .become-vendor-application-submit-btn {
        background-color: var(--color-accent);
        color: #ffffff;
        padding: var(--spacing-md) var(--spacing-xl);
        border: none;
        border-radius: var(--radius);
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: background-color 0.3s ease;
        width: 100%;
        box-sizing: border-box;
    }

    .become-vendor-application-submit-btn:hover {
        background-color: var(--color-accent-light);
    }

    small {
        font-size: 12px;
        color: var(--color-text-secondary);
        display: block;
        margin-top: var(--spacing-sm);
    }

    @media (max-width: 768px) {
        .become-vendor-application-container {
            padding: var(--spacing-lg);
        }

        .become-vendor-application-card {
            padding: var(--spacing-xl);
        }

        .become-vendor-application-card h1 {
            font-size: 24px;
        }

        .become-vendor-application-image-upload {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="become-vendor-application-container">
    <div class="become-vendor-application-card">
        <h1>Vendor Application</h1>
        
        <form action="{{ route('become.vendor.submit-application') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            @if($errors->any())
                <div class="become-vendor-application-alert">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="become-vendor-application-form-group">
                <label for="application_text">Application Details</label>
                <textarea 
                    id="application_text" 
                    name="application_text" 
                    class="become-vendor-application-textarea"
                    required
                    minlength="80"
                    maxlength="4000"
                    placeholder="Please provide:
- Information about yourself
- What products will you sell?
- Your communication address
- Previous references from other marketplaces"
                >{{ old('application_text') }}</textarea>
                <small>Your application must be between 80 and 4000 characters. Be detailed but concise.</small>
            </div>

            <div class="become-vendor-application-form-group">
                <label>Product Images (At least 1 required, maximum 4 images)</label>
                <div class="become-vendor-application-image-upload">
                    @for($i = 0; $i < 4; $i++)
                        <div class="become-vendor-application-image-slot">
                            <input 
                                type="file" 
                                name="product_images[]" 
                                accept="image/jpeg,image/png,image/gif,image/webp"
                                class="become-vendor-application-image-input"
                                {{ $i === 0 ? 'required' : '' }}
                            >
                            <span class="become-vendor-application-image-hint">
                                {{ $i === 0 ? '(Required)' : '(Optional)' }}
                            </span>
                        </div>
                    @endfor
                </div>
                <small>Supported formats: JPEG, PNG, GIF, WebP. Maximum size: 800KB per image.</small>
            </div>

            <div class="become-vendor-application-form-group">
                <button type="submit" class="become-vendor-application-submit-btn">Submit Application</button>
            </div>
        </form>
    </div>
</div>

@endsection
