@props([
    'module' => [],
    'standardPermissions' => [],
])

@php
$moduleColors = [
    'blue' => [
        'bg' => 'bg-blue-50',
        'border' => 'border-blue-200',
        'hover' => 'hover:border-blue-300',
        'icon' => 'text-blue-600',
        'iconBg' => 'bg-blue-100',
        'header' => 'bg-blue-50/80',
        'headerText' => 'text-blue-700',
        'checkbox' => 'accent-blue-600',
    ],
    'amber' => [
        'bg' => 'bg-amber-50',
        'border' => 'border-amber-200',
        'hover' => 'hover:border-amber-300',
        'icon' => 'text-amber-600',
        'iconBg' => 'bg-amber-100',
        'header' => 'bg-amber-50/80',
        'headerText' => 'text-amber-700',
        'checkbox' => 'accent-amber-600',
    ],
    'stone' => [
        'bg' => 'bg-stone-50',
        'border' => 'border-stone-200',
        'hover' => 'hover:border-stone-300',
        'icon' => 'text-stone-600',
        'iconBg' => 'bg-stone-200',
        'header' => 'bg-stone-100/80',
        'headerText' => 'text-stone-700',
        'checkbox' => 'accent-stone-600',
    ],
    'teal' => [
        'bg' => 'bg-teal-50',
        'border' => 'border-teal-200',
        'hover' => 'hover:border-teal-300',
        'icon' => 'text-teal-600',
        'iconBg' => 'bg-teal-100',
        'header' => 'bg-teal-50/80',
        'headerText' => 'text-teal-700',
        'checkbox' => 'accent-teal-600',
    ],
    'green' => [
        'bg' => 'bg-green-50',
        'border' => 'border-green-200',
        'hover' => 'hover:border-green-300',
        'icon' => 'text-green-600',
        'iconBg' => 'bg-green-100',
        'header' => 'bg-green-50/80',
        'headerText' => 'text-green-700',
        'checkbox' => 'accent-green-600',
    ],
    'purple' => [
        'bg' => 'bg-purple-50',
        'border' => 'border-purple-200',
        'hover' => 'hover:border-purple-300',
        'icon' => 'text-purple-600',
        'iconBg' => 'bg-purple-100',
        'header' => 'bg-purple-50/80',
        'headerText' => 'text-purple-700',
        'checkbox' => 'accent-purple-600',
    ],
];
$colorKey = $module['color'] ?? 'blue';
$colors = $moduleColors[$colorKey] ?? $moduleColors['blue'];
$entities = $module['entities'] ?? [];
@endphp

<div class="bg-bg-primary border {{ $colors['border'] }} rounded-2xl overflow-hidden {{ $colors['hover'] }} transition-all duration-200">
    <div class="{{ $colors['header'] }} border-b {{ $colors['border'] }} px-6 py-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 {{ $colors['iconBg'] }} rounded-lg flex items-center justify-center">
                    <i class="{{ $module['icon'] ?? 'fa-solid fa-cube' }} text-lg {{ $colors['icon'] }}"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-text-primary leading-snug">{{ $module['name'] ?? '' }}</h3>
                    <p class="text-xs text-text-secondary leading-normal">{{ count($entities) }} موجودیت</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <button onclick="toggleModulePermissions('{{ $module['id'] ?? '' }}', true)" class="text-xs text-text-secondary hover:text-primary transition-colors duration-200">انتخاب همه</button>
                <span class="text-border-medium">|</span>
                <button onclick="toggleModulePermissions('{{ $module['id'] ?? '' }}', false)" class="text-xs text-text-secondary hover:text-red-600 transition-colors duration-200">حذف انتخاب</button>
                <button onclick="toggleModule('{{ $module['id'] ?? '' }}')" class="w-8 h-8 flex items-center justify-center text-text-muted hover:text-primary hover:bg-white/50 rounded-lg transition-all duration-200">
                    <i class="fa-solid fa-chevron-down module-toggle-icon transition-transform duration-200" id="icon-{{ $module['id'] ?? '' }}"></i>
                </button>
            </div>
        </div>
    </div>

    <div class="module-content" id="content-{{ $module['id'] ?? '' }}">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="{{ $colors['bg'] }} border-b {{ $colors['border'] }}">
                        <th class="px-6 py-3 text-right text-sm font-semibold text-text-primary leading-normal min-w-[180px]">
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-cube text-text-muted"></i>
                                موجودیت
                            </div>
                        </th>
                        @foreach($standardPermissions as $perm)
                            <th class="px-3 py-3 text-center text-xs font-medium {{ $colors['headerText'] }} leading-normal min-w-[90px]">
                                <div class="flex flex-col items-center gap-1">
                                    @if(!empty($perm['icon']))
                                    <i class="{{ $perm['icon'] }} text-sm opacity-70"></i>
                                    @endif
                                    <span>{{ $perm['name'] ?? '' }}</span>
                                </div>
                            </th>
                        @endforeach
                        <th class="px-3 py-3 text-center text-xs font-medium text-text-secondary leading-normal min-w-[70px]">
                            <span>عملیات</span>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($entities as $entity)
                        <tr class="border-b border-border-light last:border-0 hover:bg-bg-secondary/50 transition-colors duration-200">
                            <td class="px-6 py-4">
                                <span class="text-sm font-medium text-text-primary leading-normal">{{ $entity['name'] ?? '' }}</span>
                            </td>
                            @foreach($standardPermissions as $permKey => $perm)
                                @php $checked = data_get($entity, 'permissions.' . $permKey, false); @endphp
                                <td class="px-3 py-4 text-center">
                                    <label class="inline-flex items-center justify-center cursor-pointer">
                                        <input type="checkbox"
                                            name="permissions[{{ $module['id'] ?? '' }}][{{ $entity['id'] ?? '' }}][{{ $permKey }}]"
                                            value="1"
                                            data-module="{{ $module['id'] ?? '' }}"
                                            data-entity="{{ ($module['id'] ?? '') . '_' . ($entity['id'] ?? '') }}"
                                            data-permission="{{ $permKey }}"
                                            class="w-4 h-4 {{ $colors['checkbox'] }} rounded border-border-medium cursor-pointer transition-all duration-200"
                                            @checked($checked) />
                                    </label>
                                </td>
                            @endforeach
                            <td class="px-3 py-4 text-center">
                                <div class="flex items-center justify-center gap-1">
                                    <button onclick="toggleEntityPermissions('{{ ($module['id'] ?? '') . '_' . ($entity['id'] ?? '') }}', true)"
                                        class="w-7 h-7 flex items-center justify-center text-text-muted hover:text-green-600 hover:bg-green-50 rounded transition-all duration-200"
                                        title="انتخاب همه">
                                        <i class="fa-solid fa-check-double text-xs"></i>
                                    </button>
                                    <button onclick="toggleEntityPermissions('{{ ($module['id'] ?? '') . '_' . ($entity['id'] ?? '') }}', false)"
                                        class="w-7 h-7 flex items-center justify-center text-text-muted hover:text-red-600 hover:bg-red-50 rounded transition-all duration-200"
                                        title="حذف انتخاب">
                                        <i class="fa-solid fa-xmark text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@once
@push('scripts')
<script>
(function(){
    window.toggleModule = function(moduleId){
        const content = document.getElementById('content-' + moduleId);
        const icon = document.getElementById('icon-' + moduleId);
        if(!content || !icon) return;
        if(getComputedStyle(content).display === 'none'){
            content.style.display = 'block';
            icon.style.transform = 'rotate(0deg)';
        }else{
            content.style.display = 'none';
            icon.style.transform = 'rotate(180deg)';
        }
    }
    window.toggleModulePermissions = function(moduleId, checked){
        document.querySelectorAll('input[data-module="' + moduleId + '"]').forEach(function(cb){ cb.checked = !!checked; });
    }
    window.toggleEntityPermissions = function(entityId, checked){
        document.querySelectorAll('input[data-entity="' + entityId + '"]').forEach(function(cb){ cb.checked = !!checked; });
    }
    window.selectAllPermissions = function(){
        document.querySelectorAll('input[type="checkbox"]').forEach(function(cb){ cb.checked = true; });
    }
    window.deselectAllPermissions = function(){
        document.querySelectorAll('input[type="checkbox"]').forEach(function(cb){ cb.checked = false; });
    }
})();
</script>
@endpush
@endonce
