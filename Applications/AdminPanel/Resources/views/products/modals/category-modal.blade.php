<div id="categoryModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4" data-modal data-modal-backdrop>
    <div class="bg-white rounded-3xl w-full max-w-[650px] shadow-2xl max-h-[90vh] overflow-y-auto">

        {{-- Header --}}
        <div class="px-6 py-4 border-b border-border-light flex items-center justify-between sticky top-0 bg-white rounded-t-3xl z-10">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-primary/10 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-folder-tree text-primary"></i>
                </div>
                <div>
                    <h3 id="categoryModalTitle" class="text-lg font-bold text-text-primary leading-snug">افزودن دسته‌بندی</h3>
                    <p class="text-sm text-text-muted leading-normal">اطلاعات دسته‌بندی را وارد کنید</p>
                </div>
            </div>
            <button type="button" data-modal-close class="w-8 h-8 flex items-center justify-center text-text-muted hover:text-text-primary hover:bg-bg-secondary rounded-lg transition-all duration-200">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>

        {{-- Body --}}
        <form id="categoryForm"
              data-ajax
              action="{{ route('api.v1.admin.product.categories.store') }}"
              data-store-url="{{ route('api.v1.admin.product.categories.store') }}"
              data-update-url="{{ route('api.v1.admin.product.categories.update', ':id') }}"
              data-method="POST"
              data-on-success="reload">
            @csrf

            <div class="p-6 space-y-5">

                {{-- نام دسته‌بندی --}}
                <x-panel::forms.input
                    name="name"
                    label="نام دسته‌بندی"
                    placeholder="مثال: الکترونیکی"
                    :required="true"
                    class="min-w-[120px]"
                />

                {{-- نامک (Slug) --}}
                <x-panel::forms.input
                    name="slug"
                    label="نامک (Slug)"
                    placeholder="electronic"
                    :required="true"
                    direction="ltr"
                    class="min-w-[120px]"
                />

                {{-- دسته‌بندی والد و ترتیب --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    <x-panel::forms.select
                        name="parent_id"
                        label="والد"
                        :options="$parentOptions"
                        placeholder="بدون والد (دسته‌بندی اصلی)"
                        class="min-w-[110px]"
                    />

                    <x-panel::forms.input
                        name="sort_order"
                        label="ترتیب نمایش"
                        type="number"
                        placeholder="۰"
                        value="0"
                        class="min-w-[110px]"
                    />
                </div>

                {{-- توضیحات --}}
                <x-panel::forms.textarea
                    name="description"
                    label="توضیحات"
                    placeholder="توضیح مختصر درباره این دسته‌بندی"
                    rows="2"
                    class="min-w-[120px]"
                />

                {{-- انتخاب آیکون --}}
                <x-panel::forms.icon-field
                    name="icon"
                    label="آیکون"
                    :icons="$categoryIcons"
                    class="min-w-[120px]"
                />

                {{-- وضعیت --}}
                <x-panel::forms.radio
                    name="is_active"
                    label="وضعیت"
                    :options="['1' => 'فعال', '0' => 'غیرفعال']"
                    value="1"
                />

            </div>

            {{-- Footer --}}
            <div class="px-6 py-4 border-t border-border-light flex items-center justify-end gap-3 sticky bottom-0 bg-bg-secondary rounded-b-3xl">
                <button type="button" data-modal-close
                        class="px-6 py-2.5 border border-border-medium text-text-secondary rounded-lg font-medium hover:bg-white transition-all duration-200 text-base leading-normal">
                    انصراف
                </button>
                <button type="submit"
                        class="px-6 py-2.5 bg-primary text-white rounded-lg font-medium hover:-translate-y-0.5 hover:shadow-button transition-all duration-200 text-base leading-normal">
                    <i class="fa-solid fa-check ml-2"></i>
                    ذخیره دسته‌بندی
                </button>
            </div>
        </form>

    </div>
</div>
