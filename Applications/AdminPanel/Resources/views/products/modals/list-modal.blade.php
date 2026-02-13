<div id="listModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4" data-modal data-modal-backdrop>
    <div class="bg-white rounded-3xl w-full max-w-[750px] shadow-2xl max-h-[90vh] overflow-y-auto">

        {{-- Header --}}
        <div class="px-6 py-4 border-b border-border-light flex items-center justify-between sticky top-0 bg-white rounded-t-3xl z-10">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-primary/10 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-clipboard-list text-primary"></i>
                </div>
                <div>
                    <h3 id="listModalTitle" class="text-lg font-bold text-text-primary leading-snug">ایجاد لیست جدید</h3>
                    <p class="text-sm text-text-muted leading-normal">اطلاعات لیست را وارد کنید</p>
                </div>
            </div>
            <button type="button" data-modal-close class="w-8 h-8 flex items-center justify-center text-text-muted hover:text-text-primary hover:bg-bg-secondary rounded-lg transition-all duration-200">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>

        {{-- Body --}}
        <form id="listForm"
              data-ajax
              action="{{ route('api.v1.admin.product.custom-lists.store') }}"
              data-store-url="{{ route('api.v1.admin.product.custom-lists.store') }}"
              data-update-url="{{ route('api.v1.admin.product.custom-lists.update', ':id') }}"
              data-method="POST"
              data-on-success="reload">
            @csrf

            <div class="p-6 space-y-6">

                {{-- اطلاعات پایه --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    <x-panel::forms.input
                        name="name"
                        label="نام لیست"
                        placeholder="مثال: تأمین‌کنندگان"
                        :required="true"
                        class="min-w-[110px]"
                    />

                    <x-panel::forms.input
                        name="name_en"
                        label="نام انگلیسی"
                        placeholder="suppliers"
                        :required="true"
                        direction="ltr"
                        class="min-w-[110px]"
                    />
                </div>

                {{-- انتخاب رنگ --}}
                <x-panel::forms.color-field
                    name="color"
                    label="رنگ لیست"
                    :withCustomColor="false"
                    class="min-w-[110px]"
                />

                {{-- انتخاب آیکون --}}
                <x-panel::forms.icon-field
                    name="icon"
                    label="آیکون لیست"
                    :icons="$suggestedIcons"
                    class="min-w-[110px]"
                />

                {{-- فیلدهای سفارشی --}}
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <label class="text-sm font-medium text-text-secondary leading-normal">
                            <i class="fa-solid fa-sliders text-slate-400 ml-2"></i>
                            فیلدهای اختیاری
                            <span class="text-text-muted text-xs">(حداکثر ۵ فیلد)</span>
                        </label>
                    </div>
                    <p class="text-xs text-text-muted mb-4 leading-relaxed">
                        <i class="fa-solid fa-info-circle ml-1"></i>
                        برای هر فیلد یک لیبل (عنوان نمایشی) و نوع فیلد انتخاب کنید. فیلدهای خالی نادیده گرفته می‌شوند.
                    </p>

                    <div class="space-y-3">
                        @for ($i = 1; $i <= 5; $i++)
                            @php $persianNums = ['۱','۲','۳','۴','۵']; @endphp
                            <div class="flex items-stretch gap-3">
                                <x-panel::forms.input
                                    name="field{{ $i }}_label"
                                    label="فیلد {{ $persianNums[$i - 1] }}"
                                    placeholder="عنوان فیلد"
                                    class="min-w-[80px]"
                                />
                                <div class="w-[160px]">
                                    <x-panel::forms.tom-select
                                        name="field{{ $i }}_type"
                                        label="نوع"
                                        :options="['text' => 'متن', 'number' => 'عدد', 'date' => 'تاریخ', 'select' => 'انتخابی', 'textarea' => 'متن بلند']"
                                        value="text"
                                        class="min-w-[50px]"
                                    />
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>

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
                    ذخیره لیست
                </button>
            </div>
        </form>

    </div>
</div>
