<?php

declare(strict_types=1);

namespace App\Providers;

use App\Core\Organization\Services\Query\DepartmentQueryServiceImplementation;
use App\Core\Organization\Services\Query\UserQueryImplementation;
use App\Domains\Department\DepartmentQuery;
use App\Domains\User\UserQuery;
use App\Services\QueryServiceLocator;
use Illuminate\Support\ServiceProvider;

class QueryServiceProvider extends ServiceProvider
{
    private array $queryServices = [
        'users'       => UserQuery::class,
        'departments' => DepartmentQuery::class,
    ];

    private array $implementations = [
        UserQuery::class       => UserQueryImplementation::class,
        DepartmentQuery::class => DepartmentQueryServiceImplementation::class,
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
