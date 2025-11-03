<?php

declare(strict_types=1);

namespace App\Core\User\Providers;

use App\Core\User\Entities\User;
use App\Core\User\Observers\UserFullNameObserver;
use App\Core\User\Observers\UserPasswordObserver;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $observers = [
        User::class => [
            UserPasswordObserver::class,
            UserFullNameObserver::class,
        ],
    ];

    protected $listen = [
    ];
}
