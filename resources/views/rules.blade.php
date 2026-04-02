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

    .rules-container {
        max-width: 900px;
        margin: 0 auto;
        padding: var(--spacing-xl);
    }

    .rules-title {
        font-size: 32px;
        font-weight: 600;
        color: var(--color-text-primary);
        text-align: center;
        margin: 0 0 var(--spacing-2xl) 0;
        padding-bottom: var(--spacing-lg);
        border-bottom: 2px solid var(--color-accent-light);
    }

    .rules-section {
        background-color: var(--color-bg-secondary);
        border: 1px solid var(--color-border);
        border-radius: var(--radius);
        padding: var(--spacing-lg);
        margin-bottom: var(--spacing-xl);
        line-height: 1.8;
	margin-right: -160px;
  	margin-left: -160px;
    }

    .rules-section > p {
        font-size: 15px;
        color: var(--color-text-primary);
        margin: 0 0 var(--spacing-md) 0;
    }

    .rules-section > h2 {
        font-size: 20px;
        font-weight: 600;
        color: var(--color-accent);
        margin: var(--spacing-lg) 0 var(--spacing-md) 0;
        padding-bottom: var(--spacing-md);
        border-bottom: 1px solid var(--color-border);
    }

    .rules-item {
        margin-bottom: var(--spacing-lg);
        padding-bottom: var(--spacing-lg);
        border-bottom: 1px solid var(--color-border);
    }

    .rules-item:last-child {
        margin-bottom: 0;
        padding-bottom: 0;
        border-bottom: none;
    }

    .rules-item h3 {
        font-size: 16px;
        font-weight: 600;
        color: var(--color-accent);
        margin: 0 0 var(--spacing-sm) 0;
    }

    .rules-item p {
        font-size: 14px;
        color: var(--color-text-primary);
        margin: 0;
        line-height: 1.6;
    }

    .rules-note {
        background-color: #f9f9f7;
        border-left: 4px solid var(--color-accent);
        padding: var(--spacing-md);
        border-radius: var(--radius);
        margin-top: var(--spacing-lg);
        font-size: 14px;
        color: var(--color-text-primary);
    }

    .rules-section > ol {
        font-size: 14px;
        color: var(--color-text-primary);
        padding-left: var(--spacing-xl);
        margin: var(--spacing-md) 0;
        line-height: 1.8;
    }

    .rules-section > ol li {
        margin-bottom: var(--spacing-sm);
    }

    .pagination-container {
        display: flex;
        justify-content: center;
        margin-top: var(--spacing-2xl);
        gap: var(--spacing-sm);
    }

    .pagination-container a,
    .pagination-container span {
        padding: var(--spacing-xs) var(--spacing-sm);
        border: 1px solid var(--color-border);
        border-radius: var(--radius);
        font-size: 13px;
        text-decoration: none;
        color: var(--color-text-primary);
        display: inline-block;
        transition: all 0.3s ease;
    }

    .pagination-container a:hover {
        background-color: var(--color-accent);
        color: #ffffff;
        border-color: var(--color-accent);
    }

    .pagination-container .active {
        background-color: var(--color-accent);
        color: #ffffff;
        border-color: var(--color-accent);
    }

    .pagination-container .disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    @media (max-width: 768px) {
        .rules-container {
            padding: var(--spacing-md);
        }

        .rules-title {
            font-size: 24px;
        }

        .rules-section {
            padding: var(--spacing-md);
        }
    }
</style>

<div class="rules-container">
    <h1 class="rules-title">Erebus Marketplace Terms & Conditions aka Rules & Guidelines Page</h1>
    
    @if(request()->get('page', 1) == 1)
        <div class="rules-section">
            <p>Welcome to the Erebus Marketplace Script, an upgraded and rewritten version of Kabus Marketplace Script running on the latest variant of Laravel 12 upgraded from Laravel 11, with a rewritten design and the addition of new features. This page is for the "rules & guidelines" of your marketplace. Here it represents the terms and conditions of using this script. By editing this text you are agreeing to not use the script for any illegal purposes. This script must include this requirement to comply with international law.</p>

            <h2>Important Notice</h2>
            <p>The Erebus Development Team reserves the right to modify these rules as needed to maintain security and improve user experience. Users are responsible for staying updated with current rules. Violations may result in the removal of this script for github.com. Your security and privacy are our top priorities.</p>
        </div>
    @elseif(request()->get('page') == 2)
        <div class="rules-section">
            <div class="rules-item">
                <h3>Term 1.0 Free Use</h3>
                <p>This script is provided for free use. The Erebus Development Team reserves no rights for this script, and it is provided for anyone to use, edit, or rewrite. The Erebus Development Team requests changes or improvements for this script to be noted in issues on github.com or by opening an issue on the Tor Git service hosting this script</p>
            </div>

            <div class="rules-item">
                <h3>Term 2.0: Limitations of Use</h3>
                <p>Users may not use this script for any form of illegal content of any shape or form. This term is required of all users to comply with international law</p>
            </div>

            <div class="rules-item">
                <h3>Term 3.0: Suggested Use</h3>
                <p>The Erebus Development Team suggests using this script for building a business similar to eBay on the clearnet where Vendors list products such as electronics aquired legally, jewlery aquired legally and other similar products aquired legally.</p>
            </div>
        </div>
    @elseif(request()->get('page') == 3)
        <div class="rules-section">
            <div class="rules-item">
                <h3>Term 4.0: Script Modification & Term Agreement Policy</h3>
                <p>By deleting this text and changing the words here to suit your marketplace you are agreeing that you are taking full and independent responsibility of your own actions, The Erebus Development Team does not approve of, suggest or condone the usage of this script for any form of illegal purposes, you must use this script legally. By deleting this text you accept and agree that all actions both legally or illegally is made of your own judgement and you understand that modifying this script for illegal purposes can make you legally liable to serve jail time and have fines imposed on you regardless of country of naturalization..</p>
            </div>

            <div class="rules-item">
                <h3>Rule 5: Listing Standards</h3>
                <p>All listings must be clear, accurate, and compliant with international regulations. Misrepresenting products or services is prohibited. Allowing users of your marketplace to list illegal products is prohibited. Prices must be clearly displayed in XMR, and all terms of sale must be explicitly stated.</p>
            </div>

            <div class="rules-item">
                <h3>Rule 6: Script Security</h3>
                <p>Report any suspicious or illegal activity immediately to The Erebus Development Team via the proper channels.</p>
            </div>
        </div>
    @elseif(request()->get('page') == 4)
        <div class="rules-section">
            <div class="rules-item">
                <h3>Rule 7: Allowed Intranets</h3>
                <p>This script is only approved by The Erebus Development Team for use on the Worldwide Web. The use of this script to host a marketplace on Tor also known as the "darknet" or THe Onion Router is strictly forbidden and by deleting this text you are agreeing tho these terms..</p>
            </div>

            <div class="rules-item">
                <h3>Rule 8: Market Conduct & Respectful and Legitimate Use Policy</h3>
                <p>Maintain professional conduct as a Market Administrator. illegal listings of any form should never be allowed. Respect international and local law at all times. By deleting this text you are agreeing to these terms.</p>
            </div>

            <div class="rules-item">
                <h3>Rule 9: Platform Security</h3>
                <p>Users who discover security issues or illegal usage should immediately report them to The Erebus Development Team through secure channels.</p>
            </div>
        </div>
    @elseif(request()->get('page') == 5)
        <div class="rules-section">
            <h2>Simplified Terms:</h2>

            <ol>
                <li>By modifying this script in any way is your agreeance to all terms specified.</li>
                <li>Illegal usage of this script is not allowed or tolerated.</li>
                <li>If you see illegal usage, report it to The Erebus Dev Team.</li>
                <li>We suggest starting an eBay like market for legally aquired electronics & jewlery.</li>
                <li>All terms listed above must be followed strictly and by deleting this text you agree to use this script responsibly.</li>
            </ol>

            <div class="rules-note">
                <strong>Note:</strong> Following the law is important and illegal activity is not tolerated.
            </div>
        </div>
    @endif

    <div class="pagination-container">
        {{ $paginatedRules->links('components.pagination') }}
    </div>
</div>
@endsection
