<x-pwa::layouts.app title="داشبورد">
    <div class="pwa-container py-4">
        <!-- Welcome Section -->
        <div class="pwa-card mb-4">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center">
                    <i class="fas fa-user text-primary text-xl"></i>
                </div>
                <div>
                    <h2 class="font-semibold text-lg">سلام، {{ auth()->user()->name ?? 'کاربر' }}</h2>
                    <p class="text-sm text-text-secondary">به پنل خود خوش آمدید</p>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        @if(isset($stats) && count($stats) > 0)
        <div class="grid grid-cols-2 gap-4 mb-6">
            @foreach($stats as $stat)
            <div class="pwa-card">
                <div class="flex flex-col items-center text-center">
                    <div class="w-12 h-12 rounded-full bg-{{ $stat['color'] ?? 'primary' }}/10 flex items-center justify-center mb-2">
                        <i class="fas fa-{{ $stat['icon'] ?? 'chart-line' }} text-{{ $stat['color'] ?? 'primary' }} text-xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-text-primary mb-1">{{ $stat['value'] ?? '0' }}</h3>
                    <p class="text-sm text-text-secondary">{{ $stat['label'] ?? '' }}</p>
                </div>
            </div>
            @endforeach
        </div>
        @endif

        <!-- Quick Access -->
        @if(isset($quickAccessModules) && count($quickAccessModules) > 0)
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-text-primary mb-3">دسترسی سریع</h3>
            <div class="grid grid-cols-3 gap-3">
                @foreach($quickAccessModules as $module)
                <a href="{{ $module['url'] ?? '#' }}" class="pwa-card text-center hover:bg-bg-secondary transition">
                    <div class="w-10 h-10 rounded-lg bg-{{ $module['color'] ?? 'primary' }}/10 flex items-center justify-center mx-auto mb-2">
                        <i class="fas fa-{{ $module['icon'] ?? 'cube' }} text-{{ $module['color'] ?? 'primary' }}"></i>
                    </div>
                    <p class="text-xs text-text-secondary">{{ $module['label'] ?? '' }}</p>
                </a>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Recent Activities -->
        <div class="mb-6">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-lg font-semibold text-text-primary">فعالیت‌های اخیر</h3>
                <a href="#" class="text-sm text-primary">مشاهده همه</a>
            </div>

            <div class="space-y-2">
                @for($i = 0; $i < 5; $i++)
                <div class="pwa-list-item">
                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center ml-3">
                        <i class="fas fa-file-alt text-blue-600"></i>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-medium text-text-primary">عنوان فعالیت {{ $i + 1 }}</h4>
                        <p class="text-sm text-text-secondary">{{ $i + 1 }} ساعت پیش</p>
                    </div>
                    <i class="fas fa-chevron-left text-text-secondary"></i>
                </div>
                @endfor
            </div>
        </div>
    </div>
</x-pwa::layouts.app>
