<div class="bg-bg-primary border border-border-light rounded-2xl p-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-base font-semibold text-text-primary leading-snug">پیش‌نمایش فرایند</h3>
        <div class="flex items-center gap-1.5">
            @if(isset($workflow['id']))
                <span class="text-[10px] bg-gray-100 text-gray-600 px-1.5 py-0.5 rounded font-mono">#{{ $workflow['id'] }}</span>
            @endif
            <span id="statesCountBadge" class="text-[10px] bg-indigo-50 text-indigo-600 px-1.5 py-0.5 rounded">{{ !empty($workflow['states'] ?? []) ? count($workflow['states']) : 0 }} مرحله</span>
        </div>
    </div>

    <div id="statesPreview">
        <h4 class="text-xs font-medium text-text-muted mb-3 leading-normal">مراحل فرایند:</h4>
        <div id="previewContainer" class="space-y-2"></div>
        <div id="emptyPreview" class="text-center py-6 text-text-muted hidden">
            <i class="fa-solid fa-layer-group text-2xl mb-2 opacity-30"></i>
            <p class="text-xs">مرحله‌ای تعریف نشده</p>
        </div>
    </div>
</div>
