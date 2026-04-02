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

    .disputes-show-container {
        max-width: 1000px;
        margin: 0 auto;
        padding: var(--spacing-xl);
    }

    .disputes-show-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: var(--spacing-2xl);
        padding-bottom: var(--spacing-lg);
        border-bottom: 2px solid var(--color-accent-light);
    }

    .disputes-show-title {
        font-size: 32px;
        font-weight: 600;
        color: var(--color-text-primary);
        margin: 0;
    }

    .disputes-show-back-link {
        display: inline-block;
        background-color: var(--color-text-secondary);
        color: #ffffff;
        padding: var(--spacing-md) var(--spacing-lg);
        border: none;
        border-radius: var(--radius);
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        text-decoration: none;
        transition: background-color 0.3s ease;
    }

    .disputes-show-back-link:hover {
        background-color: #4b5563;
    }

    .disputes-show-card {
        background-color: var(--color-bg-secondary);
        border: 1px solid var(--color-border);
        border-radius: var(--radius);
        padding: var(--spacing-lg);
        margin-bottom: var(--spacing-xl);
    }

    .disputes-show-section-title {
        font-size: 18px;
        font-weight: 600;
        color: var(--color-accent);
        margin: 0 0 var(--spacing-lg) 0;
        padding-bottom: var(--spacing-md);
        border-bottom: 2px solid var(--color-accent-light);
    }

    .disputes-show-status {
        margin-bottom: var(--spacing-lg);
    }

    .disputes-show-status-badge {
        display: inline-block;
        padding: var(--spacing-sm) var(--spacing-md);
        border-radius: var(--radius);
        font-size: 13px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .disputes-show-status-active {
        background-color: #dcfce7;
        color: #166534;
        border: 1px solid #86efac;
    }

    .disputes-show-status-resolved {
        background-color: #e5e7eb;
        color: #374151;
        border: 1px solid #d1d5db;
    }

    .disputes-show-info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: var(--spacing-lg);
        margin: var(--spacing-lg) 0;
    }

    .disputes-show-info-item {
        background-color: var(--color-bg-primary);
        border: 1px solid var(--color-border);
        border-radius: var(--radius);
        padding: var(--spacing-md);
    }

    .disputes-show-info-label {
        font-size: 12px;
        font-weight: 600;
        color: var(--color-text-secondary);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: var(--spacing-sm);
    }

    .disputes-show-info-value {
        font-size: 14px;
        color: var(--color-text-primary);
        font-weight: 500;
    }

    .disputes-show-order-btn {
        display: inline-block;
        background-color: var(--color-accent);
        color: #ffffff;
        padding: var(--spacing-md) var(--spacing-lg);
        border: none;
        border-radius: var(--radius);
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        text-decoration: none;
        transition: background-color 0.3s ease;
        margin-top: var(--spacing-lg);
    }

    .disputes-show-order-btn:hover {
        background-color: var(--color-accent-light);
    }

    .admin-disputes-show-resolution-card {
        background-color: #fef3c7;
        border: 1px solid #fbbf24;
    }

    .admin-disputes-show-instructions {
        background-color: var(--color-bg-secondary);
        border: 1px solid var(--color-border);
        border-radius: var(--radius);
        padding: var(--spacing-lg);
        margin: var(--spacing-lg) 0;
    }

    .admin-disputes-show-instructions p {
        margin: 0 0 var(--spacing-md) 0;
        color: var(--color-text-primary);
        font-size: 14px;
    }

    .admin-disputes-show-resolution-list {
        margin: 0;
        padding-left: var(--spacing-lg);
        color: var(--color-text-primary);
        font-size: 14px;
    }

    .admin-disputes-show-resolution-list li {
        margin-bottom: var(--spacing-sm);
    }

    .admin-disputes-show-actions {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: var(--spacing-lg);
        margin-top: var(--spacing-lg);
    }

    .admin-disputes-show-form {
        background-color: var(--color-bg-secondary);
        border: 1px solid var(--color-border);
        border-radius: var(--radius);
        padding: var(--spacing-lg);
    }

    .disputes-show-form-group {
        margin-bottom: var(--spacing-lg);
        display: flex;
        flex-direction: column;
    }

    .disputes-show-form-label {
        font-size: 13px;
        font-weight: 600;
        color: var(--color-text-primary);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: var(--spacing-sm);
    }

    .admin-disputes-show-textarea {
        padding: var(--spacing-md);
        border: 1px solid var(--color-border);
        border-radius: var(--radius);
        font-size: 14px;
        color: var(--color-text-primary);
        background-color: var(--color-bg-primary);
        font-family: inherit;
        box-sizing: border-box;
        resize: vertical;
        min-height: 100px;
    }

    .admin-disputes-show-textarea:focus {
        outline: none;
        border-color: var(--color-accent);
        box-shadow: 0 0 0 2px rgba(32, 128, 136, 0.1);
    }

    .admin-disputes-show-vendor-btn {
        width: 100%;
        background-color: #10b981;
        color: #ffffff;
        padding: var(--spacing-md) var(--spacing-lg);
        border: none;
        border-radius: var(--radius);
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .admin-disputes-show-vendor-btn:hover {
        background-color: #059669;
    }

    .admin-disputes-show-buyer-btn {
        width: 100%;
        background-color: #ef4444;
        color: #ffffff;
        padding: var(--spacing-md) var(--spacing-lg);
        border: none;
        border-radius: var(--radius);
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .admin-disputes-show-buyer-btn:hover {
        background-color: #dc2626;
    }

    .disputes-show-messages-list {
        display: flex;
        flex-direction: column;
        gap: var(--spacing-md);
        margin: var(--spacing-lg) 0;
    }

    .disputes-show-message {
        background-color: var(--color-bg-primary);
        border: 1px solid var(--color-border);
        border-left: 4px solid var(--color-accent);
        border-radius: var(--radius);
        padding: var(--spacing-md);
    }

    .disputes-show-message-admin {
        border-left-color: #f59e0b;
        background-color: rgba(245, 158, 11, 0.05);
    }

    .disputes-show-message-buyer {
        border-left-color: #3b82f6;
        background-color: rgba(59, 130, 246, 0.05);
    }

    .disputes-show-message-vendor {
        border-left-color: #10b981;
        background-color: rgba(16, 185, 129, 0.05);
    }

    .disputes-show-message-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: var(--spacing-md);
        padding-bottom: var(--spacing-md);
        border-bottom: 1px solid var(--color-border);
    }

    .disputes-show-message-user {
        font-weight: 600;
        color: var(--color-accent);
    }

    .disputes-show-message-time {
        font-size: 12px;
        color: var(--color-text-secondary);
    }

    .disputes-show-message-content {
        font-size: 14px;
        color: var(--color-text-primary);
        line-height: 1.6;
        white-space: pre-wrap;
        word-wrap: break-word;
    }

    .disputes-show-empty-message {
        background-color: var(--color-bg-secondary);
        border: 1px solid var(--color-border);
        border-radius: var(--radius);
        padding: var(--spacing-lg);
        text-align: center;
        color: var(--color-text-secondary);
        font-size: 14px;
    }

    .disputes-show-textarea {
        width: 100%;
        padding: var(--spacing-md);
        border: 1px solid var(--color-border);
        border-radius: var(--radius);
        font-size: 14px;
        color: var(--color-text-primary);
        background-color: var(--color-bg-secondary);
        font-family: inherit;
        box-sizing: border-box;
        resize: vertical;
        min-height: 100px;
    }

    .disputes-show-textarea:focus {
        outline: none;
        border-color: var(--color-accent);
        box-shadow: 0 0 0 2px rgba(32, 128, 136, 0.1);
    }

    .disputes-show-submit-btn {
        background-color: var(--color-accent);
        color: #ffffff;
        padding: var(--spacing-md) var(--spacing-lg);
        border: none;
        border-radius: var(--radius);
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .disputes-show-submit-btn:hover {
        background-color: var(--color-accent-light);
    }

    .disputes-show-resolved-message {
        background-color: #e5e7eb;
        border: 1px solid #d1d5db;
        border-radius: var(--radius);
        padding: var(--spacing-lg);
        color: #374151;
        text-align: center;
        font-size: 14px;
    }

    @media (max-width: 768px) {
        .disputes-show-container {
            padding: var(--spacing-md);
        }

        .disputes-show-header {
            flex-direction: column;
            gap: var(--spacing-lg);
            align-items: flex-start;
        }

        .disputes-show-title {
            font-size: 24px;
        }

        .disputes-show-back-link {
            width: 100%;
        }

        .disputes-show-info-grid {
            grid-template-columns: 1fr;
        }

        .admin-disputes-show-actions {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="disputes-show-container">
    <div class="disputes-show-header">
        <h1 class="disputes-show-title">Dispute Management</h1>
        <a href="{{ route('admin.disputes.index') }}" class="disputes-show-back-link">Return to Disputes</a>
    </div>

    <div class="disputes-show-card">
        <h2 class="disputes-show-section-title">Dispute Information</h2>
        
        <div class="disputes-show-status">
            <span class="disputes-show-status-badge disputes-show-status-{{ strtolower($dispute->status) }}">
                {{ $dispute->getFormattedStatus() }}
            </span>
        </div>

        <div class="disputes-show-info-grid">
            <div class="disputes-show-info-item">
                <div class="disputes-show-info-label">Order ID</div>
                <div class="disputes-show-info-value">{{ substr($dispute->order->id, 0, 8) }}</div>
            </div>
            <div class="disputes-show-info-item">
                <div class="disputes-show-info-label">Dispute Created</div>
                <div class="disputes-show-info-value">{{ $dispute->created_at->format('Y-m-d / H:i') }}</div>
            </div>
            <div class="disputes-show-info-item">
                <div class="disputes-show-info-label">Buyer</div>
                <div class="disputes-show-info-value">{{ $dispute->order->user->username }}</div>
            </div>
            <div class="disputes-show-info-item">
                <div class="disputes-show-info-label">Vendor</div>
                <div class="disputes-show-info-value">{{ $dispute->order->vendor->username }}</div>
            </div>
            <div class="disputes-show-info-item">
                <div class="disputes-show-info-label">Reason for Dispute</div>
                <div class="disputes-show-info-value">{{ $dispute->reason }}</div>
            </div>
            @if($dispute->resolved_at)
                <div class="disputes-show-info-item">
                    <div class="disputes-show-info-label">Resolved On</div>
                    <div class="disputes-show-info-value">{{ $dispute->resolved_at->format('Y-m-d / H:i') }}</div>
                </div>
                <div class="disputes-show-info-item">
                    <div class="disputes-show-info-label">Resolved By</div>
                    <div class="disputes-show-info-value">{{ $dispute->resolver->username }}</div>
                </div>
            @endif
        </div>

        <div style="text-align: center;">
            <a href="{{ route('orders.show', $dispute->order->unique_url) }}" class="disputes-show-order-btn">
                View Order Details
            </a>
        </div>
    </div>

    @if($dispute->status === \App\Models\Dispute::STATUS_ACTIVE)
        <div class="disputes-show-card admin-disputes-show-resolution-card">
            <h2 class="disputes-show-section-title">Resolve Dispute</h2>
            
            <div class="admin-disputes-show-instructions">
                <p>Please select a resolution for this dispute. This action cannot be undone.</p>
                <ul class="admin-disputes-show-resolution-list">
                    <li><strong>Vendor Prevails:</strong> Order will be marked as completed.</li>
                    <li><strong>Buyer Prevails:</strong> Order will be marked as cancelled.</li>
                </ul>
            </div>

            <div class="admin-disputes-show-actions">
                <form action="{{ route('admin.disputes.vendor-prevails', $dispute->id) }}" method="POST" class="admin-disputes-show-form">
                    @csrf
                    <div class="disputes-show-form-group">
                        <label for="vendor-message" class="disputes-show-form-label">Resolution Message (Optional)</label>
                        <textarea id="vendor-message" name="message" class="admin-disputes-show-textarea" placeholder="Explain why the vendor prevails..."></textarea>
                    </div>
                    <button type="submit" class="admin-disputes-show-vendor-btn">
                        Resolve: Vendor Prevails
                    </button>
                </form>

                <form action="{{ route('admin.disputes.buyer-prevails', $dispute->id) }}" method="POST" class="admin-disputes-show-form">
                    @csrf
                    <div class="disputes-show-form-group">
                        <label for="buyer-message" class="disputes-show-form-label">Resolution Message (Optional)</label>
                        <textarea id="buyer-message" name="message" class="admin-disputes-show-textarea" placeholder="Explain why the buyer prevails..."></textarea>
                    </div>
                    <button type="submit" class="admin-disputes-show-buyer-btn">
                        Resolve: Buyer Prevails
                    </button>
                </form>
            </div>
        </div>
    @endif

    <div class="disputes-show-card">
        <h2 class="disputes-show-section-title">Dispute Messages</h2>

        <div class="disputes-show-messages-list">
            @if($dispute->messages->isEmpty())
                <div class="disputes-show-empty-message">
                    No messages in this dispute yet.
                </div>
            @else
                @foreach($dispute->messages as $message)
                    <div class="disputes-show-message 
                        @if($message->isFromAdmin())
                            disputes-show-message-admin
                        @elseif($message->isFromBuyer())
                            disputes-show-message-buyer
                        @elseif($message->isFromVendor())
                            disputes-show-message-vendor
                        @endif
                    ">
                        <div class="disputes-show-message-header">
                            <div class="disputes-show-message-user">
                                @if($message->isFromAdmin())
                                    Admin: {{ $message->user->username }}
                                @elseif($message->isFromBuyer())
                                    Buyer: {{ $message->user->username }}
                                @elseif($message->isFromVendor())
                                    Vendor: {{ $message->user->username }}
                                @else
                                    {{ $message->user->username }}
                                @endif
                            </div>
                            <div class="disputes-show-message-time">
                                {{ $message->created_at->format('Y-m-d / H:i') }}
                            </div>
                        </div>
                        <div class="disputes-show-message-content">
                            {{ $message->message }}
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        @if($dispute->status === \App\Models\Dispute::STATUS_ACTIVE)
            <form action="{{ route('disputes.add-message', $dispute->id) }}" method="POST" style="margin-top: var(--spacing-lg);">
                @csrf
                <div class="disputes-show-form-group">
                    <label for="message" class="disputes-show-form-label">Add a Message</label>
                    <textarea id="message" name="message" class="disputes-show-textarea" placeholder="Type your message here..." required minlength="1" maxlength="1000"></textarea>
                </div>
                <button type="submit" class="disputes-show-submit-btn">Send Message</button>
            </form>
        @else
            <div class="disputes-show-resolved-message">
                <p>This dispute has been resolved. No new messages can be added.</p>
            </div>
        @endif
    </div>
</div>

@endsection
