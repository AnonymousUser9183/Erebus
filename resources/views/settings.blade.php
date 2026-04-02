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
        --color-accent-dark: #0f5962;
        --color-warning: #f59e61;
        --color-error: #ef4444;
        --color-success: #22c55e;
        --spacing-xs: 8px;
        --spacing-sm: 12px;
        --spacing-md: 16px;
        --spacing-lg: 20px;
        --spacing-xl: 24px;
        --spacing-2xl: 32px;
        --radius: 8px;
        --radius-lg: 12px;
        --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.05);
        --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.1);
        --shadow-lg: 0 10px 20px rgba(0, 0, 0, 0.1);
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    * {
        box-sizing: border-box;
    }

    body {
        background: linear-gradient(135deg, var(--color-bg-primary) 0%, #f5f5f2 100%);
        min-height: 100vh;
    }

    .settings-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: var(--spacing-xl);
    }

    /* ===== HEADER ===== */
    .settings-header {
        margin-bottom: var(--spacing-2xl);
    }

    .settings-header h1 {
        font-size: 28px;
        font-weight: 800;
        color: var(--color-text-primary);
        margin: 0 0 var(--spacing-sm) 0;
        letter-spacing: -0.5px;
    }

    .settings-header p {
        font-size: 14px;
        color: var(--color-text-secondary);
        margin: 0;
    }

    /* ===== GRID ===== */
    .settings-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
        gap: var(--spacing-xl);
        margin-bottom: var(--spacing-2xl);
    }

    .card {
        background-color: var(--color-bg-secondary);
        border: 1px solid var(--color-border);
        border-radius: var(--radius-lg);
        padding: var(--spacing-lg);
        box-shadow: var(--shadow-md);
        transition: var(--transition);
    }

    .card:hover {
        box-shadow: var(--shadow-lg);
        border-color: var(--color-accent-light);
    }

    .card-header {
        display: flex;
        align-items: center;
        gap: var(--spacing-md);
        margin-bottom: var(--spacing-lg);
        padding-bottom: var(--spacing-lg);
        border-bottom: 2px solid var(--color-accent-light);
    }

    .card-icon {
        font-size: 24px;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, var(--color-accent-dark) 0%, var(--color-accent) 100%);
        border-radius: var(--radius);
        color: white;
        flex-shrink: 0;
    }

    .card-title {
        font-size: 16px;
        font-weight: 700;
        color: var(--color-text-primary);
        margin: 0;
    }

    /* ===== FORM STYLES ===== */
    .form-group {
        margin-bottom: var(--spacing-lg);
    }

    .form-group:last-child {
        margin-bottom: 0;
    }

    .form-label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: var(--color-text-primary);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: var(--spacing-sm);
    }

    .form-input,
    .form-textarea {
        width: 100%;
        padding: var(--spacing-md);
        border: 2px solid var(--color-border);
        border-radius: var(--radius);
        font-size: 14px;
        color: var(--color-text-primary);
        background-color: var(--color-bg-secondary);
        font-family: inherit;
        transition: var(--transition);
    }

    .form-textarea {
        resize: vertical;
        font-family: 'Courier New', monospace;
        min-height: 120px;
    }

    .form-input:focus,
    .form-textarea:focus {
        outline: none;
        border-color: var(--color-accent);
        box-shadow: 0 0 0 3px rgba(32, 128, 136, 0.1);
    }

    .form-hint {
        font-size: 12px;
        color: var(--color-text-secondary);
        margin: var(--spacing-sm) 0 0 0;
        line-height: 1.5;
    }

    /* ===== BUTTON STYLES ===== */
    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: var(--spacing-xs);
        padding: 12px 20px;
        background-color: var(--color-accent);
        color: white;
        border: none;
        border-radius: var(--radius);
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
        text-decoration: none;
        width: 100%;
    }

    .btn:hover {
        background-color: var(--color-accent-light);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(32, 128, 136, 0.3);
    }

    .btn-small {
        width: auto;
        padding: 8px 16px;
        font-size: 12px;
    }

    /* ===== ALERTS ===== */
    .alert {
        padding: var(--spacing-md);
        border-radius: var(--radius);
        border-left: 4px solid;
        margin-bottom: var(--spacing-lg);
    }

    .alert-warning {
        background: linear-gradient(135deg, rgba(245, 158, 11, 0.08) 0%, rgba(245, 158, 11, 0.04) 100%);
        border-color: var(--color-warning);
        color: #d97706;
    }

    .alert-success {
        background: linear-gradient(135deg, rgba(34, 197, 94, 0.08) 0%, rgba(34, 197, 94, 0.04) 100%);
        border-color: var(--color-success);
        color: #059669;
    }

    .alert p {
        margin: 0;
        font-size: 14px;
        line-height: 1.6;
    }

    /* ===== HIGHLIGHT BOX ===== */
    .highlight-box {
        background: linear-gradient(135deg, var(--color-bg-primary) 0%, #f8f7f5 100%);
        border: 2px solid var(--color-accent);
        border-radius: var(--radius);
        padding: var(--spacing-lg);
        margin: var(--spacing-lg) 0;
    }

    .highlight-label {
        font-size: 12px;
        color: var(--color-text-secondary);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
        margin-bottom: var(--spacing-sm);
    }

    .highlight-value {
        font-size: 16px;
        font-weight: 700;
        color: var(--color-accent);
        font-family: 'Courier New', monospace;
        word-break: break-all;
        line-height: 1.6;
    }

    .highlight-desc {
        font-size: 13px;
        color: var(--color-text-secondary);
        margin-top: var(--spacing-md);
        line-height: 1.6;
    }

    /* ===== TOGGLE BUTTONS ===== */
    .toggle-group {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: var(--spacing-sm);
        margin-top: var(--spacing-md);
    }

    .toggle-btn {
        padding: var(--spacing-md);
        border: 2px solid var(--color-border);
        background-color: var(--color-bg-secondary);
        border-radius: var(--radius);
        font-size: 14px;
        font-weight: 600;
        color: var(--color-text-primary);
        cursor: pointer;
        transition: var(--transition);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .toggle-btn:hover {
        border-color: var(--color-accent);
        background-color: var(--color-bg-primary);
    }

    .toggle-btn.active {
        background: linear-gradient(135deg, var(--color-accent-dark) 0%, var(--color-accent) 100%);
        border-color: var(--color-accent);
        color: white;
        box-shadow: 0 4px 12px rgba(32, 128, 136, 0.3);
    }

    /* ===== TABLE ===== */
    .table-container {
        overflow-x: auto;
        margin-top: var(--spacing-lg);
        border-radius: var(--radius);
        border: 1px solid var(--color-border);
    }

    .settings-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }

    .settings-table thead {
        background: linear-gradient(135deg, var(--color-bg-primary) 0%, #f8f7f5 100%);
        border-bottom: 2px solid var(--color-border);
    }

    .settings-table th {
        padding: var(--spacing-md);
        text-align: left;
        font-weight: 700;
        color: var(--color-text-primary);
        letter-spacing: 0.3px;
        text-transform: uppercase;
        font-size: 12px;
    }

    .settings-table td {
        padding: var(--spacing-md);
        border-bottom: 1px solid var(--color-border);
        color: var(--color-text-primary);
        word-break: break-all;
        font-family: 'Courier New', monospace;
    }

    .settings-table tbody tr:hover {
        background-color: var(--color-bg-primary);
    }

    .action-btn {
        background-color: transparent;
        color: var(--color-text-primary);
        border: 1px solid var(--color-border);
        padding: 6px 12px;
        border-radius: var(--radius);
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
        white-space: nowrap;
        font-family: inherit;
    }

    .action-btn:hover {
        background-color: #fecaca;
        border-color: var(--color-error);
        color: var(--color-error);
    }

    /* ===== EMPTY STATE ===== */
    .empty-state {
        padding: var(--spacing-lg);
        background: linear-gradient(135deg, var(--color-bg-primary) 0%, #f8f7f5 100%);
        border: 2px dashed var(--color-border);
        border-radius: var(--radius);
        text-align: center;
        color: var(--color-text-secondary);
        font-size: 14px;
        margin-top: var(--spacing-lg);
    }

    .empty-icon {
        font-size: 32px;
        margin-bottom: var(--spacing-sm);
    }

    /* ===== FULL WIDTH CARD ===== */
    .card-full {
        grid-column: 1 / -1;
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 1024px) {
        .settings-grid {
            grid-template-columns: 1fr;
        }

        .card-full {
            grid-column: 1;
        }
    }

    @media (max-width: 768px) {
        .settings-container {
            padding: var(--spacing-md);
        }

        .settings-header h1 {
            font-size: 24px;
        }

        .settings-grid {
            grid-template-columns: 1fr;
            gap: var(--spacing-lg);
        }

        .toggle-group {
            grid-template-columns: 1fr 1fr;
        }

        .settings-table {
            font-size: 12px;
        }

        .settings-table th,
        .settings-table td {
            padding: var(--spacing-sm);
        }

        .form-textarea {
            min-height: 100px;
        }
    }
</style>

<div class="settings-container">
    <!-- Header -->
    <div class="settings-header">
        <h1>⚙️ Account Settings</h1>
        <p>Manage your account security, preferences, and payment information</p>
    </div>

    <!-- Settings Grid -->
    <div class="settings-grid">
        <!-- Change Password Card -->
        <div class="card">
            <div class="card-header">
                <div class="card-icon">🔐</div>
                <h2 class="card-title">Change Password</h2>
            </div>
            <form method="POST" action="{{ route('settings.changePassword') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="current_password">Current Password</label>
                    <input class="form-input" 
                           id="current_password" 
                           type="password" 
                           name="current_password" 
                           required 
                           minlength="8" 
                           maxlength="40" 
                           autocomplete="current-password"
                           placeholder="Enter your current password">
                </div>
                <div class="form-group">
                    <label class="form-label" for="password">New Password</label>
                    <input class="form-input" 
                           id="password" 
                           type="password" 
                           name="password" 
                           required 
                           minlength="8" 
                           maxlength="40" 
                           autocomplete="new-password"
                           placeholder="Enter new password">
                </div>
                <div class="form-group">
                    <label class="form-label" for="password_confirmation">Confirm New Password</label>
                    <input class="form-input" 
                           id="password_confirmation" 
                           type="password" 
                           name="password_confirmation" 
                           required 
                           minlength="8" 
                           maxlength="40" 
                           autocomplete="new-password"
                           placeholder="Confirm new password">
                </div>
                <button class="btn" type="submit">Update Password</button>
            </form>
        </div>

        <!-- PGP Key Card -->
        <div class="card">
            <div class="card-header">
                <div class="card-icon">🔑</div>
                <h2 class="card-title">PGP Public Key</h2>
            </div>
            <form method="POST" action="{{ route('settings.updatePgpKey') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="public_key">PGP Public Key</label>
                    <textarea class="form-textarea" 
                              id="public_key" 
                              name="public_key" 
                              required 
                              minlength="100" 
                              maxlength="8000"
                              placeholder="Paste your PGP public key here...">{{ old('public_key', $user->pgpKey->public_key ?? '') }}</textarea>
                </div>
                <p class="form-hint">💡 Learn more about PGP in our Guides section.</p>
                <button class="btn" type="submit">
                    {{ $user->pgpKey ? '✓ Update PGP Key' : '+ Add PGP Key' }}
                </button>
            </form>
        </div>

        <!-- Secret Phrase Card -->
        <div class="card">
            <div class="card-header">
                <div class="card-icon">🛡️</div>
                <h2 class="card-title">Anti-Phishing Phrase</h2>
            </div>
            
            @if ($user->secretPhrase)
                <div class="highlight-box">
                    <div class="highlight-label">Your Secret Phrase</div>
                    <div class="highlight-value">{{ $user->secretPhrase->phrase }}</div>
                    <div class="highlight-desc">
                        ✓ This phrase will always appear on your settings page. If you don't see it when logging in, you may be on a phishing site.
                    </div>
                </div>
            @else
                <form method="POST" action="{{ route('settings.updateSecretPhrase') }}">
                    @csrf
                    <div class="alert alert-warning">
                        <p>Set a unique phrase to protect yourself from phishing attacks.</p>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="secret_phrase">Secret Phrase (4-16 letters, no numbers)</label>
                        <input class="form-input" 
                               id="secret_phrase" 
                               type="text" 
                               name="secret_phrase" 
                               required 
                               minlength="4" 
                               maxlength="16"
                               placeholder="e.g., MySecurePhrase"
                               autocomplete="off">
                    </div>
                    <p class="form-hint">💡 Choose something memorable but unique. This is a one-time setting.</p>
                    <button class="btn" type="submit">Set Secret Phrase</button>
                </form>
            @endif
        </div>

        <!-- Account Protection Card -->
        <div class="card">
            <div class="card-header">
                <div class="card-icon">🔒</div>
                <h2 class="card-title">Account Protection</h2>
            </div>
            
            @if (!$user->pgpKey || !$user->pgpKey->verified)
                <div class="alert alert-warning">
                    <p>You need to verify your PGP key to enable 2-factor authentication.</p>
                </div>
                <p style="font-size: 13px; color: var(--color-text-secondary); margin: 0;">
                    👉 Add and verify a PGP key first using the PGP Public Key section.
                </p>
            @else
                <div class="alert alert-success">
                    <p>✓ Your PGP key is verified and ready for 2FA</p>
                </div>
                
                <form method="POST" action="{{ route('pgp.2fa.update') }}">
                    @csrf
                    @method('PUT')
                    <label class="form-label" for="two_fa">2-Factor Authentication</label>
                    
                    <div class="toggle-group">
                        <button class="toggle-btn{{ $user->pgpKey->two_fa_enabled ? ' active' : '' }}" 
                                type="submit" 
                                name="two_fa_enabled" 
                                value="1">
                            ✓ ON
                        </button>
                        <button class="toggle-btn{{ !$user->pgpKey->two_fa_enabled ? ' active' : '' }}" 
                                type="submit" 
                                name="two_fa_enabled" 
                                value="0">
                            ✗ OFF
                        </button>
                    </div>
                    
                    <p class="form-hint">
                        When enabled, you'll need to decrypt a PGP message during login. This prevents unauthorized access even if your password is compromised.
                    </p>
                </form>
            @endif
        </div>

        <!-- Monero Refund Address Card (Full Width) -->
        <div class="card card-full">
            <div class="card-header">
                <div class="card-icon">💰</div>
                <h2 class="card-title">Monero Refund Addresses</h2>
            </div>

            <div class="alert alert-warning">
                <p><strong>⚠️ Important:</strong> To shop at Erebus Market, you need at least one Monero address. Refunds will be sent to your primary address. For security, use a subaddress (starts with "8") instead of your main address (starts with "4").</p>
            </div>

            <form action="{{ route('return-addresses.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="monero_address">Add Monero Refund Address</label>
                    <input type="text" 
                           class="form-input" 
                           id="monero_address" 
                           name="monero_address" 
                           placeholder="Enter your Monero refund address..."
                           required
                           minlength="40"
                           maxlength="160">
                </div>
                <p class="form-hint">💡 Use a subaddress for enhanced privacy</p>
                <button type="submit" class="btn">+ Add Address</button>
            </form>

            @if($returnAddresses->count() > 0)
                <div class="table-container">
                    <table class="settings-table">
                        <thead>
                            <tr>
                                <th>Monero Address</th>
                                <th style="width: 100px;">Action</th>
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
                                            <button type="submit" class="action-btn">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-icon">📭</div>
                    <div>No refund addresses added yet</div>
                    <div style="font-size: 12px; margin-top: 4px; opacity: 0.8;">Add a Monero address above to get started</div>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection
