<?php

declare(strict_types=1);

namespace App\Tests\Integration\Services\Stats;

use App\Contracts\Registry\RegistrarInterface;
use App\Contracts\Registry\RegistryManagerInterface;
use App\Services\Stats\StatBuilder;
use App\Services\Stats\StatManager;
use InvalidArgumentException;
use Tests\IntegrationTestBase;

final class StatRegistrarTest extends IntegrationTestBase
{
    private StatManager $manager;

    protected function setUp(): void
    {
        parent::setUp();
        $this->manager = new StatManager();
    }

    public function test_registers_stats_using_registrar_class(): void
    {
        $registrarClass = new class () implements RegistrarInterface {
            public function register(RegistryManagerInterface $manager): void
            {
                $manager->register('test.stats', function (StatBuilder $builder) {
                    $builder->stat('تست از Registrar', 'registrar-test')
                        ->value('۱۰۰')
                        ->order(1);
                });
            }
        };

        $this->app->instance(TestStatRegistrar::class, $registrarClass);

        $result = $this->manager->registerBy(TestStatRegistrar::class);

        $this->assertSame($this->manager, $result);
        $this->assertTrue($this->manager->has('test.stats'));

        $items = $this->manager->withoutCache()->get('test.stats');
        $this->assertCount(1, $items);
        $this->assertSame('registrar-test', $items->first()->getId());
    }

    public function test_throws_exception_when_registrar_does_not_implement_interface(): void
    {
        $invalidClass = new class () {
            public function register(): void
            {
            }
        };

        $this->app->instance(InvalidRegistrar::class, $invalidClass);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('must implement');

        $this->manager->registerBy(InvalidRegistrar::class);
    }

    public function test_registrar_can_register_multiple_stats(): void
    {
        $registrarClass = new class () implements RegistrarInterface {
            public function register(RegistryManagerInterface $manager): void
            {
                $manager->register('multi.stats', function (StatBuilder $builder) {
                    $builder->stat('آمار ۱', 'stat-1')->value('۱۰')->order(1);
                    $builder->stat('آمار ۲', 'stat-2')->value('۲۰')->order(2);
                    $builder->stat('آمار ۳', 'stat-3')->value('۳۰')->order(3);
                });
            }
        };

        $this->app->instance(MultiStatRegistrar::class, $registrarClass);

        $this->manager->registerBy(MultiStatRegistrar::class);

        $items = $this->manager->withoutCache()->get('multi.stats');
        $this->assertCount(3, $items);
    }

    public function test_registrar_can_register_to_different_registries(): void
    {
        $registrarClass = new class () implements RegistrarInterface {
            public function register(RegistryManagerInterface $manager): void
            {
                $manager->register('dashboard.stats', function (StatBuilder $builder) {
                    $builder->stat('داشبورد', 'dashboard-stat')->value('۱۰۰');
                });

                $manager->register('reports.stats', function (StatBuilder $builder) {
                    $builder->stat('گزارش', 'report-stat')->value('۲۰۰');
                });
            }
        };

        $this->app->instance(MultiRegistryStatRegistrar::class, $registrarClass);

        $this->manager->registerBy(MultiRegistryStatRegistrar::class);

        $this->assertTrue($this->manager->has('dashboard.stats'));
        $this->assertTrue($this->manager->has('reports.stats'));

        $dashboardItems = $this->manager->withoutCache()->get('dashboard.stats');
        $reportItems    = $this->manager->withoutCache()->get('reports.stats');

        $this->assertCount(1, $dashboardItems);
        $this->assertCount(1, $reportItems);
    }

    public function test_multiple_registrars_can_register_to_same_registry(): void
    {
        $registrar1 = new class () implements RegistrarInterface {
            public function register(RegistryManagerInterface $manager): void
            {
                $manager->register('shared.stats', function (StatBuilder $builder) {
                    $builder->stat('از Registrar اول', 'first')->value('۱')->order(1);
                });
            }
        };

        $registrar2 = new class () implements RegistrarInterface {
            public function register(RegistryManagerInterface $manager): void
            {
                $manager->register('shared.stats', function (StatBuilder $builder) {
                    $builder->stat('از Registrar دوم', 'second')->value('۲')->order(2);
                });
            }
        };

        $this->app->instance(FirstStatRegistrar::class, $registrar1);
        $this->app->instance(SecondStatRegistrar::class, $registrar2);

        $this->manager->registerBy(FirstStatRegistrar::class);
        $this->manager->registerBy(SecondStatRegistrar::class);

        $items = $this->manager->withoutCache()->get('shared.stats');
        $this->assertCount(2, $items);
        $this->assertSame('first', $items[0]->getId());
        $this->assertSame('second', $items[1]->getId());
    }
}

// Dummy classes for testing
class TestStatRegistrar
{
}
class InvalidRegistrar
{
}
class MultiStatRegistrar
{
}
class MultiRegistryStatRegistrar
{
}
class FirstStatRegistrar
{
}
class SecondStatRegistrar
{
}
