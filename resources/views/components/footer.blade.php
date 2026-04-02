<footer class="footer">
    <style>
        :root {
            --color-primary: #1a7a99;
            --color-primary-light: #2a9db8;
            --color-text-primary: #333333;
            --color-text-secondary: #666666;
            --color-bg-secondary: #ffffff;
            --color-border: #e0e0e0;
            --spacing-md: 12px;
            --spacing-lg: 16px;
            --radius-md: 6px;
        }

        .footer {
            background-color: var(--color-bg-secondary);
            border-top: 1px solid var(--color-border);
            padding: var(--spacing-lg) var(--spacing-lg);
            margin-top: auto;
        }

        .footer-container {
            max-width: 1400px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: var(--spacing-lg);
            align-items: start;
        }

        .footer-section {
            display: flex;
            flex-direction: column;
            gap: var(--spacing-md);
        }

        .footer-section-title {
            font-weight: 600;
            font-size: 13px;
            color: var(--color-text-primary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .footer-link {
            color: var(--color-primary);
            text-decoration: none;
            font-weight: 500;
            font-size: 13px;
            transition: all 0.2s ease;
            padding: 4px 0;
            display: inline-block;
	    line-height: 5px;
        }

        .footer-link:hover {
            color: var(--color-primary-light);
            text-decoration: underline;
        }

        .footer-info {
            display: flex;
            flex-direction: column;
            gap: var(--spacing-md);
            font-size: 13px;
            color: var(--color-text-secondary);
        }

        .footer-xmr-price {
            font-weight: 600;
            color: var(--color-primary);
            font-size: 14px;
        }

        .footer-sponsor {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            padding: var(--spacing-md) 0;
        }

        .footer-sponsor a {
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: opacity 0.2s ease;
        }

        .footer-sponsor img {
            height: 48px;
            width: auto;
            object-fit: contain;
	    margin-right: 140px;
	}

        .footer-sponsor a:hover img {
            opacity: 0.8;
        }

        .footer-warning {
            display: none;
            align-items: center;
            gap: var(--spacing-md);
            padding: var(--spacing-md);
            background-color: #fffbeb;
            color: var(--color-text-primary);
            font-size: 12px;
            border-radius: var(--radius-md);
            border-left: 4px solid #f59e0b;
        }

        .footer-divider {
            height: 1px;
            background-color: var(--color-border);
            width: 100%;
        }

        .footer-bottom {
            border-top: 1px solid var(--color-border);
            padding-top: var(--spacing-lg);
            margin-top: var(--spacing-lg);
            text-align: center;
            font-size: 12px;
            color: var(--color-text-secondary);
        }

        @media (max-width: 768px) {
            .footer-container {
                grid-template-columns: 1fr;
                gap: var(--spacing-md);
            }

            .footer-section {
                gap: var(--spacing-sm);
            }

            .footer-section-title {
                font-size: 12px;
            }

            .footer-link {
                font-size: 12px;
            }

            .footer-info {
                font-size: 12px;
            }

            .footer-sponsor img {
                height: 42px;
            }
        }

        @media (max-width: 480px) {
            .footer {
                padding: var(--spacing-md);
            }

            .footer-container {
                gap: var(--spacing-md);
            }

            .footer-section-title {
                font-size: 11px;
            }

            .footer-link {
                font-size: 11px;
            }

            .footer-info {
                font-size: 11px;
            }

            .footer-sponsor img {
                height: 38px;
            }
        }
    </style>

    <div class="footer-container">
        <!-- Resources Section -->
        <div class="footer-section">
            <div class="footer-section-title">Onion Mirror Guidelines</div>
            <a href="{{ url('/pgp.txt') }}" class="footer-link">Market PGP Key</a>
            <a href="{{ url('/canary.txt') }}" class="footer-link">Canary</a>
            <a href="{{ url('/mirrors.txt') }}" class="footer-link">Mirrors</a>
            <a href="{{ url('/related.txt') }}" class="footer-link">Related Services</a>
            <a href="{{ url('/omg.txt') }}" class="footer-link">Onion Mirror Guidelines</a>
        </div>

        <!-- Security Section -->
        <div class="footer-section">
            <div class="footer-section-title">Security</div>
            <a href="/verify-mirror" class="footer-link">Verify URL</a>
            <a href="{{ route('settings') }}" class="footer-link">Account Settings</a>
        </div>

        <!-- Information Section -->
        <div class="footer-section">
            <div class="footer-section-title">Market Info</div>
            <a href="{{ route('rules') }}" class="footer-link">Market Rules</a>
            <div class="footer-info">
                <div>
                    <strong>XMR/USD Rate:</strong><br>
                    <span class="footer-xmr-price">
                        @php
                            $xmrPrice = app(App\Http\Controllers\XmrPriceController::class)->getXmrPrice();
                        @endphp
                        @if($xmrPrice !== 'UNAVAILABLE')
                            ${{ $xmrPrice }}
                        @else
                            UNAVAILABLE
                        @endif
                    </span>
                </div>
            </div>
        </div>

        <!-- Support Section -->
        <div class="footer-section">
            <div class="footer-section-title"></div>
            <div class="footer-sponsor">
                <a href="http://sponsoronionlink.onion/" target="_blank" rel="noopener noreferrer" title="Sponsor Title">
                    <img src="{{ asset('images/sponsor.png') }}" alt="Sponsor Image">
                </a>
            </div>
            <p style="font-size: 11px; color: var(--color-text-secondary);">Sponsor Text</p>
        </div>
    </div>

    @if(config('marketplace.show_javascript_warning'))
        <div class="footer-warning js-warning-display">
            <span>⚠️ Please Disable JavaScript for Better Security</span>
        </div>
    @endif

    <div class="footer-bottom">
        <p style="margin: 0;">© 2026 Erebus Development Team. All rights reserved.</p>
    </div>
</footer>

@if(config('marketplace.show_javascript_warning'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.js-warning-display').forEach(function(element) {
                element.style.display = 'flex';
            });
        });
    </script>
@endif
