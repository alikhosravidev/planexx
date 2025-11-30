@props([
    'items',
    'title' => 'آخرین فعالیت‌ها',
])

<div class="bg-white border border-border-light rounded-2xl p-6">
    <h2 class="text-lg font-semibold text-text-primary leading-snug mb-6">{{ $title }}</h2>
    <div class="space-y-4">
        @foreach($items as $act)
            <div class="flex items-start gap-3 pb-4 border-b border-border-light last:border-0 last:pb-0">
                <div class="w-8 h-8 {{ $act['icon_bg'] }} rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="{{ $act['icon'] }} text-sm"></i>
                </div>
                <div class="flex-1">
                    <p class="text-sm text-text-primary leading-normal mb-1">{{ $act['title'] }}</p>
                    <p class="text-xs text-text-muted leading-normal">{{ $act['desc'] }}</p>
                </div>
                <span class="text-xs text-text-muted leading-normal">{{ $act['time'] }}</span>
            </div>
        @endforeach
    </div>
</div>
