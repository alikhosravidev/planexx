<?php

namespace App\Core\Notify\Providers;

use App\Core\Notify\Services\SmsServiceProvider\Contracts\SmsProvider;
use App\Core\Notify\Services\SmsServiceProvider\Providers\FakeSms;
use Illuminate\Support\ServiceProvider;

class NotifyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(SmsProvider::class, static function () {
            return resolve(FakeSms::class);
        });
    }
}
