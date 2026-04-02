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
        --spacing-md: 12px;
        --spacing-lg: 16px;
        --spacing-xl: 24px;
        --radius-base: 8px;
        --radius-lg: 12px;
        --color-success: #4caf50;
        --color-warning: #ffc107;
        --color-error: #f44336;
        --color-info: #2196f3;
    }

    .product-edit-container {
        max-width: 900px;
        margin: 0 auto;
        padding: var(--spacing-xl);
    }

    .product-edit-card {
        background: var(--color-card-bg);
        border: 1px solid var(--color-border);
        border-radius: var(--radius-lg);
        padding: var(--spacing-xl);
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }

    .product-edit-title {
        font-size: 24px;
        font-weight: 600;
        color: var(--color-text-primary);
        margin: 0 0 var(--spacing-xl) 0;
    }

    .product-edit-section {
        margin-bottom: var(--spacing-xl);
        padding-bottom: var(--spacing-xl);
        border-bottom: 1px solid var(--color-border);
    }

    .product-edit-section:last-child {
        border-bottom: none;
    }

    .product-edit-section-title {
        font-size: 16px;
        font-weight: 600;
        color: var(--color-text-primary);
        margin: 0 0 var(--spacing-lg) 0;
    }

    .visibility-toggle-group {
        background: var(--color-input-bg);
        padding: var(--spacing-lg);
        border-radius: var(--radius-base);
        margin-bottom: var(--spacing-lg);
    }

    .visibility-toggle-label {
        display: flex;
        align-items: center;
        gap: var(--spacing-md);
        font-size: 14px;
        font-weight: 500;
        color: var(--color-text-primary);
        margin-bottom: var(--spacing-md);
    }

    .visibility-toggle-input {
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

    .visibility-toggle-input:checked {
        background: var(--color-accent);
    }

    .visibility-toggle-input:after {
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

    .visibility-toggle-input:checked:after {
        left: 24px;
    }

    .visibility-status {
        font-size: 13px;
        color: var(--color-text-secondary);
        margin-top: var(--spacing-md);
    }

    .visibility-status.active {
        color: var(--color-success);
    }

    .product-edit-field {
        margin-bottom: var(--spacing-lg);
    }

    .product-edit-label {
        display: block;
        font-size: 14px;
        font-weight: 500;
        color: var(--color-text-primary);
        margin-bottom: var(--spacing-md);
    }

    .product-edit-input,
    .product-edit-select,
    .product-edit-textarea {
        width: 100%;
        padding: var(--spacing-md);
        border: 1px solid var(--color-border);
        border-radius: var(--radius-base);
        font-family: inherit;
        font-size: 14px;
        color: var(--color-text-primary);
        background: var(--color-input-bg);
        transition: all 0.2s ease;
        box-sizing: border-box;
    }

    .product-edit-input:focus,
    .product-edit-select:focus,
    .product-edit-textarea:focus {
        outline: none;
        border-color: var(--color-accent);
        background: var(--color-card-bg);
        box-shadow: 0 0 0 3px rgba(32, 128, 136, 0.1);
    }

    .product-edit-textarea {
        resize: vertical;
        min-height: 120px;
    }

    .product-edit-input:disabled {
        background: var(--color-input-bg);
        cursor: not-allowed;
        opacity: 0.7;
    }

    .product-edit-price-wrapper {
        display: flex;
        align-items: center;
        gap: var(--spacing-md);
    }

    .product-edit-price-symbol {
        background: var(--color-input-bg);
        padding: var(--spacing-md);
        border: 1px solid var(--color-border);
        border-radius: var(--radius-base);
        font-weight: 600;
        color: var(--color-text-primary);
        min-width: 40px;
        text-align: center;
    }

    .product-edit-price-input {
        flex: 1;
    }

    .product-photos-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: var(--spacing-lg);
        margin-bottom: var(--spacing-lg);
    }

    .product-photo {
        border: 1px solid var(--color-border);
        border-radius: var(--radius-base);
        overflow: hidden;
        aspect-ratio: 1;
    }

    .product-photo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .product-edit-submit-btn {
        background: var(--color-accent);
        color: white;
        padding: 12px 24px;
        border: none;
        border-radius: var(--radius-base);
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        width: 100%;
        box-sizing: border-box;
    }

    .product-edit-submit-btn:hover {
        background: var(--color-accent-light);
        box-shadow: 0 4px 12px rgba(32, 128, 136, 0.2);
    }

    .product-option-card {
        background: var(--color-input-bg);
        padding: var(--spacing-lg);
        border-radius: var(--radius-base);
        margin-bottom: var(--spacing-lg);
        border: 1px solid var(--color-border);
    }

    .product-option-title {
        font-size: 14px;
        font-weight: 600;
        color: var(--color-text-primary);
        margin: 0 0 var(--spacing-md) 0;
    }

    .product-edit-help-text {
        font-size: 12px;
        color: var(--color-text-secondary);
        margin-top: var(--spacing-md);
    }

    @media (max-width: 768px) {
        .product-edit-container {
            padding: var(--spacing-lg);
        }

        .product-edit-card {
            padding: var(--spacing-lg);
        }

        .product-edit-title {
            font-size: 20px;
        }

        .product-photos-container {
            grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
        }

        .product-edit-price-wrapper {
            flex-direction: column;
            align-items: stretch;
        }

        .product-edit-price-symbol {
            min-width: auto;
        }
    }
</style>

<div class="product-edit-container">
    <div class="product-edit-card">
        <h2 class="product-edit-title">Edit {{ ucfirst($product->type) }} Product</h2>

        <form action="{{ route('vendor.products.update', $product) }}" method="POST">
            @csrf
            @method('PATCH')

            <div class="product-edit-section">
                <h3 class="product-edit-section-title">Product Visibility</h3>
                <div class="visibility-toggle-group">
                    <label class="visibility-toggle-label">
                        <span>Make Product Visible</span>
                        <input type="checkbox" name="active" value="1" class="visibility-toggle-input" {{ $product->active ? 'checked' : '' }}>
                    </label>
                    <div class="visibility-status {{ $product->active ? 'active' : '' }}">
                        {{ $product->active ? '✓ This product is visible to customers' : '✗ This product is hidden from customers' }}
                    </div>
                    <p class="product-edit-help-text" style="margin-top: var(--spacing-md);">
                        When inactive, the product will be hidden from all marketplace listings.
                    </p>
                </div>
            </div>

            <div class="product-edit-section">
                <h3 class="product-edit-section-title">Product Photos</h3>
                <div class="product-photos-container">
                    <div class="product-photo">
                        <img src="{{ $product->product_picture_url }}" alt="Product Picture">
                    </div>
                    @foreach($product->additional_photos_urls as $photoUrl)
                        <div class="product-photo">
                            <img src="{{ $photoUrl }}" alt="Additional Product Picture">
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="product-edit-section">
                <h3 class="product-edit-section-title">📝 Product Details</h3>

                <div class="product-edit-field">
                    <label class="product-edit-label">Product Name (Read-only)</label>
                    <input type="text" value="{{ $product->name }}" class="product-edit-input" disabled>
                </div>

                <div class="product-edit-field">
                    <label for="description" class="product-edit-label">Description</label>
                    <textarea name="description" id="description" class="product-edit-textarea" required minlength="4" maxlength="2400">{{ old('description', $product->description) }}</textarea>
                </div>

                <div class="product-edit-field">
                    <label for="price" class="product-edit-label">Price (USD)</label>
                    <div class="product-edit-price-wrapper">
                        <div class="product-edit-price-symbol">$</div>
                        <input type="number" name="price" id="price" class="product-edit-input product-edit-price-input" required step="0.01" min="0" max="80000" value="{{ old('price', $product->price) }}">
                    </div>
                </div>

                <div class="product-edit-field">
                    <label for="category_id" class="product-edit-label">Category</label>
                    <select name="category_id" id="category_id" class="product-edit-select" required>
                        <option value="">Select a category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" @if(old('category_id', $product->category_id) == $category->id) selected @endif>
                                {{ $category->name }}
                            </option>
                            @foreach($category->children as $child)
                                <option value="{{ $child->id }}" @if(old('category_id', $product->category_id) == $child->id) selected @endif>
                                    {{ $category->name }} - {{ $child->name }}
                                </option>
                                @foreach($child->children as $grandchild)
                                    <option value="{{ $grandchild->id }}" @if(old('category_id', $product->category_id) == $grandchild->id) selected @endif>
                                        {{ $category->name }} - {{ $child->name }} - {{ $grandchild->name }}
                                    </option>
                                @endforeach
                            @endforeach
                        @endforeach
                    </select>
                </div>

                <div class="product-edit-field">
                    <label for="stock_amount" class="product-edit-label">Stock Amount</label>
                    <input type="number" name="stock_amount" id="stock_amount" class="product-edit-input" required min="0" max="80000" value="{{ old('stock_amount', $product->stock_amount) }}">
                </div>

                <div class="product-edit-field">
                    <label for="measurement_unit" class="product-edit-label">Measurement Unit</label>
                    <select name="measurement_unit" id="measurement_unit" class="product-edit-select" required>
                        <option value="">Select a measurement unit</option>
                        @foreach($measurementUnits as $value => $label)
                            <option value="{{ $value }}" @if(old('measurement_unit', $product->measurement_unit) == $value) selected @endif>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="product-edit-section">
                <h3 class="product-edit-section-title">📍 Shipping Options</h3>

                <div class="product-edit-field">
                    <label for="ships_from" class="product-edit-label">Ships From</label>
                    <select name="ships_from" id="ships_from" class="product-edit-select" required>
                        <option value="">-- Select Country --</option>
                        @foreach($countries as $country)
                            <option value="{{ $country }}" @if(old('ships_from', $product->ships_from) === $country) selected @endif>
                                {{ $country }}
                            </option>
                        @endforeach
                    </select>
                    @error('ships_from')
                        <p class="product-edit-help-text" style="color: var(--color-error);">{{ $message }}</p>
                    @enderror
                </div>

                <div class="product-edit-field">
                    <label for="ships_to" class="product-edit-label">Ships To</label>
                    <select name="ships_to" id="ships_to" class="product-edit-select" required>
                        <option value="">-- Select Country --</option>
                        @foreach($countries as $country)
                            <option value="{{ $country }}" @if(old('ships_to', $product->ships_to) === $country) selected @endif>
                                {{ $country }}
                            </option>
                        @endforeach
                    </select>
                    @error('ships_to')
                        <p class="product-edit-help-text" style="color: var(--color-error);">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="product-edit-section">
                <h3 class="product-edit-section-title">📦 Delivery Options</h3>
                <p class="product-edit-help-text">Edit delivery options with descriptions and prices.</p>
                
                @for ($i = 0; $i < 4; $i++)
                    <div class="product-option-card">
                        <h4 class="product-option-title">Delivery Option {{ $i + 1 }}</h4>
                        <div class="product-edit-field">
                            <label for="delivery_options_{{ $i }}_description" class="product-edit-label">Description</label>
                            <input type="text" name="delivery_options[{{ $i }}][description]" id="delivery_options_{{ $i }}_description" class="product-edit-input" minlength="4" maxlength="160" placeholder="e.g., Standard Shipping (5-7 days)" value="{{ old('delivery_options.'.$i.'.description', $product->delivery_options[$i]['description'] ?? '') }}">
                        </div>
                        <div class="product-edit-field">
                            <label for="delivery_options_{{ $i }}_price" class="product-edit-label">Shipping Price (USD)</label>
                            <div class="product-edit-price-wrapper">
                                <div class="product-edit-price-symbol">$</div>
                                <input type="number" name="delivery_options[{{ $i }}][price]" id="delivery_options_{{ $i }}_price" class="product-edit-input product-edit-price-input" step="0.01" min="0" max="80000" placeholder="0.00" value="{{ old('delivery_options.'.$i.'.price', $product->delivery_options[$i]['price'] ?? '') }}">
                            </div>
                        </div>
                    </div>
                @endfor
                @error('delivery_options')
                    <p class="product-edit-help-text" style="color: var(--color-error);">{{ $message }}</p>
                @enderror
            </div>

            <div class="product-edit-section">
                <h3 class="product-edit-section-title">📦 Bulk Options</h3>
                <p class="product-edit-help-text">Edit bulk purchase options. Leave empty if not offering bulk pricing.</p>
                
                @for ($i = 0; $i < 8; $i++)
                    <div class="product-option-card">
                        <h4 class="product-option-title">Bulk Option {{ $i + 1 }}</h4>
                        <div class="product-edit-field">
                            <label for="bulk_options_{{ $i }}_amount" class="product-edit-label">Bulk Amount (Quantity)</label>
                            <input type="number" name="bulk_options[{{ $i }}][amount]" id="bulk_options_{{ $i }}_amount" class="product-edit-input" step="1" min="0" max="80000" placeholder="Enter bulk quantity" value="{{ old('bulk_options.'.$i.'.amount', $product->bulk_options[$i]['amount'] ?? '') }}">
                        </div>
                        <div class="product-edit-field">
                            <label for="bulk_options_{{ $i }}_price" class="product-edit-label">Bulk Price (USD)</label>
                            <div class="product-edit-price-wrapper">
                                <div class="product-edit-price-symbol">$</div>
                                <input type="number" name="bulk_options[{{ $i }}][price]" id="bulk_options_{{ $i }}_price" class="product-edit-input product-edit-price-input" step="0.01" min="0" max="80000" placeholder="0.00" value="{{ old('bulk_options.'.$i.'.price', $product->bulk_options[$i]['price'] ?? '') }}">
                            </div>
                        </div>
                    </div>
                @endfor
            </div>

            <button type="submit" class="product-edit-submit-btn">Update {{ ucfirst($product->type) }} Product</button>
        </form>
    </div>
</div>

@endsection
