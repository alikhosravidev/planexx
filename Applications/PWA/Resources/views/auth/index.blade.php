<x-panel::layouts.auth :title="trans('Organization::auth.login_title')">
    <x-panel::auth.logo />

    <x-panel::auth.back-button id="back-button" />

    <x-panel::auth.card>
        <x-panel::auth.mobile-step :action="route('api.v1.client.user.initiate.auth')" />

        <x-panel::auth.otp-step
            :action="route('pwa.auth')"
            :resend-action="route('api.v1.client.user.initiate.auth')"
        />
    </x-panel::auth.card>

    <x-panel::auth.disclaimer />
</x-panel::layouts.auth>
