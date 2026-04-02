@extends('layouts.app')

@section('content')

<style>
    :root {
        --color-accent: #208088;
        --color-text-primary: #134252;
        --color-text-secondary: #62746e;
        --color-card-bg: #ffffff;
        --color-border: #d4d8d6;
        --color-input-bg: #f5f7f6;
        --color-danger: #dc2626;
        --color-success: #10b981;
        --color-warning: #f59e0b;
        --radius-base: 8px;
        --radius-lg: 12px;
        --spacing-sm: 12px;
        --spacing-md: 12px;
        --spacing-lg: 16px;
        --spacing-xl: 24px;
    }

    * {
        box-sizing: border-box;
    }

    /* Main Container */
    .orders-show-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: var(--spacing-xl);
    }

    /* Header */
    .orders-show-header {
        margin-bottom: var(--spacing-xl);
    }

    .orders-show-title {
        font-size: 32px;
        margin: 0 0 var(--spacing-md) 0;
        color: var(--color-text-primary);
        font-weight: 600;
    }

    .orders-show-id {
        color: var(--color-text-secondary);
        font-size: 14px;
        margin: 0;
    }

    /* Status Container */
    .orders-show-status-container {
        margin-bottom: var(--spacing-xl);
    }

    .orders-show-status-card {
        background: var(--color-card-bg);
        border-radius: var(--radius-lg);
        border: 1px solid var(--color-border);
        padding: var(--spacing-xl);
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    .orders-show-status-title {
        font-size: 20px;
        margin: 0 0 var(--spacing-xl) 0;
        color: var(--color-text-primary);
        font-weight: 600;
    }

    /* Status Steps */
    .orders-show-status-steps {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: var(--spacing-lg);
        margin-bottom: var(--spacing-xl);
        position: relative;
    }

    .orders-show-status-step {
        text-align: center;
        padding: var(--spacing-lg);
        border-radius: var(--radius-base);
        border: 2px solid var(--color-border);
        position: relative;
        background-color: var(--color-card-bg);
        transition: all 0.3s ease;
    }

    .orders-show-status-step.active {
        border-color: var(--color-accent);
        background-color: rgba(32, 128, 136, 0.08);
    }

    .orders-show-status-step.cancelled-step {
        opacity: 0.6;
        border-color: var(--color-danger);
    }

    .orders-show-status-step-number {
        font-size: 24px;
        font-weight: 600;
        color: var(--color-accent);
        margin-bottom: 6px;
    }

    .orders-show-status-step-label {
        font-size: 13px;
        color: var(--color-text-primary);
        font-weight: 500;
        margin-bottom: 6px;
    }

    .orders-show-status-step-date {
        font-size: 12px;
        color: var(--color-text-secondary);
        margin: 0;
    }

    /* Status Markers */
    .orders-show-status-cancelled-marker,
    .orders-show-status-disputed-marker {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 16px;
    }

    .orders-show-status-cancelled-marker {
        background-color: var(--color-danger);
        color: white;
    }

    .orders-show-status-disputed-marker {
        background-color: var(--color-warning);
        color: white;
    }

    .orders-show-status-cancelled-x,
    .orders-show-status-disputed-question {
        display: block;
    }

    /* Status Explanation */
    .orders-show-status-explanation {
        background-color: var(--color-input-bg);
        padding: var(--spacing-lg);
        border-radius: var(--radius-base);
        border: 1px solid var(--color-border);
        color: var(--color-text-primary);
        line-height: 1.6;
        margin-top: var(--spacing-lg);
    }

    .orders-show-status-explanation p {
        margin: 0 0 var(--spacing-md) 0;
        font-size: 14px;
    }

    .orders-show-status-explanation p:last-child {
        margin-bottom: 0;
    }

    /* Actions */
    .orders-show-actions {
        margin-top: var(--spacing-lg);
        margin-bottom: var(--spacing-lg);
    }

    .orders-show-action-form {
        display: inline;
    }

    .orders-show-action-btn {
        background-color: var(--color-accent);
        color: white;
        padding: 10px 24px;
        border: none;
        border-radius: var(--radius-base);
        font-weight: 600;
        cursor: pointer;
        font-size: 14px;
        transition: all 0.3s ease;
        display: inline-block;
        text-decoration: none;
        text-align: center;
    }

    .orders-show-action-btn:hover {
        background-color: #1a6f78;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(32, 128, 136, 0.2);
    }

    .orders-show-confirm-delivery-btn {
        background-color: var(--color-success);
    }

    .orders-show-confirm-delivery-btn:hover {
        background-color: #059669;
    }

    .orders-show-cancel-container {
        text-align: center;
        margin: var(--spacing-xl) 0;
    }

    .orders-show-cancel-btn {
        background-color: var(--color-danger);
    }

    .orders-show-cancel-btn:hover {
        background-color: #991b1b;
    }

    .orders-show-cancel-btn-standalone {
        display: inline-block;
    }

    /* Payment Section */
    .orders-show-payment-container {
        margin-bottom: var(--spacing-xl);
    }

    .orders-show-payment-card {
        background: var(--color-card-bg);
        border-radius: var(--radius-lg);
        border: 1px solid var(--color-border);
        padding: var(--spacing-xl);
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        margin-bottom: var(--spacing-lg);
    }

    .orders-show-payment-subtitle {
        font-size: 18px;
        margin: 0 0 var(--spacing-lg) 0;
        color: var(--color-text-primary);
        font-weight: 600;
    }

    .orders-show-payment-details {
        display: flex;
        flex-direction: column;
        gap: var(--spacing-lg);
    }

    .orders-show-payment-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        padding: var(--spacing-md) 0;
        border-bottom: 1px solid var(--color-border);
    }

    .orders-show-payment-row:last-child {
        border-bottom: none;
    }

    .orders-show-payment-label {
        font-weight: 600;
        color: var(--color-text-primary);
        font-size: 14px;
        flex: 0 0 auto;
        margin-right: var(--spacing-lg);
    }

    .orders-show-payment-value {
        color: var(--color-text-secondary);
        font-size: 14px;
        text-align: right;
    }

    .orders-show-payment-value-group {
        display: flex;
        flex-direction: column;
        gap: var(--spacing-sm);
        align-items: flex-end;
    }

    .orders-show-payment-amount {
        font-weight: 600;
        color: var(--color-text-primary);
        font-family: monospace;
    }

    .orders-show-payment-remaining {
        font-size: 12px;
        color: var(--color-text-secondary);
    }

    .orders-show-payment-status-wrapper {
        display: flex;
        justify-content: flex-end;
    }

    .orders-show-payment-status {
        display: inline-block;
        padding: 6px 12px;
        border-radius: var(--radius-base);
        font-size: 12px;
        font-weight: 600;
    }

    .orders-show-payment-status-completed {
        background-color: rgba(16, 185, 129, 0.12);
        color: var(--color-success);
    }

    .orders-show-payment-status-awaiting {
        background-color: rgba(59, 130, 246, 0.12);
        color: #3b82f6;
    }

    .orders-show-payment-status-insufficient {
        background-color: rgba(244, 63, 94, 0.12);
        color: var(--color-danger);
    }

    .orders-show-payment-expiry {
        background-color: rgba(245, 158, 11, 0.08);
        padding: var(--spacing-lg);
        border-radius: var(--radius-base);
        border-left: 4px solid var(--color-warning);
        margin-top: var(--spacing-lg);
    }

    .orders-show-payment-expiry p {
        margin: 0;
        color: var(--color-text-primary);
        font-size: 14px;
        line-height: 1.6;
    }

    .orders-show-payment-disclaimer {
        background-color: var(--color-input-bg);
        padding: var(--spacing-lg);
        border-radius: var(--radius-base);
        margin-top: var(--spacing-lg);
    }

    .orders-show-payment-disclaimer p {
        margin: 0;
        color: var(--color-text-secondary);
        font-size: 13px;
        line-height: 1.6;
    }

    .orders-show-payment-qr {
        text-align: center;
        padding: var(--spacing-lg) 0;
    }

    .orders-show-payment-qr-image {
        max-width: 250px;
        border: 1px solid var(--color-border);
        border-radius: var(--radius-base);
        padding: var(--spacing-md);
        background-color: var(--color-input-bg);
    }

    .orders-show-payment-address {
        background-color: var(--color-input-bg);
        padding: var(--spacing-lg);
        border-radius: var(--radius-base);
        font-family: monospace;
        font-size: 12px;
        word-break: break-all;
        border: 1px solid var(--color-border);
        margin: var(--spacing-lg) 0;
        color: var(--color-text-primary);
    }

    .orders-show-payment-refresh {
        text-align: center;
        padding: var(--spacing-lg) 0;
    }

    .orders-show-payment-refresh-btn {
        color: var(--color-accent);
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .orders-show-payment-refresh-btn:hover {
        text-decoration: underline;
    }

    /* Order Details */
    .orders-show-details-container {
        margin-bottom: var(--spacing-xl);
    }

    .orders-show-details-card {
        background: var(--color-card-bg);
        border-radius: var(--radius-lg);
        border: 1px solid var(--color-border);
        padding: var(--spacing-xl);
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    .orders-show-details-title {
        font-size: 20px;
        margin: 0 0 var(--spacing-xl) 0;
        color: var(--color-text-primary);
        font-weight: 600;
    }

    .orders-show-info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: var(--spacing-lg);
    }

    .orders-show-info-item {
        padding: var(--spacing-lg);
        background-color: var(--color-input-bg);
        border-radius: var(--radius-base);
        border: 1px solid var(--color-border);
    }

    .orders-show-info-label {
        font-size: 12px;
        font-weight: 600;
        color: var(--color-text-secondary);
        text-transform: uppercase;
        margin-bottom: var(--spacing-md);
        display: block;
    }

    .orders-show-info-value {
        font-size: 14px;
        font-weight: 500;
        color: var(--color-text-primary);
        word-break: break-word;
    }

    .orders-show-info-value.total {
        font-size: 16px;
        font-weight: 600;
        color: var(--color-accent);
    }

    /* Order Items */
    .orders-show-items-container {
        margin-bottom: var(--spacing-xl);
    }

    .orders-show-items-card {
        background: var(--color-card-bg);
        border-radius: var(--radius-lg);
        border: 1px solid var(--color-border);
        padding: var(--spacing-xl);
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    .orders-show-items-title {
        font-size: 20px;
        margin: 0 0 var(--spacing-xl) 0;
        color: var(--color-text-primary);
        font-weight: 600;
    }

    .orders-show-items-list {
        display: flex;
        flex-direction: column;
        gap: var(--spacing-lg);
    }

    .orders-show-item {
        padding: var(--spacing-lg);
        background-color: var(--color-input-bg);
        border-radius: var(--radius-base);
        border: 1px solid var(--color-border);
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: var(--spacing-lg);
    }

    .orders-show-item-details {
        flex: 1;
    }

    .orders-show-item-name {
        margin: 0 0 var(--spacing-md) 0;
        font-size: 16px;
        color: var(--color-text-primary);
        font-weight: 600;
    }

    .orders-show-item-description {
        font-size: 13px;
        color: var(--color-text-secondary);
        margin-bottom: var(--spacing-md);
        line-height: 1.5;
    }

    .orders-show-item-meta {
        display: flex;
        flex-wrap: wrap;
        gap: var(--spacing-md);
        font-size: 13px;
    }

    .orders-show-item-quantity,
    .orders-show-item-delivery {
        color: var(--color-text-secondary);
    }

    .orders-show-item-type {
        display: inline-block;
        padding: 4px 10px;
        border-radius: var(--radius-base);
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .orders-show-item-type.type-digital {
        background-color: rgba(59, 130, 246, 0.12);
        color: #3b82f6;
    }

    .orders-show-item-type.type-cargo {
        background-color: rgba(139, 92, 246, 0.12);
        color: #8b5cf6;
    }

    .orders-show-item-type.type-deaddrop {
        background-color: rgba(236, 72, 153, 0.12);
        color: #ec4899;
    }

    .orders-show-item-type.type-deleted {
        background-color: rgba(107, 114, 128, 0.12);
        color: #6b7280;
    }

    .orders-show-item-category {
        display: inline-block;
        padding: 4px 10px;
        border-radius: var(--radius-base);
        font-size: 11px;
        font-weight: 600;
        background-color: rgba(32, 128, 136, 0.12);
        color: var(--color-accent);
    }

    .orders-show-item-delivery-text-container {
        margin-top: var(--spacing-lg);
        padding-top: var(--spacing-lg);
        border-top: 1px solid var(--color-border);
    }

    .orders-show-item-delivery-text-container h4 {
        margin: 0 0 var(--spacing-md) 0;
        font-size: 13px;
        font-weight: 600;
        color: var(--color-text-primary);
    }

    .orders-show-item-delivery-text {
        font-size: 13px;
        color: var(--color-text-secondary);
        line-height: 1.6;
        background-color: white;
        padding: var(--spacing-md);
        border-radius: var(--radius-base);
        border: 1px solid var(--color-border);
    }

    .orders-show-item-price {
        text-align: right;
        flex: 0 0 auto;
    }

    .orders-show-item-price-label {
        font-size: 12px;
        color: var(--color-text-secondary);
        margin-bottom: var(--spacing-sm);
    }

    .orders-show-item-price-value {
        font-size: 18px;
        font-weight: 700;
        color: var(--color-accent);
    }

    /* Dispute Form */
    .orders-show-dispute-form-container {
        margin-bottom: var(--spacing-xl);
    }

    .orders-show-dispute-form-card {
        background: var(--color-card-bg);
        border-radius: var(--radius-lg);
        border: 1px solid var(--color-border);
        padding: var(--spacing-xl);
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    .orders-show-dispute-form-title {
        font-size: 20px;
        margin: 0 0 var(--spacing-xl) 0;
        color: var(--color-text-primary);
        font-weight: 600;
    }

    .orders-show-dispute-form-description {
        color: var(--color-text-secondary);
        font-size: 14px;
        margin-bottom: var(--spacing-lg);
        line-height: 1.6;
    }

    .orders-show-dispute-form-textarea {
        width: 100%;
        padding: var(--spacing-md);
        border: 1px solid var(--color-border);
        border-radius: var(--radius-base);
        font-family: inherit;
        font-size: 14px;
        color: var(--color-text-primary);
        background-color: var(--color-input-bg);
        resize: vertical;
        min-height: 120px;
        transition: all 0.3s ease;
        box-sizing: border-box;
    }

    .orders-show-dispute-form-textarea:focus {
        outline: none;
        border-color: var(--color-accent);
        background-color: var(--color-card-bg);
        box-shadow: 0 0 0 3px rgba(32, 128, 136, 0.1);
    }

    .orders-show-dispute-form-submit {
        margin-top: var(--spacing-lg);
    }

    .orders-show-dispute-form-button {
        background-color: var(--color-accent);
        color: white;
        padding: 10px 24px;
        border: none;
        border-radius: var(--radius-base);
        font-weight: 600;
        cursor: pointer;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .orders-show-dispute-form-button:hover {
        background-color: #1a6f78;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(32, 128, 136, 0.2);
    }

    /* Dispute Section */
    .orders-show-dispute-container {
        margin-bottom: var(--spacing-xl);
    }

    .orders-show-dispute-card {
        background: var(--color-card-bg);
        border-radius: var(--radius-lg);
        border: 1px solid var(--color-border);
        padding: var(--spacing-xl);
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    .orders-show-dispute-title {
        font-size: 20px;
        margin: 0 0 var(--spacing-lg) 0;
        color: var(--color-text-primary);
        font-weight: 600;
    }

    .orders-show-dispute-status {
        display: inline-block;
        padding: 6px 12px;
        border-radius: var(--radius-base);
        font-size: 12px;
        font-weight: 600;
        margin-bottom: var(--spacing-lg);
    }

    .orders-show-dispute-status-active {
        background-color: rgba(245, 158, 11, 0.12);
        color: var(--color-warning);
    }

    .orders-show-dispute-status-resolved {
        background-color: rgba(16, 185, 129, 0.12);
        color: var(--color-success);
    }

    .orders-show-dispute-info {
        padding: var(--spacing-lg) 0;
        border-top: 1px solid var(--color-border);
        border-bottom: 1px solid var(--color-border);
    }

    .orders-show-dispute-section-title {
        font-size: 14px;
        font-weight: 600;
        color: var(--color-text-primary);
        margin: 0 0 var(--spacing-md) 0;
    }

    .orders-show-dispute-text {
        color: var(--color-text-secondary);
        font-size: 14px;
        line-height: 1.6;
        margin-bottom: var(--spacing-lg);
    }

    .orders-show-dispute-resolved-date {
        font-size: 13px;
        color: var(--color-text-secondary);
    }

    .orders-show-dispute-link-container {
        padding-top: var(--spacing-lg);
        text-align: center;
    }

    .orders-show-dispute-link {
        color: var(--color-accent);
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .orders-show-dispute-link:hover {
        text-decoration: underline;
    }

    /* Message Section */
    .orders-show-message-container {
        margin-bottom: var(--spacing-xl);
    }

    .orders-show-message-card {
        background: var(--color-card-bg);
        border-radius: var(--radius-lg);
        border: 1px solid var(--color-border);
        padding: var(--spacing-xl);
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    .orders-show-message-title {
        font-size: 20px;
        margin: 0 0 var(--spacing-lg) 0;
        color: var(--color-text-primary);
        font-weight: 600;
    }

    .orders-show-message-content {
        background-color: var(--color-input-bg);
        padding: var(--spacing-lg);
        border-radius: var(--radius-base);
        border: 1px solid var(--color-border);
    }

    .orders-show-message-textarea {
        width: 100%;
        padding: var(--spacing-md);
        border: 1px solid var(--color-border);
        border-radius: var(--radius-base);
        font-family: monospace;
        font-size: 12px;
        color: var(--color-text-primary);
        background-color: var(--color-card-bg);
        min-height: 150px;
        resize: vertical;
        box-sizing: border-box;
    }

    /* Reviews Section */
    .orders-show-reviews-container {
        margin-bottom: var(--spacing-xl);
    }

    .orders-show-reviews-card {
        background: var(--color-card-bg);
        border-radius: var(--radius-lg);
        border: 1px solid var(--color-border);
        padding: var(--spacing-xl);
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    .orders-show-reviews-title {
        font-size: 20px;
        margin: 0 0 var(--spacing-xl) 0;
        color: var(--color-text-primary);
        font-weight: 600;
    }

    .orders-show-reviews-list {
        display: flex;
        flex-direction: column;
        gap: var(--spacing-lg);
    }

    .orders-show-review-item {
        padding: var(--spacing-lg);
        border: 1px solid var(--color-border);
        border-radius: var(--radius-base);
        background-color: var(--color-input-bg);
    }

    .orders-show-review-product-name {
        margin: 0 0 var(--spacing-md) 0;
        font-size: 16px;
        color: var(--color-text-primary);
        font-weight: 600;
    }

    .orders-show-review-existing {
        display: flex;
        flex-direction: column;
        gap: var(--spacing-md);
    }

    .orders-show-review-date {
        font-size: 13px;
        color: var(--color-text-secondary);
        margin: 0;
    }

    .orders-show-review-sentiment {
        display: inline-block;
        padding: 6px 12px;
        border-radius: var(--radius-base);
        font-size: 12px;
        font-weight: 600;
    }

    .orders-show-review-sentiment-positive {
        background-color: rgba(16, 185, 129, 0.12);
        color: var(--color-success);
    }

    .orders-show-review-sentiment-mixed {
        background-color: rgba(245, 158, 11, 0.12);
        color: var(--color-warning);
    }

    .orders-show-review-sentiment-negative {
        background-color: rgba(239, 68, 68, 0.12);
        color: var(--color-danger);
    }

    .orders-show-review-text {
        font-size: 14px;
        color: var(--color-text-secondary);
        line-height: 1.6;
        padding: var(--spacing-md);
        background-color: white;
        border-radius: var(--radius-base);
        border: 1px solid var(--color-border);
    }

    .orders-show-review-form {
        display: flex;
        flex-direction: column;
        gap: var(--spacing-lg);
    }

    .orders-show-review-field {
        display: flex;
        flex-direction: column;
        gap: var(--spacing-md);
    }

    .orders-show-review-label {
        color: var(--color-text-primary);
        font-weight: 500;
        font-size: 14px;
    }

    .orders-show-review-textarea {
        padding: var(--spacing-md);
        border: 1px solid var(--color-border);
        border-radius: var(--radius-base);
        font-family: inherit;
        font-size: 14px;
        color: var(--color-text-primary);
        background-color: var(--color-card-bg);
        resize: vertical;
        min-height: 100px;
        transition: all 0.3s ease;
        box-sizing: border-box;
    }

    .orders-show-review-textarea:focus {
        outline: none;
        border-color: var(--color-accent);
        box-shadow: 0 0 0 3px rgba(32, 128, 136, 0.1);
    }

    .orders-show-review-sentiment-options {
        display: flex;
        gap: var(--spacing-lg);
        flex-wrap: wrap;
    }

    .orders-show-review-sentiment-option {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .orders-show-review-radio {
        cursor: pointer;
        width: 16px;
        height: 16px;
        accent-color: var(--color-accent);
    }

    .orders-show-review-radio-label {
        cursor: pointer;
        padding: 6px 12px;
        border-radius: var(--radius-base);
        border: 1px solid var(--color-border);
        font-weight: 500;
        font-size: 14px;
        transition: all 0.2s ease;
    }

    .orders-show-review-radio:checked + .orders-show-review-radio-label {
        border-color: var(--color-accent);
        background-color: rgba(32, 128, 136, 0.08);
    }

    .orders-show-review-submit-container {
        display: flex;
        gap: var(--spacing-lg);
    }

    .orders-show-review-submit-btn {
        background-color: var(--color-accent);
        color: white;
        padding: 10px 24px;
        border: none;
        border-radius: var(--radius-base);
        font-weight: 600;
        cursor: pointer;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .orders-show-review-submit-btn:hover {
        background-color: #1a6f78;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(32, 128, 136, 0.2);
    }

    /* Responsive Design */
    @media (max-width: 1024px) {
        .orders-show-container {
            padding: var(--spacing-lg);
        }

        .orders-show-info-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .orders-show-status-steps {
            grid-template-columns: repeat(2, 1fr);
            gap: var(--spacing-md);
        }

        .orders-show-title {
            font-size: 24px;
        }

        .orders-show-status-title {
            font-size: 18px;
        }

        .orders-show-item {
            flex-direction: column;
        }

        .orders-show-item-price {
            text-align: left;
        }

        .orders-show-payment-row {
            flex-direction: column;
            gap: var(--spacing-sm);
        }

        .orders-show-payment-value {
            text-align: left;
        }

        .orders-show-review-sentiment-options {
            flex-direction: column;
            gap: var(--spacing-md);
        }

        .orders-show-info-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 480px) {
        .orders-show-container {
            padding: var(--spacing-md);
        }

        .orders-show-title {
            font-size: 20px;
        }

        .orders-show-status-steps {
            grid-template-columns: 1fr;
            gap: var(--spacing-md);
        }

        .orders-show-status-card,
        .orders-show-payment-card,
        .orders-show-details-card,
        .orders-show-items-card,
        .orders-show-reviews-card,
        .orders-show-dispute-form-card,
        .orders-show-message-card {
            padding: var(--spacing-lg);
        }
    }
</style>

<div class="orders-show-container">
    <!-- Header -->
    <div class="orders-show-header">
        <h1 class="orders-show-title">Order Details</h1>
        <div class="orders-show-id">ID: {{ substr($order->id, 0, 8) }}</div>
    </div>

    <!-- Status Section -->
    <div class="orders-show-status-container">
        <div class="orders-show-status-card orders-show-status-{{ $order->status }}">
            <h2 class="orders-show-status-title">Status: {{ $order->getFormattedStatus() }}</h2>
            
            <div class="orders-show-status-steps {{ $order->status === 'cancelled' ? 'with-cancelled' : '' }} {{ isset($dispute) && $dispute ? 'with-disputed' : '' }}">
                <div class="orders-show-status-step {{ $order->status === 'waiting_payment' || $order->is_paid || $order->is_sent || $order->is_completed ? 'active' : '' }} {{ $order->status === 'cancelled' && !$order->paid_at ? 'cancelled-step' : '' }}">
                    <div class="orders-show-status-step-number">1</div>
                    <div class="orders-show-status-step-label">Waiting for Payment</div>
                    @if($order->created_at)
                        <div class="orders-show-status-step-date">{{ $order->created_at->format('Y-m-d / H:i') }}</div>
                    @endif
                    @if($order->status === 'cancelled' && !$order->paid_at)
                        <div class="orders-show-status-cancelled-marker">
                            <div class="orders-show-status-cancelled-x">X</div>
                        </div>
                    @endif
                </div>
                <div class="orders-show-status-step {{ $order->is_paid || $order->is_sent || $order->is_completed ? 'active' : '' }} {{ $order->status === 'cancelled' && $order->paid_at && !$order->sent_at ? 'cancelled-step' : '' }}">
                    <div class="orders-show-status-step-number">2</div>
                    <div class="orders-show-status-step-label">Payment Received</div>
                    @if($order->paid_at)
                        <div class="orders-show-status-step-date">{{ $order->paid_at->format('Y-m-d / H:i') }}</div>
                    @endif
                    @if($order->status === 'cancelled' && $order->paid_at && !$order->sent_at)
                        <div class="orders-show-status-cancelled-marker">
                            <div class="orders-show-status-cancelled-x">X</div>
                        </div>
                    @endif
                </div>
                <div class="orders-show-status-step {{ $order->is_sent || $order->is_completed ? 'active' : '' }} {{ $order->status === 'cancelled' && $order->sent_at && !$order->completed_at ? 'cancelled-step' : '' }}">
                    <div class="orders-show-status-step-number">3</div>
                    <div class="orders-show-status-step-label">Product Sent</div>
                    @if($order->sent_at)
                        <div class="orders-show-status-step-date">{{ $order->sent_at->format('Y-m-d / H:i') }}</div>
                    @endif
                    @if($order->status === 'cancelled' && $order->sent_at && !$order->completed_at)
                        <div class="orders-show-status-cancelled-marker">
                            <div class="orders-show-status-cancelled-x">X</div>
                        </div>
                    @endif
                    @if(isset($dispute) && $dispute)
                        <div class="orders-show-status-disputed-marker">
                            <div class="orders-show-status-disputed-question">?</div>
                        </div>
                    @endif
                </div>
                <div class="orders-show-status-step {{ $order->is_completed ? 'active' : '' }}">
                    <div class="orders-show-status-step-number">4</div>
                    <div class="orders-show-status-step-label">Order Completed</div>
                    @if($order->completed_at)
                        <div class="orders-show-status-step-date">{{ $order->completed_at->format('Y-m-d / H:i') }}</div>
                    @endif
                </div>
            </div>

            @if($isBuyer)
                @if($order->status === 'waiting_payment')
                @elseif($order->status === 'payment_received')
                    <div class="orders-show-status-explanation">
                        <p>This order will be automatically cancelled if the vendor does not mark it as sent within <strong>96 hours (4 days)</strong> after payment was received.</p>
                        @if($order->getAutoCancelDeadline())
                            <p>Auto-cancel deadline: <strong>{{ $order->getAutoCancelDeadline()->format('Y-m-d H:i') }}</strong> ({{ $order->getAutoCancelDeadline()->diffForHumans() }})</p>
                        @endif
                    </div>
                @elseif($order->status === 'product_sent')
                    <div class="orders-show-actions">
                        <form action="{{ route('orders.mark-completed', $order->unique_url) }}" method="POST" class="orders-show-action-form">
                            @csrf
                            <button type="submit" class="orders-show-action-btn orders-show-confirm-delivery-btn">Confirm Order</button>
                        </form>
                    </div>
                    @if(!isset($dispute) || !$dispute)
                        <div class="orders-show-status-explanation">
                            <p>This order will be automatically marked as completed if not confirmed within <strong>192 hours (8 days)</strong> after being marked as sent.</p>
                            @if($order->getAutoCompleteDeadline())
                                <p>Auto-complete deadline: <strong>{{ $order->getAutoCompleteDeadline()->format('Y-m-d H:i') }}</strong> ({{ $order->getAutoCompleteDeadline()->diffForHumans() }})</p>
                            @endif
                        </div>
                    @endif
                @endif
            @endif
        </div>
    </div>

    <!-- Payment Section -->
    @if($isBuyer && $order->status === 'waiting_payment')
        <div class="orders-show-payment-container">
            <div class="orders-show-payment-card">
                <h2 class="orders-show-payment-subtitle">Payment Information</h2>
            
                <div class="orders-show-payment-details">
                    <div class="orders-show-payment-row">
                        <span class="orders-show-payment-label">Required Amount:</span>
                        <span class="orders-show-payment-value">
                            <span class="orders-show-payment-amount">ɱ{{ number_format($order->required_xmr_amount, 12) }} XMR</span>
                        </span>
                    </div>
                
                    <div class="orders-show-payment-row">
                        <span class="orders-show-payment-label">USD/XMR Rate:</span>
                        <span class="orders-show-payment-value">
                            ${{ number_format($order->xmr_usd_rate, 2) }} per XMR
                        </span>
                    </div>
                
                    <div class="orders-show-payment-row">
                        <span class="orders-show-payment-label">Minimum Payment:</span>
                        <span class="orders-show-payment-value">
                            <span class="orders-show-payment-amount">ɱ{{ number_format($order->required_xmr_amount * 0.1, 12) }} XMR (10%)</span>
                        </span>
                    </div>
                
                    @if($order->total_received_xmr > 0 && !$order->is_paid)
                        <div class="orders-show-payment-row">
                            <span class="orders-show-payment-label">Amount Received:</span>
                            <div class="orders-show-payment-value-group">
                                <span class="orders-show-payment-amount">ɱ{{ number_format($order->total_received_xmr, 12) }} XMR</span>
                                <span class="orders-show-payment-remaining">
                                    Remaining: ɱ{{ number_format($order->required_xmr_amount - $order->total_received_xmr, 12) }} XMR
                                </span>
                            </div>
                        </div>
                    @endif
                
                    <div class="orders-show-payment-row">
                        <span class="orders-show-payment-label">Payment Status:</span>
                        <div class="orders-show-payment-status-wrapper">
                            @if($order->is_paid)
                                <span class="orders-show-payment-status orders-show-payment-status-completed">
                                    Payment Completed
                                </span>
                            @elseif($order->total_received_xmr > 0 && $order->total_received_xmr < $order->required_xmr_amount)
                                <span class="orders-show-payment-status orders-show-payment-status-insufficient">
                                    Insufficient Amount
                                </span>
                            @else
                                <span class="orders-show-payment-status orders-show-payment-status-awaiting">
                                    Awaiting Payment
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            
                @if($order->expires_at)
                    <div class="orders-show-payment-expiry">
                        <p>The payment window expires in {{ $order->expires_at->diffForHumans() }}. Your order will be automatically canceled if the required amount of Monero for the purchase hasn't been met, and any incomplete amount will not be returned to your address after automatic cancellation.</p>
                    </div>

                    <div class="orders-show-payment-disclaimer">
                        <p>After completing the order, if you (the buyer) or the vendor cancels the order, a small cancellation fee will be applied to protect our website and prevent spam. This means you will receive slightly less than the original amount when your money is refunded.</p>
                    </div>
                @endif
            </div>
        
            @if(!$order->is_paid)
                <div class="orders-show-payment-card">
                    @if($qrCode)
                        <h2 class="orders-show-payment-subtitle">Scan QR Code</h2>
                        <div class="orders-show-payment-qr">
                            <img src="{{ $qrCode }}" alt="Payment QR Code" class="orders-show-payment-qr-image">
                        </div>
                    @endif
                
                    <h2 class="orders-show-payment-subtitle" style="margin-top: 20px;">Payment Address</h2>
                    <div class="orders-show-payment-address">
                        {{ $order->payment_address }}
                    </div>
                
                    <div class="orders-show-payment-refresh">
                        <a href="{{ route('orders.show', $order->unique_url) }}" class="orders-show-payment-refresh-btn">
                            Refresh to check for new transactions
                        </a>
                    </div>
                </div>
            @endif
        </div>
    @endif

    <!-- Order Details -->
    <div class="orders-show-details-container">
        <div class="orders-show-details-card">
            <h2 class="orders-show-details-title">Order Information</h2>
            
            <div class="orders-show-info-grid">
                <div class="orders-show-info-item">
                    <div class="orders-show-info-label">Order Date</div>
                    <div class="orders-show-info-value">{{ $order->created_at->format('Y-m-d / H:i') }}</div>
                </div>
                <div class="orders-show-info-item">
                    <div class="orders-show-info-label">Vendor</div>
                    <div class="orders-show-info-value">{{ $order->vendor->username }}</div>
                </div>
                <div class="orders-show-info-item">
                    <div class="orders-show-info-label">Subtotal</div>
                    <div class="orders-show-info-value">${{ number_format($order->subtotal, 2) }}</div>
                </div>
                <div class="orders-show-info-item">
                    <div class="orders-show-info-label">Commission</div>
                    <div class="orders-show-info-value">${{ number_format($order->commission, 2) }}</div>
                </div>
                <div class="orders-show-info-item">
                    <div class="orders-show-info-label">Total</div>
                    <div class="orders-show-info-value total">${{ number_format($order->total, 2) }}</div>
                </div>
                <div class="orders-show-info-item">
                    <div class="orders-show-info-label">Total Items</div>
                    <div class="orders-show-info-value">{{ $totalItems ?? 0 }}</div>
                </div>
                <div class="orders-show-info-item">
                    <div class="orders-show-info-label">XMR/USD Rate</div>
                    <div class="orders-show-info-value">${{ number_format($order->xmr_usd_rate, 2) }}</div>
                </div>
                <div class="orders-show-info-item">
                    <div class="orders-show-info-label">Monero Amount</div>
                    <div class="orders-show-info-value total">ɱ{{ number_format($order->required_xmr_amount, 12) }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cancel Button Section -->
    @if($isBuyer && ($order->status === 'waiting_payment' || $order->status === 'payment_received'))
        <div class="orders-show-cancel-container">
            <form action="{{ route('orders.mark-cancelled', $order->unique_url) }}" method="POST">
                @csrf
                <button type="submit" class="orders-show-action-btn orders-show-cancel-btn orders-show-cancel-btn-standalone">Cancel Order</button>
            </form>
        </div>
    @endif

    <!-- Order Items -->
    <div class="orders-show-items-container">
        <div class="orders-show-items-card">
            <h2 class="orders-show-items-title">Items</h2>
            
            <div class="orders-show-items-list">
                @foreach($order->items as $item)
                    <div class="orders-show-item">
                        <div class="orders-show-item-details">
                            <h3 class="orders-show-item-name">{{ $item->product_name }}</h3>
                            <div class="orders-show-item-description">{{ Str::limit($item->product_description, 200) }}</div>
                            
                            <div class="orders-show-item-meta">
                                @if($item->bulk_option)
                                    <div class="orders-show-item-quantity">
                                        {{ $item->quantity }} sets of {{ $item->bulk_option['amount'] ?? 0 }} 
                                        (Total: {{ $item->quantity * ($item->bulk_option['amount'] ?? 1) }})
                                    </div>
                                @else
                                    <div class="orders-show-item-quantity">
                                        Quantity: {{ $item->quantity }}
                                    </div>
                                @endif
                                
                                @if($item->delivery_option)
                                    <div class="orders-show-item-delivery">
                                        Delivery: {{ $item->delivery_option['description'] ?? 'N/A' }}
                                        ({{ isset($item->delivery_option['price']) ? '$' . number_format($item->delivery_option['price'], 2) : 'N/A' }})
                                    </div>
                                @endif
                                
                                @if(($order->status === 'product_sent' || $order->status === 'completed') && $item->delivery_text)
                                    <div class="orders-show-item-delivery-text-container">
                                        <h4>Delivery Information:</h4>
                                        <div class="orders-show-item-delivery-text">
                                            {{ $item->delivery_text }}
                                        </div>
                                    </div>
                                @endif
                                
                                <div class="orders-show-item-type {{ $item->product ? ($item->product->type === 'digital' ? 'type-digital' : ($item->product->type === 'cargo' ? 'type-cargo' : 'type-deaddrop')) : 'type-deleted' }}">
                                    @if(!$item->product)
                                        Product Deleted
                                    @elseif($item->product->type === 'digital')
                                        Digital
                                    @elseif($item->product->type === 'cargo')
                                        Cargo
                                    @elseif($item->product->type === 'deaddrop')
                                        Dead Drop
                                    @else
                                        {{ ucfirst($item->product->type) }}
                                    @endif
                                </div>
                                <div class="orders-show-item-category">
                                    {{ $item->product && $item->product->category ? $item->product->category->name : 'Uncategorized' }}
                                </div>
                            </div>
                        </div>
                        
                        <div class="orders-show-item-price">
                            <div class="orders-show-item-price-label">Price:</div>
                            <div class="orders-show-item-price-value">${{ number_format($item->getTotalPrice(), 2) }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Dispute Form Section -->
    @if($isBuyer && $order->status === 'product_sent')
        <div class="orders-show-dispute-form-container">
            <div class="orders-show-dispute-form-card">
                <h2 class="orders-show-dispute-form-title">Do You Want to Open a Dispute?</h2>
                <form action="{{ route('disputes.store', $order->unique_url) }}" method="POST">
                    @csrf
                    <div class="orders-show-dispute-form-description">
                        Please explain why you are opening this dispute. Be specific and provide any relevant details.
                    </div>
                    <textarea name="reason" placeholder="Reason for dispute... (8-1600 characters)" required minlength="8" maxlength="1600" class="orders-show-dispute-form-textarea"></textarea>
                    <div class="orders-show-dispute-form-submit">
                        <button type="submit" class="orders-show-dispute-form-button">Submit Dispute</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Message Section -->
    @if($order->encrypted_message)
        <div class="orders-show-message-container">
            <div class="orders-show-message-card">
                <h2 class="orders-show-message-title">Encrypted Message</h2>
                <div class="orders-show-message-content">
                    <textarea readonly class="orders-show-message-textarea">{{ $order->encrypted_message }}</textarea>
                </div>
            </div>
        </div>
    @endif
    
    <!-- Dispute Section -->
    @if($dispute)
        <div class="orders-show-dispute-container">
            <div class="orders-show-dispute-card">
                <h2 class="orders-show-dispute-title">Dispute Information</h2>
                <div class="orders-show-dispute-status orders-show-dispute-status-{{ strtolower($dispute->status) }}">
                    Status: {{ $dispute->getFormattedStatus() }}
                </div>
            
                <div class="orders-show-dispute-info">
                    <h3 class="orders-show-dispute-section-title">Reason:</h3>
                    <div class="orders-show-dispute-text">{{ $dispute->reason }}</div>
                
                    @if($dispute->resolved_at)
                        <div class="orders-show-dispute-resolved-date">
                            Resolved on: {{ $dispute->resolved_at->format('Y-m-d / H:i') }}
                        </div>
                    @endif
                </div>
                
                <div class="orders-show-dispute-link-container">
                    <a href="{{ route('disputes.show', $dispute->id) }}" class="orders-show-dispute-link">
                        View Dispute Chat
                    </a>
                </div>
            </div>
        </div>
    @endif

    <!-- Reviews Section -->
    @if($isBuyer && $order->status === 'completed')
        <div class="orders-show-reviews-container">
            <div class="orders-show-reviews-card">
                <h2 class="orders-show-reviews-title">Product Reviews</h2>
                <div class="orders-show-reviews-list">
                    @forelse($order->items as $item)
                        <div class="orders-show-review-item">
                            <h3 class="orders-show-review-product-name">{{ $item->product_name }}</h3>
                            
                            @if(isset($item->existingReview) && $item->existingReview)
                                <div class="orders-show-review-existing">
                                    <p class="orders-show-review-date">You've already reviewed this product on {{ $item->existingReview->getFormattedDate() }}.</p>
                                    <div class="orders-show-review-sentiment orders-show-review-sentiment-{{ $item->existingReview->sentiment }}">
                                        {{ ucfirst($item->existingReview->sentiment) }}
                                    </div>
                                    <div class="orders-show-review-text">
                                        {{ $item->existingReview->review_text }}
                                    </div>
                                </div>
                            @else
                                <form action="{{ route('orders.submit-review', ['uniqueUrl' => $order->unique_url, 'orderItemId' => $item->id]) }}" method="POST" class="orders-show-review-form">
                                    @csrf
                                    <div class="orders-show-review-field">
                                        <label for="review_text_{{ $item->id }}" class="orders-show-review-label">Your Review</label>
                                        <textarea id="review_text_{{ $item->id }}" name="review_text" required minlength="8" maxlength="800" placeholder="Write your review here... (8-800 characters)" class="orders-show-review-textarea"></textarea>
                                    </div>
                                    
                                    <div class="orders-show-review-field">
                                        <label class="orders-show-review-label">Review Sentiment</label>
                                        <div class="orders-show-review-sentiment-options">
                                            <div class="orders-show-review-sentiment-option">
                                                <input type="radio" id="sentiment_positive_{{ $item->id }}" name="sentiment" value="positive" required class="orders-show-review-radio">
                                                <label for="sentiment_positive_{{ $item->id }}" class="orders-show-review-radio-label">Positive</label>
                                            </div>
                                            <div class="orders-show-review-sentiment-option">
                                                <input type="radio" id="sentiment_mixed_{{ $item->id }}" name="sentiment" value="mixed" class="orders-show-review-radio">
                                                <label for="sentiment_mixed_{{ $item->id }}" class="orders-show-review-radio-label">Mixed</label>
                                            </div>
                                            <div class="orders-show-review-sentiment-option">
                                                <input type="radio" id="sentiment_negative_{{ $item->id }}" name="sentiment" value="negative" class="orders-show-review-radio">
                                                <label for="sentiment_negative_{{ $item->id }}" class="orders-show-review-radio-label">Negative</label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="orders-show-review-submit-container">
                                        <button type="submit" class="orders-show-review-submit-btn">Submit Review</button>
                                    </div>
                                </form>
                            @endif
                        </div>
                    @empty
                        <div class="orders-show-review-item" style="text-align: center; color: var(--color-text-secondary);">
                            <p>No products to review.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    @endif
</div>

@endsection
