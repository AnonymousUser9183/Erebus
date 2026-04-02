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
        <div class="header-content">
            <h1>Support Requests</h1>
            <p>View and manage your support tickets</p>
        </div>
        <a href="{{ route('support.create') }}" class="btn btn-primary">
            + Create New Request
        </a>
    </div>

    <!-- Alerts -->
    @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

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

    <!-- Support Requests List -->
    @if ($supportRequests->isEmpty())
        <div class="support-empty">
            <p>You don't have any support requests yet.</p>
            <a href="{{ route('support.create') }}" class="btn btn-primary">
                Create Your First Request
            </a>
        </div>
    @else
        <div class="support-list">
            @foreach ($supportRequests as $request)
                <div class="support-card">
                    <div class="card-header">
                        <h3>
                            <!-- Updated to pass both parameters -->
                            <a href="{{ route('support.show', [$request->id, $request->ticket_id]) }}">
                                {{ $request->subject }}
                            </a>
                        </h3>
                        <span class="status status-{{ strtolower($request->status) }}">
                            {{ ucfirst($request->status) }}
                        </span>
                    </div>

                    <div class="card-meta">
                        <span class="meta-item">
                            <strong>Category:</strong> {{ ucfirst($request->category) }}
                        </span>
                        <span class="meta-item">
                            <strong>Created:</strong> {{ $request->created_at->format('M d, Y') }}
                        </span>
                        @if ($request->messages && $request->messages->count() > 0)
                            <span class="meta-item">
                                <strong>Replies:</strong> {{ $request->messages->count() }}
                            </span>
                        @endif
                    </div>

                    <div class="card-preview">
                        {{ Str::limit($request->message, 150) }}
                    </div>

                    <div class="card-actions">
                        <!-- Updated to pass both parameters -->
                        <a href="{{ route('support.show', [$request->id, $request->ticket_id]) }}" class="btn btn-sm btn-primary">
                            View Details
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="pagination-wrapper">
            {{ $supportRequests->links() }}
        </div>
    @endif
</div>

<style>
.support-container {
    max-width: 1000px;
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
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 30px;
    gap: 20px;
}

.header-content h1 {
    margin: 0 0 5px 0;
    font-size: 28px;
    color: #333;
}

.header-content p {
    margin: 0;
    color: #666;
    font-size: 14px;
}

.support-list {
    display: grid;
    gap: 15px;
    margin-bottom: 30px;
}

.support-card {
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 20px;
    background: white;
    transition: box-shadow 0.3s;
}

.support-card:hover {
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
    padding-bottom: 12px;
    border-bottom: 1px solid #eee;
}

.card-header h3 {
    margin: 0;
    font-size: 16px;
    flex: 1;
}

.card-header a {
    color: #0066cc;
    text-decoration: none;
}

.card-header a:hover {
    text-decoration: underline;
}

.status {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: bold;
    text-transform: uppercase;
    white-space: nowrap;
    margin-left: 15px;
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

.card-meta {
    display: flex;
    gap: 20px;
    margin-bottom: 12px;
    font-size: 13px;
    color: #666;
    flex-wrap: wrap;
}

.meta-item {
    display: flex;
    align-items: center;
}

.meta-item strong {
    margin-right: 6px;
    color: #333;
}

.card-preview {
    margin-bottom: 15px;
    color: #555;
    font-size: 14px;
    line-height: 1.5;
}

.card-actions {
    display: flex;
    gap: 10px;
}

.btn {
    display: inline-block;
    padding: 8px 16px;
    background: #0066cc;
    color: white;
    text-decoration: none;
    border-radius: 4px;
    border: none;
    cursor: pointer;
    font-size: 14px;
    font-weight: 600;
    transition: background 0.3s;
}

.btn:hover {
    background: #0052a3;
}

.btn-primary {
    background: #0066cc;
}

.btn-primary:hover {
    background: #0052a3;
}

.btn-sm {
    padding: 6px 12px;
    font-size: 12px;
}

.support-empty {
    text-align: center;
    padding: 60px 20px;
    color: #666;
    background: #f8f9fa;
    border-radius: 8px;
    border: 1px dashed #ddd;
}

.support-empty p {
    margin: 0 0 20px 0;
    font-size: 16px;
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

.pagination-wrapper {
    display: flex;
    justify-content: center;
    margin-top: 30px;
}

@media (max-width: 768px) {
    .support-header {
        flex-direction: column;
    }

    .card-meta {
        flex-direction: column;
        gap: 8px;
    }

    .card-header {
        flex-direction: column;
        align-items: flex-start;
    }

    .status {
        margin-left: 0;
        margin-top: 10px;
    }
}
</style>
@endsection
