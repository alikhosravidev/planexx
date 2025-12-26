<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name') }}</title>

    @routes

    @vite([
        'resources/css/shared.css',
        'Applications/AdminPanel/Resources/css/app.css',
        'Applications/AdminPanel/Resources/js/app.js'
    ])

    {{ $head ?? '' }}
</head>
<body class="bg-bg-secondary">
    {{ $slot }}

    @stack('scripts')
    {{ $scripts ?? '' }}
</body>
</html>
