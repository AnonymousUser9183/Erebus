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

    .all-products-index-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: var(--spacing-xl);
    }

    .all-products-index-header {
        margin-bottom: var(--spacing-2xl);
        padding-bottom: var(--spacing-lg);
        border-bottom: 2px solid var(--color-accent-light);
    }

    .all-products-index-title {
        font-size: 32px;
        font-weight: 600;
        color: var(--color-text-primary);
        margin: 0;
    }

    .products-index-filter-card {
        background-color: var(--color-bg-secondary);
        border: 1px solid var(--color-border);
        border-radius: var(--radius);
        padding: var(--spacing-lg);
        margin-bottom: var(--spacing-xl);
    }

    .products-index-filter-row {
        display: grid;
        grid-template-columns: 1fr 2fr;
        gap: var(--spacing-lg);
        margin-bottom: var(--spacing-lg);
    }

    .products-index-filter-left {
        display: flex;
        flex-direction: column;
    }

    .products-index-filter-right {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: var(--spacing-md);
    }

    .products-index-form-group {
        display: flex;
        flex-direction: column;
        gap: var(--spacing-sm);
    }

    .products-index-label {
        font-size: 12px;
        font-weight: 600;
        color: var(--color-text-primary);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .products-index-input,
    .products-index-select {
        padding: var(--spacing-md);
        border: 1px solid var(--color-border);
        border-radius: var(--radius);
        font-size: 14px;
        color: var(--color-text-primary);
        background-color: var(--color-bg-primary);
        font-family: inherit;
        box-sizing: border-box;
        transition: border-color 0.3s ease;
    }

    .products-index-input:focus,
    .products-index-select:focus {
        outline: none;
        border-color: var(--color-accent);
        box-shadow: 0 0 0 2px rgba(32, 128, 136, 0.1);
    }

    .products-index-search-container {
        margin: var(--spacing-lg) 0;
        padding: var(--spacing-lg) 0;
        border-top: 1px solid var(--color-border);
        border-bottom: 1px solid var(--color-border);
    }

    .products-index-main-search {
        display: flex;
        flex-direction: column;
        gap: var(--spacing-sm);
    }

    .products-index-main-search-label {
        font-size: 12px;
        font-weight: 600;
        color: var(--color-text-primary);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .products-index-main-search-input {
        padding: var(--spacing-md);
        border: 1px solid var(--color-border);
        border-radius: var(--radius);
        font-size: 14px;
        color: var(--color-text-primary);
        background-color: var(--color-bg-primary);
        font-family: inherit;
        box-sizing: border-box;
        transition: border-color 0.3s ease;
    }

    .products-index-main-search-input:focus {
        outline: none;
        border-color: var(--color-accent);
        box-shadow: 0 0 0 2px rgba(32, 128, 136, 0.1);
    }

    .products-index-filter-actions {
        display: flex;
        gap: var(--spacing-md);
        justify-content: flex-end;
    }

    .products-index-button {
        padding: var(--spacing-md) var(--spacing-lg);
        border: none;
        border-radius: var(--radius);
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        text-decoration: none;
        transition: background-color 0.3s ease;
    }

    .products-index-button-primary {
        background-color: var(--color-accent);
        color: #ffffff;
    }

    .products-index-button-primary:hover {
        background-color: var(--color-accent-light);
    }

    .products-index-button-secondary {
        background-color: var(--color-text-secondary);
        color: #ffffff;
    }

    .products-index-button-secondary:hover {
        background-color: #4b5563;
    }

    .all-products-index-table-container {
        overflow-x: auto;
    }

    .all-products-index-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
        margin-bottom: var(--spacing-xl);
    }

    .all-products-index-table thead {
        background-color: var(--color-bg-primary);
        border-bottom: 2px solid var(--color-border);
    }

    .all-products-index-table th {
        padding: var(--spacing-md);
        text-align: left;
        font-weight: 600;
        color: var(--color-text-primary);
    }

    .all-products-index-table td {
        padding: var(--spacing-md);
        border-bottom: 1px solid var(--color-border);
        color: var(--color-text-primary);
    }

    .all-products-index-table tbody tr:hover {
        background-color: var(--color-bg-primary);
    }

    .all-products-index-product-name {
        font-weight: 500;
    }

    .all-products-index-product-link {
        color: var(--color-accent);
        text-decoration: none;
    }

    .all-products-index-product-link:hover {
        text-decoration: underline;
    }

    .all-products-index-type-badge {
        display: inline-block;
        padding: var(--spacing-xs) var(--spacing-sm);
        border-radius: var(--radius);
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .all-products-index-type-badge-digital {
        background-color: #dbeafe;
        color: #1e40af;
        border: 1px solid #93c5fd;
    }

    .all-products-index-type-badge-cargo {
        background-color: #fef3c7;
        color: #854d0e;
        border: 1px solid #fbbf24;
    }

    .all-products-index-type-badge-deaddrop {
        background-color: #f3e8ff;
        color: #6b21a8;
        border: 1px solid #d8b4fe;
    }

    .all-products-index-owner-badge {
        font-weight: 500;
        color: var(--color-accent);
    }

    .all-products-index-actions {
        display: flex;
        gap: var(--spacing-sm);
        flex-wrap: wrap;
    }

    .all-products-index-btn {
        display: inline-block;
        padding: var(--spacing-xs) var(--spacing-sm);
        border: none;
        border-radius: var(--radius);
        font-size: 12px;
        font-weight: 500;
        cursor: pointer;
        text-decoration: none;
        transition: background-color 0.3s ease;
    }

    .all-products-index-btn-edit {
        background-color: var(--color-accent);
        color: #ffffff;
    }

    .all-products-index-btn-edit:hover {
        background-color: var(--color-accent-light);
    }

    .all-products-index-btn-feature {
        background-color: #10b981;
        color: #ffffff;
    }

    .all-products-index-btn-feature:hover {
        background-color: #059669;
    }

    .all-products-index-btn-unfeature {
        background-color: #f59e0b;
        color: #ffffff;
    }

    .all-products-index-btn-unfeature:hover {
        background-color: #d97706;
    }

    .all-products-index-btn-delete {
        background-color: #ef4444;
        color: #ffffff;
    }

    .all-products-index-btn-delete:hover {
        background-color: #dc2626;
    }

    .all-products-index-empty {
        background-color: var(--color-bg-primary);
        border: 1px solid var(--color-border);
        border-radius: var(--radius);
        padding: var(--spacing-lg);
        text-align: center;
        color: var(--color-text-secondary);
        font-size: 14px;
    }

    .all-products-index-pagination {
        margin-top: var(--spacing-xl);
        display: flex;
        justify-content: center;
    }

    @media (max-width: 768px) {
        .all-products-index-container {
            padding: var(--spacing-md);
        }

        .all-products-index-title {
            font-size: 24px;
        }

        .products-index-filter-row {
            grid-template-columns: 1fr;
        }

        .products-index-filter-right {
            grid-template-columns: 1fr;
        }

        .products-index-filter-actions {
            justify-content: flex-start;
            flex-direction: column;
        }

        .products-index-button {
            width: 100%;
        }

        .all-products-index-table {
            font-size: 13px;
        }

        .all-products-index-table th,
        .all-products-index-table td {
            padding: var(--spacing-sm);
        }

        .all-products-index-actions {
            flex-direction: column;
        }

        .all-products-index-btn {
            width: 100%;
        }
    }
</style>

<div class="all-products-index-container">
    <div class="all-products-index-header">
        <h1 class="all-products-index-title">All Products</h1>
    </div>

    <div class="products-index-filter-card">
        <form action="{{ route('admin.all-products') }}" method="GET">
            <div class="products-index-filter-row">
                <div class="products-index-filter-left">
                    <div class="products-index-form-group">
                        <label for="vendor" class="products-index-label">By Vendor</label>
                        <input type="text" name="vendor" id="vendor" value="{{ $filters['vendor'] ?? '' }}" placeholder="Search vendor 🔎" maxlength="50" class="products-index-input">
                    </div>
                </div>

                <div class="products-index-filter-right">
                    <div class="products-index-form-group">
                        <label for="type" class="products-index-label">By Product Type</label>
                        <select name="type" id="type" class="products-index-select">
                            <option value="">All Types</option>
                            <option value="digital" {{ ($currentType === 'digital') ? 'selected' : '' }}>Digital</option>
                            <option value="cargo" {{ ($currentType === 'cargo') ? 'selected' : '' }}>Cargo</option>
                            <option value="deaddrop" {{ ($currentType === 'deaddrop') ? 'selected' : '' }}>Dead Drop</option>
                        </select>
                    </div>

                    <div class="products-index-form-group">
                        <label for="category" class="products-index-label">By Category</label>
                        <select name="category" id="category" class="products-index-select">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ ($filters['category'] ?? '') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="products-index-form-group">
                        <label for="sort_price" class="products-index-label">Sort by Price</label>
                        <select name="sort_price" id="sort_price" class="products-index-select">
                            <option value="">Most Recent</option>
                            <option value="asc" {{ ($filters['sort_price'] ?? '') === 'asc' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="desc" {{ ($filters['sort_price'] ?? '') === 'desc' ? 'selected' : '' }}>Price: High to Low</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="products-index-search-container">
                <div class="products-index-main-search">
                    <label for="search" class="products-index-main-search-label">Search Product</label>
                    <input type="text" name="search" id="search" value="{{ $filters['search'] ?? '' }}" placeholder="Search by product title 🔎" maxlength="100" class="products-index-main-search-input">
                </div>
            </div>

            <div class="products-index-filter-actions">
                <a href="{{ route('admin.all-products') }}" class="products-index-button products-index-button-secondary">
                    Reset Filters
                </a>
                <button type="submit" class="products-index-button products-index-button-primary">
                    Apply Filters
                </button>
            </div>
        </form>
    </div>

    @if($products->isEmpty())
        <div class="all-products-index-empty">
            <p>No products found.</p>
        </div>
    @else
        <div class="all-products-index-table-container">
            <table class="all-products-index-table">
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Type</th>
                        <th>Owner</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                        <tr>
                            <td class="all-products-index-product-name">
                                <a href="{{ route('products.show', $product->slug) }}" class="all-products-index-product-link">
                                    {{ \Str::limit($product->name, 60, '...') }}
                                </a>
                            </td>
                            <td class="all-products-index-product-type">
                                <span class="all-products-index-type-badge all-products-index-type-badge-{{ $product->type }}">
                                    {{ $product->type === 'deaddrop' ? 'Dead Drop' : ucfirst($product->type) }}
                                </span>
                            </td>
                            <td class="all-products-index-product-owner">
                                <span class="all-products-index-owner-badge">{{ $product->user->username }}</span>
                            </td>
                            <td class="all-products-index-actions">
                                <a href="{{ route('admin.products.edit', $product) }}" class="all-products-index-btn all-products-index-btn-edit">Edit</a>

                                @if($product->isFeatured())
                                    <form action="{{ route('admin.products.unfeature', $product) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="all-products-index-btn all-products-index-btn-unfeature">
                                            Unfeature
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.products.feature', $product) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="all-products-index-btn all-products-index-btn-feature">
                                            Feature
                                        </button>
                                    </form>
                                @endif

                                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="all-products-index-btn all-products-index-btn-delete">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="all-products-index-pagination">
            {{ $products->links() }}
        </div>
    @endif
</div>

@endsection
