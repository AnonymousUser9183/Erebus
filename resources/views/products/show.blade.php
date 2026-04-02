@extends('layouts.app')

@section('content')

<style>
    :root {
        --color-accent: #208088;
        --color-accent-light: #32b8c6;
        --color-text-primary: #134252;
        --color-text-secondary: #62746e;
        --color-card-bg: #ffffff;
        --color-border: #e8eaea;
        --color-input-bg: #f9fafb;
        --radius-base: 8px;
        --radius-lg: 12px;
        --spacing-xs: 8px;
        --spacing-md: 12px;
        --spacing-lg: 16px;
        --spacing-xl: 24px;
        --spacing-2xl: 32px;
    }

    * {
        box-sizing: border-box;
    }

    body {
        background-color: #f5f7f8;
    }

    .products-show-container {
        max-width: 1300px;
        margin: 0 auto;
        padding: var(--spacing-xl);
    }

    .products-show-vacation-notice {
        background: linear-gradient(135deg, rgba(255, 193, 7, 0.1) 0%, rgba(255, 193, 7, 0.05) 100%);
        border: 1px solid rgba(255, 193, 7, 0.3);
        padding: var(--spacing-2xl);
        border-radius: var(--radius-lg);
        text-align: center;
        margin-bottom: var(--spacing-2xl);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    }

    .products-show-vacation-notice h2 {
        color: var(--color-text-primary);
        margin: 0 0 var(--spacing-lg) 0;
        font-size: 20px;
    }

    .products-show-vacation-notice p {
        color: var(--color-text-secondary);
        margin: 0;
    }

    .products-show-main {
        background: var(--color-card-bg);
        border-radius: var(--radius-lg);
        border: 1px solid var(--color-border);
        padding: 0;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }

    .products-show-header {
        padding: var(--spacing-2xl);
        border-bottom: 1px solid var(--color-border);
        background: linear-gradient(135deg, #f9fafb 0%, #ffffff 100%);
    }

    .products-show-title {
        margin: 0;
        font-size: 28px;
        color: var(--color-text-primary);
        font-weight: 700;
        letter-spacing: -0.5px;
    }

    /* ===== TOP SECTION: IMAGE + DETAILS ===== */
    .products-show-top-section {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: var(--spacing-2xl);
        padding: var(--spacing-2xl);
        border-bottom: 1px solid var(--color-border);
    }

    /* ===== GALLERY/IMAGE COLUMN ===== */
    .products-show-column-center {
        display: flex;
        flex-direction: column;
    }

    .products-show-gallery {
        background: var(--color-input-bg);
        border-radius: var(--radius-lg);
        border: 1px solid var(--color-border);
        padding: var(--spacing-xl);
        overflow: hidden;
    }

    .products-show-slider {
        position: relative;
    }

    .products-show-slides {
        display: flex;
        overflow: hidden;
        min-height: 450px;
        background: white;
        border-radius: var(--radius-base);
    }

    .products-show-slides > div {
        width: 100%;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        background: white;
    }

    .products-show-gallery-image {
        max-width: 100%;
        max-height: 450px;
        object-fit: contain;
        display: block;
    }

    .products-show-slider-navigation {
        display: flex;
        gap: var(--spacing-md);
        margin-top: var(--spacing-lg);
        justify-content: center;
        flex-wrap: wrap;
    }

    .products-show-image-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        background: white;
        border: 2px solid var(--color-border);
        border-radius: var(--radius-base);
        text-decoration: none;
        color: var(--color-text-primary);
        font-weight: 600;
        font-size: 12px;
        transition: all 0.2s;
        cursor: pointer;
    }

    .products-show-image-button:hover {
        border-color: var(--color-accent);
        color: var(--color-accent);
        background: rgba(32, 128, 136, 0.05);
        transform: translateY(-2px);
    }

    /* ===== RIGHT COLUMN: DETAILS ===== */
    .products-show-column-right {
        display: flex;
        flex-direction: column;
        gap: var(--spacing-xl);
    }

    .products-show-details {
        background: linear-gradient(135deg, #f9fafb 0%, #ffffff 100%);
        padding: var(--spacing-xl);
        border-radius: var(--radius-lg);
        border: 1px solid var(--color-border);
    }

    .products-show-price {
        margin-bottom: var(--spacing-xl);
        padding-bottom: var(--spacing-xl);
        border-bottom: 1px solid var(--color-border);
    }

    .products-show-price-fiat {
        display: block;
        font-size: 32px;
        font-weight: 700;
        color: var(--color-accent);
        line-height: 1.2;
    }

    .products-show-price-monero {
        display: block;
        font-size: 12px;
        color: var(--color-text-secondary);
        margin-top: var(--spacing-md);
    }

    .products-show-info {
        display: flex;
        flex-direction: column;
        gap: var(--spacing-lg);
    }

    .products-show-type {
        display: flex;
        gap: var(--spacing-md);
        flex-wrap: wrap;
    }

    .products-show-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: var(--radius-base);
        font-size: 11px;
        font-weight: 700;
        background: rgba(32, 128, 136, 0.1);
        color: var(--color-accent);
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .products-show-badge-physical {
        background: rgba(76, 175, 80, 0.1);
        color: #27ae60;
    }

    .products-show-badge-digital {
        background: rgba(33, 150, 243, 0.1);
        color: #2196f3;
    }

    .products-show-badge-service {
        background: rgba(255, 193, 7, 0.1);
        color: #f39c12;
    }

    .products-show-badge-category {
        background: rgba(156, 39, 176, 0.1);
        color: #9c27b0;
    }

    .products-show-badge-stock {
        background: rgba(244, 67, 54, 0.1);
        color: #e74c3c;
    }

    .products-show-shipping {
        padding: var(--spacing-lg);
        background: white;
        border-radius: var(--radius-base);
        border: 1px solid var(--color-border);
    }

    .products-show-shipping-badge {
        display: flex;
        flex-direction: column;
        gap: var(--spacing-md);
        text-align: center;
    }

    .products-show-shipping-from,
    .products-show-shipping-to {
        font-size: 12px;
        color: var(--color-text-secondary);
        font-weight: 500;
    }

    .products-show-shipping-arrow {
        font-size: 14px;
        color: var(--color-accent);
        font-weight: 700;
    }

    .products-show-stock {
        padding: var(--spacing-lg);
        background: white;
        border-radius: var(--radius-base);
        border: 1px solid var(--color-border);
        text-align: center;
    }

    .products-show-vendor {
        padding: var(--spacing-lg);
        background: white;
        border-radius: var(--radius-base);
        border: 1px solid var(--color-border);
    }

    .products-show-avatar-username {
        display: flex;
        gap: var(--spacing-md);
        align-items: center;
    }

    .products-show-avatar {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        overflow: hidden;
        border: 2px solid var(--color-accent);
        flex-shrink: 0;
    }

    .products-show-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .products-show-username-link {
        color: var(--color-accent);
        text-decoration: none;
        font-weight: 700;
        font-size: 13px;
        transition: all 0.2s;
    }

    .products-show-username-link:hover {
        color: var(--color-accent-light);
    }

    .products-show-vendor-button-container {
        text-align: center;
        margin-top: var(--spacing-lg);
    }

    .products-show-vendor-button {
        display: inline-block;
        background: white;
        border: 2px solid var(--color-accent);
        color: var(--color-accent);
        padding: var(--spacing-md) var(--spacing-lg);
        border-radius: var(--radius-base);
        text-decoration: none;
        font-weight: 600;
        font-size: 13px;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .products-show-vendor-button:hover {
        background: var(--color-accent);
        color: white;
        transform: translateY(-2px);
    }

    /* ===== REVIEWS SECTION ===== */
    .products-show-column-left {
        display: flex;
        flex-direction: column;
        gap: var(--spacing-xl);
        padding: var(--spacing-2xl);
        border-bottom: 1px solid var(--color-border);
    }

    .products-show-review-stats {
        background: linear-gradient(135deg, #f9fafb 0%, #ffffff 100%);
        padding: var(--spacing-xl);
        border-radius: var(--radius-lg);
        border: 1px solid var(--color-border);
    }

    .products-show-review-stats-header {
        margin: 0 0 var(--spacing-lg) 0;
        font-size: 14px;
        color: var(--color-text-primary);
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .products-show-review-percentage {
        text-align: center;
        margin-bottom: var(--spacing-lg);
        padding: var(--spacing-lg) 0;
    }

    .products-show-review-percentage-value {
        display: block;
        font-size: 42px;
        font-weight: 700;
        color: var(--color-accent);
        line-height: 1;
    }

    .products-show-review-percentage-label {
        display: block;
        font-size: 12px;
        color: var(--color-text-secondary);
        margin-top: var(--spacing-md);
        font-weight: 500;
    }

    .products-show-review-counts {
        display: flex;
        flex-direction: column;
        gap: 0;
    }

    .products-show-review-count-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: var(--spacing-md) 0;
        border-bottom: 1px solid var(--color-border);
    }

    .products-show-review-count-item:last-child {
        border-bottom: none;
    }

    .products-show-review-count-label {
        color: var(--color-text-secondary);
        font-size: 13px;
        font-weight: 500;
    }

    .products-show-review-count-value {
        font-size: 16px;
        font-weight: 700;
        color: var(--color-accent);
    }

    .products-show-review-empty {
        text-align: center;
        padding: var(--spacing-xl);
        color: var(--color-text-secondary);
        font-size: 13px;
    }

    .products-show-message-to-vendor {
        text-align: center;
    }

    .products-show-message-to-vendor-button {
        display: inline-block;
        background: linear-gradient(135deg, var(--color-accent) 0%, var(--color-accent-light) 100%);
        color: white;
        padding: var(--spacing-md) var(--spacing-xl);
        border-radius: var(--radius-base);
        text-decoration: none;
        font-weight: 600;
        font-size: 13px;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        box-shadow: 0 2px 8px rgba(32, 128, 136, 0.2);
    }

    .products-show-message-to-vendor-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(32, 128, 136, 0.3);
    }

    /* ===== WISHLIST ===== */
    .products-show-wishlist {
        padding: var(--spacing-2xl);
        border-bottom: 1px solid var(--color-border);
        background: linear-gradient(135deg, #f9fafb 0%, #ffffff 100%);
    }

    .products-show-wishlist-button {
        width: 100%;
        padding: var(--spacing-lg);
        border: 2px solid var(--color-accent);
        background: white;
        border-radius: var(--radius-base);
        color: var(--color-accent);
        font-weight: 600;
        cursor: pointer;
        font-size: 13px;
        transition: all 0.3s;
    }

    .products-show-wishlist-button:hover {
        background: var(--color-accent);
        color: white;
    }

    .products-show-wishlist-remove {
        border-color: #e74c3c;
        color: #e74c3c;
    }

    .products-show-wishlist-remove:hover {
        background: #e74c3c;
        color: white;
    }

    /* ===== CART SECTION ===== */
    .products-show-own-product {
        background: rgba(244, 67, 54, 0.1);
        color: #e74c3c;
        padding: var(--spacing-xl);
        border-radius: var(--radius-lg);
        text-align: center;
        border: 1px solid rgba(244, 67, 54, 0.2);
        margin: 0 var(--spacing-2xl);
    }

    .products-show-own-product p {
        margin: 0;
        font-weight: 500;
    }

    .products-show-cart-section {
        background: white;
        padding: var(--spacing-2xl);
        border-bottom: 1px solid var(--color-border);
    }

    .products-show-cart-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: var(--spacing-lg);
        margin-bottom: var(--spacing-xl);
    }

    .products-show-cart-field {
        display: flex;
        flex-direction: column;
    }

    .products-show-cart-field label {
        margin-bottom: var(--spacing-md);
        color: var(--color-text-primary);
        font-weight: 600;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .products-show-cart-field select,
    .products-show-cart-field input {
        padding: var(--spacing-md) var(--spacing-lg);
        border: 1px solid var(--color-border);
        border-radius: var(--radius-base);
        color: var(--color-text-primary);
        background: white;
        font-size: 13px;
        transition: all 0.2s;
        font-family: inherit;
    }

    .products-show-cart-field select:focus,
    .products-show-cart-field input:focus {
        outline: none;
        border-color: var(--color-accent);
        box-shadow: 0 0 0 3px rgba(32, 128, 136, 0.1);
    }

    .products-show-cart-button {
        width: 100%;
        padding: var(--spacing-lg);
        background: linear-gradient(135deg, var(--color-accent) 0%, var(--color-accent-light) 100%);
        color: white;
        border: none;
        border-radius: var(--radius-base);
        font-weight: 700;
        cursor: pointer;
        font-size: 14px;
        transition: all 0.3s;
        box-shadow: 0 2px 8px rgba(32, 128, 136, 0.2);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .products-show-cart-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(32, 128, 136, 0.3);
    }

    /* ===== DESCRIPTION ===== */
    .products-show-description {
        margin: var(--spacing-2xl);
        padding: var(--spacing-2xl);
        background: linear-gradient(135deg, #f9fafb 0%, #ffffff 100%);
        border-radius: var(--radius-lg);
        border: 1px solid var(--color-border);
    }

    .products-show-description h2 {
        margin: 0 0 var(--spacing-lg) 0;
        font-size: 16px;
        color: var(--color-text-primary);
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .products-show-description-content {
        color: var(--color-text-secondary);
        line-height: 1.8;
        white-space: pre-wrap;
        word-wrap: break-word;
        font-size: 13px;
    }

    /* ===== REVIEWS ===== */
    .products-show-reviews {
        margin: 0 var(--spacing-2xl) var(--spacing-2xl) var(--spacing-2xl);
    }

    .products-show-reviews-title {
        font-size: 16px;
        margin: 0 0 var(--spacing-lg) 0;
        color: var(--color-text-primary);
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .products-show-reviews-list {
        display: flex;
        flex-direction: column;
        gap: var(--spacing-lg);
    }

    .products-show-review-item {
        padding: var(--spacing-lg);
        background: white;
        border-radius: var(--radius-base);
        border: 1px solid var(--color-border);
    }

    .products-show-review-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: var(--spacing-lg);
        gap: var(--spacing-lg);
    }

    .products-show-review-user {
        display: flex;
        gap: var(--spacing-md);
    }

    .products-show-review-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        overflow: hidden;
        border: 2px solid var(--color-border);
    }

    .products-show-review-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .products-show-review-username {
        color: var(--color-text-primary);
        font-weight: 600;
        font-size: 13px;
        display: flex;
        align-items: center;
    }

    .products-show-review-meta {
        display: flex;
        gap: var(--spacing-lg);
        align-items: center;
        font-size: 11px;
    }

    .products-show-review-date {
        color: var(--color-text-secondary);
        font-weight: 500;
    }

    .products-show-review-sentiment {
        padding: 4px 8px;
        border-radius: 3px;
        font-weight: 700;
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .products-show-review-sentiment-positive {
        background: rgba(76, 175, 80, 0.15);
        color: #27ae60;
    }

    .products-show-review-sentiment-mixed {
        background: rgba(255, 193, 7, 0.15);
        color: #f39c12;
    }

    .products-show-review-sentiment-negative {
        background: rgba(244, 67, 54, 0.15);
        color: #e74c3c;
    }

    .products-show-review-content {
        color: var(--color-text-secondary);
        line-height: 1.6;
        font-size: 13px;
    }

    /* ===== VENDOR POLICY ===== */
    .products-show-vendor-policy-section {
        background: var(--color-card-bg);
        border-radius: var(--radius-lg);
        border: 1px solid var(--color-border);
        padding: var(--spacing-2xl);
        margin-top: var(--spacing-2xl);
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
    }

    .products-show-vendor-policy-container h2 {
        margin: 0 0 var(--spacing-lg) 0;
        font-size: 16px;
        color: var(--color-text-primary);
        font-weight: 700;
    }

    .products-show-vendor-policy-content {
        color: var(--color-text-secondary);
        line-height: 1.8;
        white-space: pre-wrap;
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 1024px) {
        .products-show-top-section {
            grid-template-columns: 1fr;
            gap: var(--spacing-xl);
        }

        .products-show-slides {
            min-height: 400px;
        }

        .products-show-gallery-image {
            max-height: 400px;
        }
    }

    @media (max-width: 768px) {
        .products-show-container {
            padding: var(--spacing-lg);
        }

        .products-show-header {
            padding: var(--spacing-xl);
        }

        .products-show-title {
            font-size: 22px;
        }

        .products-show-top-section,
        .products-show-column-left {
            padding: var(--spacing-lg);
        }

        .products-show-slides {
            min-height: 300px;
        }

        .products-show-gallery-image {
            max-height: 300px;
        }

        .products-show-price-fiat {
            font-size: 24px;
        }

        .products-show-cart-section,
        .products-show-description,
        .products-show-reviews,
        .products-show-vendor-policy-section {
            margin: var(--spacing-lg) !important;
            padding: var(--spacing-lg) !important;
        }

        .products-show-cart-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="products-show-container">
    @if($vendor_on_vacation)
        <div class="products-show-vacation-notice">
            <h2>⏱️ Product Currently Unavailable</h2>
            <p>This product is temporarily unavailable as the vendor is on vacation. Please check back later.</p>
        </div>
    @elseif(isset($vendor_shop_private) && $vendor_shop_private)
        <div class="products-show-vacation-notice">
            <h2>🔒 Private Shop</h2>
            <p>This product is only available to users with the vendor's reference code. Add it on your References page.</p>
        </div>
    @else
        <div class="products-show-main">
            {{-- HEADER --}}
            <div class="products-show-header">
                <h1 class="products-show-title">{{ $product->name }}</h1>
            </div>

            {{-- TOP SECTION: IMAGE + DETAILS --}}
            <div class="products-show-top-section">
                {{-- LEFT: IMAGE --}}
                <div class="products-show-column-center">
                    <div class="products-show-gallery">
                        <div class="products-show-slider">
                            <div class="products-show-slides">
                                <div id="slide-1">
                                    <img src="{{ $product->product_picture_url }}" alt="{{ $product->name }}" class="products-show-gallery-image">
                                </div>
                                @if(!empty($product->additional_photos))
                                    @foreach($product->additional_photos_urls as $index => $photoUrl)
                                        <div id="slide-{{ $index + 2 }}">
                                            <img src="{{ $photoUrl }}" alt="{{ $product->name }} - Image {{ $index + 1 }}" class="products-show-gallery-image">
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>

                        <div class="products-show-slider-navigation">
                            <a href="#slide-1" class="products-show-image-button">M</a>
                            @if(!empty($product->additional_photos))
                                @foreach($product->additional_photos_urls as $index => $photoUrl)
                                    <a href="#slide-{{ $index + 2 }}" class="products-show-image-button">{{ $index + 1 }}</a>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>

                {{-- RIGHT: DETAILS --}}
                <div class="products-show-column-right">
                    <div class="products-show-details">
                        <div class="products-show-price">
                            <span class="products-show-price-fiat">${{ number_format($product->price, 2) }}</span>
                            @if(is_numeric($xmrPrice))
                                <span class="products-show-price-monero">≈ ɱ{{ number_format($xmrPrice, 4) }}</span>
                            @endif
                        </div>

                        <div class="products-show-info">
                            <div class="products-show-type">
                                <span class="products-show-badge products-show-badge-{{ $product->type }}">{{ ucfirst($product->type) }}</span>
                                <span class="products-show-badge products-show-badge-category">{{ $product->category->name }}</span>
                            </div>

                            <div class="products-show-shipping">
                                <div class="products-show-shipping-badge">
                                    <div class="products-show-shipping-from">📤 {{ $product->ships_from }}</div>
                                    <div class="products-show-shipping-arrow">⬇</div>
                                    <div class="products-show-shipping-to">📥 {{ $product->ships_to }}</div>
                                </div>
                            </div>

                            <div class="products-show-stock">
                                <span class="products-show-badge products-show-badge-stock">📦 {{ number_format($product->stock_amount) }} {{ $formattedMeasurementUnit }}</span>
                            </div>

                            <div class="products-show-vendor">
                                <div class="products-show-avatar-username">
                                    <div class="products-show-avatar">
                                        <img src="{{ $product->user->profile ? $product->user->profile->profile_picture_url : asset('images/default-profile-picture.png') }}" alt="{{ $product->user->username }}">
                                    </div>
                                    <div>
                                        <a href="{{ route('dashboard', $product->user->username) }}" class="products-show-username-link">{{ $product->user->username }}</a>
                                    </div>
                                </div>
                            </div>

                            <div class="products-show-vendor-button-container">
                                <a href="{{ route('vendors.show', $product->user->username) }}" class="products-show-vendor-button">Visit Store</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- REVIEWS STATS SECTION --}}
            <div class="products-show-column-left">
                <div class="products-show-review-stats">
                    <h2 class="products-show-review-stats-header">Review Statistics</h2>
                    
                    @if($totalReviews > 0)
                        <div class="products-show-review-percentage">
                            <span class="products-show-review-percentage-value">{{ number_format($positivePercentage, 1) }}%</span>
                            <span class="products-show-review-percentage-label">Positive Reviews</span>
                        </div>
                        <div class="products-show-review-counts">
                            <div class="products-show-review-count-item">
                                <span class="products-show-review-count-label">✓ Positive</span>
                                <span class="products-show-review-count-value">{{ $positiveCount }}</span>
                            </div>
                            <div class="products-show-review-count-item">
                                <span class="products-show-review-count-label">~ Mixed</span>
                                <span class="products-show-review-count-value">{{ $mixedCount }}</span>
                            </div>
                            <div class="products-show-review-count-item">
                                <span class="products-show-review-count-label">✗ Negative</span>
                                <span class="products-show-review-count-value">{{ $negativeCount }}</span>
                            </div>
                            <div class="products-show-review-count-item">
                                <span class="products-show-review-count-label">Total</span>
                                <span class="products-show-review-count-value">{{ $totalReviews }}</span>
                            </div>
                        </div>
                    @else
                        <div class="products-show-review-empty">
                            No reviews yet. Be the first to review!
                        </div>
                    @endif
                </div>
                
                <div class="products-show-message-to-vendor">
                    <a href="{{ route('messages.create', ['username' => $product->user->username]) }}" class="products-show-message-to-vendor-button">
                        💬 Message Vendor
                    </a>
                </div>
            </div>

            {{-- WISHLIST --}}
            <div class="products-show-wishlist">
                @if(Auth::user()->hasWishlisted($product->id))
                    <form action="{{ route('wishlist.destroy', $product) }}" method="POST" style="display: block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="products-show-wishlist-button products-show-wishlist-remove">❤️ Remove from Wishlist</button>
                    </form>
                @else
                    <form action="{{ route('wishlist.store', $product) }}" method="POST" style="display: block;">
                        @csrf
                        <button type="submit" class="products-show-wishlist-button">🤍 Add to Wishlist</button>
                    </form>
                @endif
            </div>

            {{-- CART --}}
            @if(Auth::id() === $product->user_id)
                <div class="products-show-own-product">
                    <p>⛔ You cannot add your own products to the cart.</p>
                </div>
            @else
                <div class="products-show-cart-section">
                    <form action="{{ route('cart.store', $product) }}" method="POST">
                        @csrf
                        <div class="products-show-cart-grid">
                            <div class="products-show-cart-field">
                                <label for="delivery_option">Delivery Option</label>
                                <select name="delivery_option" id="delivery_option" required>
                                    @foreach($formattedDeliveryOptions as $index => $option)
                                        <option value="{{ $index }}">
                                            {{ $option['description'] }} ({{ str_starts_with($option['price'], '$0.00') ? 'Free' : $option['price'] }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="products-show-cart-field">
                                <label for="quantity">Quantity</label>
                                <input type="number" name="quantity" id="quantity" min="1" max="80000" value="{{ old('quantity', 1) }}" required>
                            </div>
                            @if($product->bulk_options && count($product->bulk_options) > 0)
                                <div class="products-show-cart-field">
                                    <label for="bulk_option">Bulk Discount</label>
                                    <select name="bulk_option" id="bulk_option">
                                        <option value="">Regular Price</option>
                                        @foreach($formattedBulkOptions as $index => $option)
                                            <option value="{{ $index }}">{{ $option['display_text'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                        </div>
                        <button type="submit" class="products-show-cart-button">🛒 Add to Cart</button>
                    </form>
                </div>
            @endif
        </div>

        {{-- DESCRIPTION --}}
        <div class="products-show-description">
            <h2>📝 Product Description</h2>
            <div class="products-show-description-content">{!! nl2br(e($product->description)) !!}</div>
        </div>

        {{-- REVIEWS --}}
        @if(count($reviews) > 0)
        <div class="products-show-reviews">
            <h2 class="products-show-reviews-title">⭐ Customer Reviews</h2>
            <div class="products-show-reviews-list">
                @foreach($reviews as $review)
                    <div class="products-show-review-item">
                        <div class="products-show-review-header">
                            <div class="products-show-review-user">
                                <div class="products-show-review-avatar">
                                    <img src="{{ $review->user->profile ? $review->user->profile->profile_picture_url : asset('images/default-profile-picture.png') }}" alt="{{ $review->user->username }}">
                                </div>
                                <div class="products-show-review-username">{{ $review->user->username }}</div>
                            </div>
                            <div class="products-show-review-meta">
                                <div class="products-show-review-date">{{ $review->getFormattedDate() }}</div>
                                <div class="products-show-review-sentiment products-show-review-sentiment-{{ strtolower($review->sentiment) }}">{{ ucfirst($review->sentiment) }}</div>
                            </div>
                        </div>
                        <div class="products-show-review-content">{{ $review->review_text }}</div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- VENDOR POLICY --}}
        @if(!$vendor_on_vacation && !(isset($vendor_shop_private) && $vendor_shop_private) && $product->user->vendorProfile && $product->user->vendorProfile->vendor_policy)
        <div class="products-show-vendor-policy-section">
            <div class="products-show-vendor-policy-container">
                <h2>📋 {{ $product->user->username }}'s Vendor Policy</h2>
                <div class="products-show-vendor-policy-content">{!! nl2br(e($product->user->vendorProfile->vendor_policy)) !!}</div>
            </div>
        </div>
        @endif
    @endif
</div>

@endsection
