<?php

declare(strict_types=1);

namespace App\Tests\Unit\Services\Stats;

use App\Services\Stats\StatBuilder;
use App\Services\Stats\StatItem;
use App\Services\Stats\StatManager;
use Illuminate\Support\Collection;
use Tests\PureUnitTestBase;

final class StatManagerTest extends PureUnitTestBase
{
    private StatManager $manager;

    protected function setUp(): void
    {
        parent::setUp();
        $this->manager = new StatManager();
    }

    public function test_registers_stats_with_callback(): void
    {
        $result = $this->manager->register('dashboard.stats', function (StatBuilder $builder) {
            $builder->stat('آمار تست');
        });

        $this->assertSame($this->manager, $result);
        $this->assertTrue($this->manager->has('dashboard.stats'));
    }

    public function test_returns_false_for_non_existent_stats(): void
    {
        $this->assertFalse($this->manager->has('non.existent.stats'));
    }

    public function test_gets_stats_as_collection_of_items(): void
    {
        $this->manager->register('test.stats', function (StatBuilder $builder) {
            $builder->stat('آمار ۱');
            $builder->stat('آمار ۲');
        });

        $result = $this->manager->withoutCache()->get('test.stats');

        $this->assertInstanceOf(Collection::class, $result);
        $this->assertContainsOnlyInstancesOf(StatItem::class, $result->all());
    }

    public function test_builds_stat_items_correctly(): void
    {
        $this->manager->register('dashboard', function (StatBuilder $builder) {
            $builder->stat('فروش', 'sales')->value('۱۰,۰۰۰')->icon('dollar')->order(1);
            $builder->stat('کاربران', 'users')->value('۵۰۰')->icon('users')->order(2);
        });

        $items = $this->manager->withoutCache()->get('dashboard');

        $this->assertCount(2, $items);
        $this->assertSame('sales', $items->first()->getId());
    }

    public function test_sorts_items_by_order(): void
    {
        $this->manager->register('sorted.stats', function (StatBuilder $builder) {
            $builder->stat('سوم', 'third')->order(30);
            $builder->stat('اول', 'first')->order(10);
            $builder->stat('دوم', 'second')->order(20);
        });

        $items = $this->manager->withoutCache()->get('sorted.stats');

        $this->assertSame('first', $items[0]->getId());
        $this->assertSame('second', $items[1]->getId());
        $this->assertSame('third', $items[2]->getId());
    }

    public function test_filters_inactive_items(): void
    {
        $this->manager->register('active.stats', function (StatBuilder $builder) {
            $builder->stat('فعال', 'active-stat')->active(true);
            $builder->stat('غیرفعال', 'inactive-stat')->active(false);
        });

        $items = $this->manager->withoutCache()->get('active.stats');

        $this->assertCount(1, $items);
        $this->assertSame('active-stat', $items->first()->getId());
    }

    public function test_to_array_and_to_json(): void
    {
        $this->manager->register('array.stats', function (StatBuilder $builder) {
            $builder->stat('تست', 'test-stat')->value('۱۰۰')->icon('test-icon');
        });

        $array = $this->manager->withoutCache()->toArray('array.stats');
        $json  = $this->manager->withoutCache()->toJson('array.stats');

        $this->assertIsArray($array);
        $this->assertCount(1, $array);
        $this->assertSame('test-stat', $array[0]['id']);
        $this->assertSame('۱۰۰', $array[0]['value']);

        $decoded = json_decode($json, true);
        $this->assertNotNull($decoded);
        $this->assertSame('test-stat', $decoded[0]['id']);
    }

    public function test_allows_multiple_registrations_for_same_stats(): void
    {
        $this->manager->register('multi.stats', function (StatBuilder $builder) {
            $builder->stat('آمار ۱', 'stat-1')->order(1);
        });
        $this->manager->register('multi.stats', function (StatBuilder $builder) {
            $builder->stat('آمار ۲', 'stat-2')->order(2);
        });

        $items = $this->manager->withoutCache()->get('multi.stats');
        $this->assertCount(2, $items);
    }

    public function test_disables_cache_and_enables_cache_methods_are_chainable(): void
    {
        $this->assertSame($this->manager, $this->manager->withoutCache());
        $this->assertSame($this->manager, $this->manager->withCache(7200));
    }

    public function test_filters_stats_with_active_when_callback(): void
    {
        $this->manager->register('conditional.stats', function (StatBuilder $builder) {
            $builder->stat('همیشه فعال', 'always-active')->activeWhen(fn () => true);
            $builder->stat('همیشه غیرفعال', 'always-inactive')->activeWhen(fn () => false);
        });

        $items = $this->manager->withoutCache()->get('conditional.stats');

        $this->assertCount(1, $items);
        $this->assertSame('always-active', $items->first()->getId());
    }
}
