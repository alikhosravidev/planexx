@props([
    'name',
    'label' => null,
    'placeholder' => 'تایپ کنید و Enter بزنید...',
    'required' => false,
    'value' => null,
    'options' => [],
    'maxItems' => null,
    'size' => 'md',
])

@php
    $sizeClass = $size === 'sm' ? 'ts-sm' : '';
    $selectedValues = old($name, $value);
    if (is_string($selectedValues)) {
        $selectedValues = array_filter(explode(',', $selectedValues));
    }
    $selectedValues = $selectedValues ?? [];
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
        name="{{ $name }}[]"
        id="{{ $name }}"
        data-tom-select-tags
        data-placeholder="{{ $placeholder }}"
        @if($maxItems) data-max-items="{{ $maxItems }}" @endif
        {{ $required ? 'required' : '' }}
        multiple
        {{ $attributes->except('class') }}
    >
        @foreach($options as $optionValue => $optionLabel)
            @php
                $isSelected = in_array($optionValue, $selectedValues);
            @endphp
            <option value="{{ $optionValue }}" {{ $isSelected ? 'selected' : '' }}>
                {{ $optionLabel }}
            </option>
        @endforeach

        @foreach($selectedValues as $val)
            @if(!isset($options[$val]))
                <option value="{{ $val }}" selected>{{ $val }}</option>
            @endif
        @endforeach
    </select>
</div>
@else
<div class="ts-standalone {{ $sizeClass }}" {{ $attributes->only('class') }}>
    <select
        name="{{ $name }}[]"
        id="{{ $name }}"
        data-tom-select-tags
        data-placeholder="{{ $placeholder }}"
        @if($maxItems) data-max-items="{{ $maxItems }}" @endif
        {{ $required ? 'required' : '' }}
        multiple
        {{ $attributes->except('class') }}
    >
        @foreach($options as $optionValue => $optionLabel)
            @php
                $isSelected = in_array($optionValue, $selectedValues);
            @endphp
            <option value="{{ $optionValue }}" {{ $isSelected ? 'selected' : '' }}>
                {{ $optionLabel }}
            </option>
        @endforeach

        @foreach($selectedValues as $val)
            @if(!isset($options[$val]))
                <option value="{{ $val }}" selected>{{ $val }}</option>
            @endif
        @endforeach
    </select>
</div>
@endif

@error($name)
    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
@enderror
