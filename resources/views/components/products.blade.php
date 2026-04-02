@props([
    'products',  // Required: collection of products
    'route' => 'products.show',  // Optional: route name (default: products.show, use guest-products.show for guests)
    'isGuest' => false  // Optional: whether this is guest view (for image routes)
])

<style>
    :root {
        --color-primary-dark: #0d5b7c;
        --color-primary: #1a7a99;
        --color-primary-light: #2a9db8;
        --color-text-primary: #333333;
        --color-text-secondary: #666666;
        --color-bg-primary: #f5f5f5;
        --color-bg-secondary: #ffffff;
        --color-border: #e0e0e0;
        --spacing-xs: 4px;
        --spacing-sm: 8px;
        --spacing-md: 12px;
        --spacing-lg: 16px;
        --spacing-xl: 24px;
        --radius-md: 6px;
        --radius-lg: 8px;
    }

    .products-wrapper {
        padding: var(--spacing-xl);
    }

    .products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
        gap: var(--spacing-lg);
        margin-bottom: var(--spacing-xl);
    }

    .product-card {
        position: relative;
        background-color: var(--color-bg-secondary);
        border: 1px solid var(--color-border);
        border-radius: var(--radius-lg);
        overflow: hidden;
        text-decoration: none;
        color: inherit;
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
    }

    .product-card:hover {
        box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
        border-color: var(--color-primary-light);
        transform: translateY(-2px);
    }

    .product-card-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        display: none;
        flex-direction: column;
        justify-content: flex-end;
        background: linear-gradient(to top, rgba(0,0,0,0.6), rgba(0,0,0,0));
        padding: var(--spacing-md);
        z-index: 2;
    }

    .product-card:hover .product-card-overlay {
        display: flex;
    }

    .product-card-image {
        width: 100%;
        height: 140px;
        background-color: var(--color-bg-primary);
        overflow: hidden;
        position: relative;
    }

    .product-card-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .product-card-badges {
        position: absolute;
        top: var(--spacing-md);
        left: var(--spacing-md);
        right: var(--spacing-md);
        display: flex;
        justify-content: space-between;
        z-index: 2;
    }

    .product-card-rating {
        background-color: rgba(255, 255, 255, 0.95);
        padding: var(--spacing-xs) var(--spacing-sm);
        border-radius: var(--radius-md);
        font-size: 11px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 2px;
    }

    .product-card-rating-star {
        color: #f59e0b;
    }

    .product-card-wishlist-btn {
        background-color: rgba(255, 255, 255, 0.9);
        border: none;
        width: 32px;
        height: 32px;
        border-radius: var(--radius-md);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        padding: 0;
    }

    .product-card-wishlist-btn:hover {
        background-color: white;
    }

    .product-card-wishlist-btn.active {
        background-color: #fee2e2;
    }

    .product-card-wishlist-btn img {
        width: 16px;
        height: 16px;
    }

    .product-card-content {
        padding: var(--spacing-md);
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: var(--spacing-sm);
    }

    .product-card-vendor {
        position: absolute;
        bottom: var(--spacing-md);
        left: var(--spacing-md);
        background-color: rgba(255, 255, 255, 0.95);
        padding: var(--spacing-xs) var(--spacing-sm);
        border-radius: var(--radius-md);
        font-size: 11px;
        color: var(--color-text-secondary);
        z-index: 2;
    }

    .product-card-title {
        font-size: 13px;
        font-weight: 600;
        color: var(--color-text-primary);
        margin: 0;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .product-card-price {
        font-size: 15px;
        font-weight: 700;
        color: var(--color-primary);
        margin: var(--spacing-xs) 0 0 0;
    }

    .product-card-pagination {
        display: flex;
        justify-content: center;
        padding: var(--spacing-xl);
    }

    @media (max-width: 1024px) {
        .products-grid {
            grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
            gap: var(--spacing-md);
        }
    }

    @media (max-width: 768px) {
        .products-wrapper {
            padding: var(--spacing-lg);
        }

        .products-grid {
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            gap: var(--spacing-md);
        }

        .product-card-image {
            height: 120px;
        }

        .product-card-content {
            padding: var(--spacing-sm);
        }

        .product-card-title {
            font-size: 12px;
        }

        .product-card-price {
            font-size: 14px;
        }
    }
</style>

<div class="products-wrapper">
    <div class="products-grid">
        @forelse($products as $product)
            <a href="{{ route($route, $product->id) }}" class="product-card">
                <div class="product-card-image">
                    @if($isGuest)
                        <img src="{{ $product->getGuestProductPictureUrl() }}" alt="{{ $product->name }}" onerror="this.src='{{ asset('images/default-product-picture.png') }}'">
                    @else
                        <img src="{{ $product->product_picture_url }}" alt="{{ $product->name }}" onerror="this.src='{{ asset('images/default-product-picture.png') }}'">
                    @endif
                    
                    <div class="product-card-badges">
                        <div class="product-card-rating">
                            @if($product->getPositiveReviewPercentage() !== null)
                                <span class="product-card-rating-star">★</span>
                                <span>{{ number_format($product->getPositiveReviewPercentage(), 0) }}%</span>
                            @else
                                <span>New</span>
                            @endif
                        </div>
                        
                        @auth
                            <form action="{{ Auth::user()->hasWishlisted($product->id) 
                                ? route('wishlist.destroy', $product) 
                                : route('wishlist.store', $product) }}" method="POST" onclick="event.stopPropagation();">
                                @csrf
                                @if(Auth::user()->hasWishlisted($product->id))
                                    @method('DELETE')
                                @endif
                                <button type="submit" class="product-card-wishlist-btn {{ Auth::user()->hasWishlisted($product->id) ? 'active' : '' }}" 
                                        title="{{ Auth::user()->hasWishlisted($product->id) ? 'Remove from Wishlist' : 'Add to Wishlist' }}">
                                    <img src="{{ asset('icons/wishlist.png') }}" alt="Wishlist">
                                </button>
                            </form>
                        @endauth
                    </div>

                    <div class="product-card-vendor">
                        By {{ $product->user->username }}
                    </div>
                </div>

                <div class="product-card-content">
                    <h3 class="product-card-title">{{ $product->name }}</h3>
                    <div class="product-card-price">${{ number_format($product->price, 2) }}</div>
                </div>
            </a>
        @empty
            <div style="grid-column: 1/-1; text-align: center; padding: 40px; color: var(--color-text-secondary);">
                <p>No products found.</p>
            </div>
        @endforelse
    </div>

    @if($products->count() > 0)
        <div class="product-card-pagination">
            {{ $products->links() }}
        </div>
    @endif
</div>
