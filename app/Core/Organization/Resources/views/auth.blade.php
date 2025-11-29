<x-layouts.auth :title="trans('Organization::auth.login_title')">
    <x-auth.logo />

    <x-auth.back-button id="back-button" />

    <x-auth.card>
        <x-auth.mobile-step :action="route('api.v1.admin.user.initiate.auth')" />

        <x-auth.otp-step
            :action="route('web.auth')"
            :resend-action="route('api.v1.admin.user.initiate.auth')"
        />
    </x-auth.card>

    <x-auth.disclaimer />
</x-layouts.auth>
