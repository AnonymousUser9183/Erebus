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

    .a-v-panel-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: var(--spacing-xl);
    }

    .a-v-panel-card {
        background-color: var(--color-bg-secondary);
        border: 1px solid var(--color-border);
        border-radius: var(--radius);
        padding: var(--spacing-2xl);
    }

    .a-v-panel-title {
        font-size: 32px;
        font-weight: 600;
        color: var(--color-text-primary);
        text-align: center;
        margin: 0 0 var(--spacing-md) 0;
        padding-bottom: var(--spacing-lg);
        border-bottom: 2px solid var(--color-accent-light);
    }

    .a-v-panel-welcome {
        font-size: 16px;
        color: var(--color-text-secondary);
        text-align: center;
        margin: 0 0 var(--spacing-2xl) 0;
        line-height: 1.6;
    }

    .a-v-panel-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: var(--spacing-xl);
        margin-top: var(--spacing-xl);
    }

    .a-v-panel-item {
        background-color: var(--color-bg-primary);
        border: 1px solid var(--color-border);
        border-radius: var(--radius);
        padding: var(--spacing-lg);
        display: flex;
        flex-direction: column;
        gap: var(--spacing-md);
        transition: all 0.3s ease;
    }

    .a-v-panel-item:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
        border-color: var(--color-accent);
    }

    .a-v-panel-item-title {
        font-size: 16px;
        font-weight: 600;
        color: var(--color-accent);
        margin: 0;
    }

    .a-v-panel-item-description {
        font-size: 14px;
        color: var(--color-text-primary);
        margin: 0;
        line-height: 1.6;
        flex-grow: 1;
    }

    .a-v-panel-item-link {
        display: inline-block;
        background-color: var(--color-accent);
        color: #ffffff;
        padding: var(--spacing-md) var(--spacing-lg);
        border-radius: var(--radius);
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
        text-align: center;
        transition: background-color 0.3s ease;
    }

    .a-v-panel-item-link:hover {
        background-color: var(--color-accent-light);
    }

    @media (max-width: 768px) {
        .a-v-panel-container {
            padding: var(--spacing-md);
        }

        .a-v-panel-card {
            padding: var(--spacing-lg);
        }

        .a-v-panel-title {
            font-size: 24px;
        }

        .a-v-panel-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="a-v-panel-container">
    <div class="a-v-panel-card">
        <h1 class="a-v-panel-title">Erebus Marketplace Script Admin Panel</h1>
        <p class="a-v-panel-welcome">Welcome to the Erebus Marketplace Script Admin Panel. Here you can manage various aspects of the marketplace and ensure its security and efficiency.</p>
        
        <div class="a-v-panel-grid">
            <div class="a-v-panel-item">
                <h3 class="a-v-panel-item-title">User Management</h3>
                <p class="a-v-panel-item-description">View and manage user accounts, roles, and permissions across your marketplace.</p>
                <a href="{{ route('admin.users') }}" class="a-v-panel-item-link">Manage Users</a>
            </div>
            
            <div class="a-v-panel-item">
                <h3 class="a-v-panel-item-title">Manage Products</h3>
                <p class="a-v-panel-item-description">Edit or remove products listed on your marketplace.</p>
                <a href="{{ route('admin.all-products') }}" class="a-v-panel-item-link">Product Management</a>
            </div>
            
            <div class="a-v-panel-item">
                <h3 class="a-v-panel-item-title">Support Requests</h3>
                <p class="a-v-panel-item-description">View and respond to user support requests and inquiries.</p>
                <a href="{{ route('admin.support.requests') }}" class="a-v-panel-item-link">Manage Requests</a>
            </div>
            
            <div class="a-v-panel-item">
                <h3 class="a-v-panel-item-title">Bulk Message</h3>
                <p class="a-v-panel-item-description">Send bulk messages to all users or specific user roles.</p>
                <a href="{{ route('admin.bulk-message.list') }}" class="a-v-panel-item-link">Send Message</a>
            </div>

            <div class="a-v-panel-item">
                <h3 class="a-v-panel-item-title">Disputes</h3>
                <p class="a-v-panel-item-description">View and respond to marketplace disputes from both parties.</p>
                <a href="{{ route('admin.disputes.index') }}" class="a-v-panel-item-link">View Disputes</a>
            </div>

            <div class="a-v-panel-item">
                <h3 class="a-v-panel-item-title">Categories</h3>
                <p class="a-v-panel-item-description">Add, remove, or modify marketplace product categories.</p>
                <a href="{{ route('admin.categories') }}" class="a-v-panel-item-link">View Categories</a>
            </div>
            
            <div class="a-v-panel-item">
                <h3 class="a-v-panel-item-title">System Logs</h3>
                <p class="a-v-panel-item-description">Access and analyze system logs for security and performance monitoring.</p>
                <a href="{{ route('admin.logs') }}" class="a-v-panel-item-link">View Logs</a>
            </div>
            
            <div class="a-v-panel-item">
                <h3 class="a-v-panel-item-title">Canary</h3>
                <p class="a-v-panel-item-description">Update the marketplace canary with a signed message for security assurance.</p>
                <a href="{{ route('admin.canary') }}" class="a-v-panel-item-link">Update Canary</a>
            </div>

            <div class="a-v-panel-item">
                <h3 class="a-v-panel-item-title">Vendor Applications</h3>
                <p class="a-v-panel-item-description">Review and approve/deny vendor applications requiring verification on your marketplace.</p>
                <a href="{{ route('admin.vendor-applications.index') }}" class="a-v-panel-item-link">Manage Applications</a>
            </div>

            <div class="a-v-panel-item">
                <h3 class="a-v-panel-item-title">Private Mirror Requests</h3>
                <p class="a-v-panel-item-description">Review and assign private mirrors to users requesting access on your marketplace.</p>
                <a href="{{ route('admin.private-mirror-requests.list') }}" class="a-v-panel-item-link">Manage Requests</a>
            </div>

            <div class="a-v-panel-item">
                <h3 class="a-v-panel-item-title">Web Pop-Up</h3>
                <p class="a-v-panel-item-description">Create and manage website-wide pop-up notifications for marketplace users.</p>
                <a href="{{ route('admin.popup.index') }}" class="a-v-panel-item-link">Configure Popups</a>
            </div>

            <div class="a-v-panel-item">
                <h3 class="a-v-panel-item-title">Statistics</h3>
                <p class="a-v-panel-item-description">Access marketplace analytics and performance metrics dashboards.</p>
                <a href="{{ route('admin.statistics') }}" class="a-v-panel-item-link">View Stats</a>
            </div>
        </div>
    </div>
</div>
@endsection
