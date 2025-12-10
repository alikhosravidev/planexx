<div class="bg-bg-primary border border-border-light rounded-2xl p-6">
    <div class="flex items-start justify-between gap-3 mb-6">
        <div class="flex items-start gap-3">
            <div class="w-10 h-10 bg-indigo-600 rounded-lg flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-layer-group text-white"></i>
            </div>
            <div>
                <h2 class="text-lg font-semibold text-text-primary leading-snug">مراحل فرایند</h2>
                <p class="text-sm text-text-secondary leading-normal mt-1">مراحل مختلف این فرایند را تعریف کنید</p>
            </div>
        </div>
        <button type="button" id="addStateBtn"
                class="bg-indigo-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-indigo-700 transition-all duration-200 flex items-center gap-2 text-sm leading-normal">
            <i class="fa-solid fa-plus"></i>
            <span>افزودن مرحله</span>
        </button>
    </div>

    <div id="statesContainer" class="space-y-4">
    </div>

    <div id="emptyStatesMessage" class="text-center py-12 border-2 border-dashed border-border-medium rounded-xl">
        <div class="w-16 h-16 bg-indigo-100 rounded-xl flex items-center justify-center mx-auto mb-4">
            <i class="fa-solid fa-layer-group text-indigo-600 text-2xl"></i>
        </div>
        <p class="text-text-secondary leading-relaxed mb-4">هنوز مرحله‌ای تعریف نشده است</p>
        <button type="button" onclick="addState()"
                class="bg-indigo-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-indigo-700 transition-all duration-200 text-sm leading-normal">
            <i class="fa-solid fa-plus ml-2"></i>
            افزودن اولین مرحله
        </button>
    </div>

</div>
