@props([
    'name',
    'label' => null,
    'options' => [],
    'placeholder' => 'انتخاب کنید',
    'required' => false,
    'value' => null,
])

@php
    // Recursive function to render department tree options
    function renderDepartmentOptions($departments, $level = 0, $parentId = null) {
        $html = '';
        $indent = str_repeat('  ---  ', $level);

        foreach ($departments as $dept) {
            $html .= '<option ' . ($parentId === $dept['id'] ? 'selected' : '') . ' value="' . $dept['id'] . '">' . $indent . $dept['name'] . '</option>';

            // Recursively render children if they exist
            if (! empty($dept['children'])) {
                $html .= renderDepartmentOptions($dept['children'], $level + 1, $parentId);
            }
        }

        return $html;
    }
@endphp

<div
    class="border border-border-medium rounded-xl overflow-hidden focus-within:border-primary focus-within:shadow-focus transition-all duration-200">
    <div class="flex items-stretch">
        <label for="{{ $name }}"
            {{ $attributes->merge(['class' => 'bg-bg-label border-l border-border-light px-lg py-3.5 text-sm text-text-secondary flex items-center leading-normal']) }}>
            {{ $label }}
        </label>

        <select name="{{ $name }}" id="{{ $name }}"
                {{ $required ? 'required' : '' }}
            {{ $attributes->merge(['class' => 'flex-1 px-lg py-3.5 text-base text-text-primary outline-none bg-transparent cursor-pointer leading-normal']) }}>
            <option value="">{{ $placeholder }}</option>
            {!! renderDepartmentOptions($options, 0, $value) !!}
        </select>

    </div>
</div>
