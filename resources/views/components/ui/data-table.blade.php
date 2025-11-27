@props([
    'headers' => [],
    'rows' => [],
    'actions' => null, // optional: array of HTML strings per row
    'actionsHeader' => 'عملیات',
])

<div class="bg-white border border-border-light rounded-2xl overflow-hidden" {{ $attributes }}>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-bg-secondary border-b border-border-light">
                <tr>
                    @foreach($headers as $header)
                        <th class="px-6 py-4 text-right text-sm font-semibold text-text-primary leading-normal">
                            {{ $header }}
                        </th>
                    @endforeach
                    @if(is_array($actions))
                        <th class="px-6 py-4 text-center text-sm font-semibold text-text-primary leading-normal">{{ $actionsHeader }}</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse($rows as $rowIndex => $row)
                    <tr class="border-b border-border-light last:border-0 hover:bg-bg-secondary transition-colors duration-200">
                        @foreach($row as $cell)
                            <td class="px-6 py-4">
                                {!! $cell !!}
                            </td>
                        @endforeach
                        @if(is_array($actions))
                            <td class="px-6 py-4">
                                {!! $actions[$rowIndex] ?? '' !!}
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ count($headers) + (is_array($actions) ? 1 : 0) }}" class="px-6 py-8 text-center text-text-muted">
                            <i class="fa-solid fa-inbox text-3xl mb-2 block"></i>
                            داده‌ای یافت نشد
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
