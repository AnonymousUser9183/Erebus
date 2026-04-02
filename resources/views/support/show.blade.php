@extends('layouts.app')

@section('content')
<div class="support-container">
    <!-- Navigation Tabs -->
    <div class="support-tabs">
        <a href="{{ route('support.index') }}" class="support-tab active">
            Support Requests
        </a>
        <a href="{{ route('disputes.index') }}" class="support-tab">
            Disputes
        </a>
    </div>

    <!-- Header -->
    <div class="support-header">
        <h1>Support Request #{{ $supportRequest->ticket_id }}</h1>
        <p>{{ $supportRequest->subject }}</p>
    </div>

    <!-- Alerts -->
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('info'))
        <div class="alert alert-info">
            {{ session('info') }}
        </div>
    @endif

    <!-- Support Request Details -->
    <div class="support-details">
        <div class="detail-row">
            <span class="label">Status:</span>
            <span class="status status-{{ strtolower($supportRequest->status) }}">
                {{ ucfirst($supportRequest->status) }}
            </span>
        </div>
        <div class="detail-row">
            <span class="label">Category:</span>
            <span>{{ ucfirst($supportRequest->category) }}</span>
        </div>
        <div class="detail-row">
            <span class="label">Created:</span>
            <span>{{ $supportRequest->created_at->format('M d, Y H:i') }}</span>
        </div>
    </div>

    <!-- Original Message -->
    <div class="support-message original-message">
        <div class="message-header">
            <strong>Your Request</strong>
            <span class="time">{{ $supportRequest->created_at->format('M d, Y') }}</span>
        </div>
        <div class="message-body">
            {{ $supportRequest->message }}
        </div>
    </div>

    <!-- Replies Section -->
    <div class="replies-section">
        <h2>Replies</h2>

        @if ($supportRequest->messages->isEmpty())
            <div class="no-replies">
                <p>No replies yet. Please check back later.</p>
            </div>
        @else
            <div class="replies-list">
                @foreach ($supportRequest->messages as $reply)
                    <div class="support-message reply-message">
                        <div class="message-header">
                            <strong>
                                @if ($reply->user_id === Auth::id())
                                    You
                                @else
                                    Support Team
                                @endif
                            </strong>
                            <span class="time">{{ $reply->created_at->format('M d, Y H:i') }}</span>
                        </div>
                        <div class="message-body">
                            {{ $reply->message }}
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Reply Form -->
    @if ($supportRequest->status !== 'closed')
        <div class="reply-form-container">
            <h2>Add Reply</h2>

            <!-- Updated route to use solvePowReply with both parameters -->
            <form method="POST" action="{{ route('support.solve-reply-pow', [$supportRequest->id, $supportRequest->ticket_id]) }}" class="reply-form">
                @csrf

                <!-- Message Field -->
                <div class="form-group">
                    <label for="reply-message">Your Reply *</label>
                    <textarea 
                        id="reply-message" 
                        name="message" 
                        class="form-control @error('message') is-invalid @enderror"
                        rows="5"
                        placeholder="Type your reply here..."
                        required
                        minlength="5"
                        maxlength="2000"
                    >{{ old('message') }}</textarea>
                    <small>5-2000 characters</small>
                    @error('message')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <!-- PoW Verification Section -->
                <div class="pow-section">
                    <div class="pow-header">
                        <h3>🔒 Proof of Work Verification</h3>
                        <p>Click the button below to solve the computational challenge and submit your reply.</p>
                    </div>

                    <div class="pow-challenge">
                        <p>
                            <strong>Challenge Hash:</strong><br>
                            <code>{{ session('pow_challenge') ? substr(session('pow_challenge'), 0, 32) . '...' : 'Generating...' }}</code>
                        </p>
                        <p>
                            <strong>Difficulty:</strong> {{ session('pow_difficulty', 4) }} (65,536 average hashes)
                        </p>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary btn-large">
                        🔐 Compute PoW & Submit Reply
                    </button>
                </div>

                <!-- Help Text -->
                <div class="form-help">
                    <p>
                        <strong>How it works:</strong> Click "Compute PoW & Submit Reply" above. Our server will solve a 
                        computational challenge to verify you're human. Once solved, your reply will be automatically submitted. 
                        No JavaScript required.
                    </p>
                </div>
            </form>
        </div>
    @else
        <div class="reply-closed-notice">
            <p>This support request is closed. No additional replies can be added.</p>
        </div>
    @endif

    <!-- Back Button -->
    <div class="form-actions">
        <a href="{{ route('support.index') }}" class="btn btn-secondary">
            ← Back to Support Requests
        </a>
    </div>
</div>

<style>
.support-container {
    max-width: 900px;
    margin: 0 auto;
    padding: 20px;
}

.support-tabs {
    display: flex;
    gap: 0;
    margin-bottom: 30px;
    border-bottom: 1px solid #ddd;
}

.support-tab {
    padding: 12px 20px;
    color: #666;
    text-decoration: none;
    border-bottom: 3px solid transparent;
    transition: all 0.3s;
}

.support-tab:hover {
    color: #333;
}

.support-tab.active {
    color: #0066cc;
    border-bottom-color: #0066cc;
}

.support-header {
    margin-bottom: 30px;
}

.support-header h1 {
    margin: 0 0 10px 0;
    font-size: 28px;
}

.support-header p {
    margin: 0;
    color: #666;
    font-size: 16px;
}

.support-details {
    background: #f8f9fa;
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 15px;
    margin-bottom: 20px;
    display: flex;
    gap: 30px;
    flex-wrap: wrap;
}

.detail-row {
    display: flex;
    gap: 10px;
}

.detail-row .label {
    font-weight: 600;
    min-width: 80px;
}

.status {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: bold;
    text-transform: uppercase;
}

.status-open {
    background: #fff3cd;
    color: #856404;
}

.status-in_progress {
    background: #cfe2ff;
    color: #084298;
}

.status-closed {
    background: #d4edda;
    color: #155724;
}

.support-message {
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 15px;
    margin-bottom: 15px;
    background: white;
}

.original-message {
    background: #f0f8ff;
    border-left: 4px solid #0066cc;
}

.message-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
    padding-bottom: 8px;
    border-bottom: 1px solid #eee;
}

.message-header strong {
    color: #333;
}

.time {
    font-size: 12px;
    color: #999;
}

.message-body {
    color: #555;
    line-height: 1.6;
    white-space: pre-wrap;
    word-wrap: break-word;
}

.replies-section {
    margin: 30px 0;
}

.replies-section h2 {
    margin: 0 0 20px 0;
    font-size: 20px;
    color: #333;
}

.no-replies {
    padding: 20px;
    text-align: center;
    color: #999;
    background: #f8f9fa;
    border-radius: 4px;
    margin-bottom: 20px;
}

.replies-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.reply-message {
    background: #fefef8;
}

.reply-form-container {
    background: white;
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 25px;
    margin-bottom: 20px;
}

.reply-form-container h2 {
    margin: 0 0 20px 0;
    font-size: 20px;
    color: #333;
}

.reply-closed-notice {
    background: #e5e7eb;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    padding: 20px;
    text-align: center;
    color: #374151;
    margin: 20px 0;
}

.form-group {
    margin-bottom: 20px;
    display: flex;
    flex-direction: column;
}

.form-group label {
    font-weight: 600;
    margin-bottom: 8px;
    color: #333;
}

.form-control {
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-family: inherit;
    font-size: 14px;
    transition: border-color 0.3s;
    box-sizing: border-box;
}

.form-control:focus {
    outline: none;
    border-color: #0066cc;
    box-shadow: 0 0 0 3px rgba(0, 102, 204, 0.1);
}

.form-control.is-invalid {
    border-color: #dc3545;
    background-color: #fff5f5;
}

.form-group small {
    margin-top: 5px;
    font-size: 12px;
    color: #999;
}

.invalid-feedback {
    color: #dc3545;
    font-size: 12px;
    margin-top: 4px;
}

.pow-section {
    background: #f8f9fa;
    border: 2px solid #0066cc;
    border-radius: 8px;
    padding: 20px;
    margin: 20px 0;
}

.pow-header h3 {
    margin: 0 0 10px 0;
    color: #0066cc;
    font-size: 18px;
}

.pow-header p {
    margin: 0;
    color: #666;
    font-size: 14px;
}

.pow-challenge {
    background: white;
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 15px;
    margin: 15px 0;
    font-size: 13px;
}

.pow-challenge p {
    margin: 8px 0;
}

.pow-challenge code {
    background: #f0f0f0;
    padding: 4px 8px;
    border-radius: 3px;
    font-family: monospace;
    word-break: break-all;
}

.btn {
    padding: 12px 24px;
    border: none;
    border-radius: 4px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    text-decoration: none;
    display: inline-block;
    text-align: center;
}

.btn-primary {
    background: #0066cc;
    color: white;
}

.btn-primary:hover {
    background: #0052a3;
}

.btn-large {
    padding: 14px 28px;
    font-size: 16px;
    width: 100%;
}

.btn-secondary {
    background: #6c757d;
    color: white;
}

.btn-secondary:hover {
    background: #5a6268;
}

.form-help {
    background: #e7f3ff;
    border: 1px solid #b3d9ff;
    border-radius: 4px;
    padding: 15px;
    margin-top: 20px;
    font-size: 13px;
    color: #333;
}

.form-help p {
    margin: 0;
    line-height: 1.6;
}

.form-actions {
    margin-top: 20px;
}

.alert {
    padding: 15px;
    border-radius: 4px;
    margin-bottom: 20px;
}

.alert-success {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.alert-danger {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.alert-info {
    background: #d1ecf1;
    color: #0c5460;
    border: 1px solid #bee5eb;
}

textarea.form-control {
    resize: vertical;
    min-height: 100px;
}

@media (max-width: 768px) {
    .support-details {
        flex-direction: column;
        gap: 10px;
    }
}
</style>
@endsection
