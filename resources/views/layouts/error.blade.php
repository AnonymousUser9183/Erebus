<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', config('app.name')) - Error</title>
<link rel="icon" type="image/x-icon" href="{{ asset('images/favicon.ico') }}">

<!-- Design System & Error CSS -->
<link href="{{ asset('css/errors.css') }}" rel="stylesheet">
</head>
<body>
@yield('content')
</body>
</html>
