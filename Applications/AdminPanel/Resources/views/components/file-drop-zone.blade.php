@props([
    'name' => 'file',
    'required' => true,
    'placeholderText' => 'فایل‌ها را اینجا رها کنید',
    'placeholderSubtext' => 'یا کلیک کنید تا فایل انتخاب شود',
    'selectFileText' => 'انتخاب فایل'
])

<label
    data-drop-zone
    class="border-2 border-dashed border-border-medium rounded-2xl p-10 text-center mb-6 hover:border-primary hover:bg-primary/5 transition-all duration-200 cursor-pointer block">
    <input type="file" name="{{ $name }}" class="hidden" @if($required) required @endif data-file-input>
    <div data-file-placeholder>
        <div class="w-16 h-16 bg-bg-secondary rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fa-solid fa-cloud-arrow-up text-2xl text-primary"></i>
        </div>
        <h4 class="text-lg font-semibold text-text-primary mb-2">{{ $placeholderText }}</h4>
        <p class="text-sm text-text-muted mb-4">{{ $placeholderSubtext }}</p>
        <span class="bg-bg-secondary text-text-secondary px-5 py-2.5 rounded-lg text-sm font-medium hover:bg-border-light transition-all duration-200 inline-block">
            {{ $selectFileText }}
        </span>
    </div>
    <div data-file-selected class="hidden">
        <i class="fa-solid fa-file-check text-green-500 text-2xl mb-2"></i>
        <p data-file-name class="text-text-primary text-sm font-medium"></p>
        <p class="text-text-muted text-xs mt-1">برای تغییر کلیک کنید</p>
    </div>
</label>
