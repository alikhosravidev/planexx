@props([
    'name',
    'label' => null,
    'placeholder' => 'جستجو کنید...',
    'required' => false,
    'value' => null,
    'selectedLabel' => null,
    'url' => null,
    'searchFields' => null,
    'valueField' => 'id',
    'labelField' => 'name',
    'dataPath' => null,
    'template' => 'default',
    'preload' => false,
    'minSearch' => 1,
    'multiple' => false,
    'maxItems' => null,
    'size' => 'md',
])

@php
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
        data-tom-select-ajax
        data-url="{{ $url }}"
        data-placeholder="{{ $placeholder }}"
        data-value-field="{{ $valueField }}"
        data-label-field="{{ $labelField }}"
        @if($searchFields) data-search-fields="{{ $searchFields }}" @endif
        @if($dataPath) data-path="{{ $dataPath }}" @endif
        @if($template !== 'default') data-template="{{ $template }}" @endif
        @if($preload) data-preload @endif
        @if($minSearch !== 1) data-min-search="{{ $minSearch }}" @endif
        @if($selectedValue) data-default-value="{{ $selectedValue }}" @endif
        @if($maxItems) data-max-items="{{ $maxItems }}" @endif
        {{ $required ? 'required' : '' }}
        {{ $multiple ? 'multiple' : '' }}
        {{ $attributes->except('class') }}
    >
        @if($selectedValue && $selectedLabel)
            <option value="{{ $selectedValue }}" selected>{{ $selectedLabel }}</option>
        @endif
    </select>
</div>
@else
<div class="ts-standalone {{ $sizeClass }}" {{ $attributes->only('class') }}>
    <select
        name="{{ $name }}{{ $multiple ? '[]' : '' }}"
        id="{{ $name }}"
        data-tom-select-ajax
        data-url="{{ $url }}"
        data-placeholder="{{ $placeholder }}"
        data-value-field="{{ $valueField }}"
        data-label-field="{{ $labelField }}"
        @if($searchFields) data-search-fields="{{ $searchFields }}" @endif
        @if($dataPath) data-path="{{ $dataPath }}" @endif
        @if($template !== 'default') data-template="{{ $template }}" @endif
        @if($preload) data-preload @endif
        @if($minSearch !== 1) data-min-search="{{ $minSearch }}" @endif
        @if($selectedValue) data-default-value="{{ $selectedValue }}" @endif
        @if($maxItems) data-max-items="{{ $maxItems }}" @endif
        {{ $required ? 'required' : '' }}
        {{ $multiple ? 'multiple' : '' }}
        {{ $attributes->except('class') }}
    >
        @if($selectedValue && $selectedLabel)
            <option value="{{ $selectedValue }}" selected>{{ $selectedLabel }}</option>
        @endif
    </select>
</div>
@endif

@error($name)
    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
@enderror
