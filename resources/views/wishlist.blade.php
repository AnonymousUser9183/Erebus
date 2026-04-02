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

    .wishlist-index-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: var(--spacing-xl);
    }

    .wishlist-index-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: var(--spacing-2xl);
        padding-bottom: var(--spacing-lg);
        border-bottom: 2px solid var(--color-accent-light);
    }

    .wishlist-index-title {
        font-size: 28px;
        font-weight: 600;
        color: var(--color-text-primary);
        margin: 0;
    }

    .wishlist-index-clear-btn {
        background-color: #ffffff;
        color: var(--color-text-primary);
        border: 1px solid var(--color-border);
        padding: var(--spacing-sm) var(--spacing-lg);
        border-radius: var(--radius);
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .wishlist-index-clear-btn:hover {
        background-color: var(--color-bg-primary);
        border-color: var(--color-accent);
        color: var(--color-accent);
    }

    .wishlist-index-empty {
        text-align: center;
        padding: var(--spacing-2xl) var(--spacing-xl);
        background-color: var(--color-bg-secondary);
        border: 1px solid var(--color-border);
        border-radius: var(--radius);
	margin-left: -80px;
	margin-right: -80px;
    }


    .wishlist-index-empty-text {
        font-size: 16px;
        color: var(--color-text-secondary);
        margin: 0 0 var(--spacing-lg) 0;
    }

    .wishlist-index-browse-btn {
        display: inline-block;
        background-color: var(--color-accent);
        color: #ffffff;
        padding: var(--spacing-sm) var(--spacing-lg);
        border-radius: var(--radius);
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
        transition: background-color 0.3s ease;
    }

    .wishlist-index-browse-btn:hover {
        background-color: var(--color-accent-light);
    }

    @media (max-width: 768px) {
        .wishlist-index-header {
            flex-direction: column;
            gap: var(--spacing-md);
            align-items: flex-start;
        }

        .wishlist-index-container {
            padding: var(--spacing-md);
        }
    }
</style>

<div class="wishlist-index-container">
    <div class="wishlist-index-header">
        <h1 class="wishlist-index-title">{{ $title }}</h1>
        @if(!$products->isEmpty())
            <form action="{{ route('wishlist.clear') }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="wishlist-index-clear-btn">
                    Clear Wishlist
                </button>
            </form>
        @endif
    </div>

    @if($products->isEmpty())
        <div class="wishlist-index-empty">
            <p class="wishlist-index-empty-text">Your wishlist is empty. Start exploring Erebus Market.</p>
            <a href="{{ route('products.index') }}" class="wishlist-index-browse-btn">
                Browse Products
            </a>
        </div>
    @else
        <x-products 
            :products="$products"
        />
    @endif
</div>
@endsection
