@props([
    'colspan' => 1,
    'icon' => 'fa-inbox',
    'message' => 'داده‌ای یافت نشد',
    'description' => null,
    'actionText' => null,
    'actionUrl' => null,
])

<tr>
    <td colspan="{{ $colspan }}" class="px-6 py-16">
        <div class="flex flex-col items-center justify-center text-text-muted">
            <div class="w-20 h-20 rounded-full bg-bg-secondary flex items-center justify-center mb-4">
                <i class="fa-solid {{ $icon }} text-3xl opacity-40"></i>
            </div>
            
            <p class="text-base font-medium text-text-secondary mb-1">{{ $message }}</p>
            
            @if($description)
                <p class="text-sm text-text-muted">{{ $description }}</p>
            @endif
            
            @if($actionText && $actionUrl)
                <a href="{{ $actionUrl }}" class="mt-4 inline-flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors">
                    <i class="fa-solid fa-plus"></i>
                    {{ $actionText }}
                </a>
            @endif
            
            @if($slot->isNotEmpty())
                <div class="mt-4">
                    {{ $slot }}
                </div>
            @endif
        </div>
    </td>
</tr>
