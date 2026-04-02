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

    .vendor-index-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: var(--spacing-xl);
    }

    .vendor-index-header {
        margin-bottom: var(--spacing-xl);
    }

    .vendor-index-title {
        font-size: 32px;
        font-weight: 600;
        color: var(--color-text-primary);
        margin: 0 0 var(--spacing-md) 0;
    }

    .vendor-index-subtitle {
        font-size: 14px;
        color: var(--color-text-secondary);
        margin: 0;
    }

    .vendor-actions-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: var(--spacing-lg);
        margin-bottom: var(--spacing-xl);
    }

    .vendor-action-card {
        background: var(--color-card-bg);
        border: 1px solid var(--color-border);
        border-radius: var(--radius-lg);
        padding: var(--spacing-lg);
        text-decoration: none;
        transition: all 0.2s ease;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        display: flex;
        flex-direction: column;
        gap: var(--spacing-md);
    }

    .vendor-action-card:hover {
        border-color: var(--color-accent);
        box-shadow: 0 4px 12px rgba(32, 128, 136, 0.15);
        transform: translateY(-2px);
    }

    .vendor-action-icon {
        font-size: 28px;
        width: 44px;
        height: 44px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(32, 128, 136, 0.1);
        border-radius: var(--radius-base);
    }

    .vendor-action-title {
        font-size: 16px;
        font-weight: 600;
        color: var(--color-text-primary);
        margin: 0;
    }

    .vendor-action-description {
        font-size: 13px;
        color: var(--color-text-secondary);
        margin: 0;
        line-height: 1.5;
    }

    .vendor-action-arrow {
        color: var(--color-accent);
        font-size: 18px;
        margin-top: auto;
    }

    .vendor-section {
        background: var(--color-card-bg);
        border: 1px solid var(--color-border);
        border-radius: var(--radius-lg);
        padding: var(--spacing-xl);
        margin-bottom: var(--spacing-lg);
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }

    .vendor-section-title {
        font-size: 18px;
        font-weight: 600;
        color: var(--color-text-primary);
        margin: 0 0 var(--spacing-lg) 0;
    }

    .vendor-section-description {
        font-size: 13px;
        color: var(--color-text-secondary);
        margin: 0 0 var(--spacing-lg) 0;
        line-height: 1.6;
    }

    .vendor-links-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: var(--spacing-md);
    }

    .vendor-link-btn {
        background: transparent;
        border: 1px solid var(--color-border);
        color: var(--color-accent);
        padding: 10px 12px;
        border-radius: var(--radius-base);
        text-decoration: none;
        font-size: 13px;
        font-weight: 500;
        text-align: center;
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .vendor-link-btn:hover {
        background: rgba(32, 128, 136, 0.05);
        border-color: var(--color-accent);
    }

    .vendor-link-btn-primary {
        background: var(--color-accent);
        color: white;
        border-color: var(--color-accent);
    }

    .vendor-link-btn-primary:hover {
        background: var(--color-accent-light);
        border-color: var(--color-accent-light);
    }

    .vendor-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        gap: var(--spacing-md);
        margin-bottom: var(--spacing-xl);
    }

    .vendor-stat {
        background: var(--color-input-bg);
        padding: var(--spacing-lg);
        border-radius: var(--radius-base);
        text-align: center;
    }

    .vendor-stat-value {
        font-size: 24px;
        font-weight: 600;
        color: var(--color-accent);
        margin: 0 0 var(--spacing-md) 0;
    }

    .vendor-stat-label {
        font-size: 12px;
        color: var(--color-text-secondary);
        text-transform: uppercase;
        font-weight: 600;
        margin: 0;
    }

    @media (max-width: 768px) {
        .vendor-index-container {
            padding: var(--spacing-lg);
        }

        .vendor-index-title {
            font-size: 24px;
        }

        .vendor-actions-grid {
            grid-template-columns: 1fr;
        }

        .vendor-stats {
            grid-template-columns: repeat(2, 1fr);
        }

        .vendor-links-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="vendor-index-container">
    <div class="vendor-index-header">
        <h1 class="vendor-index-title">Vendor Dashboard</h1>
        <p class="vendor-index-subtitle">Manage your products, sales, and store settings</p>
    </div>

        <!-- Quick Actions Section -->
    <div class="vendor-section">
        <h2 class="vendor-section-title">Quick Actions</h2>
        <p class="vendor-section-description">Common tasks and settings you might need</p>
        <div class="vendor-links-grid">
            <a href="{{ route('vendor.my-products') }}" class="vendor-link-btn vendor-link-btn-primary">
                View All Products
            </a>
            <a href="{{ route('vendor.sales') }}" class="vendor-link-btn">
                View All Sales
            </a>
            <a href="{{ route('vendor.disputes.index') }}" class="vendor-link-btn">
                View All Disputes
            </a>
            <a href="{{ route('vendor.appearance') }}" class="vendor-link-btn">
                Edit Store Info
            </a>
        </div>
    </div>

    <!-- Action Cards Grid -->
    <div class="vendor-actions-grid">
        <a href="{{ route('vendor.products.create', 'digital') }}" class="vendor-action-card">
            <div class="vendor-action-icon">💾</div>
            <h3 class="vendor-action-title">Add Digital Product</h3>
            <p class="vendor-action-description">Create a new digital product listing</p>
            <div class="vendor-action-arrow">→</div>
        </a>

        <a href="{{ route('vendor.products.create', 'cargo') }}" class="vendor-action-card">
            <div class="vendor-action-icon">📦</div>
            <h3 class="vendor-action-title">Add Cargo Product</h3>
            <p class="vendor-action-description">Create a new cargo/physical product</p>
            <div class="vendor-action-arrow">→</div>
        </a>

        <a href="{{ route('vendor.products.create', 'deaddrop') }}" class="vendor-action-card">
            <div class="vendor-action-icon">🎯</div>
            <h3 class="vendor-action-title">Add Dead Drop</h3>
            <p class="vendor-action-description">Create a new dead drop listing</p>
            <div class="vendor-action-arrow">→</div>
        </a>

        <a href="{{ route('vendor.my-products') }}" class="vendor-action-card">
            <div class="vendor-action-icon">📸</div>
            <h3 class="vendor-action-title">My Products</h3>
            <p class="vendor-action-description">View and manage all your products</p>
            <div class="vendor-action-arrow">→</div>
        </a>

        <a href="{{ route('vendor.sales') }}" class="vendor-action-card">
            <div class="vendor-action-icon">💰</div>
            <h3 class="vendor-action-title">My Sales</h3>
            <p class="vendor-action-description">Track all your orders and sales</p>
            <div class="vendor-action-arrow">→</div>
        </a>

        <a href="{{ route('vendor.disputes.index') }}" class="vendor-action-card">
            <div class="vendor-action-icon">⚖️</div>
            <h3 class="vendor-action-title">Disputes</h3>
            <p class="vendor-action-description">Manage open and resolved disputes</p>
            <div class="vendor-action-arrow">→</div>
        </a>

        <a href="{{ route('vendor.appearance') }}" class="vendor-action-card">
            <div class="vendor-action-icon">🎨</div>
            <h3 class="vendor-action-title">Store Settings</h3>
            <p class="vendor-action-description">Customize your store appearance</p>
            <div class="vendor-action-arrow">→</div>
        </a>
    </div>

    <!-- Help & Resources Section -->
    <div class="vendor-section">
        <h2 class="vendor-section-title">Help & Resources</h2>
        <p class="vendor-section-description">
            Need help? Here are some resources to get you started. Visit our help center for detailed guides on managing products, handling disputes, and optimizing your sales.
        </p>
        <div class="vendor-links-grid">
            <a href="#HELP_LINK_1" class="vendor-link-btn">
                Getting Started Guide
            </a>
            <a href="#HELP_LINK_2" class="vendor-link-btn">
                Product Management
            </a>
            <a href="#HELP_LINK_3" class="vendor-link-btn">
                Orders & Fulfillment
            </a>
            <a href="#HELP_LINK_4" class="vendor-link-btn">
                Dispute Resolution
            </a>
            <a href="#HELP_LINK_5" class="vendor-link-btn">
                Payment & Withdrawals
            </a>
            <a href="#HELP_LINK_6" class="vendor-link-btn">
                Contact Support
            </a>
        </div>
    </div>
</div>

@endsection
