@extends('layouts.app')

@section('content')

<style>
    :root {
        --primary: #2a6f7f;
        --primary-light: #3a8f9f;
        --primary-dark: #1a4f5f;
        --accent: #00d4ff;
        --success: #10b981;
        --warning: #f59e0b;
        --danger: #ef4444;
        --text-primary: #1f2937;
        --text-secondary: #6b7280;
        --text-light: #9ca3af;
        --bg-primary: #ffffff;
        --bg-secondary: #f9fafb;
        --bg-tertiary: #f3f4f6;
        --border: #e5e7eb;
        --border-light: #f3f4f6;
        --shadow-sm: 0 1px 2px rgba(0, 0, 0, 0.05);
        --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.07);
        --shadow-lg: 0 10px 15px rgba(0, 0, 0, 0.1);
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        background-color: var(--bg-secondary);
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        color: var(--text-primary);
        line-height: 1.6;
    }

    .vendor-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem 1rem;
    }

    /* Header & Navigation */
    .vendor-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }

    .vendor-header h1 {
        font-size: 2rem;
        font-weight: 700;
        color: var(--text-primary);
    }

    .vendor-back-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.25rem;
        color: var(--primary);
        text-decoration: none;
        font-weight: 500;
        border: 1px solid var(--border);
        border-radius: 0.5rem;
        transition: all 0.2s ease;
    }

    .vendor-back-btn:hover {
        background: var(--bg-tertiary);
        border-color: var(--primary);
    }

    /* Vacation Notice */
    .vendor-vacation-alert {
        background: linear-gradient(135deg, rgba(245, 158, 11, 0.1), rgba(245, 158, 11, 0.05));
        border: 1px solid var(--warning);
        border-radius: 0.75rem;
        padding: 1.5rem;
        margin-bottom: 2rem;
        text-align: center;
    }

    .vendor-vacation-alert h2 {
        color: var(--warning);
        margin-bottom: 0.5rem;
        font-size: 1.25rem;
    }

    .vendor-vacation-alert p {
        color: var(--text-secondary);
        font-size: 0.95rem;
    }

    /* Main Card Container */
    .vendor-card {
        background: var(--bg-primary);
        border-radius: 1rem;
        box-shadow: var(--shadow-md);
        overflow: hidden;
        margin-bottom: 2rem;
    }

    /* Profile Section */
    .vendor-profile {
        padding: 2rem;
        border-bottom: 1px solid var(--border);
    }

    .vendor-profile-header {
        display: grid;
        grid-template-columns: auto 1fr;
        gap: 1.5rem;
        align-items: start;
    }

    .vendor-avatar-wrapper {
        position: relative;
    }

    .vendor-avatar {
        width: 140px;
        height: 140px;
        border-radius: 50%;
        border: 3px solid var(--primary);
        overflow: hidden;
        box-shadow: var(--shadow-lg);
    }

    .vendor-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .vendor-info h2 {
        font-size: 1.75rem;
        margin-bottom: 0.75rem;
        color: var(--text-primary);
    }

    .vendor-info h2 a {
        color: var(--primary);
        text-decoration: none;
        transition: color 0.2s ease;
    }

    .vendor-info h2 a:hover {
        color: var(--primary-light);
    }

    .vendor-badges {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
        margin-bottom: 1rem;
    }

    .vendor-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        font-size: 0.85rem;
        font-weight: 600;
        white-space: nowrap;
    }

    .vendor-badge--verified {
        background: rgba(16, 185, 129, 0.1);
        color: var(--success);
    }

    .vendor-badge--unverified {
        background: rgba(245, 158, 11, 0.1);
        color: var(--warning);
    }

    .vendor-badge--none {
        background: rgba(107, 114, 128, 0.1);
        color: var(--text-secondary);
    }

    .vendor-description {
        color: var(--text-secondary);
        font-size: 0.95rem;
        line-height: 1.7;
        max-width: 600px;
    }

    /* Statistics Grid */
    .vendor-stats-section {
        padding: 2rem;
        border-bottom: 1px solid var(--border);
    }

    .vendor-stats-section h3 {
        font-size: 1.25rem;
        margin-bottom: 1.5rem;
        color: var(--text-primary);
    }

    .vendor-stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }

    .vendor-stat-card {
        background: var(--bg-tertiary);
        padding: 1.5rem;
        border-radius: 0.75rem;
        text-align: center;
        border: 1px solid var(--border-light);
        transition: all 0.2s ease;
    }

    .vendor-stat-card:hover {
        border-color: var(--primary);
        box-shadow: var(--shadow-sm);
    }

    .vendor-stat-label {
        display: block;
        font-size: 0.85rem;
        color: var(--text-secondary);
        margin-bottom: 0.5rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .vendor-stat-value {
        display: block;
        font-size: 2rem;
        font-weight: 700;
        color: var(--primary);
    }

    .vendor-stats-percentage {
        grid-column: 1 / -1;
        padding: 2rem;
        background: linear-gradient(135deg, rgba(42, 111, 127, 0.05), rgba(0, 212, 255, 0.05));
        border-radius: 0.75rem;
        border: 1px solid var(--border);
        text-align: center;
    }

    .vendor-stats-percentage-value {
        font-size: 3rem;
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 0.5rem;
    }

    .vendor-stats-percentage-label {
        color: var(--text-secondary);
        font-size: 0.95rem;
    }

    .vendor-empty-state {
        padding: 2rem;
        text-align: center;
        color: var(--text-secondary);
        background: var(--bg-tertiary);
        border-radius: 0.75rem;
    }

    /* Products Section */
    .vendor-products-section {
        padding: 2rem;
        border-bottom: 1px solid var(--border);
    }

    .vendor-products-section h3 {
        font-size: 1.25rem;
        margin-bottom: 1.5rem;
        color: var(--text-primary);
    }

    /* Policy & PGP Sections */
    .vendor-content-section {
        padding: 2rem;
        border-bottom: 1px solid var(--border);
    }

    .vendor-content-section:last-child {
        border-bottom: none;
    }

    .vendor-content-section h3 {
        font-size: 1.25rem;
        margin-bottom: 1rem;
        color: var(--text-primary);
    }

    .vendor-policy-text {
        color: var(--text-secondary);
        font-size: 0.95rem;
        line-height: 1.8;
        white-space: pre-wrap;
        word-break: break-word;
    }

    .vendor-pgp-key {
        background: var(--bg-tertiary);
        border: 1px solid var(--border);
        border-radius: 0.5rem;
        padding: 1.25rem;
        font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
        font-size: 0.8rem;
        max-height: 400px;
        overflow-y: auto;
        color: var(--text-primary);
        line-height: 1.5;
        word-break: break-all;
    }

    .vendor-pgp-empty {
        text-align: center;
        color: var(--text-secondary);
        padding: 1rem;
    }

    /* Reviews Section */
    .vendor-reviews-section {
        background: var(--bg-primary);
        border-radius: 1rem;
        box-shadow: var(--shadow-md);
        overflow: hidden;
        padding: 2rem;
    }

    .vendor-reviews-title {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
        color: var(--text-primary);
    }

    .vendor-reviews-list {
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
    }

    .vendor-review-card {
        background: var(--bg-tertiary);
        border: 1px solid var(--border);
        border-radius: 0.75rem;
        padding: 1.5rem;
        transition: all 0.2s ease;
    }

    .vendor-review-card:hover {
        border-color: var(--primary);
        box-shadow: var(--shadow-sm);
    }

    .vendor-review-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
        gap: 1rem;
    }

    .vendor-review-author {
        display: flex;
        gap: 0.75rem;
        flex: 1;
    }

    .vendor-review-avatar {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        overflow: hidden;
        flex-shrink: 0;
        border: 2px solid var(--border);
    }

    .vendor-review-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .vendor-review-info h4 {
        font-size: 0.95rem;
        margin-bottom: 0.25rem;
        color: var(--text-primary);
    }

    .vendor-review-info a {
        color: var(--primary);
        text-decoration: none;
        transition: color 0.2s ease;
    }

    .vendor-review-info a:hover {
        color: var(--primary-light);
    }

    .vendor-review-product {
        font-size: 0.85rem;
        color: var(--text-secondary);
    }

    .vendor-review-meta {
        display: flex;
        gap: 1rem;
        align-items: center;
    }

    .vendor-review-date {
        font-size: 0.85rem;
        color: var(--text-secondary);
    }

    .vendor-review-sentiment {
        display: inline-block;
        padding: 0.35rem 0.75rem;
        border-radius: 0.375rem;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .vendor-review-sentiment--positive {
        background: rgba(16, 185, 129, 0.1);
        color: var(--success);
    }

    .vendor-review-sentiment--mixed {
        background: rgba(245, 158, 11, 0.1);
        color: var(--warning);
    }

    .vendor-review-sentiment--negative {
        background: rgba(239, 68, 68, 0.1);
        color: var(--danger);
    }

    .vendor-review-text {
        color: var(--text-primary);
        font-size: 0.95rem;
        line-height: 1.7;
        white-space: pre-wrap;
        word-break: break-word;
    }

    /* Pagination */
    .vendor-pagination {
        margin-top: 2rem;
        display: flex;
        justify-content: center;
        gap: 0.5rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .vendor-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }

        .vendor-header h1 {
            font-size: 1.5rem;
        }

        .vendor-profile-header {
            grid-template-columns: 1fr;
        }

        .vendor-stats-grid {
            grid-template-columns: 1fr;
        }

        .vendor-container {
            padding: 1rem;
        }

        .vendor-review-header {
            flex-direction: column;
        }

        .vendor-avatar {
            width: 100px;
            height: 100px;
        }
    }

    @media (max-width: 480px) {
        .vendor-container {
            padding: 0.75rem;
        }

        .vendor-card {
            border-radius: 0.5rem;
        }

        .vendor-profile,
        .vendor-stats-section,
        .vendor-products-section,
        .vendor-content-section,
        .vendor-reviews-section {
            padding: 1.25rem;
        }

        .vendor-avatar {
            width: 80px;
            height: 80px;
        }

        .vendor-info h2 {
            font-size: 1.25rem;
        }

        .vendor-badges {
            gap: 0.5rem;
        }

        .vendor-badge {
            font-size: 0.75rem;
            padding: 0.4rem 0.75rem;
        }
    }
</style>

<div class="vendor-container">
    @if($vacation_mode)
        <div class="vendor-vacation-alert">
            <h2>On Vacation</h2>
            <p>This vendor is currently taking a break. Please check back later.</p>
        </div>
    @endif

    <div class="vendor-header">
        <h1>Vendor Profile</h1>
        <a href="{{ route('vendors.index') }}" class="vendor-back-btn">← Back to Vendors</a>
    </div>

    @if(!$vacation_mode)
        <div class="vendor-card">
            <!-- Profile Section -->
            <div class="vendor-profile">
                <div class="vendor-profile-header">
                    <div class="vendor-avatar-wrapper">
                        <div class="vendor-avatar">
                            <img src="{{ $vendor->profile ? $vendor->profile->profile_picture_url : asset('images/default-profile-picture.png') }}" 
                                 alt="{{ $vendor->username }}">
                        </div>
                    </div>

                    <div class="vendor-info">
                        <h2>
                            <a href="{{ route('dashboard', $vendor->username) }}">{{ $vendor->username }}</a>
                        </h2>

                        <div class="vendor-badges">
                            @if(!$vendor->pgpKey)
                                <span class="vendor-badge vendor-badge--none">No PGP Key</span>
                            @elseif($vendor->pgpKey->verified)
                                <span class="vendor-badge vendor-badge--verified">✓ Verified PGP</span>
                            @else
                                <span class="vendor-badge vendor-badge--unverified">⚠ Unverified PGP</span>
                            @endif
                        </div>

                        @if($vendor->vendorProfile && $vendor->vendorProfile->description)
                            <p class="vendor-description">
                                {{ $vendor->vendorProfile->description }}
                            </p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Review Statistics -->
            <div class="vendor-stats-section">
                <h3>📊 Review Statistics</h3>

                @if($totalReviews > 0)
                    <div class="vendor-stats-percentage">
                        <div class="vendor-stats-percentage-value">{{ number_format($positivePercentage, 1) }}%</div>
                        <div class="vendor-stats-percentage-label">Positive Reviews</div>
                    </div>

                    <div class="vendor-stats-grid">
                        <div class="vendor-stat-card">
                            <span class="vendor-stat-label">Positive</span>
                            <span class="vendor-stat-value" style="color: var(--success);">{{ $positiveCount }}</span>
                        </div>
                        <div class="vendor-stat-card">
                            <span class="vendor-stat-label">Mixed</span>
                            <span class="vendor-stat-value" style="color: var(--warning);">{{ $mixedCount }}</span>
                        </div>
                        <div class="vendor-stat-card">
                            <span class="vendor-stat-label">Negative</span>
                            <span class="vendor-stat-value" style="color: var(--danger);">{{ $negativeCount }}</span>
                        </div>
                        <div class="vendor-stat-card">
                            <span class="vendor-stat-label">Total</span>
                            <span class="vendor-stat-value">{{ $totalReviews }}</span>
                        </div>
                    </div>
                @else
                    <div class="vendor-empty-state">
                        No reviews yet for this vendor's products.
                    </div>
                @endif
            </div>

            <!-- Dispute Statistics -->
            <div class="vendor-stats-section">
                <h3>⚖️ Dispute Statistics</h3>

                @if($totalDisputes > 0)
                    <div class="vendor-stats-grid">
                        <div class="vendor-stat-card">
                            <span class="vendor-stat-label">Won</span>
                            <span class="vendor-stat-value" style="color: var(--success);">{{ $disputesWon }}</span>
                        </div>
                        <div class="vendor-stat-card">
                            <span class="vendor-stat-label">Open</span>
                            <span class="vendor-stat-value" style="color: var(--warning);">{{ $disputesOpen }}</span>
                        </div>
                        <div class="vendor-stat-card">
                            <span class="vendor-stat-label">Lost</span>
                            <span class="vendor-stat-value" style="color: var(--danger);">{{ $disputesLost }}</span>
                        </div>
                        <div class="vendor-stat-card">
                            <span class="vendor-stat-label">Total</span>
                            <span class="vendor-stat-value">{{ $totalDisputes }}</span>
                        </div>
                    </div>
                @else
                    <div class="vendor-empty-state">
                        No disputes yet for this vendor.
                    </div>
                @endif
            </div>

            <!-- Products -->
            <div class="vendor-products-section">
                <h3>🛍️ Products</h3>

                @if($products->isEmpty())
                    <div class="vendor-empty-state">
                        No products available at the moment.
                    </div>
                @else
                    <x-products :products="$products" />
                @endif
            </div>

            <!-- Vendor Policy -->
            @if($vendor->vendorProfile && $vendor->vendorProfile->vendor_policy)
                <div class="vendor-content-section">
                    <h3>📋 Vendor Policy</h3>
                    <p class="vendor-policy-text">{{ $vendor->vendorProfile->vendor_policy }}</p>
                </div>
            @endif

            <!-- PGP Key -->
            <div class="vendor-content-section">
                <h3>🔐 PGP Public Key</h3>
                @if($vendor->pgpKey)
                    <div class="vendor-pgp-key">{{ $vendor->pgpKey->public_key }}</div>
                @else
                    <div class="vendor-pgp-empty">
                        No PGP key has been added yet.
                    </div>
                @endif
            </div>
        </div>

        <!-- Reviews -->
        @if(isset($allReviews) && !$allReviews->isEmpty())
            <div class="vendor-reviews-section">
                <h2 class="vendor-reviews-title">⭐ All Reviews</h2>

                <div class="vendor-reviews-list">
                    @foreach($allReviews as $review)
                        <div class="vendor-review-card">
                            <div class="vendor-review-header">
                                <div class="vendor-review-author">
                                    <div class="vendor-review-avatar">
                                        <img src="{{ $review->user->profile ? $review->user->profile->profile_picture_url : asset('images/default-profile-picture.png') }}" 
                                             alt="{{ $review->user->username }}">
                                    </div>
                                    <div class="vendor-review-info">
                                        <h4>
                                            <a href="{{ route('dashboard', $review->user->username) }}">{{ $review->user->username }}</a>
                                        </h4>
                                        <div class="vendor-review-product">
                                            Reviewed: <a href="{{ route('products.show', $review->product->slug) }}" style="color: var(--primary); text-decoration: none;">{{ $review->product->name }}</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="vendor-review-meta">
                                    <span class="vendor-review-date">{{ $review->getFormattedDate() }}</span>
                                    <span class="vendor-review-sentiment vendor-review-sentiment--{{ strtolower($review->sentiment) }}">
                                        {{ ucfirst($review->sentiment) }}
                                    </span>
                                </div>
                            </div>

                            <div class="vendor-review-text">{{ $review->review_text }}</div>
                        </div>
                    @endforeach
                </div>

                <div class="vendor-pagination">
                    {{ $allReviews->appends(request()->except('reviews_page'))->links() }}
                </div>
            </div>
        @endif
    @endif
</div>

@endsection
