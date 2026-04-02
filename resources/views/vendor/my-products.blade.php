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

    .my-products-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: var(--spacing-xl);
    }

    .my-products-card {
        background: var(--color-card-bg);
        border: 1px solid var(--color-border);
        border-radius: var(--radius-lg);
        padding: var(--spacing-xl);
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }

    .my-products-header {
        margin-bottom: var(--spacing-xl);
    }

    .my-products-header h1 {
        font-size: 28px;
        font-weight: 600;
        color: var(--color-text-primary);
        margin: 0;
    }

    .my-products-empty {
        text-align: center;
        padding: var(--spacing-xl) 0;
        color: var(--color-text-secondary);
    }

    .my-products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: var(--spacing-lg);
    }

    .product-card {
        background: var(--color-input-bg);
        border: 1px solid var(--color-border);
        border-radius: var(--radius-base);
        padding: var(--spacing-lg);
        transition: all 0.2s ease;
    }

    .product-card:hover {
        border-color: var(--color-accent);
        box-shadow: 0 2px 6px rgba(32, 128, 136, 0.1);
    }

    .product-name {
        font-size: 16px;
        font-weight: 600;
        color: var(--color-text-primary);
        margin: 0 0 var(--spacing-md) 0;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .product-name a {
        color: var(--color-accent);
        text-decoration: none;
    }

    .product-name a:hover {
        color: var(--color-accent-light);
    }

    .product-type {
        display: inline-block;
        font-size: 11px;
        font-weight: 600;
        padding: 4px 8px;
        border-radius: var(--radius-base);
        margin-bottom: var(--spacing-lg);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .product-type.digital {
        background: rgba(33, 150, 243, 0.1);
        color: #1976d2;
    }

    .product-type.cargo {
        background: rgba(76, 175, 80, 0.1);
        color: #388e3c;
    }

    .product-type.deaddrop {
        background: rgba(255, 193, 7, 0.1);
        color: #f57f17;
    }

    .product-actions {
        display: flex;
        gap: var(--spacing-md);
        flex-wrap: wrap;
    }

    .product-btn {
        flex: 1;
        min-width: 80px;
        padding: 8px 12px;
        border: 1px solid var(--color-border);
        background: transparent;
        color: var(--color-text-primary);
        border-radius: var(--radius-base);
        font-size: 12px;
        font-weight: 500;
        cursor: pointer;
        text-decoration: none;
        text-align: center;
        transition: all 0.2s ease;
    }

    .product-btn:hover {
        border-color: var(--color-accent);
        background: rgba(32, 128, 136, 0.05);
    }

    .product-btn-edit {
        color: var(--color-accent);
        border-color: var(--color-accent);
    }

    .product-btn-ad {
        color: var(--color-accent);
        border-color: var(--color-accent);
    }

    .product-btn-ad-disabled {
        color: var(--color-text-secondary);
        border-color: var(--color-border);
        cursor: not-allowed;
        opacity: 0.6;
    }

    .product-btn-delete {
        color: var(--color-error);
        border-color: var(--color-error);
    }

    @media (max-width: 768px) {
        .my-products-container {
            padding: var(--spacing-lg);
        }

        .my-products-grid {
            grid-template-columns: 1fr;
        }

        .my-products-header h1 {
            font-size: 24px;
        }
    }
</style>

<div class="my-products-container">
    <div class="my-products-card">
        <div class="my-products-header">
            <h1>My Products</h1>
        </div>
        
        @if($products->isEmpty())
            <div class="my-products-empty">
                <p>You haven't added any products yet.</p>
            </div>
        @else
            <div class="my-products-grid">
                @foreach($products as $product)
                    <div class="product-card">
                        <h3 class="product-name" title="{{ $product->name }}">
                            <a href="{{ route('products.show', $product->slug) }}">
                                {{ \Str::limit($product->name, 30) }}
                            </a>
                        </h3>
                        <span class="product-type {{ strtolower($product->type) }}">
                            {{ $product->type === 'deaddrop' ? 'Dead Drop' : ucfirst($product->type) }}
                        </span>
                        <div class="product-actions">
                            <a href="{{ route('vendor.products.edit', $product) }}" class="product-btn product-btn-edit">
                                Edit
                            </a>
                            @if($product->is_advertised)
                                <span class="product-btn product-btn-ad product-btn-ad-disabled">
                                    Advertised
                                </span>
                            @else
                                <a href="{{ route('vendor.advertisement.create', $product) }}" class="product-btn product-btn-ad">
                                    Advertisement
                                </a>
                            @endif
                            <form action="{{ route('vendor.products.destroy', $product) }}" method="POST" style="flex: 1; min-width: 80px;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="product-btn product-btn-delete" style="width: 100%;">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

@endsection