<?php

declare(strict_types=1);

namespace App\Tests\Integration\Services\Menu;

use App\Contracts\MenuRegistrar;
use App\Contracts\Registry\RegistrarInterface;
use App\Contracts\Registry\RegistryManagerInterface;
use App\Services\Menu\MenuBuilder;
use App\Services\Menu\MenuManager;
use InvalidArgumentException;
use Tests\IntegrationTestBase;

final class MenuRegistrarTest extends IntegrationTestBase
{
    private MenuManager $manager;

    protected function setUp(): void
    {
        parent::setUp();
        $this->manager = new MenuManager();
    }

    public function test_registers_menu_using_new_registrar_interface(): void
    {
        $registrarClass = new class () implements RegistrarInterface {
            public function register(RegistryManagerInterface $manager): void
            {
                $manager->register('test.menu', function (MenuBuilder $builder) {
                    $builder->item('تست از Registrar', 'registrar-test')
                        ->url('/test')
                        ->order(1);
                });
            }
        };

        $this->app->instance(TestMenuRegistrar::class, $registrarClass);

        $result = $this->manager->registerBy(TestMenuRegistrar::class);

        $this->assertSame($this->manager, $result);
        $this->assertTrue($this->manager->has('test.menu'));

        $items = $this->manager->withoutCache()->get('test.menu');
        $this->assertCount(1, $items);
        $this->assertSame('registrar-test', $items->first()->getId());
    }

    public function test_registers_menu_using_legacy_menu_registrar_interface(): void
    {
        $legacyRegistrar = new class () implements MenuRegistrar {
            public function register(MenuManager $menu): void
            {
                $menu->register('legacy.menu', function (MenuBuilder $builder) {
                    $builder->item('تست Legacy', 'legacy-test')
                        ->url('/legacy')
                        ->order(1);
                });
            }
        };

        $this->app->instance(LegacyMenuRegistrar::class, $legacyRegistrar);

        $result = $this->manager->registerBy(LegacyMenuRegistrar::class);

        $this->assertSame($this->manager, $result);
        $this->assertTrue($this->manager->has('legacy.menu'));

        $items = $this->manager->withoutCache()->get('legacy.menu');
        $this->assertCount(1, $items);
        $this->assertSame('legacy-test', $items->first()->getId());
    }

    public function test_throws_exception_when_registrar_implements_neither_interface(): void
    {
        $invalidClass = new class () {
            public function register(): void
            {
            }
        };

        $this->app->instance(InvalidMenuRegistrar::class, $invalidClass);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('must implement');

        $this->manager->registerBy(InvalidMenuRegistrar::class);
    }

    public function test_registrar_can_register_multiple_menus(): void
    {
        $registrarClass = new class () implements RegistrarInterface {
            public function register(RegistryManagerInterface $manager): void
            {
                $manager->register('multi.menu', function (MenuBuilder $builder) {
                    $builder->item('منو ۱', 'menu-1')->url('/menu-1')->order(1);
                    $builder->item('منو ۲', 'menu-2')->url('/menu-2')->order(2);
                    $builder->item('منو ۳', 'menu-3')->url('/menu-3')->order(3);
                });
            }
        };

        $this->app->instance(MultiMenuRegistrar::class, $registrarClass);

        $this->manager->registerBy(MultiMenuRegistrar::class);

        $items = $this->manager->withoutCache()->get('multi.menu');
        $this->assertCount(3, $items);
    }

    public function test_registrar_can_register_to_different_menus(): void
    {
        $registrarClass = new class () implements RegistrarInterface {
            public function register(RegistryManagerInterface $manager): void
            {
                $manager->register('sidebar.menu', function (MenuBuilder $builder) {
                    $builder->item('سایدبار', 'sidebar-item')->url('/sidebar');
                });

                $manager->register('header.menu', function (MenuBuilder $builder) {
                    $builder->item('هدر', 'header-item')->url('/header');
                });
            }
        };

        $this->app->instance(MultiRegistryMenuRegistrar::class, $registrarClass);

        $this->manager->registerBy(MultiRegistryMenuRegistrar::class);

        $this->assertTrue($this->manager->has('sidebar.menu'));
        $this->assertTrue($this->manager->has('header.menu'));

        $sidebarItems = $this->manager->withoutCache()->get('sidebar.menu');
        $headerItems  = $this->manager->withoutCache()->get('header.menu');

        $this->assertCount(1, $sidebarItems);
        $this->assertCount(1, $headerItems);
    }

    public function test_multiple_registrars_can_register_to_same_menu(): void
    {
        $registrar1 = new class () implements RegistrarInterface {
            public function register(RegistryManagerInterface $manager): void
            {
                $manager->register('shared.menu', function (MenuBuilder $builder) {
                    $builder->item('از Registrar اول', 'first')->url('/first')->order(1);
                });
            }
        };

        $registrar2 = new class () implements RegistrarInterface {
            public function register(RegistryManagerInterface $manager): void
            {
                $manager->register('shared.menu', function (MenuBuilder $builder) {
                    $builder->item('از Registrar دوم', 'second')->url('/second')->order(2);
                });
            }
        };

        $this->app->instance(FirstMenuRegistrar::class, $registrar1);
        $this->app->instance(SecondMenuRegistrar::class, $registrar2);

        $this->manager->registerBy(FirstMenuRegistrar::class);
        $this->manager->registerBy(SecondMenuRegistrar::class);

        $items = $this->manager->withoutCache()->get('shared.menu');
        $this->assertCount(2, $items);
        $this->assertSame('first', $items[0]->getId());
        $this->assertSame('second', $items[1]->getId());
    }
}

// Dummy classes for testing
class TestMenuRegistrar
{
}
class LegacyMenuRegistrar
{
}
class InvalidMenuRegistrar
{
}
class MultiMenuRegistrar
{
}
class MultiRegistryMenuRegistrar
{
}
class FirstMenuRegistrar
{
}
class SecondMenuRegistrar
{
}
