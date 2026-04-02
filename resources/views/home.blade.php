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
        --color-accent-dark: #0f5962;
        --color-success: #22c55e;
        --color-warning: #f59e61;
        --color-error: #ef4444;
        --spacing-xs: 8px;
        --spacing-sm: 12px;
        --spacing-md: 16px;
        --spacing-lg: 20px;
        --spacing-xl: 24px;
        --spacing-2xl: 32px;
        --radius: 8px;
        --radius-lg: 12px;
        --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.05);
        --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.1);
        --shadow-lg: 0 10px 20px rgba(0, 0, 0, 0.1);
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    * {
        box-sizing: border-box;
    }

    body {
        background: linear-gradient(135deg, var(--color-bg-primary) 0%, #f5f5f2 100%);
        min-height: 100vh;
    }

    /* ===== POPUP ===== */
    #pop-up-toggle {
        display: none;
    }

    .pop-up-container {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.6);
        z-index: 1000;
        align-items: center;
        justify-content: center;
        padding: var(--spacing-lg);
    }

    #pop-up-toggle:checked ~ .pop-up-container {
        display: flex;
    }

    .pop-up-card {
        background: linear-gradient(135deg, var(--color-bg-secondary) 0%, #fafaf8 100%);
        border-radius: var(--radius-lg);
        padding: var(--spacing-2xl);
        max-width: 500px;
        box-shadow: var(--shadow-lg);
        border: 1px solid var(--color-border);
    }

    .pop-up-title {
        font-size: 22px;
        font-weight: 700;
        color: var(--color-text-primary);
        margin: 0 0 var(--spacing-lg) 0;
	text-align: center;
    }

    .pop-up-content {
        font-size: 14px;
        color: var(--color-text-secondary);
        margin-bottom: var(--spacing-lg);
        line-height: 1.8;
        text-align: center;
    }

    .pop-up-button-container {
        display: flex;
        gap: var(--spacing-md);
        justify-content: center;
    }

    .pop-up-close-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 12px 24px;
        background: linear-gradient(135deg, var(--color-accent-dark) 0%, var(--color-accent) 100%);
        color: white;
        border: none;
        border-radius: var(--radius);
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: var(--transition);
    }

    .pop-up-close-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(32, 128, 136, 0.3);
    }

    /* ===== MAIN WRAPPER ===== */
    .home-wrapper {
        padding: var(--spacing-xl);
        max-width: 1400px;
        margin: 0 auto;
    }

    /* ===== SCROLLABLE BANNER ===== */
    .featured-banner {
        margin-bottom: var(--spacing-2xl);
        background: linear-gradient(135deg, var(--color-bg-secondary) 0%, #fafaf8 100%);
        border: 1px solid var(--color-border);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-md);
        overflow: hidden;
    }

    .featured-banner-header {
        padding: var(--spacing-sm) var(--spacing-lg);
        border-bottom: 2px solid var(--color-accent-light);
    }

    .featured-banner-title {
        font-size: 14px;
        font-weight: 800;
        color: var(--color-text-primary);
        margin: 0;
        letter-spacing: -0.3px;
	text-align: center;
    }

    .featured-banner-scroll {
        display: flex;
        gap: var(--spacing-md);
        overflow-x: auto;
        padding: var(--spacing-md);
        scroll-behavior: smooth;
    }

    .featured-banner-scroll::-webkit-scrollbar {
        height: 6px;
    }

    .featured-banner-scroll::-webkit-scrollbar-track {
        background: var(--color-bg-primary);
        border-radius: 3px;
    }

    .featured-banner-scroll::-webkit-scrollbar-thumb {
        background: var(--color-accent);
        border-radius: 3px;
    }

    .featured-banner-scroll::-webkit-scrollbar-thumb:hover {
        background: var(--color-accent-light);
    }

    .featured-banner-card {
        flex: 0 0 120px;
        background-color: var(--color-bg-secondary);
        border: 1px solid var(--color-border);
        border-radius: var(--radius-lg);
        overflow: hidden;
        transition: var(--transition);
        display: flex;
        flex-direction: column;
        box-shadow: var(--shadow-sm);
    }

    .featured-banner-card:hover {
        box-shadow: var(--shadow-lg);
        border-color: var(--color-accent-light);
        transform: translateY(-2px);
    }

    .featured-banner-card-image {
        width: 100%;
        height: 75px;
        overflow: hidden;
        background-color: var(--color-bg-primary);
    }

    .featured-banner-card-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: var(--transition);
    }

    .featured-banner-card:hover .featured-banner-card-image img {
        transform: scale(1.05);
    }

    .featured-banner-card-content {
        padding: 6px;
        display: flex;
        flex-direction: column;
        gap: 3px;
        flex-grow: 1;
    }

    .featured-banner-card-title {
        font-size: 10px;
        font-weight: 700;
        color: var(--color-text-primary);
        margin: 0;
        line-height: 1.1;
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .featured-banner-card-price {
        font-size: 11px;
        font-weight: 800;
        color: var(--color-accent);
	text-align: center;
    }

    .featured-banner-card-button {
        padding: 2px 6px;
        background: linear-gradient(135deg, var(--color-accent-dark) 0%, var(--color-accent) 100%);
        color: white;
        border: none;
        border-radius: var(--radius);
        font-weight: 600;
        font-size: 8px;
        cursor: pointer;
        transition: var(--transition);
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .featured-banner-card-button:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(32, 128, 136, 0.3);
    }

    /* ===== ALERTS SECTION ===== */
    .home-alerts {
        display: flex;
        flex-direction: column;
        gap: var(--spacing-lg);
        margin-bottom: var(--spacing-2xl);
    }

    .alert-banner {
        padding: var(--spacing-lg);
        border-radius: var(--radius-lg);
        border-left: 4px solid;
        box-shadow: var(--shadow-sm);
        animation: slideIn 0.3s ease-out;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .alert-banner-warning {
        background: linear-gradient(135deg, rgba(245, 158, 11, 0.08) 0%, rgba(245, 158, 11, 0.04) 100%);
        border-left-color: var(--color-warning);
        color: #92400e;
    }

    .alert-banner-info {
        background: linear-gradient(135deg, rgba(32, 128, 136, 0.08) 0%, rgba(32, 128, 136, 0.04) 100%);
        border-left-color: var(--color-accent);
        color: var(--color-text-primary);
    }

    .alert-banner-notice {
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.08) 0%, rgba(59, 130, 246, 0.04) 100%);
        border-left-color: #3b82f6;
        color: var(--color-text-primary);
    }

    .alert-banner-title {
        font-weight: 700;
        margin-bottom: var(--spacing-sm);
        font-size: 15px;
    }

    .alert-banner-text {
        font-size: 13px;
        margin: 0;
        line-height: 1.6;
    }

    .alert-banner-link {
        color: inherit;
        font-weight: 600;
        text-decoration: underline;
        cursor: pointer;
    }

    /* ===== WELCOME SECTION ===== */
    .welcome-section {
        background: linear-gradient(135deg, var(--color-bg-secondary) 0%, #fafaf8 100%);
        border: 1px solid var(--color-border);
        border-radius: var(--radius-lg);
        padding: var(--spacing-2xl);
        margin-bottom: var(--spacing-2xl);
        box-shadow: var(--shadow-md);
    }

    .welcome-title {
        font-size: 32px;
        font-weight: 800;
        color: var(--color-text-primary);
        text-align: center;
        margin: 0 0 var(--spacing-md) 0;
        letter-spacing: -0.5px;
    }

    .welcome-subtitle {
        font-size: 15px;
        color: var(--color-text-secondary);
        text-align: center;
        margin: 0 0 var(--spacing-xl) 0;
        line-height: 1.6;
    }

    .welcome-list {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: var(--spacing-lg);
        margin: var(--spacing-xl) 0;
    }

    .welcome-list-item {
        background: linear-gradient(135deg, var(--color-bg-primary) 0%, #f8f7f5 100%);
        border: 1px solid var(--color-border);
        border-radius: var(--radius);
        padding: var(--spacing-lg);
        display: flex;
        flex-direction: column;
        gap: var(--spacing-sm);
    }

    .welcome-list-icon {
        font-size: 24px;
    }

    .welcome-list-title {
        font-weight: 700;
        color: var(--color-text-primary);
        margin: 0;
        font-size: 14px;
	text-align: center;
    }

    .welcome-list-text {
        font-size: 13px;
        color: var(--color-text-secondary);
        margin: 0;
        line-height: 1.5;
	text-align: center;
    }

    .welcome-list-link {
        color: var(--color-accent);
        text-decoration: none;
        font-weight: 600;
        font-size: 12px;
    }

    .welcome-list-link:hover {
        text-decoration: underline;
    }

    .notice-box {
        background: linear-gradient(135deg, rgba(245, 158, 11, 0.08) 0%, rgba(245, 158, 11, 0.04) 100%);
        border: 1px solid rgba(245, 158, 11, 0.3);
        border-left: 4px solid var(--color-warning);
        padding: var(--spacing-lg);
        border-radius: var(--radius);
        margin: var(--spacing-xl) 0;
    }

    .notice-box-title {
        font-weight: 700;
        color: var(--color-text-primary);
        margin-bottom: var(--spacing-sm);
        gap: var(--spacing-sm);
        font-size: 15px;
	text-align: center;
    }

    .notice-box-text {
        font-size: 14px;
        line-height: 1.6;
	text-align: center;
    }

    .notice-box-footer {
        margin-top: var(--spacing-md);
        font-size: 12px;
        color: var(--color-text-secondary);
        font-weight: 500;
    }

    /* ===== ACTION BUTTONS ===== */
    .action-buttons-section {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: var(--spacing-lg);
        margin-top: var(--spacing-xl);
    }

    .action-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: var(--spacing-sm);
        padding: 14px 20px;
        border: none;
        border-radius: var(--radius);
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: var(--transition);
        text-decoration: none;
        white-space: nowrap;
        text-align: center;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--color-accent-dark) 0%, var(--color-accent) 100%);
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(32, 128, 136, 0.3);
    }

    .btn-secondary {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
    }

    .btn-secondary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
    }

    .btn-success {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
    }

    .btn-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    .btn-danger {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
    }

    .btn-danger:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    }

    .btn-admin {
        background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%);
        color: white;
    }

    .btn-admin:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(124, 58, 237, 0.3);
    }

    /* ===== FEATURED SECTION ===== */
    .featured-section {
        margin-bottom: var(--spacing-2xl);
    }

    .featured-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: var(--spacing-xl);
        padding-bottom: var(--spacing-lg);
        border-bottom: 2px solid var(--color-accent-light);
    }

    .featured-title {
        font-size: 22px;
        font-weight: 800;
        color: var(--color-text-primary);
        margin: 0;
        letter-spacing: -0.3px;
    }

    .featured-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: var(--spacing-lg);
    }

    .featured-card {
        background-color: var(--color-bg-secondary);
        border: 1px solid var(--color-border);
        border-radius: var(--radius-lg);
        overflow: hidden;
        transition: var(--transition);
        display: flex;
        flex-direction: column;
        box-shadow: var(--shadow-sm);
    }

    .featured-card:hover {
        box-shadow: var(--shadow-lg);
        border-color: var(--color-accent-light);
        transform: translateY(-4px);
    }

    .featured-card-image {
        width: 100%;
        height: 200px;
        overflow: hidden;
        background-color: var(--color-bg-primary);
        position: relative;
    }

    .featured-card-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: var(--transition);
    }

    .featured-card:hover .featured-card-image img {
        transform: scale(1.05);
    }

    .featured-card-content {
        padding: var(--spacing-lg);
        display: flex;
        flex-direction: column;
        gap: var(--spacing-md);
        flex-grow: 1;
    }

    .featured-card-title {
        font-size: 15px;
        font-weight: 700;
        color: var(--color-text-primary);
        margin: 0;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .featured-card-badges {
        display: flex;
        flex-wrap: wrap;
        gap: var(--spacing-xs);
    }

    .badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: var(--radius);
        font-size: 11px;
        font-weight: 600;
        white-space: nowrap;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .badge-type {
        background-color: var(--color-accent);
        color: white;
    }

    .badge-type-cargo {
        background-color: #3b82f6;
    }

    .badge-type-digital {
        background-color: #8b5cf6;
    }

    .badge-type-deaddrop {
        background-color: #ec4899;
    }

    .badge-vendor {
        background: linear-gradient(135deg, var(--color-accent-dark) 0%, var(--color-accent) 100%);
        color: white;
        border: 1px solid rgba(32, 128, 136, 0.2);
    }

    .badge-vendor a {
        color: white;
        text-decoration: none;
        font-weight: 600;
    }

    .badge-vendor a:hover {
        text-decoration: underline;
    }

    .badge-category {
        background-color: var(--color-bg-primary);
        color: var(--color-text-secondary);
        border: 1px solid var(--color-border);
    }

    .featured-card-price-group {
        display: flex;
        align-items: baseline;
        gap: var(--spacing-sm);
    }

    .featured-card-price {
        font-size: 20px;
        font-weight: 800;
        color: var(--color-accent);
    }

    .featured-card-xmr {
        font-size: 12px;
        color: var(--color-text-secondary);
        font-weight: 500;
    }

    .featured-card-info {
        background: linear-gradient(135deg, var(--color-bg-primary) 0%, #f8f7f5 100%);
        border-radius: var(--radius);
        padding: var(--spacing-md);
        font-size: 12px;
        color: var(--color-text-secondary);
        display: flex;
        flex-direction: column;
        gap: var(--spacing-xs);
        border: 1px solid var(--color-border);
    }

    .featured-card-info-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .featured-card-info-label {
        font-weight: 600;
        color: var(--color-text-primary);
    }

    .featured-card-action {
        display: flex;
        gap: var(--spacing-sm);
    }

    .featured-card-button {
        flex: 1;
        padding: 10px var(--spacing-md);
        background: linear-gradient(135deg, var(--color-accent-dark) 0%, var(--color-accent) 100%);
        color: white;
        border: none;
        border-radius: var(--radius);
        font-weight: 600;
        font-size: 13px;
        cursor: pointer;
        transition: var(--transition);
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .featured-card-button:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(32, 128, 136, 0.3);
    }

    .featured-card-button-secondary {
        background: transparent;
        color: var(--color-accent);
        border: 1px solid var(--color-accent);
        flex: 0.5;
    }

    .featured-card-button-secondary:hover {
        background-color: var(--color-bg-primary);
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 1024px) {
        .featured-grid {
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: var(--spacing-md);
        }

        .action-buttons-section {
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: var(--spacing-md);
        }

        .featured-banner-card {
            flex: 0 0 110px;
        }
    }

    @media (max-width: 768px) {
        .home-wrapper {
            padding: var(--spacing-lg);
        }

        .welcome-section {
            padding: var(--spacing-lg);
        }

        .welcome-title {
            font-size: 26px;
        }

        .welcome-list {
            grid-template-columns: 1fr;
            gap: var(--spacing-md);
        }

        .featured-grid {
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: var(--spacing-md);
        }

        .featured-card-image {
            height: 150px;
        }

        .featured-card-content {
            padding: var(--spacing-md);
        }

        .action-buttons-section {
            grid-template-columns: 1fr;
            gap: var(--spacing-md);
        }

        .action-btn {
            width: 100%;
        }

        .featured-title {
            font-size: 18px;
        }

        .featured-banner-card {
            flex: 0 0 100px;
        }

        .featured-banner-card-image {
            height: 70px;
        }

        .featured-banner-title {
            font-size: 16px;
        }
    }
</style>

<!-- Pop-up Notice -->
@if($popup)
<input type="checkbox" id="pop-up-toggle" checked>
<div class="pop-up-container">
    <div class="pop-up-card">
        <h2 class="pop-up-title">{{ $popup->title }}</h2>
        <div class="pop-up-content">{{ $popup->message }}</div>
        <div class="pop-up-button-container">
            <label for="pop-up-toggle" class="pop-up-close-btn">
                ✓ Acknowledge & Continue
            </label>
        </div>
    </div>
</div>
@endif

<div class="home-wrapper">
    <!-- Featured Products Scrollable Banner (Above Welcome) -->
    @if(isset($featuredProducts) && count($featuredProducts) > 0)
        <div class="featured-banner">
            <div class="featured-banner-header">
                <h2 class="featured-banner-title">Trending Products</h2>
            </div>
            <div class="featured-banner-scroll">
                @foreach($featuredProducts as $featured)
                    <a href="{{ route('products.show', $featured['product']) }}" class="featured-banner-card" style="text-decoration: none; color: inherit;">
                        <div class="featured-banner-card-image">
                            <img src="{{ $featured['product']->product_picture_url }}" 
                                 alt="{{ $featured['product']->name }}"
                                 onerror="this.src='{{ asset('images/placeholder.png') }}'">
                        </div>
                        <div class="featured-banner-card-content">
                            <h3 class="featured-banner-card-title">{{ $featured['product']->name }}</h3>
                            <div class="featured-banner-card-price">
                                ${{ number_format($featured['product']->price, 2) }}
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Welcome Section -->
    <div class="welcome-section">
        <h1 class="welcome-title">Erebus Marketplace Script</h1>
        <p class="welcome-subtitle">
            Erebus Marketplace Script is an updated and rewritten version of the now vanished Kabus Marketplace and it's dedicated creator Sukunetsiz, we hope he is okay. This updated version runs on the latest Laravel 12 which was upgraded from Laravel 11. The design has been completely rewritten and I have added many cool features. This script is still under development, and I will do my best to maintain the repository for this script both on GitHub and on an private tor domain which supports git commands.
        </p>

        <!-- Features Grid -->
        <div class="welcome-list">
            <div class="welcome-list-item">
                <h3 class="welcome-list-title">Verified Vendors</h3>
                <p class="welcome-list-text">All sellers are community-rated and verified. <a href="{{ route('vendors.index') }}" class="welcome-list-link">Browse vendors →</a></p>
            </div>

            <div class="welcome-list-item">
                <h3 class="welcome-list-title">Secure Shopping</h3>
                <p class="welcome-list-text">Escrow system protects you from start to finish every transaction.</p>
            </div>

            <div class="welcome-list-item">
                <h3 class="welcome-list-title">Fast Checkout</h3>
                <p class="welcome-list-text">Multiple payment options for quick and convenient purchases.</p>
            </div>

            <div class="welcome-list-item">
                <h3 class="welcome-list-title">Global Shipping</h3>
                <p class="welcome-list-text">Products ship worldwide with various delivery options available.</p>
            </div>
        </div>

        <!-- Security Notice -->
        <div class="notice-box">
            <div class="notice-box-title">🔐 Secure Your Account</div>
            <p class="notice-box-text">
                Enable two-factor authentication for maximum security. Navigate to <strong>Account → Account Settings</strong>, add your PGP key, verify it, then enable 2FA in the Account Protection section.
            </p>
        </div>

        <!-- Action Buttons -->
        <div class="action-buttons-section">
            <a href="{{ route('harm-reduction') }}" class="action-btn btn-danger">
                🛡️ Harm Reduction
            </a>
            
            @if(auth()->check())
                @if(auth()->user()->isVendor())
                    <a href="{{ route('vendor.index') }}" class="action-btn btn-success">
                        📦 Vendor Panel
                    </a>
                @endif

                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.index') }}" class="action-btn btn-admin">
                        ⚙️ Admin Panel
                    </a>
                @endif

                @if(!auth()->user()->isVendor() && !auth()->user()->isAdmin())
                    <a href="{{ route('become.vendor') }}" class="action-btn btn-secondary">
                        🚀 Start Selling
                    </a>
                    <a href="{{ route('products.index') }}" class="action-btn btn-primary">
                        🛍️ Browse Products
                    </a>
                @endif
            @else
                <a href="{{ route('products.index') }}" class="action-btn btn-primary">
                    🛍️ Start Browsing
                </a>
            @endif
        </div>
    </div>

    <!-- Advertised Products Section -->
    @if(isset($adSlots) && count($adSlots) > 0)
        <div class="featured-section">
            <div class="featured-header">
                <h2 class="featured-title">⭐ Featured Listings</h2>
            </div>
            <div class="featured-grid">
                @for($i = 1; $i <= 8; $i++)
                    @if(isset($adSlots[$i]))
                        <div class="featured-card">
                            <!-- Product Image -->
                            <div class="featured-card-image">
                                <img src="{{ $adSlots[$i]['product']->product_picture_url }}" 
                                     alt="{{ $adSlots[$i]['product']->name }}"
                                     onerror="this.src='{{ asset('images/placeholder.png') }}'">
                            </div>

                            <!-- Product Content -->
                            <div class="featured-card-content">
                                <h3 class="featured-card-title">{{ $adSlots[$i]['product']->name }}</h3>
                                
                                <!-- Badges -->
                                <div class="featured-card-badges">
                                    <span class="badge badge-type badge-type-{{ $adSlots[$i]['product']->type }}">
                                        {{ ucfirst($adSlots[$i]['product']->type) }}
                                    </span>
                                    <span class="badge badge-vendor">
                                        <a href="{{ route('vendors.show', ['username' => $adSlots[$i]['vendor']->username]) }}">
                                            {{ $adSlots[$i]['vendor']->username }}
                                        </a>
                                    </span>
                                    <span class="badge badge-category">
                                        {{ $adSlots[$i]['product']->category->name }}
                                    </span>
                                </div>

                                <!-- Price -->
                                <div class="featured-card-price-group">
                                    <div class="featured-card-price">
                                        ${{ number_format($adSlots[$i]['product']->price, 2) }}
                                    </div>
                                    @if(isset($adSlots[$i]['xmr_price']) && $adSlots[$i]['xmr_price'] !== null)
                                        <div class="featured-card-xmr">
                                            ≈ ɱ{{ number_format($adSlots[$i]['xmr_price'], 4) }}
                                        </div>
                                    @endif
                                </div>

                                <!-- Product Info -->
                                <div class="featured-card-info">
                                    <div class="featured-card-info-item">
                                        <span class="featured-card-info-label">Stock:</span>
                                        <span>{{ number_format($adSlots[$i]['product']->stock_amount) }} {{ $adSlots[$i]['measurement_unit'] }}</span>
                                    </div>
                                    <div class="featured-card-info-item">
                                        <span class="featured-card-info-label">Ships:</span>
                                        <span>{{ $adSlots[$i]['product']->ships_from }} → {{ $adSlots[$i]['product']->ships_to }}</span>
                                    </div>
                                    @if(!empty($adSlots[$i]['bulk_options']))
                                        <div class="featured-card-info-item">
                                            <span class="featured-card-info-label">Bulk:</span>
                                            <span>{{ count($adSlots[$i]['bulk_options']) }} offers</span>
                                        </div>
                                    @endif
                                    <div class="featured-card-info-item">
                                        <span class="featured-card-info-label">Delivery:</span>
                                        <span>{{ count($adSlots[$i]['delivery_options']) }} options</span>
                                    </div>
                                </div>

                                <!-- Action Button -->
                                <div class="featured-card-action">
                                    <a href="{{ route('products.show', $adSlots[$i]['product']) }}" class="featured-card-button">
                                        View Product →
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                @endfor
            </div>
        </div>
    @endif
</div>

@endsection
