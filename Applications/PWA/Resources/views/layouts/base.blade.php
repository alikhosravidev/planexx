<!DOCTYPE html>
<html dir="rtl" lang="fa">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="theme-color" content="#3b82f6">
    <title>@yield('title') | Planexx PWA</title>
    <meta name="description" content="سیستم مدیریت هوشمند پروژه - نسخه موبایل">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- PWA Meta Tags -->
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Planexx">

    <!-- Manifest -->
    <link rel="manifest" href="/manifest.json">

    <!-- Icons -->
    <link rel="icon" type="image/png" sizes="192x192" href="/icons/icon-192x192.png">
    <link rel="icon" type="image/png" sizes="512x512" href="/icons/icon-512x512.png">
    <link rel="apple-touch-icon" href="/icons/icon-192x192.png">

    <!-- Ziggy Routes -->
    @routes

    <!-- Vite Assets - PWA Resources -->
    @vite([
        'resources/css/shared.css',
        'Applications/PWA/Resources/css/app.css',
        'Applications/PWA/Resources/js/app.js'
    ])
</head>

<body class="bg-bg-secondary">
    <!-- Offline Indicator -->
    <div id="offline-indicator" class="offline-indicator hidden">
        <i class="fas fa-wifi-slash"></i>
        <span>شما آفلاین هستید</span>
    </div>

    <!-- Pull to Refresh Indicator -->
    <div id="pull-to-refresh" class="pull-to-refresh">
        <div class="pwa-loading"></div>
    </div>

    <!-- Main Content -->
    @yield('content')

    <!-- Install Prompt -->
    <div id="pwa-install-prompt" class="install-prompt hidden">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <h3 class="font-semibold text-lg mb-1">نصب اپلیکیشن</h3>
                <p class="text-sm text-text-secondary">برای دسترسی سریع‌تر، اپلیکیشن را نصب کنید</p>
            </div>
            <button id="pwa-install-button" class="btn-pwa bg-primary text-white mr-4">
                نصب
            </button>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
