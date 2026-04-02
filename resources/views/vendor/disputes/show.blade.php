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

    .dispute-show-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: var(--spacing-xl);
    }

    .dispute-show-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: var(--spacing-xl);
    }

    .dispute-show-title {
        font-size: 28px;
        font-weight: 600;
        color: var(--color-text-primary);
        margin: 0;
    }

    .dispute-show-back {
        color: var(--color-accent);
        text-decoration: none;
        font-weight: 500;
    }

    .dispute-show-back:hover {
        color: var(--color-accent-light);
    }

    .dispute-show-card {
        background: var(--color-card-bg);
        border: 1px solid var(--color-border);
        border-radius: var(--radius-lg);
        padding: var(--spacing-xl);
        margin-bottom: var(--spacing-lg);
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }

    .dispute-show-section-title {
        font-size: 16px;
        font-weight: 600;
        color: var(--color-text-primary);
        margin: 0 0 var(--spacing-lg) 0;
    }

    .dispute-info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: var(--spacing-lg);
        margin-bottom: var(--spacing-lg);
    }

    .dispute-info-item {
        background: var(--color-input-bg);
        padding: var(--spacing-md);
        border-radius: var(--radius-base);
    }

    .dispute-info-label {
        font-size: 12px;
        font-weight: 600;
        color: var(--color-text-secondary);
        text-transform: uppercase;
        margin-bottom: var(--spacing-md);
    }

    .dispute-info-value {
        font-size: 14px;
        font-weight: 500;
        color: var(--color-text-primary);
    }

    .dispute-status-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: var(--radius-base);
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        margin-bottom: var(--spacing-lg);
    }

    .dispute-status-open {
        background: rgba(33, 150, 243, 0.1);
        color: #1976d2;
    }

    .dispute-status-in_progress {
        background: rgba(255, 193, 7, 0.1);
        color: #f57f17;
    }

    .dispute-status-closed {
        background: rgba(76, 175, 80, 0.1);
        color: #388e3c;
    }

    .messages-list {
        background: var(--color-input-bg);
        border: 1px solid var(--color-border);
        border-radius: var(--radius-base);
        max-height: 400px;
        overflow-y: auto;
        padding: var(--spacing-lg);
        margin-bottom: var(--spacing-lg);
    }

    .message {
        margin-bottom: var(--spacing-lg);
        padding-bottom: var(--spacing-lg);
        border-bottom: 1px solid var(--color-border);
    }

    .message:last-child {
        border-bottom: none;
    }

    .message-header {
        display: flex;
        justify-content: space-between;
        margin-bottom: var(--spacing-md);
        font-size: 12px;
    }

    .message-user {
        font-weight: 600;
        color: var(--color-text-primary);
    }

    .message-user-admin {
        color: var(--color-error);
    }

    .message-user-buyer {
        color: var(--color-info);
    }

    .message-user-vendor {
        color: var(--color-accent);
    }

    .message-time {
        color: var(--color-text-secondary);
    }

    .message-content {
        color: var(--color-text-primary);
        font-size: 14px;
        line-height: 1.5;
    }

    .message-admin {
        background: rgba(244, 67, 54, 0.05);
        border-left: 3px solid var(--color-error);
        padding: var(--spacing-md);
        border-radius: var(--radius-base);
    }

    .dispute-form-group {
        margin-bottom: var(--spacing-lg);
    }

    .dispute-form-label {
        display: block;
        font-size: 14px;
        font-weight: 500;
        color: var(--color-text-primary);
        margin-bottom: var(--spacing-md);
    }

    .dispute-textarea {
        width: 100%;
        padding: var(--spacing-md);
        border: 1px solid var(--color-border);
        border-radius: var(--radius-base);
        font-family: inherit;
        font-size: 14px;
        color: var(--color-text-primary);
        resize: vertical;
    }

    .dispute-btn {
        background: var(--color-accent);
        color: white;
        padding: 10px 16px;
        border: none;
        border-radius: var(--radius-base);
        font-weight: 500;
        font-size: 13px;
        cursor: pointer;
        transition: background 0.2s ease;
    }

    .dispute-btn:hover {
        background: var(--color-accent-light);
    }

    .dispute-resolved-message {
        background: rgba(76, 175, 80, 0.1);
        border: 1px solid rgba(76, 175, 80, 0.2);
        color: #388e3c;
        padding: var(--spacing-lg);
        border-radius: var(--radius-base);
        text-align: center;
    }

    .dispute-order-btn {
        display: block;
        text-align: center;
        background: var(--color-accent);
        color: white;
        padding: 10px 16px;
        border-radius: var(--radius-base);
        text-decoration: none;
        font-weight: 500;
        margin-top: var(--spacing-lg);
        transition: background 0.2s ease;
    }

    .dispute-order-btn:hover {
        background: var(--color-accent-light);
    }

    @media (max-width: 768px) {
        .dispute-show-container {
            padding: var(--spacing-lg);
        }

        .dispute-show-header {
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>

<div class="dispute-show-container">
    <div class="dispute-show-header">
        <h1 class="dispute-show-title">Dispute Details</h1>
        <a href="{{ route('vendor.disputes.index') }}" class="dispute-show-back">← Return to Disputes</a>
    </div>

    <div class="dispute-show-card">
        <h2 class="dispute-show-section-title">Order Information</h2>
        <div class="dispute-status-badge dispute-status-{{ $dispute->status }}">
            {{ $dispute->getFormattedStatus() }}
        </div>
        
        <div class="dispute-info-grid">
            <div class="dispute-info-item">
                <div class="dispute-info-label">Order ID</div>
                <div class="dispute-info-value">{{ substr($dispute->order->id, 0, 8) }}</div>
            </div>
            <div class="dispute-info-item">
                <div class="dispute-info-label">Created</div>
                <div class="dispute-info-value">{{ $dispute->created_at->format('Y-m-d / H:i') }}</div>
            </div>
            <div class="dispute-info-item">
                <div class="dispute-info-label">Buyer</div>
                <div class="dispute-info-value">{{ $dispute->order->user->username }}</div>
            </div>
            <div class="dispute-info-item">
                <div class="dispute-info-label">Reason</div>
                <div class="dispute-info-value">{{ $dispute->reason }}</div>
            </div>
            @if($dispute->resolved_at)
                <div class="dispute-info-item">
                    <div class="dispute-info-label">Resolved On</div>
                    <div class="dispute-info-value">{{ $dispute->resolved_at->format('Y-m-d / H:i') }}</div>
                </div>
            @endif
        </div>

        <a href="{{ route('vendor.sales.show', $dispute->order->unique_url) }}" class="dispute-order-btn">
            View Order Details
        </a>
    </div>

    <div class="dispute-show-card">
        <h2 class="dispute-show-section-title">Dispute Messages</h2>
        
        <div class="messages-list">
            @if($dispute->messages->isEmpty())
                <p style="text-align: center; color: var(--color-text-secondary);">No messages in this dispute yet.</p>
            @else
                @foreach($dispute->messages as $message)
                    <div class="message @if($message->isFromAdmin()) message-admin @endif">
                        <div class="message-header">
                            <span class="message-user @if($message->isFromAdmin()) message-user-admin @elseif($message->isFromBuyer()) message-user-buyer @elseif($message->isFromVendor()) message-user-vendor @endif">
                                @if($message->isFromAdmin())
                                    Admin: {{ $message->user->username }}
                                @elseif($message->isFromBuyer())
                                    Buyer: {{ $message->user->username }}
                                @elseif($message->isFromVendor())
                                    Vendor: {{ $message->user->username }}
                                @else
                                    {{ $message->user->username }}
                                @endif
                            </span>
                            <span class="message-time">{{ $message->created_at->format('Y-m-d / H:i') }}</span>
                        </div>
                        <div class="message-content">{{ $message->message }}</div>
                    </div>
                @endforeach
            @endif
        </div>
        
        @if($dispute->status === 'active' || $dispute->status === 'open' || $dispute->status === 'in_progress')
            <div class="dispute-form-group">
                <form action="{{ route('disputes.add-message', $dispute->id) }}" method="POST">
                    @csrf
                    <label class="dispute-form-label">Add a Message</label>
                    <textarea 
                        name="message" 
                        class="dispute-textarea"
                        placeholder="Type your message here..." 
                        required 
                        minlength="1" 
                        maxlength="1000"
                        rows="4"></textarea>
                    <button type="submit" class="dispute-btn" style="margin-top: var(--spacing-md);">Send Message</button>
                </form>
            </div>
        @else
            <div class="dispute-resolved-message">
                <p>This dispute has been resolved. No new messages can be added.</p>
            </div>
        @endif
    </div>
</div>

@endsection