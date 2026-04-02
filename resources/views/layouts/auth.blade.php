<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'Erebus Marketplace Script')</title>
<link rel="icon" type="image/x-icon" href="{{ asset('images/favicon.ico') }}">
<style>
* {
margin: 0;
padding: 0;
box-sizing: border-box;
}

:root {
--color-primary-dark: #0d5b7c;
--color-primary: #1a7a99;
--color-primary-light: #2a9db8;
--color-text-dark: #1a1a1a;
--color-text-primary: #333333;
--color-text-secondary: #666666;
--color-bg-primary: #f5f5f5;
--color-bg-secondary: #ffffff;
--color-border: #e0e0e0;
--color-danger: #dc2626;
--spacing-sm: 8px;
--spacing-md: 12px;
--spacing-lg: 16px;
--spacing-xl: 24px;
--spacing-2xl: 32px;
--radius-md: 6px;
--radius-lg: 8px;
}

html, body {
height: 100%;
font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Oxygen', 'Ubuntu', 'Cantarell', 'Fira Sans', 'Droid Sans', 'Helvetica Neue', sans-serif;
background-color: var(--color-bg-primary);
color: var(--color-text-primary);
}

body {
display: flex;
flex-direction: column;
min-height: 100vh;
padding-top: 100px;
}

/* Sponsorship Banner */
.sponsor-banner {
background: linear-gradient(90deg, #1a1a2e 0%, #16213e 50%, #1a1a2e 100%);
border-bottom: 2px solid #32b8c6;
position: fixed;
top: 0;
left: 0;
right: 0;
z-index: 1001;
height: 40px;
overflow: hidden;
display: flex;
align-items: center;
}

.sponsor-banner a {
display: flex;
align-items: center;
gap: 16px;
text-decoration: none;
white-space: nowrap;
animation: scroll-banner 20s linear infinite;
padding: 0 40px;
}

@keyframes scroll-banner {
0% {
transform: translateX(100%);
}
100% {
transform: translateX(-100%);
}
}

.sponsor-banner a:hover {
animation-play-state: paused;
}

.sponsor-banner img {
height: 28px;
width: auto;
object-fit: contain;
flex-shrink: 0;
}

.sponsor-banner-text {
color: #32b8c6;
font-size: 13px;
font-weight: 500;
letter-spacing: 0.5px;
}

.sponsor-banner-text strong {
color: #ffffff;
font-weight: 600;
}

/* Navbar */
.navbar {
background-color: var(--color-bg-secondary);
border-bottom: 1px solid var(--color-border);
position: fixed;
top: 40px;
left: 0;
right: 0;
z-index: 1000;
height: 60px;
}

.navbar-container {
max-width: 1400px;
margin: 0 auto;
height: 100%;
padding: 0 var(--spacing-lg);
display: flex;
align-items: center;
justify-content: space-between;
gap: var(--spacing-lg);
}

.navbar-left {
display: flex;
align-items: center;
gap: var(--spacing-md);
min-width: 0;
}

.navbar-logo-wrapper {
display: flex;
align-items: center;
gap: var(--spacing-md);
flex-shrink: 0;
}

.navbar-logo-img {
height: 40px;
width: auto;
object-fit: contain;
flex-shrink: 0;
}

.navbar-logo {
font-size: 24px;
font-weight: 600;
letter-spacing: 0.5px;
text-decoration: none;
background: linear-gradient(90deg, #1f3a45 0%, #32b8c6 100%);
-webkit-background-clip: text;
-webkit-text-fill-color: transparent;
background-clip: text;
transition: all 0.3s ease;
display: inline-block;
padding: 8px 0;
font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
animation: subtle-shimmer 3s ease-in-out infinite;
white-space: nowrap;
}

@keyframes subtle-shimmer {
0%, 100% {
background-position: 0%;
}
50% {
background-position: 100%;
}
}

.navbar-logo:hover {
letter-spacing: 1px;
opacity: 0.9;
filter: drop-shadow(0 0 8px rgba(50, 184, 198, 0.4));
}

.navbar-logo:focus {
outline: 2px solid #32b8c6;
outline-offset: 4px;
border-radius: 4px;
}

.navbar-right {
display: flex;
align-items: center;
gap: var(--spacing-md);
flex-shrink: 0;
}

.navbar-btn {
display: flex;
align-items: center;
gap: var(--spacing-sm);
padding: var(--spacing-sm) var(--spacing-md);
background: none;
border: none;
color: #1c767d;
text-decoration: none;
font-weight: 500;
font-size: 13px;
cursor: pointer;
border-radius: var(--radius-md);
transition: all 0.2s ease;
white-space: nowrap;
}

.navbar-btn:hover {
background-color: #f5f5f5;
color: var(--color-primary);
}

.navbar-btn.active {
color: var(--color-primary);
font-weight: 600;
}

.navbar-btn svg {
width: 16px;
height: 16px;
stroke: currentColor;
fill: none;
stroke-width: 2;
}

@media (max-width: 1024px) {
.navbar-center {
display: none;
}
}

@media (max-width: 768px) {
body {
padding-top: 86px;
}

.sponsor-banner {
height: 36px;
}

.sponsor-banner a {
gap: 12px;
padding: 0 30px;
}

.sponsor-banner img {
height: 24px;
}

.sponsor-banner-text {
font-size: 12px;
}

.navbar {
top: 36px;
height: 50px;
}

.navbar-container {
padding: 0 var(--spacing-md);
gap: var(--spacing-md);
}

.navbar-left {
gap: var(--spacing-sm);
}

.navbar-logo-img {
height: 32px;
}

.navbar-logo {
font-size: 18px;
}

.navbar-btn {
padding: var(--spacing-sm) var(--spacing-md);
font-size: 12px;
}
}

@media (max-width: 480px) {
body {
padding-top: 86px;
}

.sponsor-banner a {
gap: 10px;
padding: 0 20px;
}

.sponsor-banner img {
height: 22px;
}

.sponsor-banner-text {
font-size: 11px;
}

.navbar-logo-img {
height: 28px;
}

.navbar-logo {
font-size: 16px;
}

.navbar-btn {
padding: 6px 8px;
font-size: 11px;
}
}

/* Main Content */
main {
flex: 1;
display: flex;
align-items: center;
justify-content: center;
padding: var(--spacing-2xl) var(--spacing-lg);
}

/* Footer */
.footer {
background-color: var(--color-bg-secondary);
border-top: 1px solid var(--color-border);
padding: var(--spacing-lg);
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

.footer-sponsor img {
height: 48px;
width: auto;
object-fit: contain;
transition: opacity 0.2s ease;
}

.footer-sponsor a:hover img {
opacity: 0.8;
}

.footer-bottom {
border-top: 1px solid var(--color-border);
padding-top: var(--spacing-lg);
margin-top: var(--spacing-lg);
text-align: center;
font-size: 12px;
color: var(--color-text-secondary);
}

/* Verify Mirror Page Styles */
.verify-mirror-page {
max-width: 800px;
margin: 0 auto;
background-color: var(--color-bg-secondary);
border-radius: 8px;
padding: 32px;
box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.verify-mirror-header {
border-bottom: 2px solid #32b8c6;
padding-bottom: 20px;
margin-bottom: 24px;
}

.verify-mirror-header h1 {
color: var(--color-primary-dark);
font-size: 28px;
margin-bottom: 8px;
}

.verify-mirror-header p {
color: var(--color-text-secondary);
font-size: 14px;
margin: 0;
}

.pgp-message-box {
background-color: #f5f5f5;
border: 1px solid var(--color-border);
border-radius: 6px;
padding: 16px;
font-family: monospace;
font-size: 12px;
color: #333;
line-height: 1.6;
white-space: pre-wrap;
word-break: break-all;
margin-bottom: 20px;
max-height: 500px;
overflow-y: auto;
}

.mirror-list {
margin-top: 24px;
padding-top: 24px;
border-top: 1px solid var(--color-border);
}

.mirror-list h2 {
color: var(--color-primary-dark);
font-size: 18px;
margin-bottom: 16px;
font-weight: 600;
}

.mirror-item {
background-color: #f9f9f9;
border-left: 4px solid #32b8c6;
padding: 12px;
margin-bottom: 12px;
border-radius: 4px;
}

.mirror-item strong {
color: var(--color-primary-dark);
display: block;
margin-bottom: 8px;
}

.mirror-link {
color: #32b8c6;
text-decoration: none;
word-break: break-all;
font-family: monospace;
font-size: 12px;
}

.mirror-link:hover {
text-decoration: underline;
}

.verification-note {
background-color: #f0f9fb;
border: 1px solid #32b8c6;
padding: 12px;
border-radius: 4px;
font-size: 12px;
color: #333;
margin-top: 20px;
line-height: 1.6;
}

.verification-note strong {
color: var(--color-primary-dark);
}

.btn-group {
display: flex;
gap: 12px;
margin-top: 24px;
flex-wrap: wrap;
}

.btn {
display: inline-block;
padding: 10px 20px;
border-radius: 4px;
border: none;
text-decoration: none;
font-weight: 600;
font-size: 14px;
cursor: pointer;
transition: background-color 0.2s ease;
}

.btn-primary {
background-color: #32b8c6;
color: white;
}

.btn-primary:hover {
background-color: #2a9db8;
}

.btn-secondary {
background-color: var(--color-border);
color: var(--color-text-primary);
}

.btn-secondary:hover {
background-color: #d0d0d0;
}

.pgp-info {
background-color: #fff9f5;
border: 1px solid #e0a86b;
padding: 16px;
border-radius: 4px;
margin-bottom: 20px;
font-size: 13px;
line-height: 1.6;
}

.pgp-info strong {
color: var(--color-primary-dark);
}

@media (max-width: 768px) {
main {
padding: var(--spacing-lg) var(--spacing-md);
}

.verify-mirror-page {
padding: 20px;
margin: 0 10px;
}

.verify-mirror-header h1 {
font-size: 22px;
}

.footer-container {
grid-template-columns: 1fr;
}
}

@media (max-width: 480px) {
main {
padding: var(--spacing-lg) var(--spacing-sm);
}

.verify-mirror-page {
padding: 16px;
margin: 0 5px;
}

.verify-mirror-header h1 {
font-size: 20px;
}

.pgp-message-box {
font-size: 11px;
padding: 12px;
}

.btn-group {
flex-direction: column;
}

.btn {
width: 100%;
text-align: center;
}

.footer-container {
grid-template-columns: 1fr;
}
}
</style>
</head>
<body>
<!-- Sponsorship Banner -->
<div class="sponsor-banner">
<a href="/">
<img src="{{ asset('images/logo_original.png') }}" alt="Erebus Marketplace Script">
<span class="sponsor-banner-text">Welcome to <strong>Erebus Marketplace Script</strong> - A rewritten and upgraded version of Kabus Marketplace Script by Sukunetsiz. Running on the latest variant of Laravel 12 updated from Laravel 11 and a completely rewritten design.</span>
</a>
</div>

<!-- Navbar (Same as primary design) -->
<nav class="navbar">
<div class="navbar-container">
<div class="navbar-left">
<div class="navbar-logo-wrapper">
<img src="{{ asset('images/logo_original.png') }}" alt="Erebus Marketplace Script" class="navbar-logo-img">
<a href="{{ route('login-home') }}" class="navbar-logo">Erebus Marketplace Script</a>
</div>
</div>

<div class="navbar-right">
<a href="{{ route('guest-products.index') }}" class="navbar-btn @if(request()->routeIs('guest-products.index') || request()->routeIs('guest-products.show')) active @endif">
HOME
</a>
<a href="{{ route('login') }}" class="navbar-btn @if(request()->routeIs('login')) active @endif">
LOGIN
</a>
<a href="{{ route('register') }}" class="navbar-btn @if(request()->routeIs('register')) active @endif">
REGISTER
</a>
<a href="/verify-mirror" class="navbar-btn @if(request()->path() === 'verify-mirror') active @endif">
VERIFY URL
</a>
<a href="{{ route('harm-reduction') }}" class="navbar-btn @if(request()->path() == 'harm-reduction') active @endif">
HARM REDUCTION
</a>
</div>
</div>
</nav>

<!-- Alerts Component -->
@include('components.alerts')

<!-- Main Content -->
<main>
@yield('content')
</main>

<!-- Footer -->
<footer class="footer">
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
<a href="http://stopcpozlbfa4s4fuhisthyaivzbewe2rihsadarlkvqv5gaphkrvvyd.onion/" target="_blank" rel="noopener noreferrer" title="Support The StopCP Initiative">
<img src="{{ asset('images/stopcp.png') }}" alt="StopCP Initiative">
</a>
</div>
<p style="font-size: 11px; color: var(--color-text-secondary); text-align: center;">Support The StopCP Initiative</p>
</div>
</div>

<div class="footer-bottom">
<p style="margin: 0;">© 2025 Erebus Marketplace Script Market. All rights reserved.</p>
</div>
</footer>
</body>
</html>
