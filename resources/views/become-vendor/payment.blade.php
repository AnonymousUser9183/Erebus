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

    .become-vendor-payment-container {
        max-width: 900px;
        margin: 0 auto;
        padding: var(--spacing-xl);
    }

    .become-vendor-index-highlight {
        background-color: #f0f4f8;
        border: 1px solid #cbd5e1;
        border-left: 4px solid var(--color-accent-light);
        border-radius: var(--radius);
        padding: var(--spacing-lg);
        margin-bottom: var(--spacing-xl);
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

    .become-vendor-payment-card {
        background-color: var(--color-bg-secondary);
        border: 1px solid var(--color-border);
        border-radius: var(--radius);
        padding: var(--spacing-2xl);
    }

    .become-vendor-payment-alert {
        border-radius: var(--radius);
        padding: var(--spacing-lg);
        margin-bottom: var(--spacing-xl);
        text-align: center;
    }

    .become-vendor-payment-alert h2 {
        font-size: 20px;
        font-weight: 600;
        margin: 0 0 var(--spacing-sm) 0;
    }

    .become-vendor-payment-alert p {
        font-size: 14px;
        margin: 0;
    }

    .become-vendor-payment-alert-danger {
        background-color: #fee2e2;
        border: 1px solid #fecaca;
        color: #7f1d1d;
    }

    .become-vendor-payment-alert-danger h2 {
        color: #dc2626;
    }

    .become-vendor-payment-alert-info {
        background-color: #dbeafe;
        border: 1px solid #93c5fd;
        color: #1e40af;
    }

    .become-vendor-payment-alert-info h2 {
        color: #1e40af;
    }

    .become-vendor-payment-info {
        margin-bottom: var(--spacing-xl);
    }

    .become-vendor-payment-details {
        background-color: var(--color-bg-primary);
        border: 1px solid var(--color-border);
        border-radius: var(--radius);
        padding: var(--spacing-lg);
    }

    .become-vendor-payment-details p {
        font-size: 14px;
        color: var(--color-text-primary);
        margin: 0 0 var(--spacing-md) 0;
        line-height: 1.6;
    }

    .become-vendor-payment-details p strong {
        font-weight: 600;
        color: var(--color-accent);
    }

    .become-vendor-payment-address {
        display: block;
        font-family: 'Monaco', 'Menlo', monospace;
        font-size: 11px;
        word-break: break-all;
        margin-top: var(--spacing-sm);
        background-color: var(--color-bg-secondary);
        padding: var(--spacing-sm);
        border-radius: var(--radius);
    }

    .become-vendor-payment-amount {
        display: block;
        font-size: 16px;
        font-weight: 600;
        color: var(--color-accent);
        margin-top: var(--spacing-sm);
    }

    .become-vendor-payment-received {
        display: block;
        font-size: 14px;
        font-weight: 600;
        color: var(--color-text-primary);
        margin-top: var(--spacing-sm);
    }

    .become-vendor-payment-warning {
        display: block;
        font-size: 11px;
        color: #f59e0b;
        margin-top: var(--spacing-xs);
        font-weight: 600;
    }

    .become-vendor-payment-status {
        display: inline-block;
        font-weight: 600;
        padding: var(--spacing-xs) var(--spacing-sm);
        border-radius: var(--radius);
    }

    .become-vendor-payment-status-success {
        background-color: #d1fae5;
        color: #065f46;
    }

    .become-vendor-payment-status-warning {
        background-color: #fef3c7;
        color: #78350f;
    }

    .become-vendor-payment-status-info {
        background-color: #dbeafe;
        color: #1e40af;
    }

    .become-vendor-payment-next-steps {
        background-color: var(--color-bg-secondary);
        border: 1px solid var(--color-border);
        border-radius: var(--radius);
        padding: var(--spacing-lg);
        margin-top: var(--spacing-lg);
    }

    .become-vendor-payment-next-steps p {
        font-size: 14px;
        color: var(--color-text-secondary);
        margin: 0 0 var(--spacing-md) 0;
    }

    .become-vendor-payment-actions {
        display: flex;
        gap: var(--spacing-md);
    }

    .become-vendor-payment-btn {
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
        text-align: center;
        transition: background-color 0.3s ease;
    }

    .become-vendor-payment-btn:hover {
        background-color: var(--color-accent-light);
    }

    .become-vendor-payment-qr {
        text-align: center;
        padding: var(--spacing-lg) 0;
    }

    .become-vendor-payment-qr-image {
        max-width: 300px;
        width: 100%;
        height: auto;
        border: 1px solid var(--color-border);
        border-radius: var(--radius);
        padding: var(--spacing-lg);
        background-color: var(--color-bg-primary);
    }

    .become-vendor-payment-refresh {
        text-align: center;
        padding: var(--spacing-lg) 0;
    }

    .become-vendor-payment-error {
        background-color: #fee2e2;
        border: 1px solid #fecaca;
        border-radius: var(--radius);
        padding: var(--spacing-lg);
        color: #7f1d1d;
        text-align: center;
    }

    @media (max-width: 768px) {
        .become-vendor-payment-container {
            padding: var(--spacing-lg);
        }

        .become-vendor-index-highlight {
            flex-direction: column;
            gap: var(--spacing-md);
        }

        .become-vendor-index-info-icon {
            width: 28px;
            height: 28px;
        }

        .become-vendor-payment-actions {
            flex-direction: column;
        }
    }
</style>

<div class="become-vendor-payment-container">
    @if(!$hasPgpVerified)
        <div class="become-vendor-index-highlight">
            <img src="{{ asset('images/information.png') }}" alt="Information" class="become-vendor-index-info-icon">
            <div class="become-vendor-index-highlight-content">
                <h4 class="become-vendor-index-highlight-heading">PGP Verification Required!</h4>
                <p class="become-vendor-index-highlight-text">For security, you must verify your PGP key before becoming a vendor. This ensures secure communication with customers.</p>
                <hr class="become-vendor-index-divider">
                <p class="become-vendor-index-highlight-text become-vendor-index-mb-0">Visit your Account page to set up and verify your PGP key first.</p>
            </div>
        </div>
    @endif

    @if(!$hasMoneroAddress)
        <div class="become-vendor-index-highlight">
            <img src="{{ asset('images/information.png') }}" alt="Information" class="become-vendor-index-info-icon">
            <div class="become-vendor-index-highlight-content">
                <h4 class="become-vendor-index-highlight-heading">Monero Return Address Required!</h4>
                <p class="become-vendor-index-highlight-text">You must add at least one Monero return address before becoming a vendor. This ensures secure payment processing.</p>
                <hr class="become-vendor-index-divider">
                <p class="become-vendor-index-highlight-text become-vendor-index-mb-0">Visit your Addresses page to add a Monero return address first.</p>
            </div>
        </div>
    @endif

    @if($hasPgpVerified && $hasMoneroAddress)
        <div class="become-vendor-payment-card">
            @if(isset($error))
                <div class="become-vendor-payment-alert become-vendor-payment-alert-danger">
                    <h2>Error</h2>
                    <p>{{ $error }}</p>
                </div>
            @elseif(isset($alreadyVendor) && $alreadyVendor)
                <div class="become-vendor-payment-alert become-vendor-payment-alert-info">
                    <h2>You Are Already a Vendor</h2>
                    <p>Your account already has vendor privileges. No additional payment is needed.</p>
                </div>
            @elseif(isset($vendorPayment))
                <div class="become-vendor-payment-info">
                    <div class="become-vendor-payment-details">
                        <p><strong>Monero Address:</strong><span class="become-vendor-payment-address">{{ $vendorPayment->address }}</span></p>
                        <p><strong>Required Amount:</strong><span class="become-vendor-payment-amount">{{ config('monero.vendor_payment_required_amount') }} XMR</span></p>
                        <p><strong>Minimum Transaction:</strong><span class="become-vendor-payment-amount">{{ config('monero.vendor_payment_minimum_amount') }} XMR</span><span class="become-vendor-payment-warning">Payments below this will be ignored</span></p>
                        <p><strong>Total Received:</strong><span class="become-vendor-payment-received">{{ number_format($vendorPayment->total_received, 12) }} XMR</span></p>
                        <p><strong>Status:</strong>
                            @if($vendorPayment->payment_completed)
                                <span class="become-vendor-payment-status become-vendor-payment-status-success">Payment Successful!</span>
                                <div class="become-vendor-payment-next-steps">
                                    <p>Your payment has been received. You can now proceed with your vendor application.</p>
                                    <div class="become-vendor-payment-actions">
                                        <a href="{{ route('become.vendor') }}" class="become-vendor-payment-btn">Return to Application Page</a>
                                    </div>
                                </div>
                            @elseif($vendorPayment->total_received > 0)
                                <span class="become-vendor-payment-status become-vendor-payment-status-warning">Insufficient Amount</span>
                            @else
                                <span class="become-vendor-payment-status become-vendor-payment-status-info">Awaiting Payment</span>
                            @endif
                        </p>
                    </div>
                </div>
                @if($qrCodeDataUri && !$vendorPayment->payment_completed)
                    <div class="become-vendor-payment-qr">
                        <img src="{{ $qrCodeDataUri }}" alt="Monero Address QR Code" class="become-vendor-payment-qr-image">
                    </div>
                @endif
                @if(!$vendorPayment->payment_completed)
                    <div class="become-vendor-payment-refresh">
                        <a href="{{ route('become.payment') }}" class="become-vendor-payment-btn">Refresh Page to Process Free Payment</a>
                    </div>
                @endif
            @else
                <p class="become-vendor-payment-error">Error occurred while creating Monero payment address. Please try again later.</p>
            @endif
        </div>
    @endif
</div>

@endsection
