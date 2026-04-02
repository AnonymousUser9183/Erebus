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

    .become-vendor-index-container {
        max-width: 800px;
        margin: 0 auto;
        padding: var(--spacing-xl);
    }

    .become-vendor-index-card {
        background-color: var(--color-bg-secondary);
        border: 1px solid var(--color-border);
        border-radius: var(--radius);
        padding: var(--spacing-2xl);
    }

    .become-vendor-index-title {
        font-size: 32px;
        font-weight: 600;
        color: var(--color-text-primary);
        margin: 0 0 var(--spacing-lg) 0;
        padding-bottom: var(--spacing-lg);
        border-bottom: 2px solid var(--color-accent-light);
    }

    .become-vendor-index-text {
        font-size: 15px;
        color: var(--color-text-primary);
        line-height: 1.7;
        margin-bottom: var(--spacing-lg);
    }

    .become-vendor-index-link {
        color: var(--color-accent);
        text-decoration: none;
        font-weight: 500;
        transition: color 0.3s ease;
    }

    .become-vendor-index-link:hover {
        color: var(--color-accent-light);
    }

    .become-vendor-index-highlight {
        background-color: #f0f4f8;
        border: 1px solid #cbd5e1;
        border-left: 4px solid var(--color-accent-light);
        border-radius: var(--radius);
        padding: var(--spacing-lg);
        margin: var(--spacing-lg) 0;
        display: flex;
        gap: var(--spacing-lg);
    }

    .become-vendor-index-info-icon {
        width: 32px;
        height: 32px;
        flex-shrink: 0;
    }

    .become-vendor-index-highlight-content {
        flex: 1;
    }

    .become-vendor-index-highlight-heading {
        font-size: 16px;
        font-weight: 600;
        color: var(--color-accent);
        margin: 0 0 var(--spacing-sm) 0;
    }

    .become-vendor-index-highlight-text {
        font-size: 14px;
        color: var(--color-text-secondary);
        line-height: 1.6;
        margin: 0;
    }

    .become-vendor-index-divider {
        border: none;
        border-top: 1px solid #cbd5e1;
        margin: var(--spacing-md) 0;
    }

    .become-vendor-index-mb-0 {
        margin-bottom: 0 !important;
    }

    .become-vendor-index-action-container {
        background-color: var(--color-bg-primary);
        border: 1px solid var(--color-border);
        border-radius: var(--radius);
        padding: var(--spacing-lg);
        margin: var(--spacing-xl) 0;
        text-align: center;
    }

    .become-vendor-index-action-text {
        font-size: 15px;
        color: var(--color-text-secondary);
        margin: 0 0 var(--spacing-lg) 0;
    }

    .become-vendor-index-status-container {
        background-color: var(--color-bg-primary);
        border: 1px solid var(--color-border);
        border-radius: var(--radius);
        padding: var(--spacing-lg);
        margin: var(--spacing-xl) 0;
    }

    .become-vendor-index-status-title {
        font-size: 18px;
        font-weight: 600;
        color: var(--color-text-primary);
        margin: 0 0 var(--spacing-md) 0;
    }

    .become-vendor-index-status {
        font-weight: 600;
    }

    .become-vendor-index-status-waiting {
        color: #f59e0b;
    }

    .become-vendor-index-status-accepted {
        color: #10b981;
    }

    .become-vendor-index-status-denied {
        color: #ef4444;
    }

    .become-vendor-index-status-message {
        font-size: 14px;
        color: var(--color-text-secondary);
        margin: var(--spacing-md) 0;
        line-height: 1.6;
    }

    .become-vendor-index-refund-info {
        background-color: var(--color-bg-secondary);
        border: 1px solid var(--color-border);
        border-radius: var(--radius);
        padding: var(--spacing-lg);
        margin-top: var(--spacing-lg);
    }

    .become-vendor-index-highlight-info {
        font-size: 13px;
        color: var(--color-text-secondary);
        font-style: italic;
        margin: var(--spacing-xl) 0 var(--spacing-lg) 0;
        line-height: 1.6;
    }

    .become-vendor-index-btn {
        display: inline-block;
        background-color: var(--color-accent);
        color: #ffffff;
        padding: var(--spacing-md) var(--spacing-xl);
        border: none;
        border-radius: var(--radius);
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        transition: background-color 0.3s ease;
        text-align: center;
        width: 100%;
        box-sizing: border-box;
    }

    .become-vendor-index-btn:hover:not(.disabled) {
        background-color: var(--color-accent-light);
    }

    .become-vendor-index-btn.disabled {
        background-color: #cbd5e1;
        cursor: not-allowed;
        opacity: 0.6;
    }

    @media (max-width: 768px) {
        .become-vendor-index-container {
            padding: var(--spacing-lg);
        }

        .become-vendor-index-card {
            padding: var(--spacing-xl);
        }

        .become-vendor-index-title {
            font-size: 24px;
        }

        .become-vendor-index-highlight {
            flex-direction: column;
            gap: var(--spacing-md);
        }

        .become-vendor-index-info-icon {
            width: 28px;
            height: 28px;
        }
    }
</style>

<div class="become-vendor-index-container">
    <div class="become-vendor-index-card">
        <h1 class="become-vendor-index-title">Become a Vendor (Free for a Limited Time)</h1>
        
        <p class="become-vendor-index-text">
            Anyone can sell on {{ config('app.name') }}! The process involves submitting an application for review. Your application will be carefully reviewed by our administrators.
        </p>
        
        <p class="become-vendor-index-text">
            Before proceeding, make sure you have thoroughly read <a href="{{ route('rules') }}" class="become-vendor-index-link">{{ config('app.name') }}'s rules</a>. We have zero tolerance for prohibited products. If you accept these terms and site rules, you may proceed with your application.
        </p>
        
        @if(!$hasPgpVerified)
            <div class="become-vendor-index-highlight">
                <img src="{{ asset('images/information.png') }}" alt="Information" class="become-vendor-index-info-icon">
                <div class="become-vendor-index-highlight-content">
                    <h4 class="become-vendor-index-highlight-heading">PGP Verification Required</h4>
                    <p class="become-vendor-index-highlight-text">For security reasons, you must verify your PGP key before becoming a vendor. This ensures secure communication with your customers.</p>
                    <hr class="become-vendor-index-divider">
                    <p class="become-vendor-index-highlight-text become-vendor-index-mb-0">Visit your Account page to set up and verify your PGP key first.</p>
                </div>
            </div>
        @endif
        
        @if(!$hasMoneroAddress)
            <div class="become-vendor-index-highlight">
                <img src="{{ asset('images/information.png') }}" alt="Information" class="become-vendor-index-info-icon">
                <div class="become-vendor-index-highlight-content">
                    <h4 class="become-vendor-index-highlight-heading">Monero Return Address Required</h4>
                    <p class="become-vendor-index-highlight-text">You must add at least one Monero return address before becoming a vendor. This ensures secure and reliable payment processing.</p>
                    <hr class="become-vendor-index-divider">
                    <p class="become-vendor-index-highlight-text become-vendor-index-mb-0">Visit your Addresses page to add a Monero return address first.</p>
                </div>
            </div>
        @endif
        
        @if(isset($vendorPayment))
            @if($vendorPayment->payment_completed)
                @if($vendorPayment->application_status === null)
                    <div class="become-vendor-index-action-container">
                        <p class="become-vendor-index-action-text">Your payment has been received. You can now submit your vendor application.</p>
                        <a href="{{ route('become.vendor.application') }}" class="become-vendor-index-btn">Create Application</a>
                    </div>
                @else
                    <div class="become-vendor-index-status-container">
                        <h3 class="become-vendor-index-status-title">Application Status: 
                            @if($vendorPayment->application_status === 'waiting')
                                <span class="become-vendor-index-status become-vendor-index-status-waiting">Waiting for Review</span>
                            @elseif($vendorPayment->application_status === 'accepted')
                                <span class="become-vendor-index-status become-vendor-index-status-accepted">Accepted - You are a Vendor!</span>
                            @else
                                <span class="become-vendor-index-status become-vendor-index-status-denied">Denied</span>
                            @endif
                        </h3>
                        
                        @if($vendorPayment->application_status === 'waiting')
                            <p class="become-vendor-index-status-message">Your application is currently being reviewed by our administrators.</p>
                        @elseif($vendorPayment->application_status === 'accepted')
                            <p class="become-vendor-index-status-message">Congratulations! Your application has been accepted. You can now access vendor features.</p>
                        @else
                            <p class="become-vendor-index-status-message">Unfortunately, your application has been denied. You cannot submit a new application at this time.</p>
                            @if($vendorPayment->refund_amount)
                                <div class="become-vendor-index-refund-info">
                                    <p class="become-vendor-index-status-message">A refund of {{ $vendorPayment->refund_amount }} XMR has been sent to your return address.</p>
                                </div>
                            @endif
                        @endif
                    </div>
                @endif
            @else
                <a href="{{ route('become.payment') }}" class="become-vendor-index-btn {{ (!$hasPgpVerified || !$hasMoneroAddress) ? 'disabled' : '' }}">Continue to Payment</a>
            @endif
        @else
            <a href="{{ route('become.payment') }}" class="become-vendor-index-btn {{ (!$hasPgpVerified || !$hasMoneroAddress) ? 'disabled' : '' }}">Continue to Payment</a>
        @endif
    </div>
</div>

@endsection
