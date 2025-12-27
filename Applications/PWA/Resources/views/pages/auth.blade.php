<x-pwa::layouts.auth :title="trans('Organization::auth.login_title')">
    <x-pwa::auth.logo />

    <x-pwa::auth.back-button id="back-button" />

    <x-pwa::auth.card>
        <x-pwa::auth.mobile-step :action="route('api.v1.client.user.initiate.auth')" />

        <x-pwa::auth.otp-step
            :action="route('pwa.auth')"
            :resend-action="route('api.v1.client.user.initiate.auth')"
        />
    </x-pwa::auth.card>

    <x-pwa::auth.disclaimer />
</x-pwa::layouts.auth>
