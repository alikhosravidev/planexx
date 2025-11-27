@props([
    'userName' => 'کاربر',
    'userRole' => 'کاربر سیستم',
    'userAvatar' => null,
])

<div class="relative" data-dropdown-container {{ $attributes }}>
    <button
        data-dropdown-toggle="user-menu"
        class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-bg-secondary transition-all duration-200">

        <x-ui.avatar :name="$userName" :image="$userAvatar" size="md" />

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

        <div class="py-2">
            <a href="{{ route('dashboard.profile') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-text-secondary hover:bg-bg-secondary hover:text-text-primary transition-colors leading-normal">
                <i class="fa-solid fa-user w-5 text-center"></i>
                <span>پروفایل من</span>
            </a>

            <a href="{{ route('dashboard.settings') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-text-secondary hover:bg-bg-secondary hover:text-text-primary transition-colors leading-normal">
                <i class="fa-solid fa-cog w-5 text-center"></i>
                <span>تنظیمات</span>
            </a>

            <a href="{{ route('dashboard.notifications') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-text-secondary hover:bg-bg-secondary hover:text-text-primary transition-colors leading-normal">
                <i class="fa-solid fa-bell w-5 text-center"></i>
                <span>اعلان‌ها</span>
                @if(isset($notificationCount) && $notificationCount > 0)
                    <span class="mr-auto bg-red-500 text-white text-xs px-1.5 py-0.5 rounded-full">{{ $notificationCount }}</span>
                @endif
            </a>

            <div class="border-t border-border-light my-2"></div>

            <a href="{{ route('help') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-text-secondary hover:bg-bg-secondary hover:text-text-primary transition-colors leading-normal">
                <i class="fa-solid fa-question-circle w-5 text-center"></i>
                <span>راهنما و پشتیبانی</span>
            </a>

            <div class="border-t border-border-light my-2"></div>

            <form method="POST" action="">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors leading-normal">
                    <i class="fa-solid fa-sign-out-alt w-5 text-center"></i>
                    <span>خروج از حساب کاربری</span>
                </button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggleBtn = document.querySelector('[data-dropdown-toggle="user-menu"]');
    const dropdown = document.querySelector('#user-menu');

    if (toggleBtn && dropdown) {
        toggleBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            dropdown.classList.toggle('hidden');
        });

        document.addEventListener('click', () => {
            dropdown.classList.add('hidden');
        });

        dropdown.addEventListener('click', (e) => {
            e.stopPropagation();
        });
    }
});
</script>
@endpush
