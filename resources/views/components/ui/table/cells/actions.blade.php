@props([
    'item' => null,
    'actions' => [],
])

<div class="flex items-center justify-center gap-1">
    @foreach($actions as $action)
        @if(isset($action['when']))
            @if(is_callable($action['when']) && !$action['when']($item))
                @continue
            @elseif(is_string($action['when']) && !data_get($item, $action['when']))
                @continue
            @endif
        @endif
        
        @if(isset($action['unless']))
            @if(is_callable($action['unless']) && $action['unless']($item))
                @continue
            @elseif(is_string($action['unless']) && data_get($item, $action['unless']))
                @continue
            @endif
        @endif

        @include('components.ui.table.partials.action-button', [
            'action' => $action,
            'item' => $item,
        ])
    @endforeach
</div>
