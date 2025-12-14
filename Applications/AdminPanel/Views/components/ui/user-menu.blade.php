@props([
    'userName' => null,
    'userRole' => null,
    'userAvatar' => null,
    'notificationCount' => 3,
])

@php
    $user = auth()->user();
    $userName = $userName ?? ($user?->full_name ?? $user?->name ?? 'کاربر');
    $userRole = $userRole ?? ($user?->role_name ?? 'مدیر سیستم');
    $userAvatar = $userAvatar ?? ($user->avatar?->file_url ?? null);
@endphp

<div class="relative" data-dropdown-container {{ $attributes }}>
    <button
        data-dropdown-toggle="user-menu"
        class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-bg-secondary transition-all duration-200">

        <x-panel::ui.avatar :name="$userName" :image="$userAvatar" size="md" />

        <div class="text-right hidden sm:block">
            <div class="text-sm font-medium text-text-primary leading-tight">{{ $userName }}</div>
            <div class="text-xs text-text-muted leading-tight">{{ $userRole }}</div>
        </div>

        <i class="fa-solid fa-chevron-down text-xs text-text-muted"></i>
    </button>

    <div
        id="user-menu"
        data-dropdown
        class="hidden absolute top-full left-0 mt-2 w-56 bg-bg-primary border border-border-light rounded-xl shadow-lg overflow-hidden z-50">

        <div class="sm:hidden px-4 py-3 border-b border-border-light">
            <div class="text-sm font-medium text-text-primary leading-tight">{{ $userName }}</div>
            <div class="text-xs text-text-muted leading-tight mt-0.5">{{ $userRole }}</div>
        </div>

        <div class="pt-3">
            <a href="#" class="flex items-center gap-3 px-4 py-2.5 text-sm text-text-secondary hover:bg-bg-secondary hover:text-text-primary transition-colors leading-normal">
                <i class="fa-solid fa-user w-5 text-center"></i>
                <span>پروفایل من</span>
            </a>

            <a href="#" class="flex items-center gap-3 px-4 py-2.5 text-sm text-text-secondary hover:bg-bg-secondary hover:text-text-primary transition-colors leading-normal">
                <i class="fa-solid fa-cog w-5 text-center"></i>
                <span>تنظیمات</span>
            </a>

            <a href="#" class="flex items-center gap-3 px-4 py-2.5 text-sm text-text-secondary hover:bg-bg-secondary hover:text-text-primary transition-colors leading-normal">
                <i class="fa-solid fa-bell w-5 text-center"></i>
                <span>اعلان‌ها</span>
                @if(isset($notificationCount) && $notificationCount > 0)
                    <span class="mr-auto bg-red-500 text-white text-xs px-1.5 py-0.5 rounded-full">{{ $notificationCount }}</span>
                @endif
            </a>

            <div class="border-t border-border-light my-2"></div>

            <a href="#" class="flex items-center gap-3 px-4 py-2.5 text-sm text-text-secondary hover:bg-bg-secondary hover:text-text-primary transition-colors leading-normal">
                <i class="fa-solid fa-question-circle w-5 text-center"></i>
                <span>راهنما و پشتیبانی</span>
            </a>

            <div class="border-t border-border-light mt-2"></div>

            <button
                type="button"
                data-ajax
                data-action="{{ route('logout') }}"
                data-method="POST"
                data-on-success="reload"
                class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors leading-normal">
                <span>خروج از حساب کاربری</span>
            </button>
        </div>
    </div>
</div>
