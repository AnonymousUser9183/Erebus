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

    .users-details-container {
        max-width: 1000px;
        margin: 0 auto;
        padding: var(--spacing-xl);
    }

    .users-details-card {
        background-color: var(--color-bg-secondary);
        border: 1px solid var(--color-border);
        border-radius: var(--radius);
        padding: var(--spacing-lg);
    }

    .users-details-header {
        padding-bottom: var(--spacing-lg);
        border-bottom: 2px solid var(--color-accent-light);
        margin-bottom: var(--spacing-lg);
    }

    .users-details-username {
        font-size: 28px;
        font-weight: 600;
        color: var(--color-text-primary);
        margin: 0 0 var(--spacing-sm) 0;
    }

    .users-details-id {
        font-size: 14px;
        color: var(--color-text-secondary);
        margin: 0;
    }

    .users-details-body {
        display: flex;
        flex-direction: column;
        gap: var(--spacing-lg);
    }

    .users-details-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
        gap: var(--spacing-lg);
    }

    .users-details-column {
        display: flex;
        flex-direction: column;
        gap: var(--spacing-lg);
    }

    .users-details-info {
        background-color: var(--color-bg-primary);
        border: 1px solid var(--color-border);
        border-radius: var(--radius);
        padding: var(--spacing-lg);
    }

    .users-details-info p {
        font-size: 14px;
        color: var(--color-text-primary);
        margin: var(--spacing-md) 0;
        line-height: 1.6;
    }

    .users-details-info strong {
        color: var(--color-accent);
        font-weight: 600;
    }

    .users-details-form {
        background-color: var(--color-bg-primary);
        border: 1px solid var(--color-border);
        border-radius: var(--radius);
        padding: var(--spacing-lg);
    }

    .users-details-subtitle {
        font-size: 16px;
        font-weight: 600;
        color: var(--color-accent);
        margin: 0 0 var(--spacing-md) 0;
        padding-bottom: var(--spacing-md);
        border-bottom: 2px solid var(--color-accent-light);
    }

    .users-details-roles {
        display: flex;
        flex-direction: column;
        gap: var(--spacing-md);
        margin-bottom: var(--spacing-lg);
    }

    .users-details-role {
        display: flex;
        align-items: center;
        gap: var(--spacing-sm);
    }

    .users-details-role input[type="checkbox"] {
        cursor: pointer;
        width: 18px;
        height: 18px;
    }

    .users-details-role label {
        cursor: pointer;
        font-size: 14px;
        color: var(--color-text-primary);
    }

    .users-details-btn {
        padding: var(--spacing-md) var(--spacing-lg);
        border: none;
        border-radius: var(--radius);
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .users-details-btn-primary {
        background-color: var(--color-accent);
        color: #ffffff;
    }

    .users-details-btn-primary:hover {
        background-color: var(--color-accent-light);
    }

    .users-details-btn-success {
        background-color: #10b981;
        color: #ffffff;
    }

    .users-details-btn-success:hover {
        background-color: #059669;
    }

    .users-details-btn-danger {
        background-color: #ef4444;
        color: #ffffff;
    }

    .users-details-btn-danger:hover {
        background-color: #dc2626;
    }

    .users-details-btn-info {
        background-color: #3b82f6;
        color: #ffffff;
    }

    .users-details-btn-info:hover {
        background-color: #2563eb;
    }

    .users-details-btn-secondary {
        background-color: var(--color-text-secondary);
        color: #ffffff;
    }

    .users-details-btn-secondary:hover {
        background-color: #4b5563;
    }

    .users-details-input-group {
        margin-bottom: var(--spacing-md);
        display: flex;
        flex-direction: column;
        gap: var(--spacing-sm);
    }

    .users-details-input-group label {
        font-size: 13px;
        font-weight: 600;
        color: var(--color-text-primary);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .users-details-input-group input {
        padding: var(--spacing-md);
        border: 1px solid var(--color-border);
        border-radius: var(--radius);
        font-size: 14px;
        color: var(--color-text-primary);
        background-color: var(--color-bg-secondary);
        box-sizing: border-box;
    }

    .users-details-input-group input:focus {
        outline: none;
        border-color: var(--color-accent);
        box-shadow: 0 0 0 2px rgba(32, 128, 136, 0.1);
    }

    .users-details-ban-info {
        background-color: #fee2e2;
        border: 1px solid #fecaca;
        border-radius: var(--radius);
        padding: var(--spacing-lg);
    }

    .users-details-ban-info p {
        font-size: 14px;
        color: #991b1b;
        margin: var(--spacing-md) 0;
    }

    .users-details-ban-info strong {
        font-weight: 600;
    }

    .users-details-actions {
        display: flex;
        gap: var(--spacing-md);
        margin-top: var(--spacing-lg);
    }

    @media (max-width: 768px) {
        .users-details-container {
            padding: var(--spacing-md);
        }

        .users-details-username {
            font-size: 22px;
        }

        .users-details-grid {
            grid-template-columns: 1fr;
        }

        .users-details-actions {
            flex-direction: column;
        }

        .users-details-btn {
            width: 100%;
        }
    }
</style>

<div class="users-details-container">
    <div class="users-details-card">
        <div class="users-details-header">
            <h2 class="users-details-username">{{ $user->username }}</h2>
            <p class="users-details-id">ID: {{ $user->id }}</p>
        </div>

        <div class="users-details-body">
            <div class="users-details-grid">
                <div class="users-details-column">
                    <div class="users-details-info">
                        <p><strong>Reference ID:</strong> {{ $user->reference_id }}</p>
                        <p><strong>Used Reference Code:</strong> {{ $user->referred_by ? 'Yes' : 'No' }}</p>
                        @if($user->referred_by)
                            <p><strong>Referred By:</strong> {{ $user->referrer->username }}</p>
                        @endif
                        <p><strong>Last Login:</strong> {{ $user->last_login ? $user->last_login->format('Y-m-d H:i:s') : 'N/A' }}</p>
                        <p><strong>Account Creation Date:</strong> {{ $user->created_at->format('Y-m-d H:i:s') }}</p>
                    </div>

                    <form action="{{ route('admin.users.update-roles', $user) }}" method="POST" class="users-details-form">
                        @csrf
                        @method('PUT')
                        <h3 class="users-details-subtitle">User Roles</h3>
                        <div class="users-details-roles">
                            <div class="users-details-role">
                                <input type="checkbox" name="roles[]" value="admin" id="adminRole" {{ $user->hasRole('admin') ? 'checked' : '' }}>
                                <label for="adminRole">Administrator</label>
                            </div>
                            <div class="users-details-role">
                                <input type="checkbox" name="roles[]" value="vendor" id="vendorRole" {{ $user->hasRole('vendor') ? 'checked' : '' }}>
                                <label for="vendorRole">Vendor</label>
                            </div>
                        </div>
                        <button type="submit" class="users-details-btn users-details-btn-primary">Save Changes</button>
                    </form>
                </div>

                <div class="users-details-column">
                    @if ($user->isBanned())
                        <div class="users-details-ban-info">
                            <h3 class="users-details-subtitle" style="color: #991b1b; margin-top: 0;">Ban Information</h3>
                            <p><strong>Banned Until:</strong> {{ $user->bannedUser->banned_until->format('Y-m-d H:i:s') }}</p>
                            <p><strong>Reason:</strong> {{ $user->bannedUser->reason }}</p>
                            <form action="{{ route('admin.users.unban', $user) }}" method="POST">
                                @csrf
                                <button type="submit" class="users-details-btn users-details-btn-success">Remove Ban</button>
                            </form>
                        </div>
                    @else
                        <form action="{{ route('admin.users.ban', $user) }}" method="POST" class="users-details-form">
                            @csrf
                            <h3 class="users-details-subtitle">Ban User</h3>
                            <div class="users-details-input-group">
                                <label for="reason">Ban Reason</label>
                                <input type="text" id="reason" name="reason" required>
                            </div>
                            <div class="users-details-input-group">
                                <label for="duration">Ban Duration (in Days)</label>
                                <input type="number" id="duration" name="duration" min="1" required>
                            </div>
                            <button type="submit" class="users-details-btn users-details-btn-danger">Ban User</button>
                        </form>
                    @endif
                </div>
            </div>

            <div class="users-details-actions">
                <a href="{{ route('dashboard', ['username' => $user->username]) }}" class="users-details-btn users-details-btn-info" target="_blank">View User Profile</a>
                <a href="{{ route('admin.users') }}" class="users-details-btn users-details-btn-secondary">Back to User List</a>
            </div>
        </div>
    </div>
</div>

@endsection
