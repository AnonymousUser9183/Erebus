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
--color-warning: #ea580c;
--spacing-xs: 8px;
--spacing-sm: 12px;
--spacing-md: 16px;
--spacing-lg: 20px;
--spacing-xl: 24px;
--spacing-2xl: 32px;
--radius: 8px;
}

.cart-index-container {
max-width: 1000px;
margin: 0 auto;
padding: var(--spacing-xl);
}

.cart-index-breadcrumb {
font-size: 13px;
color: var(--color-text-secondary);
margin-bottom: var(--spacing-2xl);
display: flex;
gap: var(--spacing-sm);
align-items: center;
flex-wrap: wrap;
}

.cart-index-breadcrumb a {
color: var(--color-accent);
text-decoration: none;
font-weight: 500;
transition: color 0.3s ease;
}

.cart-index-breadcrumb a:hover {
color: var(--color-accent-light);
}

.cart-index-empty-card {
background-color: var(--color-bg-secondary);
border: 1px solid var(--color-border);
border-radius: var(--radius);
padding: var(--spacing-2xl);
text-align: center;
}

.cart-index-empty-title {
font-size: 28px;
font-weight: 600;
color: var(--color-text-primary);
margin: 0 0 var(--spacing-md) 0;
}

.cart-index-empty-text {
font-size: 15px;
color: var(--color-text-secondary);
margin: 0 0 var(--spacing-lg) 0;
}

.cart-index-browse-btn {
display: inline-block;
background-color: var(--color-accent);
color: #ffffff;
padding: var(--spacing-md) var(--spacing-xl);
border: none;
border-radius: var(--radius);
font-size: 14px;
font-weight: 600;
cursor: pointer;
text-decoration: none;
transition: background-color 0.3s ease;
}

.cart-index-browse-btn:hover {
background-color: var(--color-accent-light);
}

.cart-index-main-card {
background-color: var(--color-bg-secondary);
border: 1px solid var(--color-border);
border-radius: var(--radius);
padding: var(--spacing-lg);
}

.cart-index-item {
display: flex;
gap: var(--spacing-lg);
padding: var(--spacing-lg);
border-bottom: 1px solid var(--color-border);
}

.cart-index-item:last-of-type {
border-bottom: none;
}

.cart-index-item-image {
width: 140px;
min-width: 140px;
height: 140px;
border-radius: var(--radius);
overflow: hidden;
border: 1px solid var(--color-border);
background-color: var(--color-bg-primary);
}

.cart-index-item-image img {
width: 100%;
height: 100%;
object-fit: cover;
}

.cart-index-item-details {
flex: 1;
display: flex;
flex-direction: column;
gap: var(--spacing-md);
}

.cart-index-item-header {
display: flex;
justify-content: space-between;
gap: var(--spacing-lg);
align-items: flex-start;
}

.cart-index-item-title {
font-size: 16px;
font-weight: 600;
color: var(--color-text-primary);
margin: 0;
}

.cart-index-item-title span {
font-size: 13px;
font-weight: 400;
color: var(--color-text-secondary);
}

.cart-index-item-vendor {
font-size: 13px;
color: var(--color-text-secondary);
margin: var(--spacing-sm) 0 0 0;
}

.cart-index-item-price {
text-align: right;
}

.cart-index-price-fiat {
font-size: 18px;
font-weight: 600;
color: var(--color-accent);
}

.cart-index-price-crypto {
font-size: 12px;
color: var(--color-text-secondary);
}

.cart-index-item-controls {
display: flex;
align-items: center;
gap: var(--spacing-lg);
flex-wrap: wrap;
}

.cart-index-quantity-form {
display: flex;
align-items: center;
gap: var(--spacing-sm);
}

.cart-index-quantity-label {
font-size: 13px;
font-weight: 600;
color: var(--color-text-primary);
text-transform: uppercase;
letter-spacing: 0.5px;
}

.cart-index-quantity-input {
width: 80px;
padding: var(--spacing-sm);
border: 1px solid var(--color-border);
border-radius: var(--radius);
font-size: 13px;
text-align: center;
}

.cart-index-quantity-input:focus {
outline: none;
border-color: var(--color-accent);
box-shadow: 0 0 0 2px rgba(32, 128, 136, 0.1);
}

.cart-index-update-btn {
background-color: var(--color-accent);
color: #ffffff;
padding: var(--spacing-sm) var(--spacing-md);
border: none;
border-radius: var(--radius);
font-size: 12px;
font-weight: 600;
cursor: pointer;
transition: background-color 0.3s ease;
}

.cart-index-update-btn:hover {
background-color: var(--color-accent-light);
}

.cart-index-option-badge {
background-color: var(--color-bg-primary);
border: 1px solid var(--color-border);
border-radius: var(--radius);
padding: var(--spacing-sm) var(--spacing-md);
font-size: 12px;
color: var(--color-text-secondary);
}

.cart-index-additional-controls {
display: flex;
gap: var(--spacing-md);
align-items: center;
}

.cart-index-remove-btn {
background-color: #ef4444;
color: #ffffff;
padding: var(--spacing-sm) var(--spacing-md);
border: none;
border-radius: var(--radius);
font-size: 12px;
font-weight: 600;
cursor: pointer;
transition: background-color 0.3s ease;
}

.cart-index-remove-btn:hover {
background-color: #dc2626;
}

.cart-index-bulk-badge {
background-color: #fef3c7;
border: 1px solid #fcd34d;
border-radius: var(--radius);
padding: var(--spacing-sm) var(--spacing-md);
font-size: 12px;
color: #78350f;
}

.cart-index-bottom-row {
display: grid;
grid-template-columns: 1fr 350px;
gap: var(--spacing-xl);
margin-top: var(--spacing-xl);
padding-top: var(--spacing-xl);
border-top: 1px solid var(--color-border);
}

.cart-index-message-container {
display: flex;
flex-direction: column;
gap: var(--spacing-md);
}

.cart-index-message-encrypted {
background-color: var(--color-bg-primary);
border: 1px solid var(--color-border);
border-radius: var(--radius);
padding: var(--spacing-md);
}

.cart-index-message-label {
font-size: 13px;
font-weight: 600;
color: var(--color-accent);
text-transform: uppercase;
letter-spacing: 0.5px;
display: block;
margin-bottom: var(--spacing-sm);
}

.cart-index-message-encrypted textarea {
width: 100%;
padding: var(--spacing-sm);
border: 1px solid var(--color-border);
border-radius: var(--radius);
font-size: 11px;
color: var(--color-text-primary);
font-family: 'Monaco', 'Menlo', monospace;
resize: vertical;
min-height: 80px;
background-color: var(--color-bg-secondary);
box-sizing: border-box;
}

.cart-index-message-form {
background-color: var(--color-bg-primary);
border: 2px solid var(--color-warning);
border-radius: var(--radius);
padding: var(--spacing-md);
}

.cart-index-message-hint {
font-size: 12px;
color: var(--color-warning);
display: block;
margin-bottom: var(--spacing-md);
line-height: 1.5;
font-weight: 600;
}

.cart-index-message-textarea {
width: 100%;
padding: var(--spacing-sm);
border: 1px solid var(--color-border);
border-radius: var(--radius);
font-size: 13px;
color: var(--color-text-primary);
font-family: 'Monaco', 'Menlo', monospace;
resize: vertical;
min-height: 120px;
box-sizing: border-box;
}

.cart-index-message-textarea:focus {
outline: none;
border-color: var(--color-accent);
box-shadow: 0 0 0 2px rgba(32, 128, 136, 0.1);
}

.cart-index-message-button {
width: 100%;
background-color: var(--color-accent);
color: #ffffff;
padding: var(--spacing-sm) var(--spacing-md);
border: none;
border-radius: var(--radius);
font-size: 12px;
font-weight: 600;
cursor: pointer;
transition: background-color 0.3s ease;
margin-top: var(--spacing-sm);
box-sizing: border-box;
}

.cart-index-message-button:hover {
background-color: var(--color-accent-light);
}

.cart-index-summary {
background-color: var(--color-bg-primary);
border: 1px solid var(--color-border);
border-radius: var(--radius);
padding: var(--spacing-lg);
}

.cart-index-total {
background-color: var(--color-bg-secondary);
border: 1px solid var(--color-border);
border-radius: var(--radius);
padding: var(--spacing-lg);
margin-bottom: var(--spacing-lg);
display: flex;
justify-content: space-between;
align-items: center;
}

.cart-index-total-label {
font-size: 15px;
font-weight: 600;
color: var(--color-text-primary);
}

.cart-index-total-amount {
text-align: right;
}

.cart-index-total-fiat {
font-size: 20px;
font-weight: 600;
color: var(--color-accent);
}

.cart-index-total-crypto {
font-size: 12px;
color: var(--color-text-secondary);
}

.cart-index-actions {
display: flex;
flex-direction: column;
gap: var(--spacing-md);
}

.cart-index-clear-btn,
.cart-index-checkout-btn {
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

.cart-index-clear-btn {
background-color: transparent;
border: 1px solid var(--color-border);
color: var(--color-text-primary);
}

.cart-index-clear-btn:hover {
background-color: var(--color-bg-primary);
}

.cart-index-checkout-btn {
background-color: var(--color-accent);
color: #ffffff;
}

.cart-index-checkout-btn:hover {
background-color: var(--color-accent-light);
}

@media (max-width: 768px) {
.cart-index-container {
padding: var(--spacing-lg);
}

.cart-index-item {
flex-direction: column;
gap: var(--spacing-md);
}

.cart-index-item-image {
width: 100%;
height: 200px;
}

.cart-index-item-header {
flex-direction: column;
}

.cart-index-item-price {
text-align: left;
}

.cart-index-bottom-row {
grid-template-columns: 1fr;
}

.cart-index-quantity-form {
flex-wrap: wrap;
}

.cart-index-quantity-input {
width: 70px;
}

.cart-index-additional-controls {
width: 100%;
}
}
</style>

<div class="cart-index-container">
<div class="cart-index-breadcrumb">
<a href="{{ route('products.index') }}">Products</a>
<span>/</span>
<span>Shopping Cart</span>
</div>

@if($cartItems->isEmpty())
<div class="cart-index-empty-card">
<h2 class="cart-index-empty-title">Your Cart is Empty</h2>
<p class="cart-index-empty-text">Browse our products and add items to your cart.</p>
<a href="{{ route('products.index') }}" class="cart-index-browse-btn">Browse Products</a>
</div>
@else
<div class="cart-index-main-card">
@foreach($cartItems as $item)
<div class="cart-index-item">
<div class="cart-index-item-image">
<img src="{{ $item->product->product_picture_url }}" alt="{{ $item->product->name }}">
</div>

<div class="cart-index-item-details">
<div class="cart-index-item-header">
<div>
<h3 class="cart-index-item-title">
{{ $item->product->name }}
<span>({{ $item->selected_bulk_option ? ($item->quantity * $item->selected_bulk_option['amount']) : $item->quantity }} {{ $measurementUnits[$item->product->measurement_unit] ?? $item->product->measurement_unit }})</span>
</h3>
<p class="cart-index-item-vendor">Sold by: {{ $item->product->user->username }}</p>
</div>
<div class="cart-index-item-price">
<div class="cart-index-price-fiat">${{ number_format($item->getTotalPrice(), 2) }}</div>
@if(is_numeric($xmrPrice) && $xmrPrice > 0)
<div class="cart-index-price-crypto">≈ ɱ{{ number_format($item->getTotalPrice() / $xmrPrice, 4) }}</div>
@endif
</div>
</div>

<div class="cart-index-item-controls">
<form action="{{ route('cart.update', $item) }}" method="POST" class="cart-index-quantity-form">
@csrf
@method('PUT')
<label for="quantity_{{ $item->id }}" class="cart-index-quantity-label">
{{ $item->selected_bulk_option ? 'Sets:' : 'Qty:' }}
</label>
<input type="number" id="quantity_{{ $item->id }}" name="quantity" value="{{ $item->quantity }}" min="1" max="80000" class="cart-index-quantity-input">
<button type="submit" class="cart-index-update-btn">Update</button>
</form>

<div class="cart-index-option-badge">
Delivery: {{ $item->selected_delivery_option['description'] }}
({{ $item->selected_delivery_option['price'] > 0 ? '+$' . number_format($item->selected_delivery_option['price'], 2) : 'Free' }})
</div>
</div>

<div class="cart-index-additional-controls">
<form action="{{ route('cart.destroy', $item) }}" method="POST" class="cart-index-remove-form">
@csrf
@method('DELETE')
<button type="submit" class="cart-index-remove-btn">Remove</button>
</form>

@if($item->selected_bulk_option)
<div class="cart-index-bulk-badge">
Bulk: {{ $item->quantity }} × {{ $item->selected_bulk_option['amount'] }}
@ ${{ number_format($item->selected_bulk_option['price'], 2) }}/set
</div>
@endif
</div>
</div>
</div>
@endforeach

<div class="cart-index-bottom-row">
<div class="cart-index-message-container">
@foreach($cartItems as $item)
@if($item->encrypted_message)
<div class="cart-index-message-encrypted">
<label class="cart-index-message-label">🔐 Encrypted Message for {{ $item->product->user->username }}</label>
<textarea readonly>{{ $item->encrypted_message }}</textarea>
</div>
@endif
@endforeach

@php
$hasEncryptedMessage = $cartItems->contains(fn($item) => $item->encrypted_message);
$firstItem = $cartItems->first();
@endphp

@if(!$hasEncryptedMessage && $firstItem)
<form action="{{ route('cart.message.save', $firstItem) }}" method="POST" class="cart-index-message-form">
@csrf
<label for="message_{{ $firstItem->id }}" class="cart-index-message-label">
🔐 PGP Encrypted Message Required
</label>
<span class="cart-index-message-hint">
⚠️ REQUIRED: Paste your PGP encrypted message (starts with -----BEGIN PGP MESSAGE-----). Plain text messages will be rejected.
</span>
<textarea id="message_{{ $firstItem->id }}" name="message" class="cart-index-message-textarea" placeholder="-----BEGIN PGP MESSAGE-----&#10;...your encrypted message here...&#10;-----END PGP MESSAGE-----" required minlength="50" maxlength="4000"></textarea>
<button type="submit" class="cart-index-message-button">Save Encrypted Message</button>
</form>

@if($errors->any())
<div class="cart-index-message-encrypted" style="border-color: #ef4444; background-color: #fef2f2;">
<label class="cart-index-message-label" style="color: #ef4444;">❌ Error</label>
@foreach($errors->all() as $error)
<p style="color: #ef4444; margin: 0; font-size: 12px;">{{ $error }}</p>
@endforeach
</div>
@endif
@endif
</div>

<div class="cart-index-summary">
<div class="cart-index-total">
<span class="cart-index-total-label">Total:</span>
<div class="cart-index-total-amount">
<div class="cart-index-total-fiat">${{ number_format($cartTotal, 2) }}</div>
@if(is_numeric($xmrTotal))
<div class="cart-index-total-crypto">≈ ɱ{{ number_format($xmrTotal, 4) }}</div>
@endif
</div>
</div>

<div class="cart-index-actions">
<form action="{{ route('cart.clear') }}" method="POST">
@csrf
@method('DELETE')
<button type="submit" class="cart-index-clear-btn">Clear Cart</button>
</form>

<a href="{{ route('cart.checkout') }}" class="cart-index-checkout-btn">Proceed to Checkout</a>
</div>
</div>
</div>
</div>
@endif
</div>

@endsection
