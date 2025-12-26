@props([
    'name',
    'label' => null,
    'icon' => null,
    'options' => [],
    'placeholder' => 'انتخاب کنید',
    'required' => false,
    'value' => null,
    'selectClass' => '',
    'wrapperClass' => '',
])

<div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-primary focus-within:shadow-focus transition-all duration-200 {{ $wrapperClass }}">
    <div class="flex items-stretch">
        @if($label)
            <label for="{{ $name }}"
                   {{ $attributes->merge(['class' => 'bg-bg-label border-l border-border-light px-lg py-3.5 text-sm text-text-secondary flex items-center leading-normal']) }}
            >
                @if($icon)
                    <i class="{{ $icon }} ml-2"></i>
                @endif
                {{ $label }}
                @if($required)
                    <span class="text-red-500">*</span>
                @endif
            </label>
        @endif

        <select
            name="{{ $name }}"
            id="{{ $name }}"
            {{ $required ? 'required' : '' }}
            {{ $attributes->merge(['class' => "flex-1 px-lg py-3.5 text-base text-text-primary outline-none bg-transparent cursor-pointer leading-normal {$selectClass}"]) }}
        >
            <option value="">{{ $placeholder }}</option>
            @foreach($options as $valueOption => $labelOption)
                <option value="{{ $valueOption }}" {{ old($name, $value) == $valueOption ? 'selected' : '' }}>
                    {{ $labelOption }}
                </option>
            @endforeach
        </select>

        @error($name)
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
</div>
