@props([
    'id' => '',
    'label' => '',
    'icon' => 'fa-folder',
    'color' => 'blue',
    'children' => [],
    'isExpanded' => false,
])

@php
$colorClasses = [
    'purple' => 'text-purple-500',
    'pink' => 'text-pink-500',
    'green' => 'text-green-500',
    'blue' => 'text-blue-500',
    'slate' => 'text-slate-500',
    'amber' => 'text-amber-500',
];
$iconColor = $colorClasses[$color] ?? 'text-gray-500';
@endphp

<div class="folder-item" data-folder="{{ $id }}">
    <button 
        class="w-full flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium text-text-secondary hover:bg-bg-secondary hover:text-text-primary transition-all duration-200"
        data-folder-toggle="{{ $id }}">
        <i class="fa-solid fa-chevron-left text-[10px] text-text-muted transition-transform duration-200 {{ $isExpanded ? '-rotate-90' : '' }}" data-folder-arrow></i>
        <i class="fa-solid {{ $icon }} {{ $iconColor }} w-5 text-center"></i>
        <span class="leading-normal flex-1 text-right">{{ $label }}</span>
        @if(!empty($children))
            <span class="text-xs text-text-muted">{{ count($children) }}</span>
        @endif
    </button>
    
    @if(!empty($children))
        <div class="folder-children pr-8 mt-1 space-y-0.5 {{ $isExpanded ? '' : 'hidden' }}" data-folder-children="{{ $id }}">
            @foreach($children as $child)
                <a href="{{ $child['url'] ?? '#' }}" class="flex items-center gap-2 px-3 py-1.5 rounded-md text-sm transition-all duration-200 text-text-muted hover:bg-bg-secondary hover:text-text-secondary">
                    <i class="fa-solid {{ $child['icon'] ?? 'fa-folder' }} w-4 text-center text-xs"></i>
                    <span class="leading-normal">{{ $child['label'] }}</span>
                </a>
            @endforeach
        </div>
    @endif
</div>

@once
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('[data-folder-toggle]').forEach(button => {
        button.addEventListener('click', function() {
            const folderId = this.dataset.folderToggle;
            const arrow = this.querySelector('[data-folder-arrow]');
            const children = document.querySelector(`[data-folder-children="${folderId}"]`);
            
            if (children) {
                children.classList.toggle('hidden');
                arrow.classList.toggle('-rotate-90');
            }
        });
    });
});
</script>
@endpush
@endonce
