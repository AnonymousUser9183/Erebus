@extends('layouts.auth')

@section('content')

<style>
    :root {
        --color-accent: 208088;
        --color-accent-light: 32b8c6;
        --color-text-primary: 134252;
        --color-text-secondary: 62746e;
        --color-card-bg: ffffff;
        --color-border: e8eaea;
        --color-input-bg: f9fafb;
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
        background-color: f5f7f8;
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

    .products-show-private-shop-notice {
        background: linear-gradient(135deg, rgba(74, 144, 226, 0.1) 0%, rgba(74, 144, 226, 0.05) 100%);
        border: 1px solid rgba(74, 144, 226, 0.3);
        padding: var(--spacing-2xl);
        border-radius: var(--radius-lg);
        text-align: center;
        margin-bottom: var(--spacing-2xl);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    }

    .products-show-private-shop-notice h2 {
        color: var(--color-text-primary);
        margin: 0 0 var(--spacing-lg) 0;
        font-size: 20px;
    }

    .products-show-private-shop-notice p {
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

    /* TOP SECTION IMAGE & DETAILS */
    .products-show-top-section {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: var(--spacing-2xl);
        padding: var(--spacing-2xl);
        border-bottom: 1px solid var(--color-border);
    }

    /* GALLERY/IMAGE COLUMN */
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

    .products-show-slides div {
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

    /* RIGHT COLUMN DETAILS */
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

    .products-show-shipping-from, .products-show-shipping-to {
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

    /* REVIEWS SECTION */
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

    /* MESSAGE TO VENDOR */
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

    /* DESCRIPTION */
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

    /* REVIEWS */
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

    /* RESPONSIVE */
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

        .products-show-description,
        .products-show-reviews {
            margin: var(--spacing-lg) !important;
            padding: var(--spacing-lg) !important;
        }
    }
</style>

<div class="products-show-container">
    @if($vendor_on_vacation)
        <div class="products-show-vacation-notice">
            <h2>⏰ Vendor on Vacation</h2>
            <p>This vendor is currently on vacation and is not accepting orders. Please try again later.</p>
        </div>
    @elseif($vendor_shop_private ?? false)
        <div class="products-show-private-shop-notice">
            <h2>🔒 Private Shop</h2>
            <p>This vendor operates a private shop. You need to register and save a reference code to access their products.</p>
        </div>
    @else
        <div class="products-show-main">
            {{-- Header --}}
            <div class="products-show-header">
                <h1 class="products-show-title">{{ $product->name }}</h1>
            </div>

            {{-- TOP SECTION IMAGE & DETAILS --}}
            <div class="products-show-top-section">
                {{-- GALLERY/IMAGE COLUMN --}}
                <div class="products-show-column-center">
                    <div class="products-show-gallery">
                        <div class="products-show-slider">
                            <div class="products-show-slides">
                                <div>
                                    <img src="{{ $product->getGuestProductPictureUrl() }}" alt="{{ $product->name }}" class="products-show-gallery-image" onerror="this.src='{{ asset('images/default-product-picture.png') }}'">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- RIGHT COLUMN DETAILS --}}
                <div class="products-show-column-right">
                    <div class="products-show-details">
                        {{-- PRICE --}}
                        <div class="products-show-price">
                            <span class="products-show-price-fiat">${{ number_format($product->price, 2) }}</span>
                            <span class="products-show-price-monero">Ξ {{ $xmrPrice ?? 'UNAVAILABLE' }}</span>
                        </div>

                        {{-- INFO --}}
                        <div class="products-show-info">
                            {{-- Type --}}
                            <div class="products-show-type">
                                <span class="products-show-badge">{{ ucfirst($product->type) }}</span>
                                @if($product->category)
                                    <span class="products-show-badge products-show-badge-category">{{ $product->category->name }}</span>
                                @endif
                            </div>

                            {{-- Stock --}}
                            <div class="products-show-stock">
                                <strong>Stock:</strong> {{ $product->quantity }} units available
                            </div>

                            {{-- Vendor --}}
                            <div class="products-show-vendor">
                                <div class="products-show-avatar-username">
                                    <div class="products-show-avatar">
                                        <img src="{{ $product->user->profile ? route('guest.profile.picture', basename($product->user->profile->profile_picture_url)) : asset('images/default-profile-picture.png') }}" alt="{{ $product->user->username }}" onerror="this.src='{{ asset('images/default-profile-picture.png') }}'">
                                    </div>
                                    <a href="{{ route('vendors.show', $product->user->username) }}" class="products-show-username-link">{{ $product->user->username }}</a>
                                </div>
                                <div class="products-show-vendor-button-container">
                                    <a href="{{ route('vendors.show', $product->user->username) }}" class="products-show-vendor-button">View Vendor</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- REVIEWS SECTION --}}
            <div class="products-show-column-left">
                <div class="products-show-review-stats">
                    <h3 class="products-show-review-stats-header">⭐ Customer Reviews</h3>
                    @if($totalReviews > 0)
                        <div class="products-show-review-percentage">
                            <span class="products-show-review-percentage-value">{{ $positivePercentage }}%</span>
                            <span class="products-show-review-percentage-label">Positive Reviews</span>
                        </div>
                        <div class="products-show-review-counts">
                            <div class="products-show-review-count-item">
                                <span class="products-show-review-count-label">Positive</span>
                                <span class="products-show-review-count-value">{{ $positiveCount }}</span>
                            </div>
                            <div class="products-show-review-count-item">
                                <span class="products-show-review-count-label">Mixed</span>
                                <span class="products-show-review-count-value">{{ $mixedCount }}</span>
                            </div>
                            <div class="products-show-review-count-item">
                                <span class="products-show-review-count-label">Negative</span>
                                <span class="products-show-review-count-value">{{ $negativeCount }}</span>
                            </div>
                        </div>
                    @else
                        <div class="products-show-review-empty">
                            <p>No reviews yet for this product.</p>
                        </div>
                    @endif
                </div>

                <div class="products-show-message-to-vendor">
                    <p style="margin: 0 0 var(--spacing-md) 0; font-size: 13px; color: var(--color-text-secondary);">
                        <strong>⚠️ Guest Access:</strong> Please register to message this vendor or make purchases.
                    </p>
                    <a href="{{ route('register') }}" class="products-show-message-to-vendor-button">Register Now</a>
                </div>
            </div>

            {{-- DESCRIPTION --}}
            @if($product->description)
                <div class="products-show-description">
                    <h2>📝 Description</h2>
                    <div class="products-show-description-content">{!! nl2br(e($product->description)) !!}</div>
                </div>
            @endif

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
                                            <img src="{{ $review->user->profile ? route('guest.profile.picture', basename($review->user->profile->profile_picture_url)) : asset('images/default-profile-picture.png') }}" alt="{{ $review->user->username }}" onerror="this.src='{{ asset('images/default-profile-picture.png') }}'">
                                        </div>
                                        <div class="products-show-review-username">{{ $review->user->username }}</div>
                                    </div>
                                    <div class="products-show-review-meta">
                                        <div class="products-show-review-date">{{ $review->created_at->format('M d, Y') }}</div>
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
                <div style="background: var(--color-card-bg); border-radius: var(--radius-lg); border: 1px solid var(--color-border); padding: var(--spacing-2xl); margin-top: var(--spacing-2xl); box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);">
                    <h2 style="margin: 0 0 var(--spacing-lg) 0; font-size: 16px; color: var(--color-text-primary); font-weight: 700;">📋 {{ $product->user->username }}'s Vendor Policy</h2>
                    <div style="color: var(--color-text-secondary); line-height: 1.8; white-space: pre-wrap;">{!! nl2br(e($product->user->vendorProfile->vendor_policy)) !!}</div>
                </div>
            @endif
        </div>
    @endif
</div>

@endsection
