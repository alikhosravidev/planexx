<?php

declare(strict_types=1);

namespace App\Tests\Unit\Services\Stats;

use App\Services\Stats\StatBuilder;
use App\Services\Stats\StatItem;
use Tests\PureUnitTestBase;

final class StatBuilderTest extends PureUnitTestBase
{
    private StatBuilder $builder;

    protected function setUp(): void
    {
        parent::setUp();
        $this->builder = new StatBuilder();
    }

    public function test_starts_with_empty_items(): void
    {
        $this->assertEmpty($this->builder->getItems());
    }

    public function test_adds_stat_item_instance(): void
    {
        $item   = StatItem::make('تست');
        $result = $this->builder->add($item);

        $this->assertSame($this->builder, $result);
        $this->assertCount(1, $this->builder->getItems());
        $this->assertSame($item, $this->builder->getItems()[0]);
    }

    public function test_creates_and_adds_stat_using_stat_method(): void
    {
        $item = $this->builder->stat('کل فروش', 'total-sales');

        $this->assertInstanceOf(StatItem::class, $item);
        $this->assertCount(1, $this->builder->getItems());
        $this->assertSame('total-sales', $item->getId());
    }

    public function test_adds_multiple_stats_in_order(): void
    {
        $this->builder->stat('اول', 'first');
        $this->builder->stat('دوم', 'second');
        $this->builder->stat('سوم', 'third');

        $items = $this->builder->getItems();
        $this->assertCount(3, $items);
        $this->assertSame('first', $items[0]->getId());
        $this->assertSame('second', $items[1]->getId());
        $this->assertSame('third', $items[2]->getId());
    }

    public function test_returns_stat_for_method_chaining(): void
    {
        $item = $this->builder->stat('فروش')
            ->value('۱۰,۰۰۰')
            ->icon('test-icon')
            ->order(5);

        $this->assertSame('۱۰,۰۰۰', $item->toArray()['value']);
        $this->assertSame('test-icon', $item->toArray()['icon']);
        $this->assertSame(5, $item->getOrder());
    }

    public function test_allows_stat_without_explicit_id(): void
    {
        $item = $this->builder->stat('عنوان تست');
        $this->assertNotEmpty($item->getId());
    }

    public function test_builds_complex_stats_dashboard(): void
    {
        $this->builder->stat('کل فروش', 'sales')
            ->value('۱۲۵,۰۰۰')
            ->icon('heroicon-o-currency-dollar')
            ->color('green')
            ->change('+۱۵٪', 'increase')
            ->order(1);

        $this->builder->stat('کاربران جدید', 'new-users')
            ->value('۲۳۴')
            ->icon('heroicon-o-users')
            ->color('blue')
            ->change('+۸٪', 'increase')
            ->order(2);

        $this->builder->stat('سفارشات', 'orders')
            ->value('۵۶')
            ->icon('heroicon-o-shopping-cart')
            ->color('yellow')
            ->change('-۳٪', 'decrease')
            ->order(3);

        $items = $this->builder->getItems();
        $this->assertCount(3, $items);
        $this->assertContainsOnlyInstancesOf(StatItem::class, $items);
    }
}
