@props(['action', 'resendAction'])

<div id="step-otp" class="p-10 md:p-5xl hidden">
    <form id="otp-form"
          data-ajax
          action="{{ $action }}"
          data-method="POST"
          data-on-success="redirect"
          data-loading-class="opacity-50 pointer-events-none">
        @csrf

        <div class="text-center mb-4">
            <p class="text-base text-text-secondary leading-relaxed">
                {{ trans('Organization::auth.otp_sent_to') }}
                <strong id="mobile-display" class="text-text-primary" dir="ltr"></strong>
            </p>
        </div>

        <input type="hidden" name="identifier" id="otp-identifier">
        <input type="hidden" name="password" id="otp-password">
        <input type="hidden" name="authType" value="otp">

        <div class="flex items-center justify-center gap-3 mb-3xl" dir="ltr">
            @for($i = 0; $i < 4; $i++)
                <input type="text"
                       maxlength="1"
                       autocomplete="off"
                       class="otp-input w-16 h-16 text-center text-2xl font-bold text-text-primary bg-bg-secondary border-2 border-border-medium rounded-xl outline-none focus:border-primary focus:bg-bg-primary focus:shadow-focus transition-all duration-200"
                       data-index="{{ $i }}"
                       inputmode="numeric"
                       pattern="[0-9]">
            @endfor
        </div>

        <div class="text-center mb-3xl">
            <p class="text-sm text-text-muted leading-normal">
                {{ trans('Organization::auth.code_not_received') }}
            </p>
            <button type="button"
                    id="resend-button"
                    data-ajax
                    data-action="{{ $resendAction }}"
                    data-method="GET"
                    data-on-success="custom"
                    custom-action="resend-success"
                    data-show-message="true"
                    disabled
                    class="text-sm font-medium text-text-muted mt-1 leading-normal transition-colors disabled:opacity-50 disabled:cursor-not-allowed enabled:hover:text-primary enabled:cursor-pointer">
                {{ trans('Organization::auth.resend') }} (<span id="timer">60</span>)
            </button>
        </div>

        <button type="submit"
                id="otp-submit"
                class="w-full bg-primary text-white text-base font-medium rounded-xl py-4 hover:bg-gray-800 hover:-translate-y-0.5 hover:shadow-button transition-all duration-200 leading-normal">
            {{ trans('Organization::auth.login') }}
        </button>

    </form>
</div>
