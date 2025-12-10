@props([
    'name' => 'items',
    'label' => null,
    'options' => [],
    'selected' => [],
    'required' => false,
    'containerClass' => 'border border-border-medium rounded-xl p-4 mt-2',
    'labelClass' => 'block text-sm text-text-secondary mb-3 leading-normal',
    'badgeClass' => 'badge-checkbox inline-flex items-center gap-2 px-3 py-1.5 rounded-lg border-2 cursor-pointer transition-all duration-200',
    'badgeCheckedClass' => 'bg-indigo-50 border-indigo-300 text-indigo-700',
    'badgeUncheckedClass' => 'bg-gray-50 border-gray-200 text-text-secondary hover:border-gray-300',
    'iconClass' => 'badge-icon fa-solid',
    'iconCheckedClass' => 'fa-check-circle text-indigo-500',
    'iconUncheckedClass' => 'fa-circle text-gray-300',
])

@php
    $selectedValues = old($name, $selected ?? []);
    if (! is_array($selectedValues)) {
        $selectedValues = $selectedValues !== null ? [$selectedValues] : [];
    }
@endphp

<div {{ $attributes->merge(['class' => 'border border-border-medium rounded-xl p-4 mt-2']) }} data-checkbox-badges>
    @if($label)
        <label class="block text-sm text-text-secondary mb-3 leading-normal">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    <div class="flex flex-wrap gap-2">
        @foreach(($options ?? []) as $value => $label)
            @php
                $checked = in_array((string) $value, array_map('strval', $selectedValues), true);
            @endphp

            <label class="badge-checkbox inline-flex items-center gap-2 px-3 py-1.5 rounded-lg border-2 cursor-pointer transition-all duration-200 {{ $checked ? 'bg-indigo-50 border-indigo-300 text-indigo-700' : 'bg-gray-50 border-gray-200 text-text-secondary hover:border-gray-300' }}">
                <input
                    type="checkbox"
                    name="{{ $name }}[]"
                    value="{{ $value }}"
                    class="hidden badge-checkbox-input"
                    @checked($checked)
                >

                <i class="badge-icon fa-solid {{ $checked ? 'fa-check-circle text-indigo-500' : 'fa-circle text-gray-300' }} text-xs"></i>
                <span class="text-sm font-medium leading-normal">{{ $label }}</span>
            </label>
        @endforeach
    </div>
</div>

@once
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                document.querySelectorAll('[data-checkbox-badges]').forEach(function (container) {
                    container.querySelectorAll('.badge-checkbox-input').forEach(function (checkbox) {
                        checkbox.addEventListener('change', function () {
                            const badge = this.closest('.badge-checkbox');
                            const icon = badge ? badge.querySelector('.badge-icon') : null;

                            if (!badge || !icon) {
                                return;
                            }

                            if (this.checked) {
                                badge.classList.remove('bg-gray-50', 'border-gray-200', 'text-text-secondary');
                                badge.classList.add('bg-indigo-50', 'border-indigo-300', 'text-indigo-700');
                                icon.classList.remove('fa-circle', 'text-gray-300');
                                icon.classList.add('fa-check-circle', 'text-indigo-500');
                            } else {
                                badge.classList.remove('bg-indigo-50', 'border-indigo-300', 'text-indigo-700');
                                badge.classList.add('bg-gray-50', 'border-gray-200', 'text-text-secondary');
                                icon.classList.remove('fa-check-circle', 'text-indigo-500');
                                icon.classList.add('fa-circle', 'text-gray-300');
                            }
                        });
                    });
                });
            });
        </script>
    @endpush
@endonce
