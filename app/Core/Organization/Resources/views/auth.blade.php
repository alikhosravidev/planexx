@extends('layouts.base')

@section('title', trans('Organization::auth.login_title'))

@section('content')

    <!-- Auth Container -->
    <div class="w-full max-w-md">

        <!-- Logo & Title -->
        <div class="text-center mb-5xl">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-primary to-gray-700 rounded-3xl mb-4 shadow-lg">
                <i class="fa-solid fa-layer-group text-3xl text-white"></i>
            </div>
            <h1 class="text-3xl font-bold text-text-primary mb-2 leading-tight">
                {{ trans('Organization::auth.login_to_planexx') }}
            </h1>
            <p class="text-base text-text-secondary leading-normal">
                {{ trans('Organization::auth.enter_mobile_number') }}
            </p>
        </div>

        <!-- Back Button (Outside Card) -->
        <div id="back-button-container" class="hidden mb-3 pr-5">
            <button type="button"
                    id="back-button"
                    class="flex items-center gap-1.5 text-text-muted hover:text-text-primary transition-all duration-200 text-sm leading-normal">
                <i class="fa-solid fa-arrow-right text-xs"></i>
                <span>{{ trans('Organization::auth.back') }}</span>
            </button>
        </div>

        <!-- Auth Card -->
        <div class="bg-bg-primary border border-border-light rounded-3xl shadow-lg overflow-hidden">

            <!-- Step 1: Mobile Input -->
            <div id="step-mobile" class="p-10 md:p-5xl">
                <form id="mobile-form"
                      data-ajax
                      action="{{ route('user.initiate.auth') }}"
                      data-method="GET"
                      data-on-success="custom"
                      data-after-success="show-otp-step"
                      data-loading-class="opacity-50 pointer-events-none">

                    <!-- Mobile Input -->
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

                    <!-- Submit Button -->
                    <button type="submit"
                            id="mobile-submit-btn"
                            class="w-full bg-primary text-white text-base font-medium rounded-xl py-4 hover:bg-gray-800 hover:-translate-y-0.5 hover:shadow-button transition-all duration-200 flex items-center justify-center gap-2 leading-normal">
                        <span class="btn-text">{{ trans('Organization::auth.get_verification_code') }}</span>
                        <i class="fa-solid fa-arrow-left btn-icon"></i>
                        <i class="fa-solid fa-spinner fa-spin btn-loading hidden"></i>
                    </button>

                </form>
            </div>

            <!-- Step 2: OTP Input -->
            <div id="step-otp" class="p-10 md:p-5xl hidden">

                <form id="otp-form"
                      data-ajax
                      action="{{ route('user.auth') }}"
                      data-method="POST"
                      data-on-success="custom"
                      data-after-success="login-success"
                      data-loading-class="opacity-50 pointer-events-none">
                    @csrf

                    <!-- Info Text -->
                    <div class="text-center mb-4">
                        <p class="text-base text-text-secondary leading-relaxed">
                            {{ trans('Organization::auth.otp_sent_to') }}
                            <strong id="mobile-display" class="text-text-primary" dir="ltr"></strong>
                        </p>
                    </div>

                    <!-- Hidden inputs for form submission -->
                    <input type="hidden" name="identifier" id="otp-identifier">
                    <input type="hidden" name="password" id="otp-password">
                    <input type="hidden" name="authType" value="otp">

                    <!-- OTP Inputs -->
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

                    <!-- Resend Timer -->
                    <div class="text-center mb-3xl">
                        <p class="text-sm text-text-muted leading-normal">
                            {{ trans('Organization::auth.code_not_received') }}
                        </p>
                        <button type="button"
                                id="resend-button"
                                data-ajax
                                data-action="{{ route('user.initiate.auth') }}"
                                data-method="GET"
                                data-on-success="custom"
                                data-after-success="resend-success"
                                data-show-message="true"
                                disabled
                                class="text-sm font-medium text-text-muted mt-1 leading-normal transition-colors disabled:opacity-50 disabled:cursor-not-allowed enabled:hover:text-primary enabled:cursor-pointer">
                            {{ trans('Organization::auth.resend') }} (<span id="timer">60</span>)
                        </button>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                            id="otp-submit"
                            class="w-full bg-primary text-white text-base font-medium rounded-xl py-4 hover:bg-gray-800 hover:-translate-y-0.5 hover:shadow-button transition-all duration-200 leading-normal">
                        {{ trans('Organization::auth.login') }}
                    </button>

                </form>

            </div>

        </div>

        <!-- Info -->
        <p class="text-center text-sm text-text-muted mt-4 leading-normal">
            {{ trans('Organization::auth.login_disclaimer') }}
            <a href="#" class="text-primary hover:underline">{{ trans('Organization::auth.terms_and_conditions') }}</a>
        </p>

    </div>

@endsection
