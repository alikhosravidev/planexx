<div class="text-center mb-5xl">
    <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-primary to-gray-700 rounded-3xl mb-4 shadow-lg">
        <i class="fa-solid fa-layer-group text-3xl text-white"></i>
    </div>
    <h1 class="text-3xl font-bold text-white mb-2 leading-tight">
        {{ $title ?? trans('Organization::auth.login_to_planexx') }}
    </h1>
    <p class="text-base text-gray-300 leading-normal">
        {{ $subtitle ?? trans('Organization::auth.enter_mobile_number') }}
    </p>
</div>
