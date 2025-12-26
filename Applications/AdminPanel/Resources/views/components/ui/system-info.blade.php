@props([
    'title' => 'اطلاعات سیستمی',
    'createdAt' => null,
    'lastLoginAt' => null,
    'mobileVerified' => false,
    'emailVerified' => false,
])

<div class="bg-bg-primary border border-border-light rounded-2xl p-6 mb-6">
    <h2 class="text-lg font-semibold text-text-primary leading-snug mb-6">{{ $title }}</h2>
    <div class="space-y-4">
        <div>
            <p class="text-sm text-text-secondary mb-1 leading-normal">تاریخ ثبت‌نام</p>
            <p class="text-base text-text-primary font-medium leading-normal">{{ $createdAt ?? '-' }}</p>
        </div>

        <div>
            <p class="text-sm text-text-secondary mb-1 leading-normal">آخرین ورود</p>
            <p class="text-base text-text-primary font-medium leading-normal">{{ $lastLoginAt ?? '-' }}</p>
        </div>

        <div>
            <p class="text-sm text-text-secondary mb-2 leading-normal">وضعیت تایید</p>
            <div class="space-y-2">
                <div class="flex items-center gap-2">
                    <i class="fa-solid {{ $mobileVerified ? 'fa-circle-check text-green-600' : 'fa-circle-xmark text-red-600' }}"></i>
                    <span class="text-sm text-text-primary leading-normal">{{ $mobileVerified ? 'موبایل تایید شده' : 'موبایل تایید نشده' }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="fa-solid {{ $emailVerified ? 'fa-circle-check text-green-600' : 'fa-circle-xmark text-red-600' }}"></i>
                    <span class="text-sm text-text-primary leading-normal">{{ $emailVerified ? 'ایمیل تایید شده' : 'ایمیل تایید نشده' }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
