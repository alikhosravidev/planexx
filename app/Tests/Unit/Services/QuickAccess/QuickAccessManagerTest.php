<?php

declare(strict_types=1);

namespace App\Tests\Unit\Services\QuickAccess;

use App\Services\QuickAccess\QuickAccessBuilder;
use App\Services\QuickAccess\QuickAccessItem;
use App\Services\QuickAccess\QuickAccessManager;
use Illuminate\Support\Collection;
use Tests\PureUnitTestBase;

final class QuickAccessManagerTest extends PureUnitTestBase
{
    private QuickAccessManager $manager;

    protected function setUp(): void
    {
        parent::setUp();
        $this->manager = new QuickAccessManager();
    }

    public function test_registers_quick_access_with_callback(): void
    {
        $result = $this->manager->register('dashboard.quick', function (QuickAccessBuilder $builder) {
            $builder->item('آیتم تست');
        });

        $this->assertSame($this->manager, $result);
        $this->assertTrue($this->manager->has('dashboard.quick'));
    }

    public function test_returns_false_for_non_existent_quick_access(): void
    {
        $this->assertFalse($this->manager->has('non.existent.quick'));
    }

    public function test_gets_quick_access_as_collection_of_items(): void
    {
        $this->manager->register('test.quick', function (QuickAccessBuilder $builder) {
            $builder->item('آیتم ۱');
            $builder->item('آیتم ۲');
        });

        $result = $this->manager->withoutCache()->get('test.quick');

        $this->assertInstanceOf(Collection::class, $result);
        $this->assertContainsOnlyInstancesOf(QuickAccessItem::class, $result->all());
    }

    public function test_builds_quick_access_items_correctly(): void
    {
        $this->manager->register('dashboard', function (QuickAccessBuilder $builder) {
            $builder->item('ایجاد کاربر', 'create-user')->url('/users/create')->icon('user')->order(1);
            $builder->item('افزودن محصول', 'add-product')->url('/products/create')->icon('plus')->order(2);
        });

        $items = $this->manager->withoutCache()->get('dashboard');

        $this->assertCount(2, $items);
        $this->assertSame('create-user', $items->first()->getId());
    }

    public function test_sorts_items_by_order(): void
    {
        $this->manager->register('sorted.quick', function (QuickAccessBuilder $builder) {
            $builder->item('سوم', 'third')->order(30);
            $builder->item('اول', 'first')->order(10);
            $builder->item('دوم', 'second')->order(20);
        });

        $items = $this->manager->withoutCache()->get('sorted.quick');

        $this->assertSame('first', $items[0]->getId());
        $this->assertSame('second', $items[1]->getId());
        $this->assertSame('third', $items[2]->getId());
    }

    public function test_filters_inactive_items(): void
    {
        $this->manager->register('active.quick', function (QuickAccessBuilder $builder) {
            $builder->item('فعال', 'active-item')->active(true);
            $builder->item('غیرفعال', 'inactive-item')->active(false);
        });

        $items = $this->manager->withoutCache()->get('active.quick');

        $this->assertCount(1, $items);
        $this->assertSame('active-item', $items->first()->getId());
    }

    public function test_to_array_and_to_json(): void
    {
        $this->manager->register('array.quick', function (QuickAccessBuilder $builder) {
            $builder->item('تست', 'test-item')->url('/test')->icon('test-icon');
        });

        $array = $this->manager->withoutCache()->toArray('array.quick');
        $json  = $this->manager->withoutCache()->toJson('array.quick');

        $this->assertIsArray($array);
        $this->assertCount(1, $array);
        $this->assertSame('test-item', $array[0]['id']);
        $this->assertSame('/test', $array[0]['url']);

        $decoded = json_decode($json, true);
        $this->assertNotNull($decoded);
        $this->assertSame('test-item', $decoded[0]['id']);
    }

    public function test_allows_multiple_registrations_for_same_quick_access(): void
    {
        $this->manager->register('multi.quick', function (QuickAccessBuilder $builder) {
            $builder->item('آیتم ۱', 'item-1')->order(1);
        });
        $this->manager->register('multi.quick', function (QuickAccessBuilder $builder) {
            $builder->item('آیتم ۲', 'item-2')->order(2);
        });

        $items = $this->manager->withoutCache()->get('multi.quick');
        $this->assertCount(2, $items);
    }

    public function test_disables_cache_and_enables_cache_methods_are_chainable(): void
    {
        $this->assertSame($this->manager, $this->manager->withoutCache());
        $this->assertSame($this->manager, $this->manager->withCache(7200));
    }

    public function test_filters_items_with_active_when_callback(): void
    {
        $this->manager->register('conditional.quick', function (QuickAccessBuilder $builder) {
            $builder->item('همیشه فعال', 'always-active')->activeWhen(fn () => true);
            $builder->item('همیشه غیرفعال', 'always-inactive')->activeWhen(fn () => false);
        });

        $items = $this->manager->withoutCache()->get('conditional.quick');

        $this->assertCount(1, $items);
        $this->assertSame('always-active', $items->first()->getId());
    }

    public function test_filters_items_with_enabled_when_callback(): void
    {
        $this->manager->register('enabled.quick', function (QuickAccessBuilder $builder) {
            $builder->item('فعال', 'enabled-item')->enabledWhen(fn () => true);
            $builder->item('غیرفعال', 'disabled-item')->enabledWhen(fn () => false);
        });

        $items = $this->manager->withoutCache()->get('enabled.quick');
        $array = $this->manager->withoutCache()->toArray('enabled.quick');

        $this->assertCount(2, $items);
        $this->assertTrue($array[0]['enabled']);
        $this->assertFalse($array[1]['enabled']);
    }
}
