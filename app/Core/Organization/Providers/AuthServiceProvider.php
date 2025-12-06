<?php

declare(strict_types=1);

namespace App\Core\Organization\Providers;

use App\Core\Organization\Services\Auth\DTOs\AuthConfig;
use App\Core\Organization\Services\Auth\DTOs\PasswordConfig;
use App\Core\Organization\Services\Auth\ProviderResolver;
use App\Core\Organization\Services\Auth\Providers\OTP;
use App\Core\Organization\Services\Auth\Providers\Password;
use App\Core\Organization\Services\OTPService\Contracts\OTPGenerator;
use App\Core\Organization\Services\OTPService\Generators\FakeGenerator;
use App\Core\Organization\Services\OTPService\OTPConfig;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    private const AUTH_PROVIDER_TAG = 'auth.provider';

    public function register(): void
    {
        $this->app->bind(OTPGenerator::class, FakeGenerator::class);

        $this->app->singleton(OTP::class);
        $this->app->tag(OTP::class, self::AUTH_PROVIDER_TAG);
        $this->app->singleton(Password::class);
        $this->app->tag(Password::class, self::AUTH_PROVIDER_TAG);
        $this->app->singleton(ProviderResolver::class, function ($app) {
            return new ProviderResolver($app->tagged(self::AUTH_PROVIDER_TAG));
        });

        $this->app->singleton(OTPConfig::class, function () {
            return new OTPConfig(
                config('services.auth.otp.enabled'),
                (int) config('services.auth.otp.code_length'),
                (int) config('services.auth.otp.expires_in_minutes'),
                config('services.auth.otp.sms.pattern'),
            );
        });
        $this->app->singleton(PasswordConfig::class, function () {
            return new PasswordConfig(
                config('services.auth.password.validation_regex')
            );
        });
        $this->app->singleton(AuthConfig::class, function ($app) {
            return new AuthConfig(
                otpConfig                : $app->make(OTPConfig::class),
                passwordConfig           : $app->make(PasswordConfig::class),
                authGuard                : config('services.auth.guard'),
                authTokenName            : config('sanctum.token_name'),
                usernameValidationRegex  : config('services.auth.username_validation.regex'),
                usernameValidationMessage: config('services.auth.username_validation.error_message'),
            );
        });
    }
}
