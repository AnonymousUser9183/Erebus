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

    .products-common-create-container {
        max-width: 1000px;
        margin: 0 auto;
        padding: var(--spacing-xl);
    }

    .products-common-create-card {
        background-color: var(--color-bg-secondary);
        border: 1px solid var(--color-border);
        border-radius: var(--radius);
        padding: var(--spacing-lg);
    }

    .products-common-create-content {
        display: flex;
        flex-direction: column;
        gap: var(--spacing-lg);
    }

    .products-common-create-title {
        font-size: 28px;
        font-weight: 600;
        color: var(--color-text-primary);
        margin: 0;
        padding-bottom: var(--spacing-lg);
        border-bottom: 2px solid var(--color-accent-light);
    }

    .products-common-create-form {
        display: flex;
        flex-direction: column;
        gap: var(--spacing-lg);
    }

    .products-common-edit-visibility {
        background-color: var(--color-bg-primary);
        border: 1px solid var(--color-border);
        border-radius: var(--radius);
        padding: var(--spacing-lg);
    }

    .products-common-edit-visibility-title {
        font-size: 16px;
        font-weight: 600;
        color: var(--color-accent);
        margin: 0 0 var(--spacing-md) 0;
    }

    .products-common-edit-visibility-toggle {
        margin: var(--spacing-md) 0;
    }

    .products-common-edit-visibility-checkbox {
        width: 20px;
        height: 20px;
        cursor: pointer;
    }

    .products-common-edit-visibility-status {
        display: block;
        font-size: 14px;
        color: var(--color-text-secondary);
        margin: var(--spacing-md) 0;
    }

    .products-common-edit-visibility-status.active {
        color: #059669;
        font-weight: 600;
    }

    .products-common-edit-visibility-hint {
        font-size: 12px;
        color: var(--color-text-secondary);
        margin: var(--spacing-md) 0 0 0;
        font-style: italic;
    }

    .products-common-create-section {
        background-color: var(--color-bg-primary);
        border: 1px solid var(--color-border);
        border-radius: var(--radius);
        padding: var(--spacing-lg);
    }

    .products-common-create-section-title {
        font-size: 16px;
        font-weight: 600;
        color: var(--color-accent);
        margin: 0 0 var(--spacing-md) 0;
    }

    .products-common-create-section-desc {
        font-size: 13px;
        color: var(--color-text-secondary);
        margin: 0 0 var(--spacing-lg) 0;
    }

    .products-common-create-field {
        margin-bottom: var(--spacing-lg);
        display: flex;
        flex-direction: column;
        gap: var(--spacing-sm);
    }

    .products-common-create-label {
        font-size: 13px;
        font-weight: 600;
        color: var(--color-text-primary);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .products-common-create-input,
    .products-common-create-select,
    .products-common-create-textarea {
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

    .products-common-create-input:focus,
    .products-common-create-select:focus,
    .products-common-create-textarea:focus {
        outline: none;
        border-color: var(--color-accent);
        box-shadow: 0 0 0 2px rgba(32, 128, 136, 0.1);
    }

    .products-common-create-textarea {
        resize: vertical;
        min-height: 120px;
    }

    .products-common-create-price-wrapper {
        display: flex;
        gap: 0;
    }

    .products-common-create-price-symbol {
        display: flex;
        align-items: center;
        background-color: var(--color-bg-primary);
        padding: var(--spacing-md);
        border: 1px solid var(--color-border);
        border-right: none;
        border-radius: var(--radius) 0 0 var(--radius);
        font-weight: 600;
        color: var(--color-text-primary);
    }

    .products-common-create-price-input {
        flex: 1;
        padding: var(--spacing-md);
        border: 1px solid var(--color-border);
        border-radius: 0 var(--radius) var(--radius) 0;
        font-size: 14px;
        color: var(--color-text-primary);
        background-color: var(--color-bg-secondary);
        box-sizing: border-box;
        transition: border-color 0.3s ease;
    }

    .products-common-create-price-input:focus {
        outline: none;
        border-color: var(--color-accent);
        box-shadow: inset 0 0 0 2px rgba(32, 128, 136, 0.1);
    }

    .products-common-create-shipping-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: var(--spacing-lg);
        padding: var(--spacing-lg);
        background-color: var(--color-bg-primary);
        border: 1px solid var(--color-border);
        border-radius: var(--radius);
    }

    .products-common-create-option-card {
        background-color: var(--color-bg-secondary);
        border: 1px solid var(--color-border);
        border-radius: var(--radius);
        padding: var(--spacing-lg);
    }

    .products-common-create-option-title {
        font-size: 14px;
        font-weight: 600;
        color: var(--color-accent);
        margin: 0 0 var(--spacing-md) 0;
        padding-bottom: var(--spacing-md);
        border-bottom: 1px solid var(--color-border);
    }

    .products-common-create-submit-wrapper {
        margin-top: var(--spacing-lg);
    }

    .products-common-create-submit-btn {
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

    .products-common-create-submit-btn:hover {
        background-color: var(--color-accent-light);
    }

    .products-common-edit-photos-title {
        font-size: 14px;
    }

    .products-common-edit-photos-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: var(--spacing-lg);
        margin: var(--spacing-lg) 0;
    }

    .products-common-edit-photo {
        position: relative;
        overflow: hidden;
        border-radius: var(--radius);
        border: 1px solid var(--color-border);
    }

    .products-common-admin-edit-photo-wrapper {
        aspect-ratio: 1;
    }

    .products-common-create-preview {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .products-common-admin-edit-delete-btn {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background-color: rgba(0, 0, 0, 0.7);
        color: #ffffff;
        padding: var(--spacing-sm);
        border: none;
        font-size: 12px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .products-common-admin-edit-delete-btn:hover {
        background-color: rgba(0, 0, 0, 0.9);
    }

    .products-common-create-photo-upload {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: var(--spacing-lg);
    }

    .products-common-create-upload-wrapper {
        display: flex;
        flex-direction: column;
        gap: var(--spacing-sm);
    }

    .products-common-create-file-btn {
        display: block;
        background-color: var(--color-accent);
        color: #ffffff;
        padding: var(--spacing-md) var(--spacing-lg);
        border-radius: var(--radius);
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        text-align: center;
        transition: background-color 0.3s ease;
    }

    .products-common-create-file-btn:hover {
        background-color: var(--color-accent-light);
    }

    .products-common-create-help-text {
        font-size: 12px;
        color: var(--color-text-secondary);
        margin: 0;
    }

    .hidden {
        display: none;
    }

    @media (max-width: 768px) {
        .products-common-create-container {
            padding: var(--spacing-md);
        }

        .products-common-create-title {
            font-size: 22px;
        }

        .products-common-create-shipping-grid {
            grid-template-columns: 1fr;
        }

        .products-common-edit-photos-container {
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        }

        .products-common-create-photo-upload {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="products-common-create-container">
    <div class="products-common-create-card">
        <div class="products-common-create-content">
            <h2 class="products-common-create-title">
                Edit {{ ucfirst($product->type) }} Product
            </h2>

            <form action="{{ route('admin.products.update', $product) }}" method="POST" class="products-common-create-form" enctype="multipart/form-data">
                @csrf
                @method('PATCH')

                <div class="products-common-edit-visibility">
                    <h3 class="products-common-edit-visibility-title">Product Visibility</h3>
                    <div class="products-common-edit-visibility-toggle">
                        <input type="checkbox" name="active" id="active" value="1" class="products-common-edit-visibility-checkbox" {{ $product->active ? 'checked' : '' }}>
                    </div>
                    <span class="products-common-edit-visibility-status {{ $product->active ? 'active' : '' }}">
                        {{ $product->active ? 'This product is currently visible to customers' : 'This product is currently hidden from customers' }}
                    </span>
                    <p class="products-common-edit-visibility-hint">
                        When inactive, the product will be hidden from all marketplace listings.
                    </p>
                </div>

                <div class="products-common-create-section">
                    <div class="products-common-create-field">
                        <label class="products-common-create-label products-common-edit-photos-title">Photos</label>
                        <div class="products-common-edit-photos-container">
                            <div class="products-common-edit-photo products-common-admin-edit-photo-wrapper">
                                <img src="{{ $product->product_picture_url }}" alt="Product Picture" class="products-common-create-preview">
                                @if($product->product_picture !== 'default-product-picture.png')
                                    <button type="submit" name="delete_main_photo" value="1" class="products-common-admin-edit-delete-btn">Delete Main Photo</button>
                                @endif
                            </div>
                            @foreach($product->additional_photos_urls as $index => $photoUrl)
                                <div class="products-common-edit-photo products-common-admin-edit-photo-wrapper">
                                    <img src="{{ $photoUrl }}" alt="Additional Product Picture" class="products-common-create-preview">
                                    <button type="submit" name="delete_additional_photo" value="{{ $index }}" class="products-common-admin-edit-delete-btn">Delete Photo</button>
                                </div>
                            @endforeach
                        </div>
                        <div class="products-common-create-photo-upload">
                            <div class="products-common-create-upload-wrapper">
                                <label for="product_picture" class="products-common-create-file-btn">Update Main Photo</label>
                                <input type="file" name="product_picture" id="product_picture" accept="image/jpeg,image/png,image/gif,image/webp" class="hidden">
                                <p class="products-common-create-help-text">Optional. JPEG, PNG, GIF, WebP. Max 800KB.</p>
                            </div>
                            <div class="products-common-create-upload-wrapper">
                                <label for="additional_photos" class="products-common-create-file-btn">Add Additional Photos (Max 3)</label>
                                <input type="file" name="additional_photos[]" id="additional_photos" accept="image/jpeg,image/png,image/gif,image/webp" multiple class="hidden">
                                <p class="products-common-create-help-text">Optional. Select up to 3 additional photos. JPEG, PNG, GIF, WebP. Max 800KB each.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="products-common-create-field">
                    <label for="name" class="products-common-create-label">Product Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}" class="products-common-create-input" required>
                </div>

                <div class="products-common-create-field">
                    <label for="description" class="products-common-create-label">Description</label>
                    <textarea name="description" id="description" class="products-common-create-textarea" required>{{ old('description', $product->description) }}</textarea>
                </div>

                <div class="products-common-create-field">
                    <label for="price" class="products-common-create-label">Price (USD)</label>
                    <div class="products-common-create-price-wrapper">
                        <div class="products-common-create-price-symbol"><span>$</span></div>
                        <input type="number" name="price" id="price" required step="0.01" min="0" class="products-common-create-price-input" value="{{ old('price', $product->price) }}">
                    </div>
                </div>

                <div class="products-common-create-field">
                    <label for="category_id" class="products-common-create-label">Category</label>
                    <select name="category_id" id="category_id" required class="products-common-create-select">
                        <option value="">Select a category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                            @foreach($category->children as $subcategory)
                                <option value="{{ $subcategory->id }}" {{ old('category_id', $product->category_id) == $subcategory->id ? 'selected' : '' }}>
                                    -- {{ $subcategory->name }}
                                </option>
                            @endforeach
                        @endforeach
                    </select>
                </div>

                <div class="products-common-create-field">
                    <label for="stock_amount" class="products-common-create-label">Stock Amount</label>
                    <input type="number" name="stock_amount" id="stock_amount" required min="0" max="999999" class="products-common-create-input" value="{{ old('stock_amount', $product->stock_amount) }}">
                </div>

                <div class="products-common-create-field">
                    <label for="measurement_unit" class="products-common-create-label">Measurement Unit</label>
                    <select name="measurement_unit" id="measurement_unit" required class="products-common-create-select">
                        <option value="">Select a measurement unit</option>
                        @foreach($measurementUnits as $value => $label)
                            <option value="{{ $value }}" {{ old('measurement_unit', $product->measurement_unit) == $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="products-common-create-shipping-grid">
                    <div class="products-common-create-field">
                        <label for="ships_from" class="products-common-create-label">From:</label>
                        <select name="ships_from" id="ships_from" required class="products-common-create-select">
                            @foreach($countries as $country)
                                <option value="{{ $country }}" {{ old('ships_from', $product->ships_from) == $country ? 'selected' : '' }}>
                                    {{ $country }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="products-common-create-field">
                        <label for="ships_to" class="products-common-create-label">To:</label>
                        <select name="ships_to" id="ships_to" required class="products-common-create-select">
                            @foreach($countries as $country)
                                <option value="{{ $country }}" {{ old('ships_to', $product->ships_to) == $country ? 'selected' : '' }}>
                                    {{ $country }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="products-common-create-section">
                    <h3 class="products-common-create-section-title">
                        @if($product->type === 'deaddrop')
                            Pickup Options
                        @else
                            Delivery Options
                        @endif
                    </h3>
                    <p class="products-common-create-section-desc">
                        Add between 1 and 4 {{ $product->type === 'deaddrop' ? 'pickup' : 'delivery' }} options. At least one option is required.
                    </p>

                    @for ($i = 0; $i < 4; $i++)
                        <div class="products-common-create-option-card">
                            <h4 class="products-common-create-option-title">
                                {{ $product->type === 'deaddrop' ? 'Pickup' : 'Delivery' }} Option {{ $i + 1 }}
                            </h4>

                            <div class="products-common-create-field">
                                <label for="delivery_options_{{ $i }}_description" class="products-common-create-label">Description</label>
                                <input type="text" name="delivery_options[{{ $i }}][description]" id="delivery_options_{{ $i }}_description" class="products-common-create-input" value="{{ old('delivery_options.'.$i.'.description', $product->delivery_options[$i]['description'] ?? '') }}" {{ $i === 0 ? 'required' : '' }}>
                            </div>

                            <div class="products-common-create-field">
                                <label for="delivery_options_{{ $i }}_price" class="products-common-create-label">Additional Price (USD)</label>
                                <div class="products-common-create-price-wrapper">
                                    <div class="products-common-create-price-symbol"><span>$</span></div>
                                    <input type="number" name="delivery_options[{{ $i }}][price]" id="delivery_options_{{ $i }}_price" step="0.01" min="0" class="products-common-create-price-input" value="{{ old('delivery_options.'.$i.'.price', $product->delivery_options[$i]['price'] ?? '') }}" {{ $i === 0 ? 'required' : '' }}>
                                </div>
                            </div>
                        </div>
                    @endfor
                </div>

                <div class="products-common-create-section">
                    <h3 class="products-common-create-section-title">Bulk Options</h3>
                    <p class="products-common-create-section-desc">
                        Optionally add up to 8 bulk purchase options. Leave empty if not offering bulk pricing.
                    </p>

                    @for ($i = 0; $i < 8; $i++)
                        <div class="products-common-create-option-card">
                            <h4 class="products-common-create-option-title">Bulk Option {{ $i + 1 }}</h4>

                            <div class="products-common-create-field">
                                <label for="bulk_options_{{ $i }}_amount" class="products-common-create-label">Bulk Amount</label>
                                <input type="number" name="bulk_options[{{ $i }}][amount]" id="bulk_options_{{ $i }}_amount" step="0.01" min="0" class="products-common-create-input" value="{{ old('bulk_options.'.$i.'.amount', $product->bulk_options[$i]['amount'] ?? '') }}">
                            </div>

                            <div class="products-common-create-field">
                                <label for="bulk_options_{{ $i }}_price" class="products-common-create-label">Bulk Price (USD)</label>
                                <div class="products-common-create-price-wrapper">
                                    <div class="products-common-create-price-symbol"><span>$</span></div>
                                    <input type="number" name="bulk_options[{{ $i }}][price]" id="bulk_options_{{ $i }}_price" step="0.01" min="0" class="products-common-create-price-input" value="{{ old('bulk_options.'.$i.'.price', $product->bulk_options[$i]['price'] ?? '') }}">
                                </div>
                            </div>
                        </div>
                    @endfor
                </div>

                <div class="products-common-create-submit-wrapper">
                    <button type="submit" class="products-common-create-submit-btn">
                        Update {{ ucfirst($product->type) }} Product
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
