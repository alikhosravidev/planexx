<?php

declare(strict_types=1);

namespace App\Tests\Unit\Services\Distribution;

use App\Services\Distribution\DistributionBuilder;
use App\Services\Distribution\DistributionItem;
use Tests\PureUnitTestBase;

final class DistributionBuilderTest extends PureUnitTestBase
{
    private DistributionBuilder $builder;

    protected function setUp(): void
    {
        parent::setUp();
        $this->builder = new DistributionBuilder();
    }

    public function test_starts_with_empty_items(): void
    {
        $this->assertEmpty($this->builder->getItems());
    }

    public function test_adds_distribution_item_instance(): void
    {
        $item   = DistributionItem::make('تست');
        $result = $this->builder->add($item);

        $this->assertSame($this->builder, $result);
        $this->assertCount(1, $this->builder->getItems());
        $this->assertSame($item, $this->builder->getItems()[0]);
    }

    public function test_creates_and_adds_item_using_segment_method(): void
    {
        $item = $this->builder->segment('بخش فروش', 'sales-segment');

        $this->assertInstanceOf(DistributionItem::class, $item);
        $this->assertCount(1, $this->builder->getItems());
        $this->assertSame('sales-segment', $item->getId());
    }

    public function test_adds_multiple_items_in_order(): void
    {
        $this->builder->segment('اول', 'first');
        $this->builder->segment('دوم', 'second');
        $this->builder->segment('سوم', 'third');

        $items = $this->builder->getItems();
        $this->assertCount(3, $items);
        $this->assertSame('first', $items[0]->getId());
        $this->assertSame('second', $items[1]->getId());
        $this->assertSame('third', $items[2]->getId());
    }

    public function test_returns_item_for_method_chaining(): void
    {
        $item = $this->builder->segment('بخش تست')
            ->value('۱۰۰۰')
            ->percent(25)
            ->color('blue');

        $this->assertSame('۱۰۰۰', $item->toArray()['value']);
        $this->assertSame(25, $item->toArray()['percent']);
        $this->assertSame('blue', $item->toArray()['color']);
    }

    public function test_allows_item_without_explicit_id(): void
    {
        $item = $this->builder->segment('عنوان تست');
        $this->assertNotEmpty($item->getId());
    }

    public function test_builds_complex_distribution_panel(): void
    {
        $this->builder->segment('فروش', 'sales')
            ->value('۵۰۰۰۰۰')
            ->percent(50)
            ->color('green');

        $this->builder->segment('بازاریابی', 'marketing')
            ->value('۳۰۰۰۰۰')
            ->percent(30)
            ->color('blue');

        $this->builder->segment('عملیات', 'operations')
            ->value('۲۰۰۰۰۰')
            ->percent(20)
            ->color('yellow');

        $items = $this->builder->getItems();
        $this->assertCount(3, $items);
        $this->assertContainsOnlyInstancesOf(DistributionItem::class, $items);
    }
}
