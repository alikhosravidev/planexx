<?php

declare(strict_types=1);

namespace App\Tests\Unit\Services\Distribution;

use App\Services\Distribution\DistributionBuilder;
use App\Services\Distribution\DistributionItem;
use App\Services\Distribution\DistributionManager;
use Illuminate\Support\Collection;
use Tests\PureUnitTestBase;

final class DistributionManagerTest extends PureUnitTestBase
{
    private DistributionManager $manager;

    protected function setUp(): void
    {
        parent::setUp();
        $this->manager = new DistributionManager();
    }

    public function test_registers_distribution_with_callback(): void
    {
        $result = $this->manager->register('sales.distribution', function (DistributionBuilder $builder) {
            $builder->segment('بخش تست');
        });

        $this->assertSame($this->manager, $result);
        $this->assertTrue($this->manager->has('sales.distribution'));
    }

    public function test_returns_false_for_non_existent_distribution(): void
    {
        $this->assertFalse($this->manager->has('non.existent.distribution'));
    }

    public function test_gets_distribution_as_collection_of_items(): void
    {
        $this->manager->register('test.distribution', function (DistributionBuilder $builder) {
            $builder->segment('بخش ۱');
            $builder->segment('بخش ۲');
        });

        $result = $this->manager->withoutCache()->get('test.distribution');

        $this->assertInstanceOf(Collection::class, $result);
        $this->assertContainsOnlyInstancesOf(DistributionItem::class, $result->all());
    }

    public function test_builds_distribution_items_correctly(): void
    {
        $this->manager->register('dashboard', function (DistributionBuilder $builder) {
            $builder->segment('فروش', 'sales')->value('۵۰۰۰۰۰')->percent(50)->order(1);
            $builder->segment('بازاریابی', 'marketing')->value('۳۰۰۰۰۰')->percent(30)->order(2);
        });

        $items = $this->manager->withoutCache()->get('dashboard');

        $this->assertCount(2, $items);
        $this->assertSame('sales', $items->first()->getId());
    }

    public function test_sorts_items_by_order(): void
    {
        $this->manager->register('sorted.distribution', function (DistributionBuilder $builder) {
            $builder->segment('سوم', 'third')->order(30);
            $builder->segment('اول', 'first')->order(10);
            $builder->segment('دوم', 'second')->order(20);
        });

        $items = $this->manager->withoutCache()->get('sorted.distribution');

        $this->assertSame('first', $items[0]->getId());
        $this->assertSame('second', $items[1]->getId());
        $this->assertSame('third', $items[2]->getId());
    }

    public function test_filters_inactive_items(): void
    {
        $this->manager->register('active.distribution', function (DistributionBuilder $builder) {
            $builder->segment('فعال', 'active-segment')->active(true);
            $builder->segment('غیرفعال', 'inactive-segment')->active(false);
        });

        $items = $this->manager->withoutCache()->get('active.distribution');

        $this->assertCount(1, $items);
        $this->assertSame('active-segment', $items->first()->getId());
    }

    public function test_to_array_and_to_json(): void
    {
        $this->manager->register('array.distribution', function (DistributionBuilder $builder) {
            $builder->segment('تست', 'test-segment')->value('۱۰۰۰')->percent(25)->color('blue');
        });

        $array = $this->manager->withoutCache()->toArray('array.distribution');
        $json  = $this->manager->withoutCache()->toJson('array.distribution');

        $this->assertIsArray($array);
        $this->assertCount(1, $array);
        $this->assertSame('test-segment', $array[0]['id']);
        $this->assertSame('۱۰۰۰', $array[0]['value']);
        $this->assertSame(25, $array[0]['percent']);
        $this->assertSame('blue', $array[0]['color']);

        $decoded = json_decode($json, true);
        $this->assertNotNull($decoded);
        $this->assertSame('test-segment', $decoded[0]['id']);
    }

    public function test_get_transformed_returns_transformed_array(): void
    {
        $this->manager->register('transformed.distribution', function (DistributionBuilder $builder) {
            $builder->segment('بخش ۱', 'segment-1')->value('۱۰۰۰')->percent(50)->color('red');
            $builder->segment('بخش ۲', 'segment-2')->value('۲۰۰۰')->percent(50)->color('blue');
        });

        $result = $this->manager->withoutCache()->getTransformed('transformed.distribution');

        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(2, $result);

        $firstItem = $result->first();
        $this->assertSame('بخش ۱', $firstItem['label']);
        $this->assertSame('۱۰۰۰', $firstItem['value']);
        $this->assertSame(50, $firstItem['percent']);
        $this->assertSame('red', $firstItem['color']);
    }

    public function test_allows_multiple_registrations_for_same_distribution(): void
    {
        $this->manager->register('multi.distribution', function (DistributionBuilder $builder) {
            $builder->segment('بخش ۱', 'segment-1')->order(1);
        });
        $this->manager->register('multi.distribution', function (DistributionBuilder $builder) {
            $builder->segment('بخش ۲', 'segment-2')->order(2);
        });

        $items = $this->manager->withoutCache()->get('multi.distribution');
        $this->assertCount(2, $items);
    }

    public function test_disables_cache_and_enables_cache_methods_are_chainable(): void
    {
        $this->assertSame($this->manager, $this->manager->withoutCache());
        $this->assertSame($this->manager, $this->manager->withCache(7200));
    }

    public function test_filters_items_with_active_when_callback(): void
    {
        $this->manager->register('conditional.distribution', function (DistributionBuilder $builder) {
            $builder->segment('همیشه فعال', 'always-active')->activeWhen(fn () => true);
            $builder->segment('همیشه غیرفعال', 'always-inactive')->activeWhen(fn () => false);
        });

        $items = $this->manager->withoutCache()->get('conditional.distribution');

        $this->assertCount(1, $items);
        $this->assertSame('always-active', $items->first()->getId());
    }
}
