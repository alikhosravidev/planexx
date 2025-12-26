{{-- PWA App Layout Component --}}
@props([
    'title' => 'Planexx',
    'showBackButton' => false,
    'headerActions' => null,
])

<!DOCTYPE html>
<html dir="rtl" lang="fa">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="theme-color" content="#3b82f6">
    <title>{{ $title }} | Planexx PWA</title>
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

    <!-- PWA Header -->
    <header class="pwa-header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                @if($showBackButton)
                    <button onclick="history.back()" class="text-text-secondary hover:text-primary">
                        <i class="fas fa-arrow-right text-xl"></i>
                    </button>
                @endif
                <h1 class="text-lg font-semibold">{{ $title }}</h1>
            </div>

            @if($headerActions)
                <div class="flex items-center gap-3">
                    {{ $headerActions }}
                </div>
            @endif
        </div>
    </header>

    <!-- PWA Content Area -->
    <main class="pwa-content">
        {{ $slot }}
    </main>

    <!-- PWA Bottom Navigation -->
    <nav class="pwa-nav">
        <div class="flex items-center justify-around">
            <a href="{{ route('pwa.dashboard') }}" class="pwa-nav-item">
                <i class="fas fa-home text-xl"></i>
                <span class="text-xs">خانه</span>
            </a>

            <a href="#" class="pwa-nav-item">
                <i class="fas fa-tasks text-xl"></i>
                <span class="text-xs">وظایف</span>
            </a>

            <a href="#" class="pwa-nav-item">
                <i class="fas fa-bell text-xl"></i>
                <span class="text-xs">اعلان‌ها</span>
            </a>

            <a href="#" class="pwa-nav-item">
                <i class="fas fa-user text-xl"></i>
                <span class="text-xs">پروفایل</span>
            </a>
        </div>
    </nav>

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
