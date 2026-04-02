@extends('layouts.app')

@section('content')
<div class="support-create-container">
    <div class="support-header">
        <h1>Create Support Request</h1>
        <p>We're here to help. Describe your issue below.</p>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Please fix the following errors:</strong>
            @foreach ($errors->all() as $error)
                <p>• {{ $error }}</p>
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

    <form action="{{ route('support.solve-pow-create') }}" method="POST" class="support-form">
        @csrf

        <!-- Subject Field -->
        <div class="form-group">
            <label for="subject">Subject *</label>
            <input 
                type="text" 
                id="subject" 
                name="subject" 
                class="form-control @error('subject') is-invalid @enderror" 
                placeholder="Brief description of your issue"
                value="{{ old('subject') }}"
                required
                minlength="5"
                maxlength="100"
            >
            <small>5-100 characters</small>
            @error('subject')
                <div class="error-text">{{ $message }}</div>
            @enderror
        </div>

        <!-- Category Field -->
        <div class="form-group">
            <label for="category">Category *</label>
            <select id="category" name="category" class="form-control @error('category') is-invalid @enderror" required>
                <option value="">-- Select a category --</option>
                <option value="billing" {{ old('category') === 'billing' ? 'selected' : '' }}>
                    Billing & Payments
                </option>
                <option value="technical" {{ old('category') === 'technical' ? 'selected' : '' }}>
                    Technical Issue
                </option>
                <option value="account" {{ old('category') === 'account' ? 'selected' : '' }}>
                    Account & Settings
                </option>
                <option value="other" {{ old('category') === 'other' ? 'selected' : '' }}>
                    Other
                </option>
            </select>
            @error('category')
                <div class="error-text">{{ $message }}</div>
            @enderror
        </div>

        <!-- Message Field -->
        <div class="form-group">
            <label for="message">Message *</label>
            <textarea 
                id="message" 
                name="message" 
                class="form-control @error('message') is-invalid @enderror" 
                placeholder="Please provide details about your issue..."
                rows="6"
                required
                minlength="10"
                maxlength="2000"
            >{{ old('message') }}</textarea>
            <small>10-2000 characters</small>
            @error('message')
                <div class="error-text">{{ $message }}</div>
            @enderror
        </div>

        <!-- PoW Info -->
        <div class="pow-info">
            <h4>🔐 Proof of Work Verification</h4>
            <p>To prevent spam, we require proof of work verification. When you click the button below, our server will solve a computational challenge. This takes 2-3 seconds and no JavaScript is needed.</p>
        </div>

        <!-- Submit Button -->
        <div class="form-actions">
            <button type="submit" class="btn btn-primary btn-large">
                🔐 Compute PoW & Submit
            </button>
            <a href="{{ route('support.index') }}" class="btn btn-secondary">
                Cancel
            </a>
        </div>
    </form>
</div>

<style>
.support-create-container {
    max-width: 800px;
    margin: 0 auto;
    padding: 40px 20px;
}

.support-header {
    margin-bottom: 40px;
    text-align: center;
}

.support-header h1 {
    margin: 0 0 10px 0;
    font-size: 28px;
    color: #333;
}

.support-header p {
    margin: 0;
    color: #666;
    font-size: 14px;
}

.alert {
    padding: 15px;
    border-radius: 4px;
    margin-bottom: 30px;
}

.alert p {
    margin: 8px 0 0 0;
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

.alert strong {
    display: block;
    margin-bottom: 10px;
}

.support-form {
    background: white;
    padding: 30px;
    border-radius: 8px;
    border: 1px solid #ddd;
}

.form-group {
    margin-bottom: 25px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #333;
    font-size: 14px;
}

.form-control {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-family: inherit;
    font-size: 14px;
    transition: border-color 0.3s;
    box-sizing: border-box;
}

.form-control.is-invalid {
    border-color: #dc3545;
    background-color: #fff5f5;
}

.form-control:focus {
    outline: none;
    border-color: #0066cc;
    box-shadow: 0 0 0 3px rgba(0, 102, 204, 0.1);
}

textarea.form-control {
    resize: vertical;
    line-height: 1.5;
}

.form-group small {
    display: block;
    margin-top: 5px;
    color: #999;
    font-size: 12px;
}

.error-text {
    color: #dc3545;
    font-size: 12px;
    margin-top: 5px;
}

.pow-info {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 4px;
    margin: 30px 0;
    border-left: 4px solid #0066cc;
}

.pow-info h4 {
    margin: 0 0 10px 0;
    font-size: 14px;
    color: #333;
}

.pow-info p {
    margin: 8px 0 0 0;
    font-size: 13px;
    color: #666;
    line-height: 1.5;
}

.form-actions {
    display: flex;
    gap: 10px;
    margin-top: 30px;
}

.btn {
    padding: 12px 24px;
    font-size: 14px;
    font-weight: 600;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
    transition: background 0.3s;
    text-align: center;
}

.btn-primary {
    background: #0066cc;
    color: white;
}

.btn-primary:hover {
    background: #0052a3;
}

.btn-secondary {
    background: #6c757d;
    color: white;
    flex: 1;
}

.btn-secondary:hover {
    background: #5a6268;
}

.btn-large {
    padding: 14px 28px;
    font-size: 15px;
    flex: 1;
}

@media (max-width: 768px) {
    .support-create-container {
        padding: 20px 10px;
    }

    .support-form {
        padding: 20px;
    }

    .form-actions {
        flex-direction: column;
    }

    .btn {
        width: 100%;
    }
}
</style>
@endsection
