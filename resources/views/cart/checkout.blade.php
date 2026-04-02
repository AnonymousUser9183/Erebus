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

.cart-checkout-container {
max-width: 1000px;
margin: 0 auto;
padding: var(--spacing-xl);
}

.cart-checkout-breadcrumb {
font-size: 13px;
color: var(--color-text-secondary);
margin-bottom: var(--spacing-2xl);
display: flex;
gap: var(--spacing-sm);
align-items: center;
flex-wrap: wrap;
}

.cart-checkout-breadcrumb a {
color: var(--color-accent);
text-decoration: none;
font-weight: 500;
transition: color 0.3s ease;
}

.cart-checkout-breadcrumb a:hover {
color: var(--color-accent-light);
}

.cart-checkout-card {
background-color: var(--color-bg-secondary);
border: 1px solid var(--color-border);
border-radius: var(--radius);
padding: var(--spacing-lg);
}

.cart-checkout-title {
font-size: 28px;
font-weight: 600;
color: var(--color-text-primary);
margin: 0 0 var(--spacing-xl) 0;
padding-bottom: var(--spacing-lg);
border-bottom: 2px solid var(--color-accent-light);
}

.cart-checkout-content {
display: grid;
grid-template-columns: 1fr 350px;
gap: var(--spacing-xl);
}

.cart-checkout-order-details {
background-color: var(--color-bg-primary);
border: 1px solid var(--color-border);
border-radius: var(--radius);
padding: var(--spacing-lg);
}

.cart-checkout-header {
display: flex;
justify-content: space-between;
align-items: center;
margin-bottom: var(--spacing-lg);
padding-bottom: var(--spacing-lg);
border-bottom: 1px solid var(--color-border);
}

.cart-checkout-subtitle {
font-size: 16px;
font-weight: 600;
color: var(--color-text-primary);
margin: 0;
}

.cart-checkout-vendor-badge {
background-color: var(--color-bg-secondary);
border: 1px solid var(--color-border);
border-radius: var(--radius);
padding: var(--spacing-sm) var(--spacing-md);
font-size: 12px;
color: var(--color-text-secondary);
font-weight: 500;
}

.cart-checkout-items {
display: flex;
flex-direction: column;
gap: var(--spacing-md);
}

.cart-checkout-item {
background-color: var(--color-bg-secondary);
border: 1px solid var(--color-border);
border-radius: var(--radius);
padding: var(--spacing-lg);
}

.cart-checkout-item-details {
display: flex;
justify-content: space-between;
gap: var(--spacing-lg);
}

.cart-checkout-item-info {
flex: 1;
}

.cart-checkout-item-name {
font-size: 15px;
font-weight: 600;
color: var(--color-text-primary);
margin: 0 0 var(--spacing-sm) 0;
}

.cart-checkout-item-bulk,
.cart-checkout-item-quantity {
font-size: 13px;
color: var(--color-text-secondary);
margin: 0;
line-height: 1.5;
}

.cart-checkout-item-price {
text-align: right;
}

.cart-checkout-price-fiat {
font-size: 16px;
font-weight: 600;
color: var(--color-accent);
}

.cart-checkout-price-crypto {
font-size: 12px;
color: var(--color-text-secondary);
}

.cart-checkout-message-section {
margin-top: var(--spacing-lg);
padding-top: var(--spacing-lg);
border-top: 1px solid var(--color-border);
}

.cart-checkout-message-encrypted {
background-color: var(--color-bg-secondary);
border: 1px solid var(--color-border);
border-radius: var(--radius);
padding: var(--spacing-md);
}

.cart-checkout-message-label {
font-size: 13px;
font-weight: 600;
color: var(--color-accent);
text-transform: uppercase;
letter-spacing: 0.5px;
display: block;
margin-bottom: var(--spacing-sm);
}

.cart-checkout-message-content {
width: 100%;
padding: var(--spacing-sm);
border: 1px solid var(--color-border);
border-radius: var(--radius);
font-size: 11px;
color: var(--color-text-primary);
font-family: 'Monaco', 'Menlo', monospace;
background-color: var(--color-bg-primary);
resize: none;
min-height: 80px;
}

.cart-checkout-empty {
background-color: var(--color-bg-secondary);
border: 1px solid var(--color-border);
border-radius: var(--radius);
padding: var(--spacing-lg);
text-align: center;
color: var(--color-text-secondary);
font-size: 14px;
margin: 0;
}

.cart-checkout-summary {
display: flex;
flex-direction: column;
gap: var(--spacing-lg);
}

.cart-checkout-summary-card {
background-color: var(--color-bg-primary);
border: 1px solid var(--color-border);
border-radius: var(--radius);
padding: var(--spacing-lg);
}

.cart-checkout-summary-title {
font-size: 16px;
font-weight: 600;
color: var(--color-accent);
margin: 0 0 var(--spacing-md) 0;
}

.cart-checkout-summary-content {
display: flex;
flex-direction: column;
gap: var(--spacing-md);
}

.cart-checkout-summary-row {
display: flex;
justify-content: space-between;
font-size: 13px;
color: var(--color-text-secondary);
}

.cart-checkout-summary-total {
display: flex;
justify-content: space-between;
align-items: center;
padding: var(--spacing-md) 0;
border-top: 2px solid var(--color-border);
border-bottom: 2px solid var(--color-border);
margin: var(--spacing-md) 0;
font-weight: 600;
color: var(--color-text-primary);
}

.cart-checkout-total-amount {
text-align: right;
}

.cart-checkout-total-fiat {
font-size: 18px;
font-weight: 600;
color: var(--color-accent);
}

.cart-checkout-total-crypto {
font-size: 11px;
color: var(--color-text-secondary);
}

.cart-checkout-actions {
display: flex;
flex-direction: column;
gap: var(--spacing-md);
}

.cart-checkout-back-btn,
.cart-checkout-proceed-btn {
padding: var(--spacing-md) var(--spacing-lg);
border: none;
border-radius: var(--radius);
font-size: 14px;
font-weight: 600;
cursor: pointer;
text-decoration: none;
transition: all 0.3s ease;
text-align: center;
}

.cart-checkout-back-btn {
background-color: transparent;
border: 1px solid var(--color-border);
color: var(--color-text-primary);
}

.cart-checkout-back-btn:hover {
background-color: var(--color-bg-primary);
}

.cart-checkout-proceed-btn {
background-color: var(--color-accent);
color: #ffffff;
}

.cart-checkout-proceed-btn:hover {
background-color: var(--color-accent-light);
}

@media (max-width: 768px) {
.cart-checkout-container {
padding: var(--spacing-lg);
}

.cart-checkout-content {
grid-template-columns: 1fr;
}

.cart-checkout-item-details {
flex-direction: column;
}

.cart-checkout-item-price {
text-align: left;
}

.cart-checkout-header {
flex-direction: column;
gap: var(--spacing-md);
}
}
</style>

<div class="cart-checkout-container">
<div class="cart-checkout-breadcrumb">
<a href="{{ route('products.index') }}">Products</a>
<span>/</span>
<a href="{{ route('cart.index') }}">Cart</a>
<span>/</span>
<span>Checkout</span>
</div>

<div class="cart-checkout-card">
<h2 class="cart-checkout-title">Checkout Details</h2>

<div class="cart-checkout-content">
<div class="cart-checkout-order-details">
<div class="cart-checkout-header">
<h3 class="cart-checkout-subtitle">Order Information</h3>
@if($cartItems->isNotEmpty())
<span class="cart-checkout-vendor-badge">Vendor: {{ $cartItems->first()->product->user->username }}</span>
@endif
</div>

<div class="cart-checkout-items">
@if($cartItems->isNotEmpty())
@foreach($cartItems as $item)
<div class="cart-checkout-item">
<div class="cart-checkout-item-details">
<div class="cart-checkout-item-info">
<div class="cart-checkout-item-name">{{ $item->product->name }}</div>
@if($item->selected_bulk_option)
<div class="cart-checkout-item-bulk">
Bulk: {{ $item->quantity }} sets of {{ $item->selected_bulk_option['amount'] }} {{ $measurementUnits[$item->product->measurement_unit] ?? $item->product->measurement_unit }}
(Total: {{ $item->quantity * $item->selected_bulk_option['amount'] }} {{ $measurementUnits[$item->product->measurement_unit] ?? $item->product->measurement_unit }})
</div>
@else
<div class="cart-checkout-item-quantity">
Quantity: {{ $item->quantity }} {{ $measurementUnits[$item->product->measurement_unit] ?? $item->product->measurement_unit }}
</div>
@endif
</div>
<div class="cart-checkout-item-price">
<div class="cart-checkout-price-fiat">${{ number_format($item->getTotalPrice(), 2) }}</div>
@if(is_numeric($xmrPrice) && $xmrPrice > 0)
<div class="cart-checkout-price-crypto">
≈ ɱ{{ number_format($item->getTotalPrice() / $xmrPrice, 4) }}
</div>
@endif
</div>
</div>
</div>
@endforeach

@if($hasEncryptedMessage && $messageItem)
<div class="cart-checkout-message-section">
<div class="cart-checkout-message-encrypted">
<label class="cart-checkout-message-label">
🔐 Encrypted Message for {{ $messageItem->product->user->username }}
</label>
<textarea readonly class="cart-checkout-message-content">{{ $messageItem->encrypted_message }}</textarea>
</div>
</div>
@endif
@else
<p class="cart-checkout-empty">No items in cart.</p>
@endif
</div>
</div>

<div class="cart-checkout-summary">
<div class="cart-checkout-summary-card">
<h3 class="cart-checkout-summary-title">Price Summary</h3>

<div class="cart-checkout-summary-content">
<div class="cart-checkout-summary-row">
<span>Subtotal</span>
<span>${{ number_format($subtotal, 2) }}</span>
</div>

<div class="cart-checkout-summary-row">
<span>Commission ({{ $commissionPercentage }}%)</span>
<span>${{ number_format($commission, 2) }}</span>
</div>

<div class="cart-checkout-summary-total">
<span>Total</span>
<div class="cart-checkout-total-amount">
<div class="cart-checkout-total-fiat">${{ number_format($total, 2) }}</div>
@if(is_numeric($xmrTotal))
<div class="cart-checkout-total-crypto">
≈ ɱ{{ number_format($xmrTotal, 4) }}
</div>
@endif
</div>
</div>
</div>
</div>

<div class="cart-checkout-actions">
<a href="{{ route('cart.index') }}" class="cart-checkout-back-btn">Back to Cart</a>

<form action="{{ route('orders.store') }}" method="POST">
@csrf
<button type="submit" class="cart-checkout-proceed-btn">Proceed with Order</button>
</form>
</div>
</div>
</div>
</div>

@endsection
