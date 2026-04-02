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
        --color-warning: #ffc107;
    }

    .rate-limit-container {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
        padding: var(--spacing-xl);
        background: var(--color-input-bg);
    }

    .rate-limit-card {
        background: var(--color-card-bg);
        border: 1px solid var(--color-border);
        border-radius: var(--radius-lg);
        padding: var(--spacing-xl);
        max-width: 500px;
        text-align: center;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }

    .rate-limit-icon {
        font-size: 48px;
        margin-bottom: var(--spacing-lg);
    }

    .rate-limit-title {
        font-size: 24px;
        font-weight: 600;
        color: var(--color-text-primary);
        margin: 0 0 var(--spacing-md) 0;
    }

    .rate-limit-message {
        font-size: 14px;
        color: var(--color-text-secondary);
        line-height: 1.6;
        margin: 0 0 var(--spacing-lg) 0;
    }

    .rate-limit-explanation {
        background: rgba(255, 193, 7, 0.05);
        border: 1px solid rgba(255, 193, 7, 0.2);
        padding: var(--spacing-lg);
        border-radius: var(--radius-base);
        margin-bottom: var(--spacing-lg);
    }

    .rate-limit-explanation-title {
        font-size: 13px;
        font-weight: 600;
        color: var(--color-text-primary);
        margin: 0 0 var(--spacing-md) 0;
    }

    .rate-limit-explanation-text {
        font-size: 12px;
        color: var(--color-text-secondary);
        margin: 0;
        line-height: 1.6;
    }

    .cooldown-timer {
        font-size: 28px;
        font-weight: 600;
        color: var(--color-warning);
        margin: var(--spacing-lg) 0;
        font-family: monospace;
    }

    .cooldown-label {
        font-size: 12px;
        color: var(--color-text-secondary);
        text-transform: uppercase;
        font-weight: 600;
        margin-bottom: var(--spacing-md);
    }

    .rate-limit-return-btn {
        display: inline-block;
        background: var(--color-accent);
        color: white;
        padding: 10px 24px;
        border: none;
        border-radius: var(--radius-base);
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        transition: background 0.2s ease;
        margin-top: var(--spacing-lg);
    }

    .rate-limit-return-btn:hover {
        background: var(--color-accent-light);
    }
</style>

<div class="rate-limit-container">
    <div class="rate-limit-card">
        <div class="rate-limit-icon">⏱️</div>
        <h1 class="rate-limit-title">Rate Limited</h1>
        <p class="rate-limit-message">
            You're creating advertisements too frequently. Please wait before creating another advertisement.
        </p>

        <div class="rate-limit-explanation">
            <h2 class="rate-limit-explanation-title">Why this is happening</h2>
            <p class="rate-limit-explanation-text">
                To ensure fair access for all vendors and maintain marketplace health, we limit advertisement creation frequency. This helps prevent abuse and ensures everyone has equal opportunities.
            </p>
        </div>

        <div class="cooldown-label">Time until next advertisement:</div>
        <div class="cooldown-timer" id="cooldown-timer">
            @if($cooldownEnds)
                {{ $cooldownEnds->diffForHumans() }}
            @else
                Calculating...
            @endif
        </div>

        <a href="{{ route('vendor.my-products') }}" class="rate-limit-return-btn">
            Return to My Products
        </a>
    </div>
</div>

<script>
    @if($cooldownEnds)
        function updateCooldownTimer() {
            const now = new Date();
            const end = new Date('{{ $cooldownEnds->toDateTimeString() }}');
            const diff = Math.max(0, Math.floor((end - now) / 1000));

            if (diff <= 0) {
                document.getElementById('cooldown-timer').textContent = 'Ready!';
                clearInterval(timerInterval);
                return;
            }

            const hours = Math.floor(diff / 3600);
            const minutes = Math.floor((diff % 3600) / 60);
            const seconds = diff % 60;

            let timerText = '';
            if (hours > 0) timerText += hours + 'h ';
            if (minutes > 0 || hours > 0) timerText += minutes + 'm ';
            timerText += seconds + 's';

            document.getElementById('cooldown-timer').textContent = timerText;
        }

        updateCooldownTimer();
        const timerInterval = setInterval(updateCooldownTimer, 1000);
    @endif
</script>

@endsection