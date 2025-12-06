@php
    $indent = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $level);
    $hasChildren = !empty($dept['children']);
    $parentClass = $level > 0 ? "child-of-{$dept['parent_id']}" : '';
@endphp

<tr class="border-b border-border-light last:border-0 hover:bg-bg-secondary transition-colors duration-200 department-row {{ $parentClass }}"
    data-level="{{ $level }}"
    data-dept-id="{{ $dept['id'] }}"
    @if($level > 0) data-parent-id="{{ $dept['parent_id'] }}" @endif
    data-has-children="{{ $hasChildren ? '1' : '0' }}">
    <td class="px-6 py-4">
        <div class="flex items-center">
            @if($hasChildren)
                <button type="button" class="toggle-dept w-6 h-6 flex items-center justify-center text-text-muted hover:text-primary transition-colors" data-parent-id="{{ $dept['id'] }}">
                    <i class="fa-solid fa-chevron-down transition-transform duration-200"></i>
                </button>
            @else
                <div class="w-6"></div>
            @endif
            {!! $indent !!}
            @php
                $type = $dept['type'] ?? 3;
                $hasImage = ($type == 1 || $type == 2); // holding or brand
            @endphp
            @if($hasImage && !empty($dept['image_url']))
                <img src="{{ $dept['image_url'] }}"
                     alt="{{ $dept['name'] }}"
                     class="w-10 h-10 rounded-full object-cover border-2 border-border-light ml-1">
            @else
                @php
                    $iconClass = !empty($dept['icon']) ? $dept['icon'] : 'fa-building';
                @endphp
                <i class="fa-solid {{ $iconClass }} text-{{ $dept['color'] ?? 'primary' }}"></i>
            @endif
            <span class="text-base text-text-primary font-medium leading-normal">{{ $dept['name'] }}</span>
        </div>
    </td>
    <td class="px-6 py-4 text-base text-text-secondary leading-normal">{{ $dept['code'] ?? '-' }}</td>
    <td class="px-6 py-4">
        @php
            $typeLabels = [
                1 => 'هولدینگ',
                2 => 'برند',
                3 => 'دپارتمان',
                4 => 'تیم'
            ];
            $typeColors = [
                1 => 'purple',
                2 => 'orange',
                3 => 'blue',
                4 => 'green'
            ];
            $type = $dept['type'] ?? 3;
            $label = $typeLabels[$type] ?? 'دپارتمان';
            $color = $typeColors[$type] ?? 'blue';
        @endphp
        <span class="inline-flex items-center gap-1.5 bg-{{ $color }}-50 text-{{ $color }}-700 px-2.5 py-1 rounded-lg text-xs font-medium leading-normal">
            {{ $label }}
        </span>
    </td>
    <td class="px-6 py-4 text-base text-text-secondary leading-normal">{{ $dept['manager']['full_name'] ?? '-' }}</td>
    <td class="px-6 py-4 text-base text-text-secondary leading-normal">{{ $dept['employees_count'] ?? 0 }} نفر</td>
    <td class="px-6 py-4">
        @if($dept['is_active'])
            <span class="inline-flex items-center gap-1.5 bg-green-50 text-green-700 px-2.5 py-1 rounded-lg text-xs font-medium leading-normal">
                <i class="fa-solid fa-circle text-[6px]"></i>
                فعال
            </span>
        @else
            <span class="inline-flex items-center gap-1.5 bg-red-50 text-red-700 px-2.5 py-1 rounded-lg text-xs font-medium leading-normal">
                <i class="fa-solid fa-circle text-[6px]"></i>
                غیرفعال
            </span>
        @endif
    </td>
    <td class="px-6 py-4">
        <div class="flex items-center justify-center gap-2">
            <a href="{{ route('web.org.departments.edit', ['department' => $dept['id']]) }}" class="w-8 h-8 flex items-center justify-center text-text-muted hover:text-primary hover:bg-bg-secondary rounded transition-all duration-200" title="ویرایش">
                <i class="fa-solid fa-pen"></i>
            </a>
            <button type="button"
                    data-ajax
                    data-confirm="آیا از حذف این دپارتمان اطمینان دارید؟"
                    data-action="{{ route('api.v1.admin.org.departments.destroy', ['department' => $dept['id']]) }}"
                    data-method="DELETE"
                    data-on-success="reload"
                    class="w-8 h-8 flex items-center justify-center text-text-muted hover:text-red-600 hover:bg-red-50 rounded transition-all duration-200"
                    title="حذف">
                <i class="fa-solid fa-trash"></i>
            </button>
        </div>
    </td>
</tr>

@if($hasChildren)
    @foreach($dept['children'] as $child)
        @include('Organization::departments.partials.department-row', ['dept' => $child, 'level' => $level + 1])
    @endforeach
@endif
