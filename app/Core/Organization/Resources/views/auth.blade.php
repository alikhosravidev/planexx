<x-layouts.auth :title="trans('Organization::auth.login_title')">
    <x-Organization::auth.logo />

    <x-Organization::auth.back-button id="back-button" />

    <x-Organization::auth.card>
        <x-Organization::auth.mobile-step :action="route('api.v1.admin.user.initiate.auth')" />

        <x-Organization::auth.otp-step
            :action="route('auth')"
            :resend-action="route('api.v1.admin.user.initiate.auth')"
        />
    </x-Organization::auth.card>

    <x-Organization::auth.disclaimer />
</x-layouts.auth>
