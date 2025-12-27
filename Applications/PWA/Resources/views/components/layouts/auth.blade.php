@props(['title' => 'ورود'])

<!DOCTYPE html>
<html dir="rtl" lang="fa">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="theme-color" content="#0f172a">
    <title>{{ $title }} | Planexx PWA</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">

    @routes

    <!-- Font Awesome - آیکون‌ها -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    @vite([
        'Applications/PWA/Resources/css/app.css',
        'Applications/PWA/Resources/js/app.js'
    ])
</head>
<body class="bg-gray-100">
    <div class="max-w-[480px] mx-auto bg-white min-h-screen shadow-xl">
        <div class="min-h-screen flex items-center justify-center p-4">
            <div class="w-full max-w-md">
                {{ $slot }}
            </div>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
