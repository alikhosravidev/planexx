@props([
    'name',
    'label' => null,
    'icon' => null,
    'placeholder' => 'جستجو کنید...',
    'required' => false,
    'value' => null,
    'url' => null,
    'searchFields' => null,
    'valueField' => 'id',
    'labelField' => 'label',
    'dataPath' => 'result',
    'template' => 'keyValList',
    'preload' => true,
    'minSearch' => 1,
    'multiple' => false,
    'maxItems' => null,
    'size' => 'md',
    'selectClass' => '',
    'wrapperClass' => '',
])

@php
    $selectedValue = old($name, $value);
    $sizeClass = $size === 'sm' ? 'ts-sm' : '';
@endphp

@if($label)
<div class="ts-field-wrapper {{ $wrapperClass }} {{ $sizeClass }}" {{ $attributes->only('class') }}>
    <label for="{{ $name }}" {{ $attributes->merge(['class' => 'ts-field-label']) }}>
        @if($icon)
            <i class="{{ $icon }} ml-2"></i>
        @endif
        {{ $label }}
        @if($required)
            <span class="text-red-500">*</span>
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
        class="{{ $selectClass }}"
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
    ></select>
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
        class="{{ $selectClass }}"
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
    ></select>
</div>
@endif

@error($name)
    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
@enderror
