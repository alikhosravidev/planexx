@props([
    'name' => 'color',
    'colors' => null,
    'selected' => null,
    'class' => null,
    'size' => 8,
    'wrapperClass' => 'px-lg'
])

@php
    $colors = $colors ?? [
        'blue-500', 'blue-700', 'blue-900',
        'sky-500', 'sky-700', 'sky-900',
        'cyan-500', 'cyan-700', 'cyan-900',
        'teal-500', 'teal-700', 'teal-900',
        'green-500', 'green-700', 'green-900',
        'emerald-500', 'emerald-700', 'emerald-900',
        'lime-500', 'lime-700', 'lime-900',
        'yellow-500', 'yellow-700', 'yellow-900',
        'amber-500', 'amber-700', 'amber-900',
        'orange-500', 'orange-700', 'orange-900',
        'red-500', 'red-700', 'red-900',
        'rose-500', 'rose-700', 'rose-900',
        'pink-500', 'pink-700', 'pink-900',
        'fuchsia-500', 'fuchsia-700', 'fuchsia-900',
        'purple-500', 'purple-700', 'purple-900',
        'violet-500', 'violet-700', 'violet-900',
        'indigo-500', 'indigo-700', 'indigo-900',
        'slate-500', 'slate-700', 'slate-900',
        'gray-500', 'gray-700', 'gray-900',
        'zinc-500', 'zinc-700', 'zinc-900',
        'stone-500', 'stone-700', 'stone-900',
    ];
    $selected = $selected ?? $colors[0] ?? null;
@endphp

<div class="flex-1 py-3.5 flex flex-wrap gap-2 {{ $wrapperClass }}">
    @foreach($colors as $color)
        <label class="cursor-pointer color-option">
            <input type="radio" name="{{ $name }}" value="{{ $color }}"
                   class="peer hidden" {{ $color === $selected ? 'checked' : '' }}>
            <div
                class="w-{{ $size }} h-{{ $size }} bg-{{ $color }} rounded-lg border-2 border-transparent peer-checked:border-gray-800 peer-checked:ring-2 peer-checked:ring-gray-300 transition-all flex items-center justify-center">
                <i class="fa-solid fa-check text-white text-xs opacity-0 peer-checked:opacity-100 check-icon"></i>
            </div>
        </label>
    @endforeach
</div>

@once
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('.color-option input').forEach(input => {
                    input.addEventListener('change', function() {
                        document.querySelectorAll('.color-option .check-icon').forEach(icon => {
                            icon.classList.add('opacity-0');
                            icon.classList.remove('opacity-100');
                        });
                        if (this.checked) {
                            this.nextElementSibling.querySelector('.check-icon').classList.remove('opacity-0');
                            this.nextElementSibling.querySelector('.check-icon').classList.add('opacity-100');
                        }
                    });
                });

                document.querySelectorAll('.icon-option input').forEach(input => {
                    input.addEventListener('change', function() {
                        document.querySelectorAll('.icon-option > div').forEach(div => {
                            div.classList.remove('border-primary', 'bg-primary/10');
                            div.classList.add('border-transparent');
                            div.querySelector('i').classList.remove('text-primary');
                            div.querySelector('i').classList.add('text-text-secondary');
                        });
                        if (this.checked) {
                            this.nextElementSibling.classList.add('border-primary', 'bg-primary/10');
                            this.nextElementSibling.classList.remove('border-transparent');
                            this.nextElementSibling.querySelector('i').classList.add('text-primary');
                            this.nextElementSibling.querySelector('i').classList.remove('text-text-secondary');
                        }
                    });
                });

                const checkedColor = document.querySelector('.color-option input:checked');
                if (checkedColor) {
                    checkedColor.nextElementSibling.querySelector('.check-icon').classList.remove('opacity-0');
                    checkedColor.nextElementSibling.querySelector('.check-icon').classList.add('opacity-100');
                }

                const checkedIcon = document.querySelector('.icon-option input:checked');
                if (checkedIcon) {
                    checkedIcon.nextElementSibling.classList.add('border-primary', 'bg-primary/10');
                    checkedIcon.nextElementSibling.classList.remove('border-transparent');
                    checkedIcon.nextElementSibling.querySelector('i').classList.add('text-primary');
                    checkedIcon.nextElementSibling.querySelector('i').classList.remove('text-text-secondary');
                }
            });
        </script>
    @endpush
@endonce
