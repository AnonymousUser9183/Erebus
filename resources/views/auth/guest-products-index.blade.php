@extends('layouts.auth')

@section('content')

<style>
    :root {
        --color-primary: 1a7a99;
        --color-primary-light: 2a9db8;
        --color-text-primary: 333333;
        --color-text-secondary: 666666;
        --color-bg-primary: f5f5f5;
        --color-bg-secondary: ffffff;
        --color-border: e0e0e0;
        --color-accent: 208088;
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

    /* WELCOME SECTION */
    .welcome-header {
        text-align: center;
        margin-bottom: var(--spacing-2xl);
    }

    .welcome-header h1 {
        margin: 0 0 var(--spacing-md) 0;
        font-size: 32px;
        color: var(--color-primary);
        font-weight: 700;
        letter-spacing: -0.5px;
    }

    .welcome-header p {
        margin: 0 0 var(--spacing-lg) 0;
        font-size: 16px;
        color: var(--color-text-secondary);
        line-height: 1.6;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
    }

    .welcome-header-cta {
        display: inline-block;
        background: var(--color-primary);
        color: white;
        padding: var(--spacing-md) var(--spacing-lg);
        border-radius: var(--radius-base);
        text-decoration: none;
        font-weight: 600;
        font-size: 13px;
        transition: all 0.3s ease;
    }

    .welcome-header-cta:hover {
        background: var(--color-primary-light);
        transform: translateY(-2px);
    }

    /* SEARCH SECTION */
    .search-section {
        background: var(--color-bg-secondary);
        border-radius: var(--radius-lg);
        border: 1px solid var(--color-border);
        padding: var(--spacing-xl);
        margin-bottom: var(--spacing-xl);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    }

    .search-form {
        display: grid;
        grid-template-columns: 1fr 250px auto;
        gap: var(--spacing-lg);
        align-items: flex-end;
    }

    .search-group {
        display: flex;
        flex-direction: column;
        gap: var(--spacing-sm);
    }

    .search-label {
        color: var(--color-text-primary);
        font-weight: 600;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .search-input {
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

    .search-input:focus {
        outline: none;
        border-color: var(--color-primary);
        box-shadow: 0 0 0 2px rgba(26, 122, 153, 0.1);
    }

    .search-input::placeholder {
        color: var(--color-text-secondary);
    }

    .search-select {
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

    .search-select:focus {
        outline: none;
        border-color: var(--color-primary);
        box-shadow: 0 0 0 2px rgba(26, 122, 153, 0.1);
    }

    .search-button {
        padding: var(--spacing-md) var(--spacing-xl);
        background: var(--color-primary);
        color: white;
        border: none;
        border-radius: var(--radius-base);
        font-weight: 600;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        cursor: pointer;
        transition: all 0.3s ease;
        white-space: nowrap;
    }

    .search-button:hover {
        background: var(--color-primary-light);
        transform: translateY(-2px);
    }

    .search-button:active {
        transform: translateY(0);
    }

    .reset-button {
        padding: var(--spacing-md) var(--spacing-lg);
        background: transparent;
        color: var(--color-primary);
        border: 1px solid var(--color-border);
        border-radius: var(--radius-base);
        font-weight: 600;
        font-size: 12px;
        text-decoration: none;
        display: inline-block;
        text-align: center;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .reset-button:hover {
        background: var(--color-bg-primary);
        border-color: var(--color-primary);
    }

    .products-empty {
        text-align: center;
        padding: var(--spacing-2xl);
        color: var(--color-text-secondary);
        background: var(--color-bg-secondary);
        border-radius: var(--radius-lg);
        border: 1px solid var(--color-border);
    }

    /* RESPONSIVE */
    @media (max-width: 1024px) {
        .search-form {
            grid-template-columns: 1fr;
            gap: var(--spacing-md);
        }

        .search-button,
        .reset-button {
            width: 100%;
        }
    }

    @media (max-width: 768px) {
        .products-container {
            padding: var(--spacing-lg);
        }

        .welcome-header h1 {
            font-size: 24px;
        }

        .welcome-header p {
            font-size: 14px;
        }

        .search-section {
            padding: var(--spacing-lg);
        }

        .search-form {
            grid-template-columns: 1fr;
        }

        .search-button,
        .reset-button {
            width: 100%;
        }
    }
</style>

<div class="products-container">
    {{-- Welcome Header --}}
    <div class="welcome-header">
        <h1>Erebus Marketplace Script</h1>
        <p>Developed by The Erebus Development Team based off of Kabus Marketplace Script by Sukunetsiz.</p>
        <a href="{{ route('register') }}" class="welcome-header-cta">Register Now</a>
    </div>

    {{-- Search Section --}}
    <div class="search-section">
        <form action="{{ route('guest-products.index') }}" method="GET" class="search-form">
            {{-- Search by Product Title --}}
            <div class="search-group">
                <label for="search" class="search-label">Search Products</label>
                <input type="text" name="search" id="search" value="{{ $filters['search'] ?? '' }}" placeholder="Enter product title..." class="search-input">
            </div>

            {{-- Filter by Category --}}
            <div class="search-group">
                <label for="category" class="search-label">Category</label>
                <select name="category" id="category" class="search-select">
                    <option value="">All Categories</option>
                    @php
                        function renderCategoryOptions($categories, $selectedId = null) {
                            foreach ($categories as $category) {
                                $isSelected = $selectedId == $category->id ? 'selected' : '';
                                
                                // Add proper spacing based on level
                                if ($category->level == 1) {
                                    $spacing = '';
                                } elseif ($category->level == 2) {
                                    $spacing = '→ ';
                                } elseif ($category->level == 3) {
                                    $spacing = '→→ ';
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
                        @php renderCategoryOptions($category->children, $filters['category'] ?? null) @endphp
                    @endforeach
                </select>
            </div>

            {{-- Buttons --}}
            <div style="display: flex; gap: var(--spacing-md); align-items: flex-end;">
                <button type="submit" class="search-button">Search</button>
                <a href="{{ route('guest-products.index') }}" class="reset-button">Reset</a>
            </div>
        </form>
    </div>

    {{-- Products Grid --}}
    @if($products->isEmpty())
        <div class="products-empty">
            <p>No products found matching your criteria. Try adjusting your search or browse all products.</p>
        </div>
    @else
        <x-products :products="$products" route="guest-products.show" :isGuest="true" />
    @endif
</div>

@endsection
