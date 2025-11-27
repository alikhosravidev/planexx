<footer class="bg-gray-900 text-white mt-16">
    <div class="max-w-[1400px] mx-auto px-8 py-20">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            
            <div>
                <h3 class="text-lg font-bold mb-4 leading-snug">درباره {{ config('app.name') }}</h3>
                <p class="text-gray-400 leading-relaxed">{{ $description ?? 'سیستم یکپارچه مدیریت سازمانی' }}</p>
            </div>
            
            <div>
                <h3 class="text-lg font-bold mb-4 leading-snug">لینک‌های مفید</h3>
                <ul class="space-y-2 text-gray-400">
                    <li><a href="#" class="hover:text-white transition-all duration-200 leading-normal">تماس با ما</a></li>
                    <li><a href="#" class="hover:text-white transition-all duration-200 leading-normal">درباره ما</a></li>
                    <li><a href="#" class="hover:text-white transition-all duration-200 leading-normal">قوانین و مقررات</a></li>
                    <li><a href="#" class="hover:text-white transition-all duration-200 leading-normal">راهنمای استفاده</a></li>
                </ul>
            </div>
            
            <div>
                <h3 class="text-lg font-bold mb-4 leading-snug">تماس با ما</h3>
                <ul class="space-y-2 text-gray-400">
                    <li class="flex items-center gap-2 leading-normal">
                        <i class="fa-solid fa-envelope"></i>
                        <span>info@sapel.ir</span>
                    </li>
                    <li class="flex items-center gap-2 leading-normal">
                        <i class="fa-solid fa-phone"></i>
                        <span>۰۲۱-۱۲۳۴۵۶۷۸</span>
                    </li>
                    <li class="flex items-center gap-2 leading-normal">
                        <i class="fa-solid fa-location-dot"></i>
                        <span>تهران، ایران</span>
                    </li>
                </ul>
            </div>
            
        </div>
        
        <div class="border-t border-gray-800 mt-8 pt-6 text-center text-gray-500">
            <p class="leading-normal">تمامی حقوق محفوظ است © {{ date('Y') }} - {{ config('app.name') }}</p>
        </div>
    </div>
</footer>
