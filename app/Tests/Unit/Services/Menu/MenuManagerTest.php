<?php

declare(strict_types=1);

namespace App\Tests\Unit\Services\Menu;

use App\Services\Menu\MenuBuilder;
use App\Services\Menu\MenuItem;
use App\Services\Menu\MenuManager;
use Illuminate\Support\Collection;
use Tests\PureUnitTestBase;

final class MenuManagerTest extends PureUnitTestBase
{
    private MenuManager $manager;

    protected function setUp(): void
    {
        parent::setUp();
        $this->manager = new MenuManager();
    }

    public function test_registers_menu_with_callback(): void
    {
        $result = $this->manager->register('test.menu', function (MenuBuilder $builder) {
            $builder->item('آیتم تست');
        });

        $this->assertSame($this->manager, $result);
        $this->assertTrue($this->manager->has('test.menu'));
    }

    public function test_returns_false_for_non_existent_menu(): void
    {
        $this->assertFalse($this->manager->has('non.existent.menu'));
    }

    public function test_gets_menu_as_collection_of_items(): void
    {
        $this->manager->register('test.menu', function (MenuBuilder $builder) {
            $builder->item('آیتم ۱');
            $builder->item('آیتم ۲');
        });

        $result = $this->manager->withoutCache()->get('test.menu');

        $this->assertInstanceOf(Collection::class, $result);
        $this->assertContainsOnlyInstancesOf(MenuItem::class, $result->all());
    }

    public function test_builds_menu_items_correctly(): void
    {
        $this->manager->register('sidebar', function (MenuBuilder $builder) {
            $builder->item('داشبورد', 'dashboard')->url('/dashboard')->icon('home')->order(1);
            $builder->item('کاربران', 'users')->url('/users')->icon('users')->order(2);
        });

        $items = $this->manager->withoutCache()->get('sidebar');

        $this->assertCount(2, $items);
        $this->assertSame('dashboard', $items->first()->getId());
    }

    public function test_sorts_items_by_order(): void
    {
        $this->manager->register('sorted.menu', function (MenuBuilder $builder) {
            $builder->item('سوم', 'third')->order(30);
            $builder->item('اول', 'first')->order(10);
            $builder->item('دوم', 'second')->order(20);
        });

        $items = $this->manager->withoutCache()->get('sorted.menu');

        $this->assertSame('first', $items[0]->getId());
        $this->assertSame('second', $items[1]->getId());
        $this->assertSame('third', $items[2]->getId());
    }

    public function test_filters_inactive_items(): void
    {
        $this->manager->register('active.menu', function (MenuBuilder $builder) {
            $builder->item('فعال', 'active-item')->active(true);
            $builder->item('غیرفعال', 'inactive-item')->active(false);
        });

        $items = $this->manager->withoutCache()->get('active.menu');

        $this->assertCount(1, $items);
        $this->assertSame('active-item', $items->first()->getId());
    }

    public function test_extends_existing_menu_item(): void
    {
        $this->manager->register('extendable.menu', function (MenuBuilder $builder) {
            $builder->item('والد', 'parent-item');
        });

        $this->manager->extend('extendable.menu', 'parent-item', function (MenuBuilder $builder) {
            $builder->item('فرزند اضافه شده', 'added-child');
        });

        $items  = $this->manager->withoutCache()->get('extendable.menu');
        $parent = $items->first();

        $this->assertTrue($parent->hasChildren());
        $this->assertCount(1, $parent->getChildren());
    }

    public function test_to_array_and_to_json(): void
    {
        $this->manager->register('array.menu', function (MenuBuilder $builder) {
            $builder->item('تست', 'test-item')->url('/test')->icon('test-icon');
        });

        $array = $this->manager->withoutCache()->toArray('array.menu');
        $json  = $this->manager->withoutCache()->toJson('array.menu');

        $this->assertIsArray($array);
        $this->assertCount(1, $array);
        $this->assertSame('test-item', $array[0]['id']);
        $this->assertSame('/test', $array[0]['url']);

        $decoded = json_decode($json, true);
        $this->assertNotNull($decoded);
        $this->assertSame('test-item', $decoded[0]['id']);
    }

    public function test_allows_multiple_registrations_for_same_menu(): void
    {
        $this->manager->register('multi.menu', function (MenuBuilder $builder) {
            $builder->item('آیتم ۱', 'item-1')->order(1);
        });
        $this->manager->register('multi.menu', function (MenuBuilder $builder) {
            $builder->item('آیتم ۲', 'item-2')->order(2);
        });

        $items = $this->manager->withoutCache()->get('multi.menu');
        $this->assertCount(2, $items);
    }

    public function test_disables_cache_and_enables_cache_methods_are_chainable(): void
    {
        $this->assertSame($this->manager, $this->manager->withoutCache());
        $this->assertSame($this->manager, $this->manager->withCache(7200));
    }

    public function test_sorts_children_recursively(): void
    {
        $this->manager->register('nested.menu', function (MenuBuilder $builder) {
            $builder->group('گروه', 'group-item')->children(function (MenuBuilder $sub) {
                $sub->item('سوم', 'child-3')->order(30);
                $sub->item('اول', 'child-1')->order(10);
                $sub->item('دوم', 'child-2')->order(20);
            });
        });

        $items    = $this->manager->withoutCache()->get('nested.menu');
        $children = $items->first()->getChildren();

        $this->assertSame('child-1', $children[0]->getId());
        $this->assertSame('child-2', $children[1]->getId());
        $this->assertSame('child-3', $children[2]->getId());
    }
}
