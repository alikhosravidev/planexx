@props([
    'name',
    'label' => null,
    'placeholder' => 'انتخاب کنید',
    'required' => false,
    'value' => null,
    'options' => [],
    'multiple' => false,
    'tags' => false,
    'maxItems' => null,
    'size' => 'md',
])

@php
    $selectAttr = $multiple ? 'data-tom-select-multiple' : ($tags ? 'data-tom-select-tags' : 'data-tom-select');
    $selectedValue = old($name, $value);
    $sizeClass = $size === 'sm' ? 'ts-sm' : '';
@endphp

@if($label)
<div class="ts-field-wrapper {{ $sizeClass }}" {{ $attributes->only('class') }}>
    <label for="{{ $name }}" class="ts-field-label">
        {{ $label }}
        @if($required)
            <span class="required">*</span>
        @endif
    </label>
    <select
        name="{{ $name }}{{ $multiple ? '[]' : '' }}"
        id="{{ $name }}"
        {{ $selectAttr }}
        data-placeholder="{{ $placeholder }}"
        @if($maxItems) data-max-items="{{ $maxItems }}" @endif
        {{ $required ? 'required' : '' }}
        {{ $multiple ? 'multiple' : '' }}
        {{ $attributes->except('class') }}
    >
        @if(!$multiple && !$tags)
            <option value="">{{ $placeholder }}</option>
        @endif

        @foreach($options as $optionValue => $optionLabel)
            @php
                $isSelected = $multiple
                    ? (is_array($selectedValue) && in_array($optionValue, $selectedValue))
                    : ($selectedValue == $optionValue);
            @endphp
            <option value="{{ $optionValue }}" {{ $isSelected ? 'selected' : '' }}>
                {{ $optionLabel }}
            </option>
        @endforeach
    </select>
</div>
@else
<div class="ts-standalone {{ $sizeClass }}" {{ $attributes->only('class') }}>
    <select
        name="{{ $name }}{{ $multiple ? '[]' : '' }}"
        id="{{ $name }}"
        {{ $selectAttr }}
        data-placeholder="{{ $placeholder }}"
        @if($maxItems) data-max-items="{{ $maxItems }}" @endif
        {{ $required ? 'required' : '' }}
        {{ $multiple ? 'multiple' : '' }}
        {{ $attributes->except('class') }}
    >
        @if(!$multiple && !$tags)
            <option value="">{{ $placeholder }}</option>
        @endif

        @foreach($options as $optionValue => $optionLabel)
            @php
                $isSelected = $multiple
                    ? (is_array($selectedValue) && in_array($optionValue, $selectedValue))
                    : ($selectedValue == $optionValue);
            @endphp
            <option value="{{ $optionValue }}" {{ $isSelected ? 'selected' : '' }}>
                {{ $optionLabel }}
            </option>
        @endforeach
    </select>
</div>
@endif

@error($name)
    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
@enderror
