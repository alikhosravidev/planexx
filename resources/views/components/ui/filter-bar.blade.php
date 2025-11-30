@props([
    'filters' => [],
    'resetUrl' => null,
])

<div class="bg-bg-primary border border-border-light rounded-2xl p-6" {{ $attributes }}>
    <div class="flex flex-wrap items-stretch gap-4">
        
        @foreach($filters as $filter)
            @if($filter['type'] === 'text')
                <div class="flex-1 min-w-[250px] border border-border-medium rounded-xl overflow-hidden focus-within:border-primary focus-within:shadow-focus transition-all duration-200">
                    <div class="flex items-stretch">
                        <label class="bg-bg-label border-l border-border-light min-w-[100px] px-4 py-3.5 text-sm text-text-secondary flex items-center leading-normal">
                            {{ $filter['label'] }}
                        </label>
                        <input 
                            type="text" 
                            name="{{ $filter['name'] }}"
                            value="{{ $filter['value'] ?? '' }}"
                            class="flex-1 px-4 py-3.5 text-base text-text-primary outline-none bg-transparent leading-normal"
                            placeholder="{{ $filter['placeholder'] ?? '' }}">
                    </div>
                </div>
            @elseif($filter['type'] === 'select')
                <div class="flex-1 min-w-[200px] border border-border-medium rounded-xl overflow-hidden focus-within:border-primary focus-within:shadow-focus transition-all duration-200">
                    <div class="flex items-stretch">
                        <label class="bg-bg-label border-l border-border-light min-w-[100px] px-4 py-3.5 text-sm text-text-secondary flex items-center leading-normal">
                            {{ $filter['label'] }}
                        </label>
                        <select 
                            name="{{ $filter['name'] }}"
                            class="flex-1 px-4 py-3.5 text-base text-text-primary outline-none bg-transparent cursor-pointer leading-normal">
                            @foreach($filter['options'] as $value => $label)
                                <option value="{{ $value }}" {{ ($filter['selected'] ?? '') == $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @endif
        @endforeach
        
        <div class="flex items-center gap-2">
            <button type="submit" class="bg-primary text-white px-5 py-3.5 rounded-lg font-medium hover:bg-blue-700 transition-all duration-200 text-base leading-normal whitespace-nowrap">
                <i class="fa-solid fa-search ml-2"></i>
                <span>اعمال فیلتر</span>
            </button>
            @if($resetUrl)
                <a href="{{ $resetUrl }}" class="bg-bg-secondary text-text-secondary border border-border-medium px-5 py-3.5 rounded-lg font-medium hover:bg-gray-100 transition-all duration-200 text-base leading-normal whitespace-nowrap">
                    <i class="fa-solid fa-rotate-right ml-2"></i>
                    <span>پاک کردن</span>
                </a>
            @else
                <button type="reset" class="bg-bg-secondary text-text-secondary border border-border-medium px-5 py-3.5 rounded-lg font-medium hover:bg-gray-100 transition-all duration-200 text-base leading-normal whitespace-nowrap">
                    <i class="fa-solid fa-rotate-right ml-2"></i>
                    <span>پاک کردن</span>
                </button>
            @endif
        </div>
        
    </div>
</div>
