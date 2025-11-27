@props([
    'name',
    'label' => null,
    'options' => [], // ['value' => 'Label']
    'value' => null, // selected value
    'required' => false,
    'error' => null,
])

<div class="mb-4">
    @if($label)
        <label class="block text-sm font-medium text-text-primary mb-2">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    <div class="flex items-center gap-6">
        @foreach($options as $optionValue => $optionLabel)
            @php
                $id = $name . '_' . $optionValue;
                $checked = old($name, strval($value)) === strval($optionValue);
            @endphp
            <label for="{{ $id }}" class="flex items-center gap-2 cursor-pointer">
                <input
                    type="radio"
                    name="{{ $name }}"
                    id="{{ $id }}"
                    value="{{ $optionValue }}"
                    {{ $checked ? 'checked' : '' }}
                    {{ $required ? 'required' : '' }}
                    {{ $attributes->merge(['class' => 'w-4 h-4 text-primary accent-primary']) }}
                >
                <span class="text-base text-text-primary leading-normal">{{ $optionLabel }}</span>
            </label>
        @endforeach
    </div>

    @error($name)
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror

    @if($error)
        <p class="mt-1 text-sm text-red-600">{{ $error }}</p>
    @endif
</div>
