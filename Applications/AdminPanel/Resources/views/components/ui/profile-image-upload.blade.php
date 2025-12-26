@props([
    'name' => 'image',
    'label' => 'تصویر پروفایل',
    'value' => null,
    'standalone' => false,
])

@php
    $uid = uniqid($name.'_');
    $inputId = $uid.'_input';
    $previewId = $uid.'_preview';
    $iconId = $uid.'_icon';
@endphp

@if($standalone)
    <div class="bg-bg-primary border border-border-light rounded-2xl p-6 mb-6">
        <h2 class="text-lg font-semibold text-text-primary leading-snug mb-6">{{ $label }}</h2>
        <div class="flex items-start gap-6">
            <div id="{{ $previewId }}" class="w-32 h-32 bg-gray-100 rounded-xl flex items-center justify-center border-2 border-dashed border-border-medium overflow-hidden">
                @if($value)
                    <img src="{{ $value }}" alt="" class="w-full h-full object-cover">
                @else
                    <i id="{{ $iconId }}" class="fa-solid fa-user text-4xl text-text-muted"></i>
                @endif
            </div>
            <div class="flex-1">
                <input type="file" name="{{ $name }}" accept="image/*" class="hidden" id="{{ $inputId }}">
                <label for="{{ $inputId }}" class="inline-flex items-center gap-2 bg-bg-secondary text-text-secondary border border-border-medium px-xl py-md rounded-lg font-medium hover:bg-gray-100 transition-all duration-200 text-base leading-normal cursor-pointer">
                    <i class="fa-solid fa-upload"></i>
                    <span>انتخاب تصویر</span>
                </label>
                <p class="text-sm text-text-muted mt-2 leading-normal">فرمت‌های مجاز: JPG, PNG - حداکثر حجم: 2MB</p>
            </div>
        </div>
    </div>
@else
    <div>
        <h2 class="text-lg font-semibold text-text-primary leading-snug mb-6">{{ $label }}</h2>
        <div class="flex items-start gap-6">
            <div id="{{ $previewId }}" class="w-32 h-32 bg-gray-100 rounded-xl flex items-center justify-center border-2 border-dashed border-border-medium overflow-hidden">
                @if($value)
                    <img src="{{ $value }}" alt="" class="w-full h-full object-cover">
                @else
                    <i id="{{ $iconId }}" class="fa-solid fa-user text-4xl text-text-muted"></i>
                @endif
            </div>
            <div class="flex-1">
                <input type="file" name="{{ $name }}" accept="image/*" class="hidden" id="{{ $inputId }}">
                <label for="{{ $inputId }}" class="inline-flex items-center gap-2 bg-bg-secondary text-text-secondary border border-border-medium px-xl py-md rounded-lg font-medium hover:bg-gray-100 transition-all duration-200 text-base leading-normal cursor-pointer">
                    <i class="fa-solid fa-upload"></i>
                    <span>انتخاب تصویر</span>
                </label>
                <p class="text-sm text-text-muted mt-2 leading-normal">فرمت‌های مجاز: JPG, PNG - حداکثر حجم: 2MB</p>
            </div>
        </div>
    </div>
@endif

<script>
    (function(){
        const input = document.getElementById('{{ $inputId }}');
        const preview = document.getElementById('{{ $previewId }}');
        const icon = document.getElementById('{{ $iconId }}');
        if(!input || !preview) return;
        input.addEventListener('change', function(e){
            const file = e.target.files && e.target.files[0];
            if(!file) return;
            const reader = new FileReader();
            reader.onload = function(ev){
                const url = ev.target.result;
                preview.innerHTML = '';
                const img = document.createElement('img');
                img.src = url;
                img.className = 'w-full h-full object-cover';
                preview.appendChild(img);
            };
            reader.readAsDataURL(file);
        });
    })();
</script>
