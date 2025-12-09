@props([
    'name' => 'color',
    'label' => 'رنگ',
    'selected' => null,
    'colors' => null,
    'withCustomColor' => true,
])

<div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-primary focus-within:shadow-focus transition-all duration-200">
    <div class="flex">
        <div
            {{ $attributes->merge(['class' => 'bg-bg-label border-l border-border-light px-lg py-3.5 text-sm text-text-secondary flex items-center leading-normal']) }}>
            {{ $label }}
        </div>

        <div class="flex flex-col">
            <x-forms.color-radio-group
                :name="$name"
                :colors="$colors"
                :selected="$selected"
            />

            @if($withCustomColor)
                <div class="border-t border-border-light px-lg py-3 flex items-center gap-3">
                    <input
                        type="color"
                        class="w-10 h-10 rounded border border-border-medium js-custom-color-input"
                        data-color-hidden-name="{{ $name }}"
                    >
                    <span class="text-xs text-text-muted">رنگ سفارشی</span>
                </div>
            @endif
        </div>

    </div>
</div>

@once
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                function ensureHidden(form, name) {
                    let hidden = form.querySelector('input[type="hidden"][name="' + name + '"]');
                    if (!hidden) {
                        hidden = document.createElement('input');
                        hidden.type = 'hidden';
                        hidden.name = name;
                        form.appendChild(hidden);
                    }
                    return hidden;
                }

                document.querySelectorAll('.js-custom-color-input').forEach(function (input) {
                    const fieldName = input.getAttribute('data-color-hidden-name');
                    if (!fieldName) return;

                    const form = input.closest('form');
                    if (!form) return;

                    input.addEventListener('input', function () {
                        const value = this.value;
                        const radios = form.querySelectorAll('input[type="radio"][name="' + fieldName + '"]');

                        if (value) {
                            radios.forEach(function (r) { r.checked = false; });
                            const hidden = ensureHidden(form, fieldName);
                            hidden.value = value;
                        }
                    });

                    const radios = form.querySelectorAll('input[type="radio"][name="' + fieldName + '"]');
                    radios.forEach(function (radio) {
                        radio.addEventListener('change', function () {
                            if (!this.checked) return;

                            const hidden = form.querySelector('input[type="hidden"][name="' + fieldName + '"]');
                            if (hidden) {
                                hidden.parentNode.removeChild(hidden);
                            }
                        });
                    });
                });
            });
        </script>
    @endpush
@endonce
