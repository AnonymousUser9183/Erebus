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

    .sales-show-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: var(--spacing-xl);
    }

    .sales-show-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: var(--spacing-xl);
    }

    .sales-show-title {
        font-size: 28px;
        font-weight: 600;
        color: var(--color-text-primary);
        margin: 0;
    }

    .sales-show-id {
        font-size: 14px;
        color: var(--color-text-secondary);
    }

    .sales-show-card {
        background: var(--color-card-bg);
        border: 1px solid var(--color-border);
        border-radius: var(--radius-lg);
        padding: var(--spacing-xl);
        margin-bottom: var(--spacing-lg);
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }

    .sales-show-status-title {
        font-size: 18px;
        font-weight: 600;
        color: var(--color-text-primary);
        margin: 0 0 var(--spacing-lg) 0;
    }

    .timeline {
        display: flex;
        justify-content: space-between;
        margin: var(--spacing-xl) 0;
        position: relative;
    }

    .timeline::before {
        content: '';
        position: absolute;
        top: 20px;
        left: 0;
        right: 0;
        height: 2px;
        background: var(--color-border);
    }

    .timeline-step {
        flex: 1;
        text-align: center;
        position: relative;
    }

    .timeline-step-number {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: var(--color-input-bg);
        border: 2px solid var(--color-border);
        font-weight: 600;
        color: var(--color-text-primary);
        margin-bottom: var(--spacing-md);
        position: relative;
        z-index: 1;
    }

    .timeline-step.active .timeline-step-number {
        background: var(--color-accent);
        color: white;
        border-color: var(--color-accent);
    }

    .timeline-step-label {
        font-size: 13px;
        font-weight: 500;
        color: var(--color-text-primary);
        margin-bottom: var(--spacing-md);
    }

    .timeline-step-date {
        font-size: 12px;
        color: var(--color-text-secondary);
    }

    .delivery-form {
        background: var(--color-input-bg);
        padding: var(--spacing-lg);
        border-radius: var(--radius-base);
        margin: var(--spacing-lg) 0;
    }

    .delivery-label {
        display: block;
        font-size: 14px;
        font-weight: 500;
        color: var(--color-text-primary);
        margin-bottom: var(--spacing-md);
    }

    .delivery-textarea {
        width: 100%;
        padding: var(--spacing-md);
        border: 1px solid var(--color-border);
        border-radius: var(--radius-base);
        font-family: inherit;
        font-size: 14px;
        color: var(--color-text-primary);
        margin-bottom: var(--spacing-md);
    }

    .sales-action-btn {
        display: inline-block;
        background: var(--color-accent);
        color: white;
        padding: 10px 16px;
        border-radius: var(--radius-base);
        text-decoration: none;
        font-weight: 500;
        font-size: 13px;
        border: none;
        cursor: pointer;
        transition: background 0.2s ease;
    }

    .sales-action-btn:hover {
        background: var(--color-accent-light);
    }

    .sales-action-btn-cancel {
        background: var(--color-error);
    }

    .sales-action-btn-cancel:hover {
        background: #d32f2f;
    }

    .dispute-card {
        background: rgba(244, 67, 54, 0.05);
        border: 1px solid rgba(244, 67, 54, 0.2);
        padding: var(--spacing-lg);
        border-radius: var(--radius-base);
        margin-top: var(--spacing-lg);
    }

    .dispute-title {
        font-size: 16px;
        font-weight: 600;
        color: var(--color-error);
        margin: 0 0 var(--spacing-md) 0;
    }

    .dispute-status {
        display: inline-block;
        background: rgba(244, 67, 54, 0.1);
        color: var(--color-error);
        padding: 4px 12px;
        border-radius: var(--radius-base);
        font-size: 12px;
        font-weight: 600;
        margin-bottom: var(--spacing-md);
    }

    .auto-cancel-notice {
        background: rgba(255, 193, 7, 0.1);
        border: 1px solid rgba(255, 193, 7, 0.2);
        padding: var(--spacing-lg);
        border-radius: var(--radius-base);
        color: #f57f17;
        font-size: 13px;
        line-height: 1.6;
        margin-bottom: var(--spacing-lg);
    }

    @media (max-width: 768px) {
        .sales-show-container {
            padding: var(--spacing-lg);
        }

        .timeline {
            flex-direction: column;
        }

        .timeline::before {
            left: 20px;
            top: 0;
            height: auto;
        }

        .timeline-step {
            text-align: left;
            margin-bottom: var(--spacing-lg);
        }

        .timeline-step-number {
            margin-right: var(--spacing-md);
        }
    }
</style>

<div class="sales-show-container">
    <div class="sales-show-header">
        <h1 class="sales-show-title">Sale Details</h1>
        <div class="sales-show-id">ID: {{ substr($sale->id, 0, 8) }}</div>
    </div>

    <div class="sales-show-card">
        <h2 class="sales-show-status-title">Status: {{ $sale->getFormattedStatus() }}</h2>
        
        <div class="timeline">
            <div class="timeline-step {{ $sale->status === 'waiting_payment' || $sale->is_paid || $sale->is_sent || $sale->is_completed ? 'active' : '' }}">
                <div class="timeline-step-number">1</div>
                <div class="timeline-step-label">Waiting for Payment</div>
                @if($sale->created_at)
                    <div class="timeline-step-date">{{ $sale->created_at->format('Y-m-d / H:i') }}</div>
                @endif
            </div>
            <div class="timeline-step {{ $sale->is_paid || $sale->is_sent || $sale->is_completed ? 'active' : '' }}">
                <div class="timeline-step-number">2</div>
                <div class="timeline-step-label">Payment Received</div>
                @if($sale->paid_at)
                    <div class="timeline-step-date">{{ $sale->paid_at->format('Y-m-d / H:i') }}</div>
                @endif
            </div>
            <div class="timeline-step {{ $sale->is_sent || $sale->is_completed ? 'active' : '' }}">
                <div class="timeline-step-number">3</div>
                <div class="timeline-step-label">Product Sent</div>
                @if($sale->sent_at)
                    <div class="timeline-step-date">{{ $sale->sent_at->format('Y-m-d / H:i') }}</div>
                @endif
            </div>
            <div class="timeline-step {{ $sale->is_completed ? 'active' : '' }}">
                <div class="timeline-step-number">4</div>
                <div class="timeline-step-label">Order Completed</div>
                @if($sale->completed_at)
                    <div class="timeline-step-date">{{ $sale->completed_at->format('Y-m-d / H:i') }}</div>
                @endif
            </div>
        </div>

        @if($sale->status === 'payment_received')
            <div class="auto-cancel-notice">
                <strong>Important Notice:</strong> This order will be automatically cancelled if not marked as sent within <strong>96 hours (4 days)</strong> after payment was received.
                @if($sale->getAutoCancelDeadline())
                    <br>Auto-cancel deadline: <strong>{{ $sale->getAutoCancelDeadline()->format('Y-m-d H:i') }}</strong>
                @endif
            </div>

            <form action="{{ route('vendor.sales.update-delivery-text', $sale->unique_url) }}" method="POST" class="delivery-form">
                @csrf
                <h3 class="sales-show-status-title">Delivery Information</h3>
                <p>Please enter delivery information for each product below before marking the order as sent</p>
                
                @foreach($sale->items as $item)
                    @if($item->product)
                        <div style="margin-bottom: var(--spacing-lg);">
                            <label class="delivery-label">
                                <strong>{{ $item->product_name }}</strong>
                                <br><span style="font-size: 12px; color: var(--color-text-secondary);">Enter delivery details (e.g., GPS coordinates, a website link, or cargo tracking number)</span>
                            </label>
                            <textarea 
                                name="delivery_text[{{ $item->product_id }}]" 
                                class="delivery-textarea"
                                rows="3" 
                                minlength="8"
                                maxlength="800"
                                placeholder="Enter delivery details (8-800 characters)"
                                required>{{ $item->delivery_text }}</textarea>
                        </div>
                    @endif
                @endforeach
                
                <button type="submit" class="sales-action-btn">Update Delivery Information</button>
            </form>

            <form action="{{ route('orders.mark-sent', $sale->unique_url) }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="sales-action-btn">Deliver Products</button>
            </form>
        @endif

        @if($sale->status !== 'completed' && $sale->status !== 'cancelled' && !$sale->dispute)
            <form action="{{ route('orders.mark-cancelled', $sale->unique_url) }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="sales-action-btn sales-action-btn-cancel">Cancel Sale</button>
            </form>
        @endif
    </div>

    @if($sale->dispute)
        <div class="sales-show-card">
            <div class="dispute-card">
                <h2 class="dispute-title">Dispute Information</h2>
                <div class="dispute-status">{{ $sale->dispute->getFormattedStatus() }}</div>
                <p><strong>Reason:</strong> {{ $sale->dispute->reason }}</p>
                @if($sale->dispute->resolved_at)
                    <p><strong>Resolved on:</strong> {{ $sale->dispute->resolved_at->format('Y-m-d / H:i') }}</p>
                @endif
                <a href="{{ route('vendor.disputes.show', $sale->dispute->id) }}" class="sales-action-btn">View Dispute Chat</a>
            </div>
        </div>
    @endif
</div>

@endsection