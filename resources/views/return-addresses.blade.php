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

    .return-addresses-container {
        max-width: 900px;
        margin: 0 auto;
        padding: var(--spacing-xl);
    }

    .return-addresses-disclaimer {
        background-color: #fef3c7;
        border: 1px solid #fbbf24;
        border-radius: var(--radius);
        padding: var(--spacing-lg);
        margin-bottom: var(--spacing-xl);
        font-size: 14px;
        color: var(--color-text-primary);
        line-height: 1.6;
    }

    .return-addresses-card {
        background-color: var(--color-bg-secondary);
        border: 1px solid var(--color-border);
        border-radius: var(--radius);
        padding: var(--spacing-lg);
        margin-bottom: var(--spacing-xl);
    }

    .return-addresses-form-group {
        margin-bottom: var(--spacing-md);
    }

    .return-addresses-input {
        width: 100%;
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

    .return-addresses-input:focus {
        outline: none;
        border-color: var(--color-accent);
        box-shadow: 0 0 0 2px rgba(32, 128, 136, 0.1);
    }

    .return-addresses-submit-container {
        margin-top: var(--spacing-lg);
    }

    .return-addresses-submit-btn {
        background-color: var(--color-accent);
        color: #ffffff;
        border: none;
        padding: var(--spacing-md) var(--spacing-lg);
        border-radius: var(--radius);
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .return-addresses-submit-btn:hover {
        background-color: var(--color-accent-light);
    }

    .return-addresses-table-container {
        overflow-x: auto;
    }

    .return-addresses-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }

    .return-addresses-table thead {
        background-color: var(--color-bg-primary);
        border-bottom: 2px solid var(--color-border);
    }

    .return-addresses-table th {
        padding: var(--spacing-md);
        text-align: left;
        font-weight: 600;
        color: var(--color-text-primary);
    }

    .return-addresses-table td {
        padding: var(--spacing-md);
        border-bottom: 1px solid var(--color-border);
        color: var(--color-text-primary);
        word-break: break-all;
    }

    .return-addresses-table tbody tr:hover {
        background-color: var(--color-bg-primary);
    }

    .return-addresses-delete-btn {
        background-color: #ffffff;
        color: var(--color-text-primary);
        border: 1px solid var(--color-border);
        padding: var(--spacing-xs) var(--spacing-sm);
        border-radius: var(--radius);
        font-size: 12px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .return-addresses-delete-btn:hover {
        background-color: #fecaca;
        border-color: #ef4444;
        color: #dc2626;
    }

    .return-addresses-empty {
        background-color: var(--color-bg-secondary);
        border: 1px solid var(--color-border);
        border-radius: var(--radius);
        padding: var(--spacing-lg);
        text-align: center;
        color: var(--color-text-secondary);
        font-size: 14px;
    }

    @media (max-width: 768px) {
        .return-addresses-container {
            padding: var(--spacing-md);
        }

        .return-addresses-table {
            font-size: 13px;
        }

        .return-addresses-table th,
        .return-addresses-table td {
            padding: var(--spacing-sm);
        }
    }
</style>

<div class="return-addresses-container">
    <div class="return-addresses-disclaimer">
       To shop at Erebus Market, you need to add at least one Monero address. Refunds will be made to this address. For your security, use a subaddress instead of your main address and be careful not to share this address elsewhere. Main Monero addresses usually start with "4", while subaddresses start with "8".
    </div>
    
    <div class="return-addresses-card">
        <form action="{{ route('return-addresses.store') }}" method="POST">
            @csrf
            <div class="return-addresses-form-group">
                <input type="text" 
                       class="return-addresses-input" 
                       id="monero_address" 
                       name="monero_address" 
                       placeholder="Enter your Monero refund address"
                       required
                       minlength="40"
                       maxlength="160">
            </div>
            <div class="return-addresses-submit-container">
                <button type="submit" class="return-addresses-submit-btn">Add Monero Address</button>
            </div>
        </form>
    </div>

    @if($returnAddresses->count() > 0)
        <div class="return-addresses-card">
            <div class="return-addresses-table-container">
                <table class="return-addresses-table">
                    <thead>
                        <tr>
                            <th>Address</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($returnAddresses as $address)
                            <tr>
                                <td>{{ $address->monero_address }}</td>
                                <td>
                                    <form action="{{ route('return-addresses.destroy', $address) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="return-addresses-delete-btn">
                                        Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="return-addresses-empty">
            You haven't added any refund addresses yet.
        </div>
    @endif
</div>
@endsection
