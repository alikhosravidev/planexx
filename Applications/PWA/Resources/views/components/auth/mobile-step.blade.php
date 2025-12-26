@props(['action', 'customAction' => 'show-otp-step'])

<div id="step-mobile" class="p-10 md:p-5xl">
    <form id="mobile-form"
          data-ajax
          action="{{ $action }}"
          data-method="GET"
          data-on-success="custom"
          custom-action="{{ $customAction }}"
          data-loading-class="opacity-50 pointer-events-none">

        <div class="mb-3xl">
            <input type="tel"
                   id="mobile-input"
                   name="identifier"
                   required
                   maxlength="11"
                   placeholder="09112345678"
                   autocomplete="off"
                   class="w-full text-center text-2xl font-semibold text-text-primary bg-transparent border-2 border-border-medium rounded-2xl px-6 py-4 outline-none focus:border-primary focus:shadow-focus transition-all duration-200 leading-normal"
                   dir="ltr">
            <input type="hidden" name="authType" value="otp">
            <p class="text-sm text-text-muted mt-2 text-center leading-normal">
                {{ trans('Organization::auth.mobile_format_hint') }}
            </p>
        </div>

        <button type="submit"
                id="mobile-submit-btn"
                class="w-full bg-primary text-white text-base font-medium rounded-xl py-4 hover:bg-gray-800 hover:-translate-y-0.5 hover:shadow-button transition-all duration-200 flex items-center justify-center gap-2 leading-normal">
            <span class="btn-text">{{ trans('Organization::auth.get_verification_code') }}</span>
            <i class="fa-solid fa-arrow-left btn-icon"></i>
            <i class="fa-solid fa-spinner fa-spin btn-loading hidden"></i>
        </button>

    </form>
</div>
