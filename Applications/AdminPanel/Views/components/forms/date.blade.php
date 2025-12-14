@props([
    'name',
    'label' => 'تاریخ',
    'required' => false,
    'placeholder' => null,
    'value' => null,
    'min' => null,
    'max' => null,
])

<div class="">
    <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-primary focus-within:shadow-focus transition-all duration-200">
        <div class="flex items-stretch">
            <label for="{{ $name }}" class="bg-bg-label border-l border-border-light min-w-[140px] px-lg py-3.5 text-sm text-text-secondary flex items-center leading-normal">
                {{ $label }} @if($required)<span class="text-red-500 mr-1">*</span>@endif
            </label>
            <input
                type="date"
                name="{{ $name }}"
                id="{{ $name }}"
                @if($required) required @endif
                @if($placeholder) placeholder="{{ $placeholder }}" @endif
                @if($value) value="{{ $value }}" @endif
                @if($min) min="{{ $min }}" @endif
                @if($max) max="{{ $max }}" @endif
                {{ $attributes->merge(['class' => 'flex-1 px-lg py-3.5 text-base text-text-primary outline-none bg-transparent leading-normal']) }}
            />
        </div>
    </div>
    @error($name)
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
