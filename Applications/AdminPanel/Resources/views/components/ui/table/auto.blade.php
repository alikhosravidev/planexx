@props([
    'columns' => [],
    'data' => [],
    'actions' => null,
    'actionsHeader' => 'عملیات',
    'actionsWidth' => null,
    'emptyIcon' => 'fa-inbox',
    'emptyMessage' => 'داده‌ای یافت نشد',
    'emptyDescription' => null,
    'emptyActionText' => null,
    'emptyActionUrl' => null,
    'striped' => false,
    'hoverable' => true,
    'compact' => false,
    'stickyHeader' => false,
    'rowClick' => null,
    'selectable' => false,
    'selected' => [],
    'rowClass' => null,
])

@php
    $totalColumns = count($columns) + ($actions ? 1 : 0) + ($selectable ? 1 : 0);

    $actionsWidth = $actionsWidth ?? (count($actions ?? []) * 36 + 24) . 'px';

    $resolveRowClass = function ($item) use ($rowClass) {
        if (! $rowClass) {
            return null;
        }

        if ($rowClass instanceof \Closure) {
            return $rowClass($item);
        }

        if (is_callable($rowClass)) {
            return call_user_func($rowClass, $item);
        }

        return $rowClass;
    };
@endphp

<x-panel::ui.table
    :striped="$striped"
    :hoverable="$hoverable"
    {{ $attributes }}
>
    <x-panel::ui.table.head :sticky="$stickyHeader">
        @if($selectable)
            <x-panel::ui.table.th width="50px" align="center">
                <input
                    type="checkbox"
                    class="rounded border-border-light text-primary focus:ring-primary"
                    onchange="toggleSelectAll(this)"
                >
            </x-panel::ui.table.th>
        @endif

        @foreach($columns as $column)
            @php
                // Header-specific icon support. Will NOT use cell icon keys.
                $icon = $column['header_icon'] ?? null;
                $iconClass = $column['header_icon_class'] ?? null;
                if (!$iconClass && isset($column['header_icon_color']) && is_string($column['header_icon_color'])) {
                    $iconClass = 'text-' . $column['header_icon_color'] . '-600';
                }
            @endphp
            <x-panel::ui.table.th
                :align="$column['align'] ?? 'right'"
                :width="$column['width'] ?? null"
                :sortable="$column['sortable'] ?? false"
                :sortKey="$column['sort_key'] ?? $column['key']"
                :sorted="request('sort') === ($column['sort_key'] ?? $column['key']) ? request('order', 'asc') : null"
            >
                @if($icon)
                    <div class="flex items-center gap-2">
                        <i class="{{ $icon }} {{ $iconClass ?? 'text-text-muted' }}"></i>
                        <span>{{ $column['label'] }}</span>
                    </div>
                @else
                    {{ $column['label'] }}
                @endif
            </x-panel::ui.table.th>
        @endforeach

        @if($actions)
            <x-panel::ui.table.th align="center" :width="$actionsWidth">
                {{ $actionsHeader }}
            </x-panel::ui.table.th>
        @endif
    </x-panel::ui.table.head>

    <x-panel::ui.table.body>
        @forelse($data as $item)
            @php
                $itemId = data_get($item, 'id');
                $isSelected = in_array($itemId, $selected);
                $rowUrl = null;
                $rowClassValue = $resolveRowClass($item);

                if ($rowClick) {
                    if (is_callable($rowClick)) {
                        $rowUrl = $rowClick($item);
                    } elseif (is_string($rowClick)) {
                        $rowUrl = route($rowClick, $itemId);
                    }
                }
            @endphp

            <x-panel::ui.table.row
                :selected="$isSelected"
                :href="$rowUrl"
                :clickable="(bool) $rowUrl"
                :class="$rowClassValue"
            >
                @if($selectable)
                    <x-panel::ui.table.td align="center" :compact="$compact">
                        <input
                            type="checkbox"
                            name="selected[]"
                            value="{{ $itemId }}"
                            class="rounded border-border-light text-primary focus:ring-primary"
                            @checked($isSelected)
                            onclick="event.stopPropagation()"
                        >
                    </x-panel::ui.table.td>
                @endif

                @foreach($columns as $column)
                    <x-panel::ui.table.td
                        :align="$column['align'] ?? 'right'"
                        :compact="$compact"
                        :nowrap="$column['nowrap'] ?? false"
                    >
                        @if(isset($column['component']))
                            <x-dynamic-component
                                :component="'panel::ui.table.cells.' . $column['component']"
                                :item="$item"
                                :value="data_get($item, $column['key'])"
                                :options="$column['options'] ?? []"
                            />
                        @elseif(isset($column['view']))
                            @include($column['view'], ['item' => $item, 'value' => data_get($item, $column['key'])])
                        @elseif(isset($column['render']))
                            {!! $column['render']($item, data_get($item, $column['key'])) !!}
                        @elseif(isset($column['slot']) && isset($$column['slot']))
                            {{ $$column['slot'] }}
                        @else
                            <span class="text-text-secondary">
                                {{ data_get($item, $column['key']) ?? '-' }}
                            </span>
                        @endif
                    </x-panel::ui.table.td>
                @endforeach

                @if($actions)
                    <x-panel::ui.table.td align="center" :compact="$compact">
                        <x-panel::ui.table.cells.actions :item="$item" :actions="$actions" />
                    </x-panel::ui.table.td>
                @endif
            </x-panel::ui.table.row>
        @empty
            <x-panel::ui.table.empty
                :colspan="$totalColumns"
                :icon="$emptyIcon"
                :message="$emptyMessage"
                :description="$emptyDescription"
                :actionText="$emptyActionText"
                :actionUrl="$emptyActionUrl"
            />
        @endforelse
    </x-panel::ui.table.body>
</x-panel::ui.table>

@once
    @push('scripts')
    <script>
        function handleSort(key) {
            const url = new URL(window.location);
            const currentSort = url.searchParams.get('sort');
            const currentOrder = url.searchParams.get('order') || 'asc';

            url.searchParams.set('sort', key);
            url.searchParams.set('order', currentSort === key && currentOrder === 'asc' ? 'desc' : 'asc');

            window.location = url;
        }

        function toggleSelectAll(checkbox) {
            document.querySelectorAll('input[name="selected[]"]').forEach(cb => {
                cb.checked = checkbox.checked;
            });
        }
    </script>
    @endpush
@endonce
