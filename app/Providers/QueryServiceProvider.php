<?php

declare(strict_types=1);

namespace App\Providers;

use App\Core\Organization\Services\Adaptor\DepartmentQueryServiceImplementation;
use App\Core\User\Services\Query\UserQueryServiceImplementation;
use App\Query\Domains\Department\DepartmentQueryService;
use App\Query\Domains\User\UserQueryService;
use App\Query\Services\QueryServiceLocator;
use Illuminate\Support\ServiceProvider;

class QueryServiceProvider extends ServiceProvider
{
    private array $queryServices = [
        'users'       => UserQueryService::class,
        'departments' => DepartmentQueryService::class,
    ];

    private array $implementations = [
        UserQueryService::class       => UserQueryServiceImplementation::class,
        DepartmentQueryService::class => DepartmentQueryServiceImplementation::class,
    ];

    public function register(): void
    {
        $this->app->singleton(QueryServiceLocator::class, function () {
            $locator = new QueryServiceLocator();

            foreach ($this->queryServices as $context => $interface) {
                $locator->register($context, $interface);
            }

            return $locator;
        });

        foreach ($this->implementations as $interface => $implementation) {
            $this->app->bind($interface, $implementation);
        }
    }

    public function boot(): void
    {
    }
}
