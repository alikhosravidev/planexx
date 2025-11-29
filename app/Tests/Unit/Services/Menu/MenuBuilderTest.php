<?php

declare(strict_types=1);

namespace App\Tests\Unit\Services\Menu;

use App\Services\Menu\MenuBuilder;
use App\Services\Menu\MenuGroup;
use App\Services\Menu\MenuItem;
use Tests\PureUnitTestBase;

final class MenuBuilderTest extends PureUnitTestBase
{
    private MenuBuilder $builder;

    protected function setUp(): void
    {
        parent::setUp();
        $this->builder = new MenuBuilder();
    }

    public function test_starts_with_empty_items(): void
    {
        $this->assertEmpty($this->builder->getItems());
    }

    public function test_adds_menu_item_instance(): void
    {
        $item   = MenuItem::make('تست');
        $result = $this->builder->add($item);

        $this->assertSame($this->builder, $result);
        $this->assertCount(1, $this->builder->getItems());
        $this->assertSame($item, $this->builder->getItems()[0]);
    }

    public function test_creates_and_adds_item_using_item_method(): void
    {
        $item = $this->builder->item('داشبورد', 'dashboard');

        $this->assertInstanceOf(MenuItem::class, $item);
        $this->assertCount(1, $this->builder->getItems());
        $this->assertSame('dashboard', $item->getId());
    }

    public function test_creates_and_adds_group_using_group_method(): void
    {
        $group = $this->builder->group('تنظیمات', 'settings');

        $this->assertInstanceOf(MenuGroup::class, $group);
        $this->assertCount(1, $this->builder->getItems());
        $this->assertSame('settings', $group->getId());
    }

    public function test_adds_divider(): void
    {
        $result = $this->builder->divider();

        $this->assertSame($this->builder, $result);
        $items = $this->builder->getItems();
        $this->assertCount(1, $items);

        $dividerArray = $items[0]->toArray();
        $this->assertSame('divider', $dividerArray['title']);
        $this->assertSame('divider', $dividerArray['attributes']['type']);
        $this->assertSame('divider', $dividerArray['type']);
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

    public function test_mixes_items_groups_and_dividers(): void
    {
        $this->builder->item('آیتم ۱');
        $this->builder->divider();
        $this->builder->group('گروه ۱');
        $this->builder->divider();
        $this->builder->item('آیتم ۲');

        $items = $this->builder->getItems();
        $this->assertCount(5, $items);

        $this->assertInstanceOf(MenuItem::class, $items[0]);
        $this->assertSame('divider', $items[1]->toArray()['attributes']['type'] ?? null);
        $this->assertInstanceOf(MenuGroup::class, $items[2]);
        $this->assertSame('divider', $items[3]->toArray()['attributes']['type'] ?? null);
        $this->assertInstanceOf(MenuItem::class, $items[4]);
    }

    public function test_returns_item_for_method_chaining(): void
    {
        $item = $this->builder->item('تست')->icon('test-icon')->order(5);

        $this->assertSame('test-icon', $item->toArray()['icon']);
        $this->assertSame(5, $item->getOrder());
    }

    public function test_divider_generates_unique_ids(): void
    {
        $this->builder->divider();
        $this->builder->divider();
        $this->builder->divider();

        $items = $this->builder->getItems();
        $ids   = array_map(fn ($i) => $i->getId(), $items);

        $this->assertCount(3, array_unique($ids));
    }

    public function test_allows_item_without_explicit_id(): void
    {
        $item = $this->builder->item('عنوان تست');
        $this->assertNotEmpty($item->getId());
    }

    public function test_allows_group_without_explicit_id(): void
    {
        $group = $this->builder->group('گروه تست');
        $this->assertNotEmpty($group->getId());
    }
}
