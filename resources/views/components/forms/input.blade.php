@props([
    'name',
    'label' => null,
    'type' => 'text',
    'placeholder' => '',
    'required' => false,
    'error' => null,
    'value' => null,
])

<div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-primary focus-within:shadow-focus transition-all duration-200">
    <div class="flex items-stretch">
        @if($label)
            <label for="{{ $name }}"
                {{ $attributes->merge(['class' => 'bg-bg-label border-l border-border-light px-lg py-3.5 text-sm text-text-secondary flex items-center leading-normal']) }}>
                {{ $label }}
                @if($required)
                    <span class="text-red-500">*</span>
                @endif
            </label>
        @endif

        <input
            type="{{ $type }}"
            name="{{ $name }}"
            id="{{ $name }}"
            placeholder="{{ $placeholder }}"
            {{ $required ? 'required' : '' }}
            {{ $attributes->merge(['class' => 'flex-1 px-lg py-3.5 text-base text-text-primary outline-none bg-transparent leading-normal']) }}
            value="{{ old($name, $value) }}"
        >

        @error($name)
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror

        @if($error)
            <p class="mt-1 text-sm text-red-600">{{ $error }}</p>
        @endif
    </div>
</div>
