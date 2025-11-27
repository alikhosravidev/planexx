@props([
    'roleName' => 'نقش',
    'usersCount' => null,
])

<div class="bg-bg-primary border border-border-light rounded-2xl p-6 mb-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 bg-primary/10 rounded-xl flex items-center justify-center">
                <i class="fa-solid fa-shield-halved text-2xl text-primary"></i>
            </div>
            <div>
                <h2 class="text-xl font-bold text-text-primary leading-snug">{{ $roleName }}</h2>
                @if(!is_null($usersCount))
                <p class="text-sm text-text-secondary leading-normal mt-1">
                    <i class="fa-solid fa-users ml-1"></i>
                    {{ $usersCount }} کاربر با این نقش
                </p>
                @endif
            </div>
        </div>
        <div class="flex items-center gap-3">
            <button onclick="selectAllPermissions()" class="bg-bg-secondary text-text-secondary border border-border-medium px-4 py-2.5 rounded-lg font-medium hover:bg-gray-100 transition-all duration-200 text-sm leading-normal flex items-center gap-2">
                <i class="fa-solid fa-check-double"></i>
                <span>انتخاب همه</span>
            </button>
            <button onclick="deselectAllPermissions()" class="bg-bg-secondary text-text-secondary border border-border-medium px-4 py-2.5 rounded-lg font-medium hover:bg-gray-100 transition-all duration-200 text-sm leading-normal flex items-center gap-2">
                <i class="fa-solid fa-xmark"></i>
                <span>حذف انتخاب‌ها</span>
            </button>
        </div>
    </div>
</div>
