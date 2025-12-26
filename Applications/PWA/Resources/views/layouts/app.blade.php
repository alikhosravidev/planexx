@extends('pwa::layouts.base')

@section('title', $title ?? 'داشبورد')

@section('content')
    <!-- PWA Header -->
    <header class="pwa-header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                @if(isset($showBackButton) && $showBackButton)
                    <button onclick="history.back()" class="text-text-secondary hover:text-primary">
                        <i class="fas fa-arrow-right text-xl"></i>
                    </button>
                @endif
                <h1 class="text-lg font-semibold">{{ $title ?? 'Planexx' }}</h1>
            </div>

            <div class="flex items-center gap-3">
                @if(isset($headerActions))
                    {{ $headerActions }}
                @endif
            </div>
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
@endsection
