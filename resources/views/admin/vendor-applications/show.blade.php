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

    .vendor-show-container {
        max-width: 1000px;
        margin: 0 auto;
        padding: var(--spacing-xl);
    }

    .vendor-show-card {
        background-color: var(--color-bg-secondary);
        border: 1px solid var(--color-border);
        border-radius: var(--radius);
        padding: var(--spacing-lg);
    }

    .vendor-show-title {
        font-size: 28px;
        font-weight: 600;
        color: var(--color-text-primary);
        margin: 0 0 var(--spacing-lg) 0;
        padding-bottom: var(--spacing-lg);
        border-bottom: 2px solid var(--color-accent-light);
    }

    .vendor-show-section {
        margin-bottom: var(--spacing-2xl);
        padding: var(--spacing-lg);
        background-color: var(--color-bg-primary);
        border: 1px solid var(--color-border);
        border-radius: var(--radius);
    }

    .vendor-show-section-title {
        font-size: 16px;
        font-weight: 600;
        color: var(--color-accent);
        margin: 0 0 var(--spacing-md) 0;
        padding-bottom: var(--spacing-md);
        border-bottom: 2px solid var(--color-accent-light);
    }

    .vendor-show-detail-row {
        font-size: 14px;
        color: var(--color-text-primary);
        margin: var(--spacing-md) 0;
        line-height: 1.6;
    }

    .vendor-show-detail-row strong {
        color: var(--color-accent);
        font-weight: 600;
    }

    .vendor-show-application-text {
        font-size: 14px;
        color: var(--color-text-primary);
        line-height: 1.6;
        background-color: var(--color-bg-secondary);
        padding: var(--spacing-md);
        border: 1px solid var(--color-border);
        border-radius: var(--radius);
        white-space: pre-wrap;
        word-wrap: break-word;
    }

    .vendor-show-image-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: var(--spacing-lg);
        margin-top: var(--spacing-md);
    }

    .vendor-show-image-container {
        background-color: var(--color-bg-secondary);
        border: 1px solid var(--color-border);
        border-radius: var(--radius);
        overflow: hidden;
        aspect-ratio: 1;
    }

    .vendor-show-image-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .vendor-show-actions {
        display: flex;
        gap: var(--spacing-md);
        margin-top: var(--spacing-lg);
    }

    .inline-form {
        display: inline;
    }

    .vendor-show-btn {
        padding: var(--spacing-md) var(--spacing-lg);
        border: none;
        border-radius: var(--radius);
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .vendor-show-btn-accept {
        background-color: #10b981;
        color: #ffffff;
    }

    .vendor-show-btn-accept:hover {
        background-color: #059669;
    }

    .vendor-show-btn-deny {
        background-color: #ef4444;
        color: #ffffff;
    }

    .vendor-show-btn-deny:hover {
        background-color: #dc2626;
    }

    .vendor-show-status {
        background-color: var(--color-bg-secondary);
        padding: var(--spacing-md);
        border: 1px solid var(--color-border);
        border-radius: var(--radius);
    }

    .vendor-show-status strong {
        color: var(--color-accent);
        font-weight: 600;
    }

    .vendor-show-back {
        margin-top: var(--spacing-2xl);
        padding-top: var(--spacing-lg);
        border-top: 1px solid var(--color-border);
    }

    .vendor-show-back-link {
        display: inline-block;
        color: var(--color-accent);
        text-decoration: none;
        font-weight: 500;
        padding: var(--spacing-xs) var(--spacing-sm);
        border-radius: var(--radius);
        transition: background-color 0.3s ease;
    }

    .vendor-show-back-link:hover {
        background-color: var(--color-bg-primary);
    }

    @media (max-width: 768px) {
        .vendor-show-container {
            padding: var(--spacing-md);
        }

        .vendor-show-title {
            font-size: 22px;
        }

        .vendor-show-image-grid {
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        }

        .vendor-show-actions {
            flex-direction: column;
        }

        .vendor-show-btn {
            width: 100%;
        }
    }
</style>

<div class="vendor-show-container">
    <div class="vendor-show-card">
        <h1 class="vendor-show-title">Review Vendor Application</h1>

        <div class="vendor-show-section">
            <h2 class="vendor-show-section-title">Applicant Details</h2>
            <p class="vendor-show-detail-row"><strong>Username:</strong> {{ $application->user->username }}</p>
            <p class="vendor-show-detail-row"><strong>Submitted:</strong> {{ $application->application_submitted_at->format('Y-m-d / H:i') }}</p>
            <p class="vendor-show-detail-row"><strong>Payment Amount:</strong> {{ $application->total_received }} XMR</p>
        </div>

        <div class="vendor-show-section">
            <h2 class="vendor-show-section-title">Application Text</h2>
            <div class="vendor-show-application-text">{{ $application->application_text }}</div>
        </div>

        @if($application->application_images)
            <div class="vendor-show-section">
                <h2 class="vendor-show-section-title">Product Images</h2>
                <div class="vendor-show-image-grid">
                    @foreach(json_decode($application->application_images) as $image)
                        <div class="vendor-show-image-container">
                            <img src="{{ route('admin.vendor-applications.show', ['application' => $application, 'image' => $image]) }}" alt="Product Image">
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @if($application->application_status === 'waiting')
            <div class="vendor-show-section">
                <h2 class="vendor-show-section-title">Make Decision</h2>
                <div class="vendor-show-actions">
                    <form action="{{ route('admin.vendor-applications.accept', $application) }}" method="POST" class="inline-form">
                        @csrf
                        <button type="submit" class="vendor-show-btn vendor-show-btn-accept">Accept Application</button>
                    </form>

                    <form action="{{ route('admin.vendor-applications.deny', $application) }}" method="POST" class="inline-form">
                        @csrf
                        <button type="submit" class="vendor-show-btn vendor-show-btn-deny">Deny Application</button>
                    </form>
                </div>
            </div>
        @else
            <div class="vendor-show-section">
                <h2 class="vendor-show-section-title">Status</h2>
                <div class="vendor-show-status">
                    <p>
                        This application has been 
                        <strong>
                            {{ $application->application_status === 'accepted' ? 'ACCEPTED' : 'DENIED' }}
                        </strong>
                        on {{ $application->admin_response_at->format('Y-m-d / H:i') }}
                    </p>
                </div>
            </div>

            @if($application->application_status === 'denied' && $application->refund_amount)
            <div class="vendor-show-section">
                <h2 class="vendor-show-section-title">Refund Details</h2>
                <div class="vendor-show-status">
                    <p class="vendor-show-detail-row">
                        <strong>Refund Amount:</strong> {{ $application->refund_amount }} XMR
                    </p>
                    <p class="vendor-show-detail-row">
                        <strong>Refund Address:</strong> {{ $application->refund_address }}
                    </p>
                </div>
            </div>
            @endif
        @endif

        <div class="vendor-show-back">
            <a href="{{ route('admin.vendor-applications.index') }}" class="vendor-show-back-link">← Back to Applications List</a>
        </div>
    </div>
</div>

@endsection
