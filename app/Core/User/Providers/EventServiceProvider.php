<?php

declare(strict_types=1);

namespace App\Core\User\Providers;

use App\Core\User\Entities\User;
use App\Core\User\Observers\UserBeforeCommitObserver;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $observers = [
        User::class => [
            UserBeforeCommitObserver::class,
        ],
    ];

    protected $listen = [
    ];
}
