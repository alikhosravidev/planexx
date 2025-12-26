@props([
    'entityType',
    'entityId',
    'moduleName',
    'isPublic' => true,
    // 2 -> FileCollectionEnum::ATTACHMENT
    'collection' => '2',
    'currentFolder' => null
])

<div
    id="attachmentModal"
    data-modal
    data-modal-backdrop
    class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">

    <div class="bg-bg-primary rounded-3xl shadow-lg max-w-[600px] w-full max-h-[90vh] overflow-y-auto">
        <div class="px-6 py-5 border-b border-border-light flex items-center justify-between">
            <h3 class="text-xl font-semibold text-text-primary leading-snug">افزودن پیوست</h3>
            <button type="button" data-modal-close class="w-9 h-9 flex items-center justify-center text-text-muted hover:text-primary hover:bg-bg-secondary rounded-lg transition-all duration-200">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>

            <form
                data-ajax
                action="{{ route('api.v1.admin.file-manager.files.store') }}"
                method="POST"
                enctype="multipart/form-data"
                data-on-success="reload"
                data-loading-class="opacity-50 pointer-events-none"
                class="p-6">
                @csrf

                <input type="hidden" name="entity_type" value="{{ $entityType }}">
                <input type="hidden" name="entity_id" value="{{ $entityId }}">
                <input type="hidden" name="is_public" value="{{ $isPublic }}">
                <input type="hidden" name="module_name" value="{{ $moduleName }}">
                <input type="hidden" name="collection" value="{{ $collection }}">

                @if(null !== $currentFolder)
                    <input type="hidden" name="folder_id" value="{{ $currentFolder['id'] }}">
                @endif

                <x-panel::file-drop-zone
                    name="file"
                    :required="true"
                    placeholderText="فایل‌ها را اینجا رها کنید"
                    placeholderSubtext="یا کلیک کنید تا فایل انتخاب شود"
                    selectFileText="انتخاب فایل"
                />

                <div class="mb-4">
                    <x-panel::forms.input
                        name="title"
                        label="عنوان فایل"
                        placeholder="عنوان توصیفی فایل را وارد کنید"
                        class="min-w-[120px]"
                    />
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
                    <i class="fa-solid fa-cloud-arrow-up" data-icon></i>
                    <i class="fa-solid fa-spinner fa-spin hidden" data-loading-icon></i>
                    <span data-text>آپلود</span>
                    <span class="hidden" data-loading-text>در حال آپلود...</span>
                </button>
            </div>
        </form>
    </div>
</div>
