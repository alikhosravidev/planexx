{{-- PWA App Layout Component --}}
@props([
    'title' => 'Planexx',
    'showBackButton' => false,
    'showHeader' => true,
    'headerActions' => null,
    'currentTab' => 'home',
])

<!DOCTYPE html>
<html dir="rtl" lang="fa">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="theme-color" content="#0f172a">
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
    {{--<link rel="icon" type="image/png" sizes="192x192" href="/icons/icon-192x192.png">
    <link rel="icon" type="image/png" sizes="512x512" href="/icons/icon-512x512.png">
    <link rel="apple-touch-icon" href="/icons/icon-192x192.png">--}}

    <!-- Ziggy Routes -->
    @routes

    <!-- Font Awesome - آیکون‌ها -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Vite Assets - PWA Resources -->
    @vite([
        'Applications/PWA/Resources/css/app.css',
        'Applications/PWA/Resources/js/app.js'
    ])

    {{ $head ?? '' }}
</head>

<body class="bg-gray-100">
    <!-- Offline Indicator -->
    <div id="offline-indicator" class="offline-indicator hidden">
        <i class="fa-solid fa-wifi-slash"></i>
        <span>شما آفلاین هستید</span>
    </div>

    <!-- App Container -->
    <div class="max-w-[480px] mx-auto bg-gray-100 min-h-screen shadow-xl relative pb-24">

        @if($showHeader && !isset($customHeader))
            <!-- Default Header -->
            <header class="pwa-header bg-white border-b border-gray-200">
                <div class="flex items-center justify-between px-5 py-4">
                    <div class="flex items-center gap-3">
                        @if($showBackButton)
                            <button onclick="history.back()" class="text-slate-500 hover:text-slate-900">
                                <i class="fa-solid fa-arrow-right text-xl"></i>
                            </button>
                        @endif
                        <h1 class="text-lg font-bold text-slate-900">{{ $title }}</h1>
                    </div>

                    @if($headerActions)
                        <div class="flex items-center gap-3">
                            {{ $headerActions }}
                        </div>
                    @endif
                </div>
            </header>
        @endif

        <!-- Custom Header Slot -->
        {{ $customHeader ?? '' }}

        <!-- PWA Content Area -->
        <main>
            {{ $slot }}
        </main>

        <!-- PWA Bottom Navigation -->
        <x-pwa::bottom-nav :current-tab="$currentTab" />

    </div>

    <!-- Install Prompt -->
    <div id="pwa-install-prompt" class="install-prompt hidden">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <h3 class="font-semibold text-lg mb-1">نصب اپلیکیشن</h3>
                <p class="text-sm text-slate-500">برای دسترسی سریع‌تر، اپلیکیشن را نصب کنید</p>
            </div>
            <button id="pwa-install-button" class="btn-pwa bg-slate-900 text-white mr-4">
                نصب
            </button>
        </div>
    </div>

    @stack('scripts')
    {{ $scripts ?? '' }}
</body>
</html>
