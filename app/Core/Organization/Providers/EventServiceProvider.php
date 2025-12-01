<?php

declare(strict_types=1);

namespace App\Core\Organization\Providers;

use App\Core\Organization\Entities\User;
use App\Core\Organization\events\UserCreated;
use App\Core\Organization\events\UserUpdated;
use App\Core\Organization\Listeners\OnUserCreated;
use App\Core\Organization\Listeners\OnUserUpdated;
use App\Core\Organization\Observers\UserFullNameObserver;
use App\Core\Organization\Observers\UserPasswordObserver;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        UserCreated::class => [OnUserCreated::class],
        UserUpdated::class => [OnUserUpdated::class],
    ];

    protected $observers = [
        User::class => [
            UserPasswordObserver::class,
            UserFullNameObserver::class,
        ],
    ];
}
