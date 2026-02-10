<?php

declare(strict_types=1);

namespace App\Providers;

use App\Core\Organization\Entities\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Laravel\Telescope\Avatar;
use Laravel\Telescope\IncomingEntry;
use Laravel\Telescope\Telescope;
use Laravel\Telescope\TelescopeApplicationServiceProvider;

class TelescopeServiceProvider extends TelescopeApplicationServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Telescope::night();

        $this->hideSensitiveRequestDetails();

        $isLocal = $this->app->environment('local');

        Telescope::filter(function (IncomingEntry $entry) use ($isLocal) {
            return $isLocal || $entry->isReportableException() || $entry->isFailedRequest() || $entry->isFailedJob() || $entry->isScheduledTask() || $entry->hasMonitoredTag();
        });

        // Fix for Email Value Object compatibility with Telescope Avatar
        Avatar::register(function ($userId, $email) {
            // Handle Email Value Object
            if (is_array($email) && isset($email['value'])) {
                $email = $email['value'];
            } elseif (is_object($email)) {
                $email = (string) $email;
            }

            if (empty($email)) {
                return null;
            }

            return 'https://www.gravatar.com/avatar/' . md5(Str::lower($email)) . '?s=200';
        });
    }

    /**
     * Prevent sensitive request details from being logged by Telescope.
     */
    protected function hideSensitiveRequestDetails(): void
    {
        if ($this->app->environment('local')) {
            return;
        }

        Telescope::hideRequestParameters(['_token']);

        Telescope::hideRequestHeaders([
            'cookie',
            'x-csrf-token',
            'x-xsrf-token',
        ]);
    }

    /**
     * Register the Telescope gate.
     *
     * This gate determines who can access Telescope in non-local environments.
     */
    protected function gate(): void
    {
        Gate::define('viewTelescope', function (User $user) {
            return $user->hasRole('god');
        });
    }
}
