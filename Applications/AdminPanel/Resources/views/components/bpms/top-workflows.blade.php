@props(['items' => [], 'class' => ''])

<div {{ $attributes->merge(['class' => 'bg-bg-primary border border-border-light rounded-2xl overflow-hidden '.$class]) }}>
    <div class="px-6 py-4 border-b border-border-light flex items-center justify-between">
        <h3 class="text-lg font-semibold text-text-primary leading-snug">فرایندهای پرکار</h3>
        <a href="#" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium leading-normal">
            مشاهده همه
            <i class="fa-solid fa-arrow-left mr-1"></i>
        </a>
    </div>
    <div class="divide-y divide-border-light">
        @foreach($items as $item)
            <div class="px-6 py-4 hover:bg-bg-secondary transition-colors duration-200">
                <div class="flex items-start justify-between gap-4 mb-2">
                    <h4 class="text-base font-medium text-text-primary leading-snug flex-1">{{ $item['title'] ?? '' }}</h4>
                    <span class="inline-flex items-center gap-1.5 bg-indigo-50 text-indigo-700 px-2.5 py-1 rounded-lg text-xs font-medium leading-normal flex-shrink-0">
                        {{ $item['value'] }} کار
                    </span>
                </div>
                <div class="flex flex-wrap items-center gap-3 text-sm text-text-secondary leading-normal">
                    @if(!empty($item['payload']['department']))
                        <span class="inline-flex items-center gap-1.5">
                            <i class="fa-solid fa-sitemap text-xs"></i>
                            {{ $item['payload']['department'] }}
                        </span>
                    @endif
                    @if(!empty($item['payload']['states_count']))
                        <span class="inline-flex items-center gap-1.5">
                            <i class="fa-solid fa-layer-group text-xs"></i>
                            {{ $item['payload']['states_count'] }} مرحله
                        </span>
                    @endif
                    @if(!empty($item['payload']['slug']))
                        <span class="inline-flex items-center gap-1.5 text-text-muted">
                            <i class="fa-solid fa-code text-xs"></i>
                            {{ $item['payload']['slug'] }}
                        </span>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>
