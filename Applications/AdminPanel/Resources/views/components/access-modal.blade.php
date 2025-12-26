<div id="accessModal" data-modal data-modal-backdrop class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl w-full max-w-lg shadow-2xl">
        <form data-ajax action="#" method="POST" data-on-success="reload">
            @method('PUT')
            @csrf
            <button type="button" id="prefillRolesBtn" data-method="GET" class="hidden"></button>

            <!-- Header -->
            <div class="px-6 py-4 border-b border-border-light flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center">
                        <i class="fa-solid fa-user-shield text-amber-600"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-text-primary leading-snug">تنظیم سطح دسترسی</h3>
                        <p class="text-sm text-text-secondary leading-normal" data-modal-username></p>
                    </div>
                </div>
                <button type="button" data-modal-close
                        class="w-8 h-8 flex items-center justify-center text-text-muted hover:text-text-primary hover:bg-bg-secondary rounded-lg transition-all duration-200">
                    <i class="fa-solid fa-times"></i>
                </button>
            </div>

            <div class="p-6 space-y-6">
                <x-panel::forms.tom-select
                    name="primary_role"
                    id="primaryRole"
                    label="نقش اصلی"
                    icon="fa-solid fa-star text-amber-500"
                    placeholder="انتخاب کنید"
                    :options="$roles"
                    required
                />

                <!--  TODO: load roles using ajax request -->
                <div>
                    <label class="block text-sm font-medium text-text-secondary mb-3 leading-normal">
                        <i class="fa-solid fa-tags text-slate-400 ml-2"></i>
                        نقش‌های جانبی
                        <span class="text-text-muted text-xs">(چند انتخابی)</span>
                    </label>
                    <div class="border border-border-medium rounded-xl p-4 bg-bg-tertiary space-y-3 max-h-[200px] overflow-y-auto">
                        @if(!empty($roles))
                            @foreach($roles as $id => $title)
                                <label class="flex items-center gap-3 p-2 hover:bg-white rounded-lg cursor-pointer transition-all duration-200">
                                    <input type="checkbox" name="secondary_roles[]"
                                           class="w-4 h-4 text-primary border-border-medium rounded focus:ring-primary focus:ring-offset-0" value="{{ $id }}">
                                    <span class="text-sm text-text-primary leading-normal">{{ $title }}</span>
                                </label>
                            @endforeach
                        @endif
                    </div>
                    <p class="text-xs text-text-muted mt-2 leading-normal">
                        <i class="fa-solid fa-info-circle ml-1"></i>
                        نقش‌های جانبی دسترسی‌های اضافی به کاربر اعطا می‌کنند.
                    </p>
                </div>
            </div>

            <!-- Footer -->
            <div class="px-6 py-4 border-t border-border-light flex items-center justify-end gap-3 bg-bg-secondary rounded-b-2xl">
                <button type="button" data-modal-close
                        class="px-6 py-2.5 border border-border-medium text-text-secondary rounded-lg font-medium hover:bg-white transition-all duration-200 text-base leading-normal">
                    انصراف
                </button>
                <button type="submit"
                        class="px-6 py-2.5 bg-primary text-white rounded-lg font-medium hover:bg-primary/90 transition-all duration-200 text-base leading-normal">
                    <i class="fa-solid fa-check ml-2"></i>
                    ذخیره تغییرات
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const modal = document.getElementById('accessModal');
        if (!modal) return;

        modal.addEventListener('modal:data-loaded', (e) => {
            const {userId, userName} = e.detail;

            const usernameEl = modal.querySelector('[data-modal-username]');
            if (usernameEl && userName) {
                usernameEl.textContent = userName;
            }

            const form = modal.querySelector('form[data-ajax]');
            if (form && userId) {
                const url = window.route('api.v1.admin.org.users.roles.update', {user: userId});
                form.setAttribute('action', url);
            }

            // Prefill current roles using AJAX system
            if (userId) {
                const prefillBtn = modal.querySelector('#prefillRolesBtn');
                if (prefillBtn) {
                    const showUrl = window.route('api.v1.admin.org.users.roles.show', {user: userId});
                    prefillBtn.setAttribute('action', showUrl);
                    prefillBtn.setAttribute('data-action', showUrl);

                    const onSuccess = (event) => {
                        try {
                            const responsePayload = event.detail?.response || {};
                            const data = event.detail?.data || responsePayload.data || {};
                            const payload = responsePayload.result || data.result || data.data || data || {};
                            const primary = payload.primary_role ?? null;
                            const secondary = Array.isArray(payload.secondary_roles) ? payload.secondary_roles : [];

                            const primarySelect = modal.querySelector('#primaryRole');
                            if (primarySelect) {
                                primarySelect.value = primary != null ? String(primary) : '';
                                primarySelect.dispatchEvent(new Event('change', {bubbles: true}));
                            }

                            const checkboxes = modal.querySelectorAll('input[name="secondary_roles[]"]');
                            const secondaryStr = secondary.map(String);
                            checkboxes.forEach(cb => {
                                const shouldCheck = secondaryStr.includes(cb.value);
                                if (cb.checked !== shouldCheck) {
                                    cb.checked = shouldCheck;
                                    cb.dispatchEvent(new Event('change', {bubbles: true}));
                                }
                            });
                        } catch (_) {
                            // ignore
                        } finally {
                            prefillBtn.removeEventListener('ajax:success', onSuccess, {once: true});
                        }
                    };

                    prefillBtn.addEventListener('ajax:success', onSuccess, {once: true});
                    // Add data-ajax only after action is ready to avoid init errors
                    prefillBtn.setAttribute('data-ajax', '');
                    prefillBtn.click();
                }
            }
        });

        modal.addEventListener('modal:closed', () => {
            const form = modal.querySelector('form[data-ajax]');
            if (form) {
                form.reset();
                form.setAttribute('action', '#');
            }
            const usernameEl = modal.querySelector('[data-modal-username]');
            if (usernameEl) {
                usernameEl.textContent = '';
            }
        });
    });
</script>
