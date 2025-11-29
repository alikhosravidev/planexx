<?php

declare(strict_types=1);

namespace App\Tests\Integration\Services\QuickAccess;

use App\Contracts\Registry\RegistrarInterface;
use App\Contracts\Registry\RegistryManagerInterface;
use App\Services\QuickAccess\QuickAccessBuilder;
use App\Services\QuickAccess\QuickAccessManager;
use InvalidArgumentException;
use Tests\IntegrationTestBase;

final class QuickAccessRegistrarTest extends IntegrationTestBase
{
    private QuickAccessManager $manager;

    protected function setUp(): void
    {
        parent::setUp();
        $this->manager = new QuickAccessManager();
    }

    public function test_registers_quick_access_using_registrar_class(): void
    {
        $registrarClass = new class () implements RegistrarInterface {
            public function register(RegistryManagerInterface $manager): void
            {
                $manager->register('test.quick', function (QuickAccessBuilder $builder) {
                    $builder->item('تست از Registrar', 'registrar-test')
                        ->url('/test')
                        ->order(1);
                });
            }
        };

        $this->app->instance(TestQuickAccessRegistrar::class, $registrarClass);

        $result = $this->manager->registerBy(TestQuickAccessRegistrar::class);

        $this->assertSame($this->manager, $result);
        $this->assertTrue($this->manager->has('test.quick'));

        $items = $this->manager->withoutCache()->get('test.quick');
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

        $this->app->instance(InvalidQuickAccessRegistrar::class, $invalidClass);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('must implement');

        $this->manager->registerBy(InvalidQuickAccessRegistrar::class);
    }

    public function test_registrar_can_register_multiple_items(): void
    {
        $registrarClass = new class () implements RegistrarInterface {
            public function register(RegistryManagerInterface $manager): void
            {
                $manager->register('multi.quick', function (QuickAccessBuilder $builder) {
                    $builder->item('آیتم ۱', 'item-1')->url('/item-1')->order(1);
                    $builder->item('آیتم ۲', 'item-2')->url('/item-2')->order(2);
                    $builder->item('آیتم ۳', 'item-3')->url('/item-3')->order(3);
                });
            }
        };

        $this->app->instance(MultiQuickAccessRegistrar::class, $registrarClass);

        $this->manager->registerBy(MultiQuickAccessRegistrar::class);

        $items = $this->manager->withoutCache()->get('multi.quick');
        $this->assertCount(3, $items);
    }

    public function test_registrar_can_register_to_different_registries(): void
    {
        $registrarClass = new class () implements RegistrarInterface {
            public function register(RegistryManagerInterface $manager): void
            {
                $manager->register('dashboard.quick', function (QuickAccessBuilder $builder) {
                    $builder->item('داشبورد', 'dashboard-item')->url('/dashboard');
                });

                $manager->register('admin.quick', function (QuickAccessBuilder $builder) {
                    $builder->item('مدیریت', 'admin-item')->url('/admin');
                });
            }
        };

        $this->app->instance(MultiRegistryQuickAccessRegistrar::class, $registrarClass);

        $this->manager->registerBy(MultiRegistryQuickAccessRegistrar::class);

        $this->assertTrue($this->manager->has('dashboard.quick'));
        $this->assertTrue($this->manager->has('admin.quick'));

        $dashboardItems = $this->manager->withoutCache()->get('dashboard.quick');
        $adminItems     = $this->manager->withoutCache()->get('admin.quick');

        $this->assertCount(1, $dashboardItems);
        $this->assertCount(1, $adminItems);
    }

    public function test_multiple_registrars_can_register_to_same_registry(): void
    {
        $registrar1 = new class () implements RegistrarInterface {
            public function register(RegistryManagerInterface $manager): void
            {
                $manager->register('shared.quick', function (QuickAccessBuilder $builder) {
                    $builder->item('از Registrar اول', 'first')->url('/first')->order(1);
                });
            }
        };

        $registrar2 = new class () implements RegistrarInterface {
            public function register(RegistryManagerInterface $manager): void
            {
                $manager->register('shared.quick', function (QuickAccessBuilder $builder) {
                    $builder->item('از Registrar دوم', 'second')->url('/second')->order(2);
                });
            }
        };

        $this->app->instance(FirstQuickAccessRegistrar::class, $registrar1);
        $this->app->instance(SecondQuickAccessRegistrar::class, $registrar2);

        $this->manager->registerBy(FirstQuickAccessRegistrar::class);
        $this->manager->registerBy(SecondQuickAccessRegistrar::class);

        $items = $this->manager->withoutCache()->get('shared.quick');
        $this->assertCount(2, $items);
        $this->assertSame('first', $items[0]->getId());
        $this->assertSame('second', $items[1]->getId());
    }
}

// Dummy classes for testing
class TestQuickAccessRegistrar
{
}
class InvalidQuickAccessRegistrar
{
}
class MultiQuickAccessRegistrar
{
}
class MultiRegistryQuickAccessRegistrar
{
}
class FirstQuickAccessRegistrar
{
}
class SecondQuickAccessRegistrar
{
}
