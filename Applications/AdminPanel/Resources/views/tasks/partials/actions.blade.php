{{--Action buttons section--}}

<div class="bg-bg-primary border border-border-light rounded-2xl p-5">
    <div class="space-y-3">
        <button {{ $nextState === null ? 'disabled' : '' }} type="button" data-modal-open="forwardModal"
                class="w-full h-12 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-medium transition-all flex items-center justify-center gap-2 shadow-sm {{ $nextState === null ? 'opacity-60 cursor-not-allowed' : '' }}">
            <i class="fa-solid fa-paper-plane"></i>
            ارجاع به مرحله بعد
        </button>
        <button type="button" data-modal-open="followUpModal"
                class="w-full h-12 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-medium transition-all flex items-center justify-center gap-2">
            <i class="fa-solid fa-comment"></i>
            ثبت یادداشت
        </button>
    </div>
</div>
