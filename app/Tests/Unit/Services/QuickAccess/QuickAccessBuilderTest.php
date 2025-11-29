<?php

declare(strict_types=1);

namespace App\Tests\Unit\Services\QuickAccess;

use App\Services\QuickAccess\QuickAccessBuilder;
use App\Services\QuickAccess\QuickAccessItem;
use Tests\PureUnitTestBase;

final class QuickAccessBuilderTest extends PureUnitTestBase
{
    private QuickAccessBuilder $builder;

    protected function setUp(): void
    {
        parent::setUp();
        $this->builder = new QuickAccessBuilder();
    }

    public function test_starts_with_empty_items(): void
    {
        $this->assertEmpty($this->builder->getItems());
    }

    public function test_adds_quick_access_item_instance(): void
    {
        $item   = QuickAccessItem::make('تست');
        $result = $this->builder->add($item);

        $this->assertSame($this->builder, $result);
        $this->assertCount(1, $this->builder->getItems());
        $this->assertSame($item, $this->builder->getItems()[0]);
    }

    public function test_creates_and_adds_item_using_item_method(): void
    {
        $item = $this->builder->item('ایجاد کاربر', 'create-user');

        $this->assertInstanceOf(QuickAccessItem::class, $item);
        $this->assertCount(1, $this->builder->getItems());
        $this->assertSame('create-user', $item->getId());
    }

    public function test_adds_multiple_items_in_order(): void
    {
        $this->builder->item('اول', 'first');
        $this->builder->item('دوم', 'second');
        $this->builder->item('سوم', 'third');

        $items = $this->builder->getItems();
        $this->assertCount(3, $items);
        $this->assertSame('first', $items[0]->getId());
        $this->assertSame('second', $items[1]->getId());
        $this->assertSame('third', $items[2]->getId());
    }

    public function test_returns_item_for_method_chaining(): void
    {
        $item = $this->builder->item('ایجاد')
            ->url('/create')
            ->icon('test-icon')
            ->order(5);

        $this->assertSame('/create', $item->getUrl());
        $this->assertSame('test-icon', $item->toArray()['icon']);
        $this->assertSame(5, $item->getOrder());
    }

    public function test_allows_item_without_explicit_id(): void
    {
        $item = $this->builder->item('عنوان تست');
        $this->assertNotEmpty($item->getId());
    }

    public function test_builds_complex_quick_access_panel(): void
    {
        $this->builder->item('ایجاد کاربر', 'create-user')
            ->url('/users/create')
            ->icon('heroicon-o-user-plus')
            ->color('blue')
            ->order(1);

        $this->builder->item('افزودن محصول', 'add-product')
            ->url('/products/create')
            ->icon('heroicon-o-plus-circle')
            ->color('green')
            ->order(2);

        $this->builder->item('گزارش جدید', 'new-report')
            ->url('/reports/create')
            ->icon('heroicon-o-document-text')
            ->color('yellow')
            ->order(3);

        $items = $this->builder->getItems();
        $this->assertCount(3, $items);
        $this->assertContainsOnlyInstancesOf(QuickAccessItem::class, $items);
    }
}
