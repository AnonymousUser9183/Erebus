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
        --radius-base: 8px;
        --radius-lg: 12px;
        --spacing-md: 12px;
        --spacing-lg: 16px;
        --spacing-xl: 24px;
    }
    
    .vendors-container {
        max-width: 1000px;
        margin: 0 auto;
        padding: var(--spacing-xl);
    }
    
    .vendors-card {
        background: var(--color-card-bg);
        border-radius: var(--radius-lg);
        border: 1px solid var(--color-border);
        padding: var(--spacing-xl);
    }
    
    .vendors-title {
        font-size: 28px;
        font-weight: 600;
        margin: 0 0 var(--spacing-xl) 0;
        color: var(--color-text-primary);
    }
    
    .vendors-empty {
        text-align: center;
        padding: var(--spacing-xl);
        color: var(--color-text-secondary);
    }
    
    .vendors-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: var(--spacing-lg);
    }
    
    .vendors-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: var(--spacing-lg);
        border-radius: var(--radius-lg);
        border: 1px solid var(--color-border);
        text-decoration: none;
        transition: all 0.2s ease;
    }
    
    .vendors-item:hover {
        border-color: var(--color-accent);
        box-shadow: 0 2px 8px rgba(32, 128, 136, 0.15);
    }
    
    .vendors-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        overflow: hidden;
        margin-bottom: var(--spacing-md);
        border: 2px solid var(--color-border);
    }
    
    .vendors-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .vendors-username {
        color: var(--color-text-primary);
        font-weight: 600;
        text-align: center;
        font-size: 14px;
        word-break: break-word;
    }
</style>

<div class="vendors-container">
    <div class="vendors-card">
        <h1 class="vendors-title">Vendor List</h1>
        @if($vendors->isEmpty())
            <p class="vendors-empty">No vendors found.</p>
        @else
            <div class="vendors-grid">
                @foreach($vendors as $vendor)
                    <a href="{{ route('vendors.show', $vendor->username) }}" class="vendors-item">
                        <div class="vendors-avatar">
                            <img src="{{ $vendor->profile ? $vendor->profile->profile_picture_url : asset('images/default-profile-picture.png') }}" 
                                 alt="{{ $vendor->username }}'s Profile Picture">
                        </div>
                        <span class="vendors-username">{{ $vendor->username }}</span>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
