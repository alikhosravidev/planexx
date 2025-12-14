@props([
    'items',
    'title' => 'توزیع بر اساس نوع کاربر',
])

<div class="bg-white border border-border-light rounded-2xl p-6">
    <h2 class="text-lg font-semibold text-text-primary leading-snug mb-6">{{ $title }}</h2>
    <div class="space-y-4">
        @foreach($items as $row)
            <div>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm text-text-secondary leading-normal">{{ $row['label'] }}</span>
                    <span class="text-sm font-semibold text-text-primary leading-normal">{{ $row['value'] }}</span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-2.5">
                    <div class="bg-{{ $row['color'] }}-500 h-2.5 rounded-full" style="width: {{ $row['percent'] }}%"></div>
                </div>
            </div>
        @endforeach
    </div>
</div>
