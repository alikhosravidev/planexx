@props(['id' => 'back-button'])

<div id="{{ $id }}-container" class="hidden mb-3 pr-5">
    <button type="button"
            id="{{ $id }}"
            class="flex items-center gap-1.5 text-gray-300 hover:text-white transition-all duration-200 text-sm leading-normal">
        <i class="fa-solid fa-arrow-right text-xs"></i>
        <span>{{ trans('Organization::auth.back') }}</span>
    </button>
</div>
