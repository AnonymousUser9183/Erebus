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
            --spacing-md: 16px;
            --spacing-lg: 20px;
            --spacing-xl: 24px;
            --radius: 8px;
        }

        .disputes-show-container {
            max-width: 1000px;
            margin: 0 auto;
            padding: var(--spacing-lg);
        }

        /* Navigation */
        .disputes-show-nav {
            display: flex;
            gap: var(--spacing-md);
            margin-bottom: var(--spacing-xl);
            border-bottom: 2px solid var(--color-border);
        }

        .disputes-show-nav-link {
            padding: var(--spacing-md) var(--spacing-lg);
            background: none;
            border: none;
            color: var(--color-text-secondary);
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            border-bottom: 3px solid transparent;
            transition: all 0.3s ease;
            margin-bottom: -2px;
            text-decoration: none;
            display: inline-block;
        }

        .disputes-show-nav-link:hover {
            color: var(--color-text-primary);
        }

        .disputes-show-nav-link.active {
            color: var(--color-accent);
            border-bottom-color: var(--color-accent);
        }

        .disputes-show-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: var(--spacing-xl);
            flex-wrap: wrap;
            gap: var(--spacing-lg);
        }

        .disputes-show-title {
            font-size: 28px;
            font-weight: 700;
            color: var(--color-text-primary);
            margin: 0;
        }

        .disputes-show-back-link {
            color: var(--color-accent);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .disputes-show-back-link:hover {
            text-decoration: underline;
        }

        .disputes-show-card {
            background-color: var(--color-bg-secondary);
            border: 1px solid var(--color-border);
            border-radius: var(--radius);
            padding: var(--spacing-xl);
            margin-bottom: var(--spacing-xl);
        }

        .disputes-show-section-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--color-text-primary);
            margin: 0 0 var(--spacing-lg) 0;
        }

        .disputes-show-status {
            margin-bottom: var(--spacing-xl);
        }

        .disputes-show-status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .disputes-show-status-active {
            background-color: #dbeafe;
            color: #1e3a8a;
        }

        .disputes-show-status-resolved {
            background-color: #dcfce7;
            color: #166534;
        }

        .disputes-show-info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: var(--spacing-lg);
            margin-bottom: var(--spacing-xl);
        }

        .disputes-show-info-item {
            background-color: var(--color-bg-primary);
            padding: var(--spacing-lg);
            border-radius: var(--radius);
        }

        .disputes-show-info-label {
            font-size: 12px;
            font-weight: 600;
            color: var(--color-text-secondary);
            text-transform: uppercase;
            margin-bottom: 6px;
        }

        .disputes-show-info-value {
            font-size: 14px;
            font-weight: 500;
            color: var(--color-text-primary);
            word-break: break-word;
        }

        .disputes-show-order-btn {
            display: inline-block;
            padding: var(--spacing-md) var(--spacing-lg);
            background-color: var(--color-accent);
            color: #ffffff;
            text-decoration: none;
            border-radius: var(--radius);
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .disputes-show-order-btn:hover {
            background-color: #1a6f7a;
        }

        .disputes-show-messages-list {
            max-height: 400px;
            overflow-y: auto;
            border: 1px solid var(--color-border);
            border-radius: var(--radius);
            padding: var(--spacing-lg);
            margin-bottom: var(--spacing-xl);
            background-color: var(--color-bg-primary);
        }

        .disputes-show-empty-message {
            text-align: center;
            color: var(--color-text-secondary);
            padding: var(--spacing-xl);
        }

        .disputes-show-message {
            margin-bottom: var(--spacing-lg);
            padding: var(--spacing-lg);
            background-color: var(--color-bg-secondary);
            border-left: 4px solid var(--color-border);
            border-radius: var(--radius);
        }

        .disputes-show-message-admin {
            border-left-color: #f59e0b;
        }

        .disputes-show-message-buyer {
            border-left-color: var(--color-accent);
        }

        .disputes-show-message-vendor {
            border-left-color: #dc2626;
        }

        .disputes-show-message-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
            flex-wrap: wrap;
            gap: 8px;
        }

        .disputes-show-message-user {
            font-weight: 600;
            color: var(--color-text-primary);
            font-size: 13px;
        }

        .disputes-show-message-time {
            font-size: 12px;
            color: var(--color-text-secondary);
        }

        .disputes-show-message-content {
            color: var(--color-text-primary);
            line-height: 1.6;
            word-break: break-word;
        }

        .disputes-show-form-group {
            margin-bottom: var(--spacing-lg);
        }

        .disputes-show-form-label {
            display: block;
            font-weight: 600;
            color: var(--color-text-primary);
            margin-bottom: 8px;
            font-size: 14px;
        }

        .disputes-show-textarea {
            width: 100%;
            padding: var(--spacing-md);
            border: 1px solid var(--color-border);
            border-radius: var(--radius);
            font-family: inherit;
            font-size: 14px;
            color: var(--color-text-primary);
            resize: vertical;
            min-height: 100px;
        }

        .disputes-show-textarea:focus {
            outline: none;
            border-color: var(--color-accent);
            box-shadow: 0 0 0 3px rgba(32, 128, 136, 0.1);
        }

        .disputes-show-submit-btn {
            padding: var(--spacing-md) var(--spacing-lg);
            background-color: var(--color-accent);
            color: #ffffff;
            border: none;
            border-radius: var(--radius);
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .disputes-show-submit-btn:hover {
            background-color: #1a6f7a;
        }

        .disputes-show-resolved-message {
            padding: var(--spacing-lg);
            background-color: #dcfce7;
            border: 1px solid #16a34a;
            border-radius: var(--radius);
            color: #166534;
        }

        @media (max-width: 768px) {
            .disputes-show-container {
                padding: var(--spacing-md);
            }

            .disputes-show-title {
                font-size: 20px;
            }

            .disputes-show-info-grid {
                grid-template-columns: 1fr;
            }

            .disputes-show-messages-list {
                max-height: 300px;
            }
        }
    </style>

    <div class="disputes-show-container">
        <!-- Navigation -->
        <div class="disputes-show-nav">
            <a href="{{ route('support.index') }}" class="disputes-show-nav-link">
                📝 Support Requests
            </a>
            <a href="{{ route('disputes.index') }}" class="disputes-show-nav-link active">
                ⚖️ Disputes
            </a>
        </div>

        <div class="disputes-show-header">
            <h1 class="disputes-show-title">Dispute Details</h1>
            <a href="{{ route('disputes.index') }}" class="disputes-show-back-link">← Back to Disputes</a>
        </div>

        <div class="disputes-show-card">
            <h2 class="disputes-show-section-title">Order Information</h2>
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
                    <div class="disputes-show-info-label">Created</div>
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
                @endif
            </div>
            
            <div style="text-align: center;">
                <a href="{{ route('orders.show', $dispute->order->unique_url) }}" class="disputes-show-order-btn">
                    View Order Details
                </a>
            </div>
        </div>

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
                <form action="{{ route('disputes.add-message', $dispute->id) }}" method="POST">
                    @csrf
                    <div class="disputes-show-form-group">
                        <label for="message" class="disputes-show-form-label">Add a Message</label>
                        <textarea 
                            id="message" 
                            name="message" 
                            class="disputes-show-textarea"
                            placeholder="Type your message here..." 
                            required 
                            minlength="4" 
                            maxlength="800"></textarea>
                    </div>
                    <div>
                        <button type="submit" class="disputes-show-submit-btn">Send Message</button>
                    </div>
                </form>
            @else
                <div class="disputes-show-resolved-message">
                    <p>This dispute has been resolved. No new messages can be added.</p>
                </div>
            @endif
        </div>
    </div>
@endsection
