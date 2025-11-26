<?php

declare(strict_types=1);

namespace App\Core\Organization\Providers;

use App\Core\Organization\Entities\User;
use App\Core\Organization\Observers\UserFullNameObserver;
use App\Core\Organization\Observers\UserPasswordObserver;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $observers = [
        User::class => [
            UserPasswordObserver::class,
            UserFullNameObserver::class,
        ],
    ];
}
