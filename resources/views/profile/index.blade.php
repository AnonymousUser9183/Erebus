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
	--color-success: #22c55e;
	--color-warning: #f59e61;
	--color-error: #ef4444;
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

/* ===== UNIFIED HEADER SECTION ===== */
.profile-header {
	background: linear-gradient(135deg, var(--color-accent-dark) 0%, var(--color-accent) 100%);
	border-radius: var(--radius-lg);
	padding: var(--spacing-2xl);
	color: white;
	margin-bottom: var(--spacing-2xl);
	box-shadow: var(--shadow-lg);
}

.profile-header-top {
	display: grid;
	grid-template-columns: auto 1fr auto;
	gap: var(--spacing-2xl);
	align-items: flex-start;
	margin-bottom: var(--spacing-2xl);
}

.profile-avatar-wrapper {
	position: relative;
}

.profile-avatar {
	width: 140px;
	height: 140px;
	border-radius: 50%;
	border: 4px solid rgba(255, 255, 255, 0.3);
	object-fit: cover;
	box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
	transition: var(--transition);
}

.profile-avatar:hover {
	transform: scale(1.05);
	border-color: rgba(255, 255, 255, 0.6);
}

.profile-avatar-badge {
	position: absolute;
	bottom: 0;
	right: 0;
	width: 40px;
	height: 40px;
	background-color: var(--color-success);
	border: 3px solid white;
	border-radius: 50%;
	display: flex;
	align-items: center;
	justify-content: center;
	font-size: 20px;
	box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

.profile-header-content {
	display: flex;
	flex-direction: column;
	gap: var(--spacing-sm);
}

.profile-header-name {
	font-size: 32px;
	font-weight: 800;
	margin: 0;
	letter-spacing: -0.5px;
}

.profile-header-role {
	display: inline-flex;
	align-items: center;
	gap: var(--spacing-xs);
	padding: var(--spacing-xs) var(--spacing-md);
	background-color: rgba(255, 255, 255, 0.2);
	border-radius: var(--radius);
	width: fit-content;
	font-size: 13px;
	font-weight: 600;
	text-transform: uppercase;
	letter-spacing: 0.5px;
}

.profile-header-meta {
	display: flex;
	gap: var(--spacing-lg);
	margin-top: var(--spacing-md);
	font-size: 14px;
	flex-wrap: wrap;
}

.meta-badge {
	display: flex;
	flex-direction: column;
	gap: 2px;
}

.meta-badge-label {
	font-size: 11px;
	opacity: 0.9;
	text-transform: uppercase;
	letter-spacing: 0.3px;
}

.meta-badge-value {
	font-weight: 700;
	font-size: 15px;
}

.profile-header-actions {
	display: flex;
	flex-direction: column;
	gap: var(--spacing-md);
	align-items: center;
	justify-content: flex-start;
}

.pgp-status-large {
	display: inline-flex;
	align-items: center;
	gap: var(--spacing-xs);
	padding: var(--spacing-sm) var(--spacing-lg);
	background-color: rgba(255, 255, 255, 0.15);
	border: 2px solid rgba(255, 255, 255, 0.3);
	border-radius: var(--radius);
	font-size: 13px;
	font-weight: 600;
	text-transform: uppercase;
	letter-spacing: 0.5px;
	backdrop-filter: blur(10px);
	white-space: nowrap;
}

.pgp-status-large.verified {
	background-color: rgba(34, 197, 94, 0.2);
	border-color: rgba(34, 197, 94, 0.4);
	color: #e8f5e9;
}

.pgp-status-large.unverified {
	background-color: rgba(245, 158, 11, 0.2);
	border-color: rgba(245, 158, 11, 0.4);
	color: #fff3e0;
}

/* ===== PROFILE DESCRIPTION SECTION ===== */
.profile-description-section {
	margin-bottom: var(--spacing-2xl);
}

.section-title {
	font-size: 16px;
	font-weight: 700;
	color: white;
	margin: 0 0 var(--spacing-md) 0;
	display: flex;
	align-items: center;
	gap: var(--spacing-sm);
}

.section-title::before {
	content: '';
	width: 4px;
	height: 20px;
	background-color: rgba(255, 255, 255, 0.3);
	border-radius: 2px;
}

.description-display {
	background: rgba(255, 255, 255, 0.1);
	border-left: 4px solid rgba(255, 255, 255, 0.3);
	border-radius: var(--radius);
	padding: var(--spacing-lg);
	font-size: 14px;
	line-height: 1.8;
	color: rgba(255, 255, 255, 0.95);
	min-height: 80px;
	display: flex;
	align-items: center;
}

.description-display p {
	margin: 0;
}

.description-display em {
	opacity: 0.7;
}

/* ===== PGP SECTION IN HEADER ===== */
.pgp-header-section {
	margin-bottom: var(--spacing-2xl);
}

.pgp-header-title {
	font-size: 16px;
	font-weight: 700;
	color: white;
	margin: 0 0 var(--spacing-md) 0;
	display: flex;
	align-items: center;
	gap: var(--spacing-sm);
}

.pgp-header-title::before {
	content: '';
	width: 4px;
	height: 20px;
	background-color: rgba(255, 255, 255, 0.3);
	border-radius: 2px;
}

.pgp-badge-status {
	display: inline-block;
	padding: 6px 12px;
	border-radius: var(--radius);
	font-size: 12px;
	font-weight: 600;
	text-align: center;
}

.pgp-badge-status.verified {
	background-color: rgba(34, 197, 94, 0.2);
	color: #a7d8a8;
	border: 1px solid rgba(34, 197, 94, 0.5);
}

.pgp-badge-status.unverified {
	background-color: rgba(245, 158, 11, 0.2);
	color: #ffcc80;
	border: 1px solid rgba(245, 158, 11, 0.5);
}

/* ===== EDIT SECTION (DETAILS/SUMMARY) ===== */
.edit-details {
	margin-top: var(--spacing-lg);
}

.edit-summary {
	background: rgba(255, 255, 255, 0.2);
	color: white;
	border: 1px solid rgba(255, 255, 255, 0.3);
	padding: var(--spacing-md) var(--spacing-lg);
	border-radius: var(--radius);
	font-size: 13px;
	font-weight: 600;
	cursor: pointer;
	transition: var(--transition);
	user-select: none;
	display: flex;
	align-items: center;
	gap: var(--spacing-sm);
}

.edit-details[open] > .edit-summary {
	background: rgba(255, 255, 255, 0.3);
	border-color: rgba(255, 255, 255, 0.5);
}

.edit-summary:hover {
	background: rgba(255, 255, 255, 0.3);
	border-color: rgba(255, 255, 255, 0.5);
}

.edit-summary::marker {
	color: white;
}

.edit-content {
	margin-top: var(--spacing-lg);
	padding-top: var(--spacing-lg);
	border-top: 1px solid rgba(255, 255, 255, 0.2);
}

.edit-grid {
	display: grid;
	grid-template-columns: 280px 1fr;
	gap: var(--spacing-xl);
}

.picture-edit-card {
	background: rgba(255, 255, 255, 0.08);
	border-radius: var(--radius-lg);
	border: 1px solid rgba(255, 255, 255, 0.2);
	padding: var(--spacing-lg);
	display: flex;
	flex-direction: column;
	align-items: center;
	gap: var(--spacing-lg);
	height: fit-content;
}

.picture-preview {
	width: 180px;
	height: 180px;
	border-radius: 50%;
	border: 4px solid rgba(255, 255, 255, 0.3);
	overflow: hidden;
	box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
	transition: var(--transition);
}

.picture-preview:hover {
	transform: scale(1.05);
	border-color: rgba(255, 255, 255, 0.6);
}

.picture-preview img {
	width: 100%;
	height: 100%;
	object-fit: cover;
}

.file-input-label {
	display: block;
	padding: 10px 16px;
	background-color: rgba(255, 255, 255, 0.25);
	color: white;
	border-radius: var(--radius);
	font-size: 13px;
	font-weight: 600;
	cursor: pointer;
	text-align: center;
	transition: var(--transition);
	width: 100%;
	border: 1px solid rgba(255, 255, 255, 0.2);
}

.file-input-label:hover {
	background-color: rgba(255, 255, 255, 0.35);
	transform: translateY(-1px);
}

.file-input-label input {
	display: none;
}

.file-hint {
	font-size: 12px;
	color: rgba(255, 255, 255, 0.8);
	text-align: center;
	line-height: 1.4;
}

/* ===== FORM STYLES ===== */
.form-card {
	background: rgba(255, 255, 255, 0.08);
	border-radius: var(--radius-lg);
	border: 1px solid rgba(255, 255, 255, 0.2);
	padding: var(--spacing-lg);
}

.form-group {
	display: flex;
	flex-direction: column;
	gap: var(--spacing-sm);
	margin-bottom: var(--spacing-lg);
}

.form-group:last-child {
	margin-bottom: 0;
}

.form-label {
	font-size: 14px;
	font-weight: 600;
	color: white;
}

.form-textarea {
	padding: var(--spacing-md);
	border: 2px solid rgba(255, 255, 255, 0.2);
	border-radius: var(--radius);
	font-family: inherit;
	font-size: 14px;
	color: var(--color-text-primary);
	background-color: rgba(255, 255, 255, 0.95);
	resize: vertical;
	line-height: 1.6;
	transition: var(--transition);
}

.form-textarea:focus {
	outline: none;
	border-color: rgba(255, 255, 255, 0.6);
	box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.1);
}

.form-hint {
	font-size: 12px;
	color: rgba(255, 255, 255, 0.8);
	line-height: 1.4;
}

.form-submit {
	background-color: rgba(255, 255, 255, 0.25);
	color: white;
	padding: 12px 20px;
	border: 1px solid rgba(255, 255, 255, 0.3);
	border-radius: var(--radius);
	font-size: 14px;
	font-weight: 600;
	cursor: pointer;
	transition: var(--transition);
	align-self: flex-start;
}

.form-submit:hover {
	background-color: rgba(255, 255, 255, 0.35);
	transform: translateY(-2px);
}

/* ===== MAIN GRID ===== */
.profile-main-grid {
	display: grid;
	grid-template-columns: 1fr 1fr;
	gap: var(--spacing-xl);
	margin-bottom: var(--spacing-2xl);
}

.card {
	background-color: var(--color-bg-secondary);
	border-radius: var(--radius-lg);
	box-shadow: var(--shadow-md);
	border: 1px solid var(--color-border);
	transition: var(--transition);
}

.card:hover {
	box-shadow: var(--shadow-lg);
	border-color: var(--color-accent-light);
}

.card-header {
	padding: var(--spacing-lg);
	border-bottom: 2px solid var(--color-accent-light);
}

.card-title {
	font-size: 16px;
	font-weight: 700;
	color: var(--color-text-primary);
	margin: 0;
	display: flex;
	align-items: center;
	gap: var(--spacing-sm);
}

.card-title::before {
	content: '';
	width: 4px;
	height: 20px;
	background-color: var(--color-accent);
	border-radius: 2px;
}

.card-content {
	padding: var(--spacing-lg);
}

/* ===== MIRROR SECTION ===== */
.mirror-status-box {
	padding: var(--spacing-lg);
	border-radius: var(--radius);
	border-left: 5px solid;
	margin-bottom: var(--spacing-lg);
	display: flex;
	align-items: center;
	gap: var(--spacing-md);
}

.mirror-status-box.assigned {
	background: linear-gradient(135deg, rgba(34, 197, 94, 0.08) 0%, rgba(34, 197, 94, 0.04) 100%);
	border-color: var(--color-success);
	color: #2e7d32;
}

.mirror-status-box.pending {
	background: linear-gradient(135deg, rgba(245, 158, 11, 0.08) 0%, rgba(245, 158, 11, 0.04) 100%);
	border-color: var(--color-warning);
	color: #e65100;
}

.mirror-status-box.none {
	background: linear-gradient(135deg, rgba(32, 128, 136, 0.08) 0%, rgba(32, 128, 136, 0.04) 100%);
	border-color: var(--color-accent);
	color: var(--color-text-primary);
}

.mirror-status-icon {
	font-size: 24px;
	flex-shrink: 0;
}

.mirror-status-content {
	flex: 1;
}

.mirror-status-title {
	font-weight: 700;
	font-size: 14px;
	margin-bottom: 2px;
}

.mirror-status-desc {
	font-size: 13px;
	opacity: 0.9;
}

.mirror-url {
	background: linear-gradient(135deg, var(--color-bg-primary) 0%, #f8f7f5 100%);
	border: 2px solid var(--color-accent);
	border-radius: var(--radius);
	padding: var(--spacing-lg);
	font-family: 'Courier New', monospace;
	font-size: 12px;
	color: var(--color-text-primary);
	word-break: break-all;
	margin: var(--spacing-lg) 0;
	box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.02);
}

/* ===== BUTTON STYLES ===== */
.btn {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	gap: var(--spacing-xs);
	padding: 10px 18px;
	background-color: var(--color-accent);
	color: white;
	border: none;
	border-radius: var(--radius);
	font-size: 14px;
	font-weight: 600;
	cursor: pointer;
	transition: var(--transition);
	text-decoration: none;
	white-space: nowrap;
}

.btn:hover {
	background-color: var(--color-accent-light);
	transform: translateY(-2px);
	box-shadow: 0 4px 12px rgba(32, 128, 136, 0.3);
}

.btn:active {
	transform: translateY(0);
}

.btn-secondary {
	background-color: rgba(255, 255, 255, 0.2);
	color: white;
	border: 1px solid rgba(255, 255, 255, 0.3);
}

.btn-secondary:hover {
	background-color: rgba(255, 255, 255, 0.3);
	border-color: rgba(255, 255, 255, 0.5);
}

.btn-danger {
	background-color: var(--color-error);
}

.btn-danger:hover {
	background-color: #dc3545;
}

/* ===== REFERENCES SECTION ===== */
.references-grid {
	display: grid;
	grid-template-columns: repeat(auto-fit, minmax(340px, 1fr));
	gap: var(--spacing-xl);
}

.ref-card {
	background-color: var(--color-bg-secondary);
	border-radius: var(--radius-lg);
	box-shadow: var(--shadow-md);
	border: 1px solid var(--color-border);
	padding: var(--spacing-lg);
	transition: var(--transition);
}

.ref-card:hover {
	box-shadow: var(--shadow-lg);
	border-color: var(--color-accent-light);
}

.ref-card-title {
	font-size: 16px;
	font-weight: 700;
	color: var(--color-text-primary);
	margin: 0 0 var(--spacing-lg) 0;
	display: flex;
	align-items: center;
	gap: var(--spacing-sm);
}

.ref-card-title::before {
	content: '';
	width: 4px;
	height: 20px;
	background-color: var(--color-accent);
	border-radius: 2px;
}

.ref-display {
	background: linear-gradient(135deg, var(--color-bg-primary) 0%, #f8f7f5 100%);
	border: 2px solid var(--color-accent);
	border-radius: var(--radius);
	padding: var(--spacing-lg);
	font-family: 'Courier New', monospace;
	font-size: 12px;
	color: var(--color-text-primary);
	text-align: center;
	word-break: break-all;
	margin-bottom: var(--spacing-lg);
	box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.02);
}

.ref-info-grid {
	display: grid;
	grid-template-columns: repeat(2, 1fr);
	gap: var(--spacing-md);
	margin-bottom: var(--spacing-lg);
}

.ref-info-box {
	background: linear-gradient(135deg, var(--color-bg-primary) 0%, #f8f7f5 100%);
	border-radius: var(--radius);
	padding: var(--spacing-md);
	display: flex;
	flex-direction: column;
	gap: 4px;
	border: 1px solid var(--color-border);
}

.ref-info-label {
	font-size: 11px;
	color: var(--color-text-secondary);
	text-transform: uppercase;
	font-weight: 600;
	letter-spacing: 0.3px;
}

.ref-info-value {
	font-size: 13px;
	color: var(--color-text-primary);
	font-weight: 600;
}

.vendor-list {
	display: flex;
	flex-direction: column;
	gap: var(--spacing-sm);
}

.vendor-item {
	background: linear-gradient(135deg, var(--color-bg-primary) 0%, #f8f7f5 100%);
	border: 1px solid var(--color-border);
	border-radius: var(--radius);
	padding: var(--spacing-md);
	display: flex;
	justify-content: space-between;
	align-items: center;
	gap: var(--spacing-md);
	transition: var(--transition);
}

.vendor-item:hover {
	background-color: var(--color-bg-primary);
	border-color: var(--color-accent-light);
}

.vendor-info {
	display: flex;
	flex-direction: column;
	gap: 4px;
	flex: 1;
}

.vendor-name {
	font-size: 14px;
	font-weight: 600;
	color: var(--color-text-primary);
}

.vendor-id {
	font-size: 12px;
	color: var(--color-text-secondary);
	font-family: 'Courier New', monospace;
}

.ref-form {
	display: flex;
	flex-direction: column;
	gap: var(--spacing-md);
	margin-bottom: var(--spacing-lg);
}

.ref-input-group {
	display: flex;
	gap: var(--spacing-sm);
}

.ref-input {
	flex: 1;
	padding: var(--spacing-md);
	border: 2px solid var(--color-border);
	border-radius: var(--radius);
	font-size: 14px;
	color: var(--color-text-primary);
	background-color: var(--color-bg-secondary);
	transition: var(--transition);
}

.ref-input:focus {
	outline: none;
	border-color: var(--color-accent);
	box-shadow: 0 0 0 3px rgba(32, 128, 136, 0.1);
}

.ref-button {
	background-color: var(--color-accent);
	color: white;
	padding: var(--spacing-md) var(--spacing-lg);
	border: none;
	border-radius: var(--radius);
	font-size: 14px;
	font-weight: 600;
	cursor: pointer;
	transition: var(--transition);
}

.ref-button:hover {
	background-color: var(--color-accent-light);
	transform: translateY(-2px);
	box-shadow: 0 4px 12px rgba(32, 128, 136, 0.3);
}

.vendor-note {
	background: linear-gradient(135deg, rgba(32, 128, 136, 0.08) 0%, rgba(32, 128, 136, 0.04) 100%);
	border-left: 4px solid var(--color-accent);
	border-radius: var(--radius);
	padding: var(--spacing-md);
	margin-bottom: var(--spacing-lg);
}

.vendor-note p {
	margin: 0;
	font-size: 13px;
	color: var(--color-text-primary);
	line-height: 1.6;
}

.empty-state {
	background: linear-gradient(135deg, var(--color-bg-primary) 0%, #f8f7f5 100%);
	border: 2px dashed var(--color-border);
	border-radius: var(--radius);
	padding: var(--spacing-lg);
	text-align: center;
	color: var(--color-text-secondary);
	font-size: 14px;
}

.empty-state-icon {
	font-size: 32px;
	margin-bottom: var(--spacing-sm);
}

.referral-list {
	display: flex;
	flex-direction: column;
	gap: var(--spacing-sm);
}

.referral-item {
	background: linear-gradient(135deg, var(--color-bg-primary) 0%, #f8f7f5 100%);
	border: 1px solid var(--color-border);
	border-radius: var(--radius);
	padding: var(--spacing-md);
	font-size: 14px;
	color: var(--color-text-primary);
	font-weight: 500;
	transition: var(--transition);
}

.referral-item:hover {
	background-color: var(--color-bg-primary);
	border-color: var(--color-accent-light);
}

/* ===== RESPONSIVE ===== */
@media (max-width: 1024px) {
	.profile-main-grid {
		grid-template-columns: 1fr;
	}
	
	.profile-header-top {
		grid-template-columns: auto 1fr;
		gap: var(--spacing-xl);
	}
	
	.profile-header-actions {
		grid-column: 2;
		align-items: flex-start;
	}
	
	.edit-grid {
		grid-template-columns: 1fr;
	}
	
	.ref-info-grid {
		grid-template-columns: 1fr;
	}
}

@media (max-width: 768px) {
	.profile-header-top {
		grid-template-columns: 1fr;
		text-align: center;
	}
	
	.profile-avatar-wrapper {
		justify-self: center;
	}
	
	.profile-header-actions {
		grid-column: 1;
		align-items: stretch;
		width: 100%;
	}
	
	.profile-header-meta {
		flex-direction: column;
		gap: var(--spacing-md);
		justify-content: center;
	}
	
	.references-grid {
		grid-template-columns: 1fr;
	}
	
	.ref-input-group {
		flex-direction: column;
	}
	
	.btn {
		width: 100%;
		justify-content: center;
	}
	
	.edit-grid {
		grid-template-columns: 1fr;
	}
}
</style>

<!-- ===== UNIFIED PROFILE HEADER ===== -->
<div class="profile-header">
<!-- TOP: Avatar, Name, Role, Meta, PGP, Settings -->
<div class="profile-header-top">
<div class="profile-avatar-wrapper">
<img src="{{ $profile->profile_picture_url }}" alt="{{ $user->username }}" class="profile-avatar">
<div class="profile-avatar-badge" title="Active">✓</div>
</div>

<div class="profile-header-content">
<h1 class="profile-header-name">{{ $user->username }}</h1>
<span class="profile-header-role">
<span>●</span> {{ $userRole }}
</span>
<div class="profile-header-meta">
<div class="meta-badge">
<span class="meta-badge-label">Joined</span>
<span class="meta-badge-value">{{ $user->created_at->format('M Y') }}</span>
</div>
<div class="meta-badge">
<span class="meta-badge-label">Account Age</span>
<span class="meta-badge-value">{{ $user->created_at->diffForHumans() }}</span>
</div>
</div>
</div>

<div class="profile-header-actions">
@if($pgpKey)
@if($pgpKey->verified)
<span class="pgp-status-large verified">✓ PGP Verified</span>
@else
<span class="pgp-status-large unverified">⚠ PGP Unverified</span>
@endif
@else
<span class="pgp-status-large">No PGP Key</span>
@endif
<a href="{{ route('settings') }}" class="btn btn-secondary">Account Settings</a>
</div>
</div>

<!-- PROFILE DESCRIPTION -->
<div class="profile-description-section">
<h2 class="section-title">Profile Description</h2>
<div class="description-display">
{!! $profile && $profile->description ? nl2br(e(Crypt::decryptString($profile->description))) : '<em>No description added yet.</em>' !!}
</div>
</div>

<!-- PGP SECTION IN HEADER -->
<div class="pgp-header-section">
<h2 class="pgp-header-title">🔐 PGP Public Key</h2>
@if($user->pgpKey)
@if($user->pgpKey->verified)
<span class="pgp-badge-status verified">✓ Verified</span>
@else
<span class="pgp-badge-status unverified">⚠ Unverified</span>
@endif
@else
<span class="pgp-badge-status unverified">Not Added</span>
@endif
</div>

<!-- EDIT SECTION (COLLAPSIBLE WITH DETAILS/SUMMARY) -->
<details class="edit-details">
<summary class="edit-summary">✎ Edit Your Profile</summary>

<div class="edit-content">
<form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
@csrf
<div class="edit-grid">
<!-- Picture Sidebar -->
<div>
<div class="picture-edit-card">
<div class="picture-preview">
<img src="{{ $profile->profile_picture_url }}" alt="Profile Picture">
</div>
<label class="file-input-label">
Change Picture
<input type="file" name="profile_picture" accept="image/*">
</label>
<p class="file-hint">JPG, PNG, GIF, WebP. Max 800KB.</p>
</div>
</div>

<!-- Form -->
<div class="form-card">
<h3 style="font-size: 16px; font-weight: 700; margin: 0 0 var(--spacing-lg) 0; color: white;">
Edit Description
</h3>
<div class="form-group">
<label for="description" class="form-label">Your Profile Description</label>
<textarea name="description" id="description" rows="10" required minlength="4" maxlength="800" class="form-textarea" placeholder="Write something about yourself...">{{ old('description', $profile->description ? e(Crypt::decryptString($profile->description)) : '') }}</textarea>
<small class="form-hint">4–800 characters. Letters, numbers, spaces, and punctuation allowed.</small>
</div>
<button type="submit" class="form-submit">💾 Save Changes</button>
</div>
</div>
</form>
</div>
</details>
</div>

<!-- ===== MAIN GRID (Mirror + Referrals) ===== -->
<div class="profile-main-grid">
<!-- Private Mirror Card -->
<div class="card">
<div class="card-header">
<h2 class="card-title">Private Mirror</h2>
</div>
<div class="card-content">
@if($assignedMirrorUrl)
<div class="mirror-status-box assigned">
<span class="mirror-status-icon">✓</span>
<div class="mirror-status-content">
<div class="mirror-status-title">Mirror Assigned</div>
<div class="mirror-status-desc">You have a private mirror</div>
</div>
</div>
<div class="mirror-url">{{ $assignedMirrorUrl }}</div>
<p style="font-size: 13px; color: var(--color-text-secondary); margin: 0;">
Keep this URL safe and share only with trusted individuals.
</p>

@elseif($hasPendingMirrorRequest)
<div class="mirror-status-box pending">
<span class="mirror-status-icon">⏳</span>
<div class="mirror-status-content">
<div class="mirror-status-title">Request Pending</div>
<div class="mirror-status-desc">Admin review in progress</div>
</div>
</div>
<p style="font-size: 13px; color: var(--color-text-secondary); margin: var(--spacing-lg) 0 0 0;">
An administrator is reviewing your request. You'll be notified once a mirror is assigned.
</p>

@else
<div class="mirror-status-box none">
<span class="mirror-status-icon">○</span>
<div class="mirror-status-content">
<div class="mirror-status-title">No Mirror Yet</div>
<div class="mirror-status-desc">Request a private mirror below</div>
</div>
</div>
<form action="{{ route('profile.private-mirror.request') }}" method="POST" style="margin-top: var(--spacing-lg);">
@csrf
<button type="submit" class="btn" style="width: 100%; justify-content: center;">Request Mirror</button>
</form>
@endif
</div>
</div>

<!-- Referrals Card -->
<div class="card">
<div class="card-header">
<h2 class="card-title">Your Referrals</h2>
</div>
<div class="card-content">
@if($referrals->count() > 0)
<p style="font-size: 13px; color: var(--color-text-secondary); margin: 0 0 var(--spacing-lg) 0;">
{{ $referrals->count() }} {{ $referrals->count() === 1 ? 'user' : 'users' }} used your reference code:
</p>
<div class="referral-list">
@foreach($referrals as $referral)
<div class="referral-item">✓ {{ $referral->username }}</div>
@endforeach
</div>
@else
<div class="empty-state">
<div class="empty-state-icon">📭</div>
<div>No referrals yet</div>
<div style="font-size: 12px; margin-top: 4px; opacity: 0.8;">When someone uses your code, they'll appear here</div>
</div>
@endif
</div>
</div>
</div>

<!-- ===== REFERENCES SECTION ===== -->
<div style="margin-top: var(--spacing-2xl);">
<h2 style="font-size: 20px; font-weight: 700; margin-bottom: var(--spacing-lg); color: var(--color-text-primary);">
Referrals & Vendors
</h2>

<div class="references-grid">
<!-- Your Reference -->
<div class="ref-card">
<h3 class="ref-card-title">Your Reference ID</h3>
<p style="font-size: 13px; color: var(--color-text-secondary); margin: 0 0 var(--spacing-lg) 0;">
Share this unique ID with trusted individuals:
</p>
<div class="ref-display">{{ $referenceId }}</div>
<div class="ref-info-grid">
<div class="ref-info-box">
<span class="ref-info-label">Used a Code?</span>
<span class="ref-info-value">{{ $usedReferenceCode ? '✓ Yes' : '✗ No' }}</span>
</div>
@if($usedReferenceCode && $referrerUsername)
<div class="ref-info-box">
<span class="ref-info-label">Referred By</span>
<span class="ref-info-value">{{ $referrerUsername }}</span>
</div>
@endif
</div>
<p style="font-size: 12px; color: var(--color-text-secondary); margin: 0;">
⚠ Only share with trusted individuals.
</p>
</div>

<!-- Saved Vendors -->
<div class="ref-card">
<h3 class="ref-card-title">Saved Vendors</h3>
<p style="font-size: 13px; color: var(--color-text-secondary); margin: 0 0 var(--spacing-lg) 0;">
Add vendor reference IDs to save their shops:
</p>
<form action="{{ route('references.store', auth()->user()->id) }}" method="POST" class="ref-form">
@csrf
<div class="ref-input-group">
<input type="text" name="vendor_reference_id" placeholder="Vendor Reference ID" required minlength="12" maxlength="20" class="ref-input">
<button type="submit" class="ref-button">Add</button>
</div>
</form>
<div class="vendor-note">
<p>💡 Only vendor reference IDs can be added. Personal user IDs will be rejected.</p>
</div>

@if(isset($privateShops) && $privateShops->count() > 0)
<div style="margin-top: var(--spacing-lg);">
<h4 style="font-size: 13px; font-weight: 700; margin: 0 0 var(--spacing-md) 0; color: var(--color-text-primary);">
Vendors ({{ $privateShops->count() }})
</h4>
<div class="vendor-list">
@foreach($privateShops as $shop)
<div class="vendor-item">
<div class="vendor-info">
<div class="vendor-name">{{ $shop->vendor_username }}</div>
<div class="vendor-id">{{ $shop->vendor_reference_id }}</div>
</div>
<form action="{{ route('references.remove', $shop->id) }}" method="POST" style="margin: 0;">
@csrf
@method('DELETE')
<button type="submit" class="btn btn-danger" style="font-size: 12px; padding: 6px 12px;">Remove</button>
</form>
</div>
@endforeach
</div>
</div>
@else
<div class="empty-state">
<div class="empty-state-icon">🏪</div>
<div>No vendors saved yet</div>
</div>
@endif
</div>
</div>
</div>

@endsection
