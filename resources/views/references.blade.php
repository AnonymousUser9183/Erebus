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

    .references-index-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: var(--spacing-xl);
    }

    .references-index-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: var(--spacing-xl);
    }

    .references-index-card {
        background-color: var(--color-bg-secondary);
        border: 1px solid var(--color-border);
        border-radius: var(--radius);
        padding: var(--spacing-lg);
    }

    .references-index-card h2 {
        font-size: 18px;
        font-weight: 600;
        color: var(--color-text-primary);
        margin: 0 0 var(--spacing-md) 0;
        padding-bottom: var(--spacing-md);
        border-bottom: 2px solid var(--color-accent-light);
    }

    .references-index-card h3 {
        font-size: 16px;
        font-weight: 600;
        color: var(--color-accent);
        margin: var(--spacing-lg) 0 var(--spacing-md) 0;
    }

    .references-index-section {
        display: flex;
        flex-direction: column;
        gap: var(--spacing-md);
    }

    .references-index-text {
        font-size: 13px;
        color: var(--color-text-secondary);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin: 0;
        font-weight: 500;
    }

    .references-index-highlight {
        background-color: var(--color-bg-primary);
        border-left: 4px solid var(--color-accent);
        padding: var(--spacing-md);
        border-radius: var(--radius);
        display: flex;
        flex-direction: column;
        gap: var(--spacing-sm);
    }

    .references-index-highlight strong {
        font-size: 18px;
        color: var(--color-accent);
        word-break: break-word;
    }

    .references-index-highlight p {
        font-size: 13px;
        color: var(--color-text-secondary);
        margin: 0;
    }

    .references-index-ref-info {
        display: flex;
        flex-direction: column;
        gap: var(--spacing-sm);
    }

    .references-index-ref-box {
        display: flex;
        justify-content: space-between;
        padding: var(--spacing-sm) var(--spacing-md);
        background-color: var(--color-bg-primary);
        border-radius: var(--radius);
        font-size: 14px;
    }

    .references-index-ref-box span {
        color: var(--color-text-secondary);
        font-weight: 500;
    }

    .references-index-ref-box strong {
        color: var(--color-accent);
        font-weight: 600;
        word-break: break-word;
    }

    .references-index-note {
        font-size: 12px;
        color: var(--color-text-secondary);
        margin: 0;
        font-style: italic;
    }

    .references-index-referral-list,
    .references-index-vendor-list {
        display: flex;
        flex-direction: column;
        gap: var(--spacing-sm);
    }

    .references-index-referral-item,
    .references-index-vendor-item {
        background-color: var(--color-bg-primary);
        border: 1px solid var(--color-border);
        padding: var(--spacing-sm) var(--spacing-md);
        border-radius: var(--radius);
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 14px;
    }

    .references-index-referral-item strong,
    .references-index-vendor-name {
        color: var(--color-text-primary);
        word-break: break-word;
    }

    .references-index-vendor-info {
        display: flex;
        flex-direction: column;
        gap: var(--spacing-xs);
        flex-grow: 1;
    }

    .references-index-vendor-id {
        font-size: 12px;
        color: var(--color-text-secondary);
        font-family: 'Courier New', monospace;
    }

    .references-index-form {
        display: flex;
        flex-direction: column;
        gap: var(--spacing-md);
    }

    .references-index-input-group {
        display: flex;
        gap: var(--spacing-sm);
    }

    .references-index-input {
        flex-grow: 1;
        padding: var(--spacing-md);
        border: 1px solid var(--color-border);
        border-radius: var(--radius);
        font-size: 14px;
        color: var(--color-text-primary);
        background-color: var(--color-bg-secondary);
        font-family: inherit;
        box-sizing: border-box;
        transition: border-color 0.3s ease;
    }

    .references-index-input:focus {
        outline: none;
        border-color: var(--color-accent);
        box-shadow: 0 0 0 2px rgba(32, 128, 136, 0.1);
    }

    .references-index-button,
    .references-index-remove-button {
        padding: var(--spacing-md) var(--spacing-lg);
        border: none;
        border-radius: var(--radius);
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .references-index-button {
        background-color: var(--color-accent);
        color: #ffffff;
    }

    .references-index-button:hover {
        background-color: var(--color-accent-light);
    }

    .references-index-remove-button {
        background-color: #ffffff;
        color: var(--color-text-primary);
        border: 1px solid var(--color-border);
        padding: var(--spacing-xs) var(--spacing-sm);
        font-size: 12px;
    }

    .references-index-remove-button:hover {
        background-color: #fecaca;
        border-color: #ef4444;
        color: #dc2626;
    }

    .references-index-vendor-note {
        font-size: 12px;
        color: var(--color-text-secondary);
        background-color: var(--color-bg-primary);
        padding: var(--spacing-md);
        border-radius: var(--radius);
        margin-top: var(--spacing-md);
    }

    .references-index-vendor-note p {
        margin: 0;
    }

    .references-index-vendor-empty {
        background-color: var(--color-bg-primary);
        border: 1px solid var(--color-border);
        padding: var(--spacing-md);
        border-radius: var(--radius);
        text-align: center;
        color: var(--color-text-secondary);
        font-size: 14px;
    }

    .references-index-vendor-empty p {
        margin: 0;
    }

    @media (max-width: 768px) {
        .references-index-container {
            padding: var(--spacing-md);
        }

        .references-index-grid {
            grid-template-columns: 1fr;
        }

        .references-index-input-group {
            flex-direction: column;
        }

        .references-index-referral-item,
        .references-index-vendor-item {
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>

<div class="references-index-container">
    <div class="references-index-grid">
        <div class="references-index-card">
            <h2>Your Erebus Market Reference</h2>
            <div class="references-index-section">
                <p class="references-index-text">Your Unique Reference ID</p>
                <div class="references-index-highlight">
                    <strong>{{ $referenceId }}</strong>
                </div>
                <div class="references-index-ref-info">
                    <div class="references-index-ref-box">
                        <span>Used a Reference Code?</span>
                        <strong>{{ $usedReferenceCode ? 'Yes' : 'No' }}</strong>
                    </div>
                    @if($usedReferenceCode && $referrerUsername)
                    <div class="references-index-ref-box">
                        <span>Referred By</span>
                        <strong>{{ $referrerUsername }}</strong>
                    </div>
                    @endif
                </div>
                <p class="references-index-note">Please share this number only with trusted people and not with everyone.</p>
            </div>
        </div>

        <div class="references-index-card">
            <h2>Users Who Used Your Reference</h2>
            <div class="references-index-section">
                @if($referrals->count() > 0)
                    <p class="references-index-text">The following users have used your reference code:</p>
                    <div class="references-index-referral-list">
                        @foreach($referrals as $referral)
                            <div class="references-index-referral-item">
                                <strong>{{ $referral->username }}</strong>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="references-index-highlight">
                        <p class="references-index-text">No one has used your reference code yet.</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="references-index-card">
            <h2>Manage Vendor References</h2>
            <div class="references-index-section">
                <p class="references-index-text">Enter a vendor's reference ID to add to your private shops list:</p>
        
                <form action="{{ route('references.store') }}" method="POST" class="references-index-form">
                    @csrf
                    <div class="references-index-input-group">
                        <input type="text" name="vendor_reference_id" placeholder="Vendor Reference ID" required minlength="12" maxlength="20" class="references-index-input">
                        <button type="submit" class="references-index-button">Add Vendor</button>
                    </div>
                </form>
        
                <div class="references-index-vendor-note">
                    <p>Note: Only vendor reference IDs can be added. Regular user IDs will not be accepted.</p>
                </div>
        
                @if(isset($privateShops) && $privateShops->count() > 0)
                    <h3>Your Saved Vendor References</h3>
                    <div class="references-index-vendor-list">
                        @foreach($privateShops as $shop)
                            <div class="references-index-vendor-item">
                                <div class="references-index-vendor-info">
                                    <strong class="references-index-vendor-name">{{ $shop->vendor_username }}</strong>
                                    <small class="references-index-vendor-id">{{ $shop->vendor_reference_id }}</small>
                                </div>
                                <form action="{{ route('references.remove', $shop->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="references-index-remove-button">Remove</button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="references-index-vendor-empty">
                        <p>You haven't added any vendor reference IDs yet.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
