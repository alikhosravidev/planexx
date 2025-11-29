<div id="accessModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl w-full max-w-lg shadow-2xl">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-border-light flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-user-shield text-amber-600"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-text-primary leading-snug">تنظیم سطح دسترسی</h3>
                    <p class="text-sm text-text-secondary leading-normal" id="accessModalUserName"></p>
                </div>
            </div>
            <button onclick="closeAccessModal()" class="w-8 h-8 flex items-center justify-center text-text-muted hover:text-text-primary hover:bg-bg-secondary rounded-lg transition-all duration-200">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>
        
        <!-- Body -->
        <div class="p-6 space-y-6">
            <!-- نقش کاربری اصلی -->
            <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-primary focus-within:shadow-focus transition-all duration-200">
                <div class="flex items-stretch">
                    <label class="bg-bg-label border-l border-border-light min-w-[130px] px-lg py-3.5 text-sm text-text-secondary flex items-center leading-normal">
                        <i class="fa-solid fa-star text-amber-500 ml-2"></i>
                        نقش اصلی
                    </label>
                    <select id="primaryRole" class="flex-1 px-lg py-3.5 text-base text-text-primary outline-none bg-transparent cursor-pointer leading-normal">
                        <option value="">انتخاب کنید</option>
                        <option value="1">مدیر سیستم</option>
                        <option value="2">مدیر ارشد</option>
                        <option value="3">مدیر فروش</option>
                        <option value="4">کارشناس فروش</option>
                        <option value="5">کارشناس فنی</option>
                        <option value="6">منابع انسانی</option>
                        <option value="7">مدیر مالی</option>
                        <option value="8">کارشناس مالی</option>
                        <option value="9">مدیر مارکتینگ</option>
                        <option value="10">کارشناس مارکتینگ</option>
                        <option value="11">کارشناس CRM</option>
                    </select>
                </div>
            </div>
            
            <!-- نقش‌های کاربری جانبی -->
            <div>
                <label class="block text-sm font-medium text-text-secondary mb-3 leading-normal">
                    <i class="fa-solid fa-tags text-slate-400 ml-2"></i>
                    نقش‌های جانبی
                    <span class="text-text-muted text-xs">(چند انتخابی)</span>
                </label>
                <div class="border border-border-medium rounded-xl p-4 bg-bg-tertiary space-y-3 max-h-[200px] overflow-y-auto">
                    <label class="flex items-center gap-3 p-2 hover:bg-white rounded-lg cursor-pointer transition-all duration-200">
                        <input type="checkbox" class="w-4 h-4 text-primary border-border-medium rounded focus:ring-primary focus:ring-offset-0" value="1">
                        <span class="text-sm text-text-primary leading-normal">مدیر سیستم</span>
                    </label>
                    <label class="flex items-center gap-3 p-2 hover:bg-white rounded-lg cursor-pointer transition-all duration-200">
                        <input type="checkbox" class="w-4 h-4 text-primary border-border-medium rounded focus:ring-primary focus:ring-offset-0" value="2">
                        <span class="text-sm text-text-primary leading-normal">مدیر ارشد</span>
                    </label>
                    <label class="flex items-center gap-3 p-2 hover:bg-white rounded-lg cursor-pointer transition-all duration-200">
                        <input type="checkbox" class="w-4 h-4 text-primary border-border-medium rounded focus:ring-primary focus:ring-offset-0" value="3">
                        <span class="text-sm text-text-primary leading-normal">مدیر فروش</span>
                    </label>
                    <label class="flex items-center gap-3 p-2 hover:bg-white rounded-lg cursor-pointer transition-all duration-200">
                        <input type="checkbox" class="w-4 h-4 text-primary border-border-medium rounded focus:ring-primary focus:ring-offset-0" value="4">
                        <span class="text-sm text-text-primary leading-normal">کارشناس فروش</span>
                    </label>
                    <label class="flex items-center gap-3 p-2 hover:bg-white rounded-lg cursor-pointer transition-all duration-200">
                        <input type="checkbox" class="w-4 h-4 text-primary border-border-medium rounded focus:ring-primary focus:ring-offset-0" value="5">
                        <span class="text-sm text-text-primary leading-normal">کارشناس فنی</span>
                    </label>
                    <label class="flex items-center gap-3 p-2 hover:bg-white rounded-lg cursor-pointer transition-all duration-200">
                        <input type="checkbox" class="w-4 h-4 text-primary border-border-medium rounded focus:ring-primary focus:ring-offset-0" value="6">
                        <span class="text-sm text-text-primary leading-normal">منابع انسانی</span>
                    </label>
                    <label class="flex items-center gap-3 p-2 hover:bg-white rounded-lg cursor-pointer transition-all duration-200">
                        <input type="checkbox" class="w-4 h-4 text-primary border-border-medium rounded focus:ring-primary focus:ring-offset-0" value="7">
                        <span class="text-sm text-text-primary leading-normal">مدیر مالی</span>
                    </label>
                    <label class="flex items-center gap-3 p-2 hover:bg-white rounded-lg cursor-pointer transition-all duration-200">
                        <input type="checkbox" class="w-4 h-4 text-primary border-border-medium rounded focus:ring-primary focus:ring-offset-0" value="8">
                        <span class="text-sm text-text-primary leading-normal">کارشناس مالی</span>
                    </label>
                    <label class="flex items-center gap-3 p-2 hover:bg-white rounded-lg cursor-pointer transition-all duration-200">
                        <input type="checkbox" class="w-4 h-4 text-primary border-border-medium rounded focus:ring-primary focus:ring-offset-0" value="9">
                        <span class="text-sm text-text-primary leading-normal">مدیر مارکتینگ</span>
                    </label>
                    <label class="flex items-center gap-3 p-2 hover:bg-white rounded-lg cursor-pointer transition-all duration-200">
                        <input type="checkbox" class="w-4 h-4 text-primary border-border-medium rounded focus:ring-primary focus:ring-offset-0" value="10">
                        <span class="text-sm text-text-primary leading-normal">کارشناس مارکتینگ</span>
                    </label>
                    <label class="flex items-center gap-3 p-2 hover:bg-white rounded-lg cursor-pointer transition-all duration-200">
                        <input type="checkbox" class="w-4 h-4 text-primary border-border-medium rounded focus:ring-primary focus:ring-offset-0" value="11">
                        <span class="text-sm text-text-primary leading-normal">کارشناس CRM</span>
                    </label>
                </div>
                <p class="text-xs text-text-muted mt-2 leading-normal">
                    <i class="fa-solid fa-info-circle ml-1"></i>
                    نقش‌های جانبی دسترسی‌های اضافی به کاربر اعطا می‌کنند.
                </p>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="px-6 py-4 border-t border-border-light flex items-center justify-end gap-3 bg-bg-secondary rounded-b-2xl">
            <button onclick="closeAccessModal()" class="px-6 py-2.5 border border-border-medium text-text-secondary rounded-lg font-medium hover:bg-white transition-all duration-200 text-base leading-normal">
                انصراف
            </button>
            <button onclick="saveAccessSettings()" class="px-6 py-2.5 bg-primary text-white rounded-lg font-medium hover:bg-primary/90 transition-all duration-200 text-base leading-normal">
                <i class="fa-solid fa-check ml-2"></i>
                ذخیره تغییرات
            </button>
        </div>
    </div>
</div>
