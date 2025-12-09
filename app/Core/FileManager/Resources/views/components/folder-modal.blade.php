@props(['folders' => [], 'folder' => null])

@php
    $isEdit = isset($folder['id']);
    $modalTitle = $isEdit ? 'ویرایش پوشه' : 'ایجاد پوشه جدید';
    $submitUrl = $isEdit
        ? route('api.v1.admin.file-manager.folders.update', ['folder' => $folder['id']])
        : route('api.v1.admin.file-manager.folders.store');
    $method = $isEdit ? 'PUT' : 'POST';

    $colors = ['purple-500', 'pink-500', 'green-500', 'blue-500', 'amber-500', 'slate-500', 'teal-500', 'orange-500', 'red-500'];
    $selectedColor = isset($folder['color']) ? "{$folder['color']}-500" : 'purple-500';
@endphp

<div
    id="folderModal"
    data-modal
    data-modal-backdrop
    class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-bg-primary rounded-3xl shadow-lg max-w-[500px] w-full max-h-[90vh] overflow-y-auto">
        <div class="px-6 py-5 border-b border-border-light flex items-center justify-between">
            <div>
                <h3 class="text-xl font-semibold text-text-primary leading-snug">{{ $modalTitle }}</h3>
                <p class="text-sm text-text-muted mt-1">در <span class="text-primary font-medium" data-folder-parent-name>ریشه اسناد</span></p>
            </div>
            <button type="button" data-modal-close
                    class="w-9 h-9 flex items-center justify-center text-text-muted hover:text-primary hover:bg-bg-secondary rounded-lg transition-all duration-200">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>

        <form
            data-ajax
            action="{{ $submitUrl }}"
            data-method="{{ $method }}"
            data-on-success="reload"
            data-loading-class="opacity-50 pointer-events-none"
            class="p-6">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-medium text-text-secondary mb-2">نام پوشه</label>
                <input
                    type="text"
                    name="name"
                    value="{{ $folder['name'] ?? '' }}"
                    required
                    data-folder-name-input
                    class="w-full px-4 py-3 border border-border-medium rounded-xl text-sm text-text-primary outline-none focus:border-primary focus:shadow-focus transition-all duration-200"
                    placeholder="نام پوشه را وارد کنید...">
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-text-secondary">رنگ پوشه</label>
                <div class="flex flex-col">
                    <x-forms.color-radio-group
                        name="color"
                        wrapperClass=""
                        :colors="$colors"
                        :size="10"
                        :selected="$selectedColor"
                    />
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-text-secondary">آیکون پوشه</label>
                <div class="flex flex-col mt-3">
                    <x-forms.icon-radio-group
                        name="icon"
                        wrapperClass=""
                        :size="10"
                        :selected="$folder['icon'] ?? null"
                    />
                </div>
            </div>

            <div class="px-6 py-4 border-t border-border-light flex items-center justify-end gap-3 -mx-6 -mb-6">
                <button
                    type="button"
                    data-modal-close
                    class="px-5 py-2.5 border border-border-medium text-text-secondary rounded-lg font-medium hover:bg-bg-secondary transition-all duration-200 text-sm">
                    انصراف
                </button>

                <button
                    type="submit"
                    data-submit-button
                    class="bg-primary text-white px-6 py-2.5 rounded-lg font-medium hover:-translate-y-0.5 hover:shadow-button transition-all duration-200 text-sm flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                    <i class="fa-solid fa-folder-plus" data-icon></i>
                    <i class="fa-solid fa-spinner fa-spin hidden" data-loading-icon></i>
                    <span>{{ $isEdit ? 'ذخیره تغییرات' : 'ایجاد پوشه' }}</span>
                </button>
            </div>

        </form>
    </div>
</div>
