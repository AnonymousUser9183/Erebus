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

    .payment-container {
        max-width: 900px;
        margin: 0 auto;
        padding: var(--spacing-xl);
    }

    .payment-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: var(--spacing-xl);
    }

    .payment-details-card {
        background: var(--color-card-bg);
        border: 1px solid var(--color-border);
        border-radius: var(--radius-lg);
        padding: var(--spacing-xl);
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }

    .payment-title {
        font-size: 20px;
        font-weight: 600;
        color: var(--color-text-primary);
        margin: 0 0 var(--spacing-lg) 0;
    }

    .payment-info-group {
        margin-bottom: var(--spacing-lg);
        padding-bottom: var(--spacing-lg);
        border-bottom: 1px solid var(--color-border);
    }

    .payment-info-group:last-child {
        border-bottom: none;
    }

    .payment-info-label {
        font-size: 12px;
        font-weight: 600;
        color: var(--color-text-secondary);
        text-transform: uppercase;
        margin-bottom: var(--spacing-md);
    }

    .payment-info-value {
        font-size: 14px;
        color: var(--color-text-primary);
        word-break: break-all;
    }

    .payment-info-value.amount {
        font-size: 24px;
        font-weight: 600;
    }

    .payment-status-badge {
        display: inline-block;
        padding: 8px 12px;
        border-radius: var(--radius-base);
        font-size: 12px;
        font-weight: 600;
        margin-bottom: var(--spacing-lg);
    }

    .payment-status-awaiting {
        background: rgba(255, 193, 7, 0.1);
        color: #f57f17;
    }

    .payment-status-insufficient {
        background: rgba(244, 67, 54, 0.1);
        color: #c62828;
    }

    .payment-status-completed {
        background: rgba(76, 175, 80, 0.1);
        color: #2e7d32;
    }

    .qr-code-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        background: var(--color-input-bg);
        padding: var(--spacing-lg);
        border-radius: var(--radius-base);
        margin-bottom: var(--spacing-lg);
    }

    .qr-code-image {
        max-width: 200px;
        margin-bottom: var(--spacing-lg);
    }

    .qr-code-image img {
        width: 100%;
        height: auto;
    }

    .payment-address-box {
        background: var(--color-input-bg);
        padding: var(--spacing-lg);
        border-radius: var(--radius-base);
        margin-bottom: var(--spacing-lg);
    }

    .payment-address-label {
        font-size: 12px;
        font-weight: 600;
        color: var(--color-text-secondary);
        text-transform: uppercase;
        margin-bottom: var(--spacing-md);
    }

    .payment-address {
        background: var(--color-card-bg);
        padding: var(--spacing-md);
        border: 1px solid var(--color-border);
        border-radius: var(--radius-base);
        font-size: 13px;
        font-family: monospace;
        color: var(--color-text-primary);
        word-break: break-all;
        margin-bottom: var(--spacing-md);
    }

    .payment-refresh-btn {
        background: var(--color-accent);
        color: white;
        padding: 8px 12px;
        border: none;
        border-radius: var(--radius-base);
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s ease;
    }

    .payment-refresh-btn:hover {
        background: var(--color-accent-light);
    }

    .payment-instructions {
        background: rgba(33, 150, 243, 0.05);
        border: 1px solid rgba(33, 150, 243, 0.2);
        padding: var(--spacing-lg);
        border-radius: var(--radius-base);
        margin-bottom: var(--spacing-lg);
    }

    .payment-instructions-title {
        font-size: 14px;
        font-weight: 600;
        color: var(--color-text-primary);
        margin: 0 0 var(--spacing-md) 0;
    }

    .payment-instructions-text {
        font-size: 13px;
        color: var(--color-text-secondary);
        line-height: 1.6;
        margin: 0;
    }

    .payment-success-message {
        background: rgba(76, 175, 80, 0.1);
        border: 1px solid rgba(76, 175, 80, 0.2);
        padding: var(--spacing-lg);
        border-radius: var(--radius-base);
        margin-bottom: var(--spacing-lg);
    }

    .payment-success-icon {
        font-size: 24px;
        color: var(--color-success);
        margin-right: var(--spacing-md);
    }

    .payment-success-text {
        font-size: 14px;
        color: var(--color-success);
        font-weight: 600;
    }

    .payment-warning {
        background: rgba(244, 67, 54, 0.05);
        border: 1px solid rgba(244, 67, 54, 0.2);
        padding: var(--spacing-lg);
        border-radius: var(--radius-base);
        margin-bottom: var(--spacing-lg);
    }

    .payment-warning-text {
        font-size: 13px;
        color: var(--color-error);
        line-height: 1.6;
        margin: 0;
    }

    .payment-return-btn {
        display: block;
        background: var(--color-accent);
        color: white;
        padding: 10px 16px;
        border: none;
        border-radius: var(--radius-base);
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        text-align: center;
        transition: background 0.2s ease;
    }

    .payment-return-btn:hover {
        background: var(--color-accent-light);
    }

    @media (max-width: 768px) {
        .payment-container {
            padding: var(--spacing-lg);
        }

        .payment-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="payment-container">
    <div class="payment-grid">
        <div class="payment-details-card">
            <h2 class="payment-title">Payment Details</h2>

            <span class="payment-status-badge payment-status-{{ strtolower($advertisement->payment_status) }}">
                {{ $advertisement->getFormattedPaymentStatus() }}
            </span>

            <div class="payment-info-group">
                <div class="payment-info-label">Product</div>
                <div class="payment-info-value">{{ $advertisement->product->name }}</div>
            </div>

            <div class="payment-info-group">
                <div class="payment-info-label">Advertisement Slot</div>
                <div class="payment-info-value">Slot {{ $advertisement->slot->position }}</div>
            </div>

            <div class="payment-info-group">
                <div class="payment-info-label">Duration</div>
                <div class="payment-info-value">{{ $advertisement->duration }} day{{ $advertisement->duration !== 1 ? 's' : '' }}</div>
            </div>

            <div class="payment-info-group">
                <div class="payment-info-label">Required Amount</div>
                <div class="payment-info-value amount">{{ $advertisement->amount_required }} XMR</div>
            </div>

            @if($advertisement->amount_received > 0)
                <div class="payment-info-group">
                    <div class="payment-info-label">Amount Received</div>
                    <div class="payment-info-value">{{ $advertisement->amount_received }} XMR</div>
                </div>

                <div class="payment-info-group">
                    <div class="payment-info-label">Remaining</div>
                    <div class="payment-info-value">{{ $advertisement->amount_required - $advertisement->amount_received }} XMR</div>
                </div>
            @endif

            @if($advertisement->payment_status === 'completed')
                <div class="payment-success-message">
                    <div style="display: flex; align-items: center;">
                        <span class="payment-success-icon">✓</span>
                        <span class="payment-success-text">Payment completed successfully!</span>
                    </div>
                </div>
            @endif
        </div>

        <div class="payment-details-card">
            <h2 class="payment-title">Send Payment</h2>

            @if($advertisement->payment_status === 'awaiting' || $advertisement->payment_status === 'insufficient')
                <div class="qr-code-container">
                    <div class="qr-code-image">
                        {!! $qrCode !!}
                    </div>
                    <button class="payment-refresh-btn" onclick="location.reload()">Refresh Status</button>
                </div>

                <div class="payment-address-box">
                    <div class="payment-address-label">Monero Payment Address</div>
                    <div class="payment-address">{{ $advertisement->payment_address }}</div>
                    <button class="payment-refresh-btn" onclick="copyToClipboard('{{ $advertisement->payment_address }}')">Copy Address</button>
                </div>

                <div class="payment-instructions">
                    <h3 class="payment-instructions-title">How to Pay</h3>
                    <p class="payment-instructions-text">
                        Send exactly <strong>{{ $advertisement->amount_required }} XMR</strong> to the address above using your Monero wallet. Payments are confirmed on the blockchain and may take a few minutes to appear in this interface.
                    </p>
                </div>

                @if($advertisement->payment_status === 'insufficient')
                    <div class="payment-warning">
                        <p class="payment-warning-text">
                            <strong>⚠️ Insufficient Payment:</strong> We've received {{ $advertisement->amount_received }} XMR, but we need {{ $advertisement->amount_required }} XMR. Please send the remaining amount to complete your advertisement.
                        </p>
                    </div>
                @endif
            @else
                <div style="text-align: center; padding: var(--spacing-xl); background: var(--color-input-bg); border-radius: var(--radius-base);">
                    <p class="payment-success-text">✓ Your payment has been completed!</p>
                    <p style="color: var(--color-text-secondary); margin-top: var(--spacing-md);">Your advertisement will be live shortly.</p>
                </div>
            @endif

            <a href="{{ route('vendor.my-products') }}" class="payment-return-btn" style="margin-top: var(--spacing-lg);">
                Return to My Products
            </a>
        </div>
    </div>
</div>

<script>
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            alert('Address copied to clipboard!');
        });
    }
</script>

@endsection