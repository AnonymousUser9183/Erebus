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

    .advertisement-create-container {
        max-width: 900px;
        margin: 0 auto;
        padding: var(--spacing-xl);
    }

    .advertisement-create-card {
        background: var(--color-card-bg);
        border: 1px solid var(--color-border);
        border-radius: var(--radius-lg);
        padding: var(--spacing-xl);
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }

    .advertisement-create-title {
        font-size: 24px;
        font-weight: 600;
        color: var(--color-text-primary);
        margin: 0 0 var(--spacing-xl) 0;
    }

    .advertisement-section {
        margin-bottom: var(--spacing-xl);
        padding-bottom: var(--spacing-xl);
        border-bottom: 1px solid var(--color-border);
    }

    .advertisement-section:last-child {
        border-bottom: none;
    }

    .advertisement-section-title {
        font-size: 16px;
        font-weight: 600;
        color: var(--color-text-primary);
        margin: 0 0 var(--spacing-lg) 0;
    }

    .product-details-card {
        background: var(--color-input-bg);
        border: 1px solid var(--color-border);
        border-radius: var(--radius-base);
        padding: var(--spacing-lg);
        display: flex;
        gap: var(--spacing-lg);
        margin-bottom: var(--spacing-lg);
    }

    .product-details-image {
        width: 100px;
        height: 100px;
        border-radius: var(--radius-base);
        overflow: hidden;
        flex-shrink: 0;
    }

    .product-details-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .product-details-content {
        flex: 1;
    }

    .product-details-name {
        font-size: 16px;
        font-weight: 600;
        color: var(--color-text-primary);
        margin: 0 0 var(--spacing-md) 0;
    }

    .product-details-description {
        font-size: 13px;
        color: var(--color-text-secondary);
        line-height: 1.5;
    }

    .slots-selection {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        gap: var(--spacing-lg);
        margin-bottom: var(--spacing-lg);
    }

    .slot-option {
        position: relative;
        cursor: pointer;
    }

    .slot-option input[type="radio"] {
        position: absolute;
        opacity: 0;
    }

    .slot-option-label {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: var(--spacing-lg);
        background: var(--color-input-bg);
        border: 2px solid var(--color-border);
        border-radius: var(--radius-base);
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .slot-option input[type="radio"]:checked + .slot-option-label {
        border-color: var(--color-accent);
        background: rgba(32, 128, 136, 0.05);
    }

    .slot-option input[type="radio"]:disabled + .slot-option-label {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .slot-option-label:hover:not(.disabled) {
        border-color: var(--color-accent);
    }

    .slot-number {
        font-size: 18px;
        font-weight: 600;
        color: var(--color-text-primary);
        margin-bottom: var(--spacing-md);
    }

    .slot-price {
        font-size: 12px;
        color: var(--color-text-secondary);
    }

    .slot-occupied {
        font-size: 11px;
        color: var(--color-error);
        font-weight: 600;
    }

    .advertisement-form-group {
        margin-bottom: var(--spacing-lg);
    }

    .advertisement-form-label {
        display: block;
        font-size: 14px;
        font-weight: 500;
        color: var(--color-text-primary);
        margin-bottom: var(--spacing-md);
    }

    .advertisement-form-select {
        width: 100%;
        padding: var(--spacing-md);
        border: 1px solid var(--color-border);
        border-radius: var(--radius-base);
        font-family: inherit;
        font-size: 14px;
        color: var(--color-text-primary);
    }

    .advertisement-form-select:focus {
        outline: none;
        border-color: var(--color-accent);
        box-shadow: 0 0 0 2px rgba(32, 128, 136, 0.1);
    }

    .pricing-info {
        background: var(--color-input-bg);
        padding: var(--spacing-lg);
        border-radius: var(--radius-base);
        margin-bottom: var(--spacing-lg);
    }

    .pricing-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: var(--spacing-md);
        font-size: 14px;
    }

    .pricing-row:last-child {
        margin-bottom: 0;
        font-weight: 600;
        color: var(--color-text-primary);
        font-size: 16px;
        border-top: 1px solid var(--color-border);
        padding-top: var(--spacing-md);
    }

    .pricing-label {
        color: var(--color-text-secondary);
    }

    .pricing-value {
        font-weight: 500;
        color: var(--color-text-primary);
    }

    .advertisement-submit-btn {
        background: var(--color-accent);
        color: white;
        padding: 12px 24px;
        border: none;
        border-radius: var(--radius-base);
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s ease;
        width: 100%;
    }

    .advertisement-submit-btn:hover {
        background: var(--color-accent-light);
    }

    @media (max-width: 768px) {
        .advertisement-create-container {
            padding: var(--spacing-lg);
        }

        .advertisement-create-title {
            font-size: 20px;
        }

        .slots-selection {
            grid-template-columns: repeat(2, 1fr);
        }

        .product-details-card {
            flex-direction: column;
        }

        .product-details-image {
            width: 100%;
        }
    }
</style>

<div class="advertisement-create-container">
    <div class="advertisement-create-card">
        <h2 class="advertisement-create-title">Create Advertisement</h2>

        <div class="advertisement-section">
            <h3 class="advertisement-section-title">Product Details</h3>
            <div class="product-details-card">
                <div class="product-details-image">
                    <img src="{{ $product->product_picture_url }}" alt="{{ $product->name }}">
                </div>
                <div class="product-details-content">
                    <h4 class="product-details-name">{{ $product->name }}</h4>
                    <p class="product-details-description">{{ \Str::limit($product->description, 150) }}</p>
                </div>
            </div>
        </div>

        <form action="{{ route('vendor.advertisement.store', $product) }}" method="POST">
            @csrf

            <div class="advertisement-section">
                <h3 class="advertisement-section-title">Select Advertisement Slot</h3>
                <div class="slots-selection">
                    @forelse($slots as $slot)
                        <div class="slot-option">
                            <input 
                                type="radio" 
                                id="slot_{{ $slot->position }}" 
                                name="slot_id" 
                                value="{{ $slot->id }}"
                                {{ $slot->is_occupied ? 'disabled' : '' }}
                                required
                            >
                            <label for="slot_{{ $slot->position }}" class="slot-option-label">
                                <span class="slot-number">Slot {{ $slot->position }}</span>
                                @if($slot->is_occupied)
                                    <span class="slot-occupied">Occupied</span>
                                @else
                                    <span class="slot-price">${{ number_format($slot->price_per_day, 2) }}/day</span>
                                @endif
                            </label>
                        </div>
                    @empty
                        <p>No slots available at this time.</p>
                    @endforelse
                </div>
            </div>

            <div class="advertisement-section">
                <div class="advertisement-form-group">
                    <label for="duration" class="advertisement-form-label">Duration (Days)</label>
                    <select name="duration" id="duration" class="advertisement-form-select" required onchange="updatePricing()">
                        <option value="">Select duration</option>
                        <option value="1">1 Day</option>
                        <option value="3">3 Days</option>
                        <option value="7">7 Days (1 Week)</option>
                        <option value="30">30 Days (1 Month)</option>
                        <option value="90">90 Days (3 Months)</option>
                    </select>
                </div>
            </div>

            <div class="pricing-info">
                <div class="pricing-row">
                    <span class="pricing-label">Daily Rate</span>
                    <span class="pricing-value" id="daily-rate">$0.00</span>
                </div>
                <div class="pricing-row">
                    <span class="pricing-label">Duration</span>
                    <span class="pricing-value" id="duration-display">-</span>
                </div>
                <div class="pricing-row">
                    <span class="pricing-label">Total XMR Price</span>
                    <span class="pricing-value" id="total-xmr">0.00 XMR</span>
                </div>
            </div>

            <button type="submit" class="advertisement-submit-btn">Proceed to Payment</button>
        </form>
    </div>
</div>

<script>
    const slotRadios = document.querySelectorAll('input[name="slot_id"]');
    const durationSelect = document.getElementById('duration');

    function updatePricing() {
        const selectedSlot = document.querySelector('input[name="slot_id"]:checked');
        const duration = durationSelect.value;

        if (selectedSlot && duration) {
            const dailyRate = parseFloat(selectedSlot.closest('.slot-option').querySelector('.slot-price')?.textContent || 0);
            const durationDays = parseInt(duration);
            const total = (dailyRate * durationDays).toFixed(2);
            const xmrPrice = (total / 60).toFixed(4); // Assuming 1 XMR = $60

            document.getElementById('daily-rate').textContent = '$' + dailyRate.toFixed(2);
            document.getElementById('duration-display').textContent = durationDays + ' day' + (durationDays !== 1 ? 's' : '');
            document.getElementById('total-xmr').textContent = xmrPrice + ' XMR';
        }
    }

    slotRadios.forEach(radio => {
        radio.addEventListener('change', updatePricing);
    });

    durationSelect.addEventListener('change', updatePricing);
</script>

@endsection