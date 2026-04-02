@extends('layouts.app')

@section('content')

<style>
    :root {
        --color-primary: #1a7a99;
        --color-primary-light: #2a9db8;
        --color-text-primary: #333333;
        --color-text-secondary: #666666;
        --color-bg-primary: #f5f5f5;
        --color-bg-secondary: #ffffff;
        --color-border: #e0e0e0;
        --color-accent: #208088;
        --radius-base: 8px;
        --radius-lg: 12px;
        --spacing-sm: 8px;
        --spacing-md: 12px;
        --spacing-lg: 16px;
        --spacing-xl: 24px;
    }
    
    .products-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: var(--spacing-xl);
        background-color: var(--color-bg-primary);
    }
    
    .filter-card {
        background: var(--color-bg-secondary);
        border-radius: var(--radius-lg);
        border: 1px solid var(--color-border);
        padding: var(--spacing-xl);
        margin-bottom: var(--spacing-xl);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    }
    
    .filter-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: var(--spacing-lg);
        margin-bottom: var(--spacing-lg);
    }
    
    .filter-left {
        display: flex;
        flex-direction: column;
    }
    
    .filter-right {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: var(--spacing-lg);
    }
    
    .form-group {
        display: flex;
        flex-direction: column;
        gap: var(--spacing-sm);
    }
    
    .form-label {
        color: var(--color-text-primary);
        font-weight: 600;
        font-size: 12px;
        text-transform: uppercase;
    }
    
    .form-input,
    .form-select,
    .products-select {
        padding: var(--spacing-md) var(--spacing-lg);
        border: 1px solid var(--color-border);
        border-radius: var(--radius-base);
        font-size: 14px;
        color: var(--color-text-primary);
        background: var(--color-bg-secondary);
        font-family: inherit;
        transition: border-color 0.3s ease;
        box-sizing: border-box;
    }
    
    .form-input:focus,
    .form-select:focus,
    .products-select:focus {
        outline: none;
        border-color: var(--color-primary);
        box-shadow: 0 0 0 2px rgba(26, 122, 153, 0.1);
    }
    
    .form-input::placeholder {
        color: var(--color-text-secondary);
    }
    
    .search-container {
        margin: var(--spacing-xl) 0;
    }
    
    .main-search {
        display: flex;
        flex-direction: column;
        gap: var(--spacing-sm);
    }
    
    .main-search-label {
        color: var(--color-text-primary);
        font-weight: 600;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    
    .main-search-input {
        padding: var(--spacing-md) var(--spacing-lg);
        border: 1px solid var(--color-border);
        border-radius: var(--radius-base);
        font-size: 14px;
        color: var(--color-text-primary);
        background: var(--color-bg-secondary);
        font-family: inherit;
        transition: border-color 0.3s ease;
        box-sizing: border-box;
    }
    
    .main-search-input:focus {
        outline: none;
        border-color: var(--color-primary);
        box-shadow: 0 0 0 2px rgba(26, 122, 153, 0.1);
    }
    
    .main-search-input::placeholder {
        color: var(--color-text-secondary);
    }
    
    .filter-actions {
        display: flex;
        gap: var(--spacing-lg);
        justify-content: flex-end;
        margin-top: var(--spacing-lg);
    }
    
    .btn {
        padding: var(--spacing-md) var(--spacing-lg);
        border: none;
        border-radius: var(--radius-base);
        font-weight: 600;
        cursor: pointer;
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        transition: all 0.3s ease;
    }
    
    .btn-primary {
        background: var(--color-primary);
        color: white;
    }
    
    .btn-primary:hover {
        background: var(--color-primary-light);
    }
    
    .btn-primary:active {
        transform: scale(0.98);
    }
    
    .btn-secondary {
        background: var(--color-bg-primary);
        color: var(--color-text-primary);
        border: 1px solid var(--color-border);
        text-decoration: none;
        display: inline-block;
        text-align: center;
    }
    
    .btn-secondary:hover {
        background: var(--color-bg-secondary);
        border-color: var(--color-primary);
    }
    
    .products-empty {
        text-align: center;
        padding: var(--spacing-xl);
        color: var(--color-text-secondary);
        background: var(--color-bg-secondary);
        border-radius: var(--radius-lg);
        border: 1px solid var(--color-border);
    }

    /* Category option indentation */
    .category-option-level-1 {
        padding-left: 0;
    }

    .category-option-level-2 {
        padding-left: 1rem;
    }

    .category-option-level-3 {
        padding-left: 2rem;
    }
    
    @media (max-width: 1024px) {
        .filter-right {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    @media (max-width: 768px) {
        .products-container {
            padding: var(--spacing-lg);
        }
        
        .filter-row {
            grid-template-columns: 1fr;
        }
        
        .filter-right {
            grid-template-columns: 1fr;
        }
        
        .filter-actions {
            flex-direction: column;
        }
        
        .btn {
            width: 100%;
        }
    }
</style>

<div class="products-container">
    {{-- Search and Filter Form --}}
    <div class="filter-card">
        <form action="{{ route('products.index') }}" method="GET">
            {{-- Top Row: Vendor + Dropdowns --}}
            <div class="filter-row">
                <div class="filter-left">
                    <div class="form-group">
                        <label for="vendor" class="form-label">By Vendor</label>
                        <input type="text" 
                               name="vendor" 
                               id="vendor" 
                               value="{{ $filters['vendor'] ?? '' }}"
                               placeholder="Search vendor 🔎"
                               minlength="1"
                               maxlength="16"
                               class="form-input">
                    </div>
                </div>
                
                <div class="filter-right">
                    <div class="form-group">
                        <label for="type" class="form-label">By Product Type</label>
                        <select name="type" 
                                id="type" 
                                class="products-select">
                            <option value="">All Types</option>
                            <option value="digital" {{ ($currentType === 'digital') ? 'selected' : '' }}>Digital</option>
                            <option value="cargo" {{ ($currentType === 'cargo') ? 'selected' : '' }}>Cargo</option>
                            <option value="deaddrop" {{ ($currentType === 'deaddrop') ? 'selected' : '' }}>Dead Drop</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="category" class="form-label">By Category</label>
                        <select name="category" 
                                id="category" 
                                class="form-select">
                            <option value="">All Categories</option>
                            @php
                                function renderCategoryOptions($categories, $selectedId = null) {
                                    foreach ($categories as $category) {
                                        $isSelected = ($selectedId == $category->id) ? 'selected' : '';
                                        
                                        // Add proper spacing based on level
                                        $spacing = '';
                                        if ($category->level === 1) {
                                            $spacing = '';
                                        } elseif ($category->level === 2) {
                                            $spacing = '→ ';
                                        } elseif ($category->level === 3) {
                                            $spacing = '→ → ';
                                        }
                                        
                                        echo '<option value="' . $category->id . '" ' . $isSelected . '>' . $spacing . $category->name . '</option>';
                                        
                                        // Recursively render children
                                        if ($category->children->isNotEmpty()) {
                                            renderCategoryOptions($category->children, $selectedId);
                                        }
                                    }
                                }
                            @endphp
                            @foreach($categories as $category)
                                @php renderCategoryOptions([$category], $filters['category'] ?? null) @endphp
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="sort_price" class="form-label">Sort by Price</label>
                        <select name="sort_price" 
                                id="sort_price" 
                                class="products-select">
                            <option value="">Most Recent</option>
                            <option value="asc" {{ ($filters['sort_price'] ?? '') === 'asc' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="desc" {{ ($filters['sort_price'] ?? '') === 'desc' ? 'selected' : '' }}>Price: High to Low</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- Middle Row: Main Search --}}
            <div class="search-container">
                <div class="main-search">
                    <label for="search" class="main-search-label">Search Product</label>
                    <input type="text" 
                           name="search" 
                           id="search" 
                           value="{{ $filters['search'] ?? '' }}"
                           placeholder="Search by product title 🔎"
                           minlength="1"
                           maxlength="80"
                           class="main-search-input">
                </div>
            </div>

            {{-- Bottom Row: Buttons --}}
            <div class="filter-actions">
                <a href="{{ route('products.index') }}" class="btn btn-secondary">
                    Reset Filters
                </a>
                <button type="submit" class="btn btn-primary">
                    Apply Filters
                </button>
            </div>
        </form>
    </div>

    @if($products->isEmpty())
        <div class="products-empty">
            <p>No products found matching your criteria.</p>
        </div>
    @else
        <x-products 
            :products="$products"
        />
    @endif
</div>

@endsection
