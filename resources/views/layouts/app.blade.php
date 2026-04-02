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
--color-success: #16a34a;
--color-warning: #ea580c;
--color-info: #0084ff;
--spacing-xs: 4px;
--spacing-sm: 8px;
--spacing-md: 12px;
--spacing-lg: 16px;
--spacing-xl: 24px;
--spacing-2xl: 32px;
--radius-sm: 4px;
--radius-md: 6px;
--radius-lg: 8px;
--shadow-sm: 0 1px 2px rgba(0, 0, 0, 0.05);
--shadow-md: 0 4px 6px rgba(0, 0, 0, 0.07);
--shadow-lg: 0 10px 15px rgba(0, 0, 0, 0.1);
}

html, body {
height: 100%;
font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Oxygen', 'Ubuntu', 'Cantarell', 'Fira Sans', 'Droid Sans', 'Helvetica Neue', sans-serif;
background-color: var(--color-bg-primary);
color: var(--color-text-primary);
}

#app {
display: flex;
flex-direction: column;
min-height: 100vh;
}

main {
flex: 1;
display: flex;
width: 100%;
margin-top: 100px;
}

@media (max-width: 768px) {
main {
margin-top: 86px;
}
}

@media (max-width: 480px) {
main {
margin-top: 86px;
}
}

.main-container {
display: flex;
width: 100%;
flex: 1;
}

.main-left {
width: 200px;
background-color: var(--color-bg-secondary);
border-right: 1px solid var(--color-border);
overflow-y: auto;
position: sticky;
top: 60px;
height: calc(100vh - 60px);
}

.main-center {
flex: 1;
overflow-y: auto;
position: relative;
}

.main-right {
width: 200px;
background-color: var(--color-bg-secondary);
border-left: 1px solid var(--color-border);
overflow-y: auto;
position: sticky;
top: 60px;
height: calc(100vh - 60px);
}

@media (max-width: 1200px) {
.main-left,
.main-right {
width: 180px;
}
}

@media (max-width: 768px) {
.main-container {
flex-direction: column;
}

.main-left,
.main-right {
width: 100%;
height: auto;
position: static;
border-right: none;
border-left: none;
border-bottom: 1px solid var(--color-border);
}

.main-left {
order: 3;
}

.main-center {
order: 1;
}

.main-right {
order: 2;
}
}

/* Scrollbar styling */
::-webkit-scrollbar {
width: 8px;
height: 8px;
}

::-webkit-scrollbar-track {
background: transparent;
}

::-webkit-scrollbar-thumb {
background: #ccc;
border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
background: #999;
}

footer {
margin-top: auto;
}
</style>
</head>
<body>
<div id="app">
@include('components.navbar')
@include('components.alerts')

<main>
<div class="main-center">
@yield('content')
</div>
</main>

@include('components.footer')
</div>
</body>
</html>
