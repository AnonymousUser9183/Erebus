<div class="alerts-container">
<style>
:root {
--color-success: #16a34a;
--color-danger: #dc2626;
--color-warning: #ea580c;
--color-info: #0084ff;
--color-bg-secondary: #ffffff;
--spacing-md: 12px;
--spacing-lg: 16px;
--radius-md: 6px;
}

.alerts-container {
position: fixed;
top: 110px;
right: 16px;
z-index: 999;
display: flex;
flex-direction: column;
gap: var(--spacing-md);
max-width: 400px;
}

.alert {
padding: var(--spacing-md) var(--spacing-lg);
border-radius: var(--radius-md);
background-color: var(--color-bg-secondary);
border-left: 4px solid;
box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
animation: slideIn 0.3s ease-out;
}

@keyframes slideIn {
from {
transform: translateX(400px);
opacity: 0;
}
to {
transform: translateX(0);
opacity: 1;
}
}

.alert-success {
border-left-color: var(--color-success);
color: var(--color-success);
}

.alert-danger,
.alert-error {
border-left-color: var(--color-danger);
color: var(--color-danger);
}

.alert-warning {
border-left-color: var(--color-warning);
color: var(--color-warning);
}

.alert-info,
.alert-status {
border-left-color: var(--color-info);
color: var(--color-info);
}

.alert-message {
font-size: 13px;
font-weight: 500;
margin: 0;
}

@media (max-width: 768px) {
.alerts-container {
left: 8px;
right: 8px;
max-width: none;
top: 96px;
}

.alert {
margin: 0;
}
}

@media (max-width: 480px) {
.alerts-container {
top: 96px;
}
}
</style>

<!-- Session Success Messages -->
@if(session('success'))
<div class="alert alert-success">
<p class="alert-message">{{ session('success') }}</p>
</div>
@endif

<!-- Session Status Messages -->
@if(session('status'))
<div class="alert alert-info">
<p class="alert-message">{{ session('status') }}</p>
</div>
@endif

<!-- Session Error Messages -->
@if(session('error'))
<div class="alert alert-danger">
<p class="alert-message">{{ session('error') }}</p>
</div>
@endif

<!-- Session Info Messages -->
@if(session('info'))
<div class="alert alert-info">
<p class="alert-message">{{ session('info') }}</p>
</div>
@endif

<!-- Validation Error Messages (First Error Only) -->
@if ($errors->any())
<div class="alert alert-danger">
<p class="alert-message">{{ $errors->first() }}</p>
</div>
@endif

</div>
