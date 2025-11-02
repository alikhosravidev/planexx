<?php

declare(strict_types=1);

namespace App\Core\User\Providers;

use App\Contracts\User\UserRepositoryInterface;
use App\Core\User\Repositories\UserRepository;
use App\Core\User\Services\Auth\DTOs\AuthConfig;
use App\Core\User\Services\Auth\DTOs\PasswordConfig;
use App\Core\User\Services\Auth\ProviderResolver;
use App\Core\User\Services\Auth\Providers\OTP;
use App\Core\User\Services\Auth\Providers\Password;
use App\Core\User\Services\OTPService\Contracts\OTPGenerator;
use App\Core\User\Services\OTPService\Generators\RealGenerator;
use App\Core\User\Services\OTPService\OTPConfig;
use App\Utilities\ProviderUtility;
use Illuminate\Support\ServiceProvider;

class UserServiceProvider extends ServiceProvider
{
    private const AUTH_PROVIDER_TAG = 'auth.provider';
    public function register(): void
    {
        $this->app->register(EventServiceProvider::class);
        $this->app->register(EventServiceProvider::class);

        // Bind OTPGenerator
        $this->app->bind(OTPGenerator::class, RealGenerator::class);

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

        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(
            ProviderUtility::corePath('User/Routes/V1/Admin/routes.php')
        );

        $this->loadMigrationsFrom(
            ProviderUtility::corePath('User/Database/Migrations')
        );

        $this->loadTranslationsFrom(
            ProviderUtility::corePath('User/Resources/lang'),
            'user'
        );
    }
}
