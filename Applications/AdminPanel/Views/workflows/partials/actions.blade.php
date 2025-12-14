<div class="bg-bg-primary border border-border-light rounded-2xl p-6">
    <div class="space-y-4">
        <div class="flex items-center justify-between pb-4 border-b border-border-light">
            <span class="text-sm text-text-secondary leading-normal">وضعیت فرایند</span>
            <label class="relative inline-flex items-center cursor-pointer">
                <input type="checkbox" id="isActiveToggle" class="sr-only peer" {{ $isActive ? 'checked' : '' }}>
                <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:bg-green-500 after:content-[''] after:absolute after:top-[2px] after:right-[2px] after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:-translate-x-full"></div>
            </label>
        </div>

        <div class="space-y-3">
            <x-panel::ui.button type="submit" variant="green" icon="fa-solid fa-check" class="w-full justify-center">
                {{ isset($workflow) ? 'ذخیره فرایند' : 'ایجاد فرایند' }}
            </x-panel::ui.button>

            <a href="{{ $listUrl }}"
               class="w-full inline-flex items-center justify-center gap-2 bg-bg-secondary text-text-secondary border border-border-medium px-xl py-md rounded-lg font-medium hover:bg-gray-100 transition-all duration-200 text-base leading-normal">
                <i class="fa-solid fa-times ml-2"></i>
                <span>انصراف</span>
            </a>

            @if(isset($workflow))
                <button type="button"
                        data-ajax
                        data-confirm="آیا از حذف این فرایند اطمینان دارید؟"
                        data-action="{{ route('api.v1.admin.bpms.workflows.destroy', ['workflow' => $workflow['id']]) }}"
                        data-method="DELETE"
                        data-on-success="redirect"
                        data-redirect-url="{{ $listUrl }}"
                        class="inline-flex items-center gap-2 bg-red-600 text-white px-xl py-md rounded-lg font-medium hover:bg-red-700 transition-all duration-200 text-base leading-normal w-full justify-center">
                    <i class="fa-solid fa-trash ml-2"></i>
                    <span>حذف فرایند</span>
                </button>
            @endif
        </div>
    </div>
</div>
