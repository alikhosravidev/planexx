<div id="editItemModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4" data-modal data-modal-backdrop>
    <div class="bg-white rounded-2xl w-full max-w-lg shadow-2xl">

        {{-- Header --}}
        <div class="px-6 py-4 border-b border-border-light flex items-center justify-between">
            <h3 class="text-lg font-bold text-text-primary leading-snug">ویرایش آیتم</h3>
            <button type="button" data-modal-close class="w-8 h-8 flex items-center justify-center text-text-muted hover:text-text-primary hover:bg-bg-secondary rounded-lg transition-all duration-200">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>

        {{-- Body --}}
        <form id="editItemForm"
              data-ajax
              action=""
              data-method="PUT"
              data-on-success="reload">
            @csrf

            <div class="p-6 space-y-4">
                <x-panel::forms.input
                    name="title"
                    label="عنوان"
                    placeholder="عنوان آیتم"
                    :required="true"
                    id="editItemTitle"
                />

                <x-panel::forms.input
                    name="code"
                    label="کد"
                    placeholder="کد آیتم"
                    id="editItemCode"
                />

                @foreach ($activeFields as $field)
                    <x-panel::forms.input
                        name="field{{ $field['num'] }}"
                        label="{{ $field['label'] }}"
                        placeholder="{{ $field['label'] }}"
                        type="{{ $field['type'] === 'number' ? 'number' : 'text' }}"
                        id="editItemField{{ $field['num'] }}"
                    />
                @endforeach

                {{-- وضعیت --}}
                <x-panel::forms.radio
                    name="is_active"
                    label="وضعیت"
                    :options="['1' => 'فعال', '0' => 'غیرفعال']"
                    value="1"
                    id="editItemIsActive"
                />
            </div>

            {{-- Footer --}}
            <div class="px-6 py-4 border-t border-border-light flex items-center justify-end gap-3 bg-bg-secondary rounded-b-2xl">
                <button type="button" data-modal-close
                        class="px-6 py-2.5 border border-border-medium text-text-secondary rounded-lg font-medium hover:bg-white transition-all duration-200 text-base leading-normal">
                    انصراف
                </button>
                <button type="submit"
                        class="px-6 py-2.5 bg-primary text-white rounded-lg font-medium hover:-translate-y-0.5 hover:shadow-button transition-all duration-200 text-base leading-normal">
                    <i class="fa-solid fa-check ml-2"></i>
                    ذخیره تغییرات
                </button>
            </div>
        </form>
    </div>
</div>
