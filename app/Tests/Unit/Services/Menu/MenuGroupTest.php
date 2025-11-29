<?php

declare(strict_types=1);

namespace App\Tests\Unit\Services\Menu;

use App\Services\Menu\MenuGroup;
use App\Services\Menu\MenuItem;
use Tests\PureUnitTestBase;

final class MenuGroupTest extends PureUnitTestBase
{
    public function test_extends_menu_item(): void
    {
        $group = new MenuGroup('گروه تست');
        $this->assertInstanceOf(MenuItem::class, $group);
    }

    public function test_has_type_group_in_array(): void
    {
        $group = new MenuGroup('گروه تست');
        $array = $group->toArray();
        $this->assertSame('group', $array['type']);
    }

    public function test_is_collapsible_by_default(): void
    {
        $group = new MenuGroup('گروه تست');
        $array = $group->toArray();
        $this->assertTrue($array['collapsible']);
    }

    public function test_can_set_collapsible_to_false(): void
    {
        $group = (new MenuGroup('گروه تست'))->collapsible(false);
        $this->assertFalse($group->toArray()['collapsible']);
    }

    public function test_is_not_collapsed_by_default(): void
    {
        $group = new MenuGroup('گروه تست');
        $array = $group->toArray();
        $this->assertFalse($array['collapsed']);
    }

    public function test_can_set_collapsed_to_true(): void
    {
        $group = (new MenuGroup('گروه تست'))->collapsed(true);
        $this->assertTrue($group->toArray()['collapsed']);
    }

    public function test_inherits_all_menu_item_functionality(): void
    {
        $group = (new MenuGroup('گروه کامل', 'full-group'))
            ->icon('folder-icon')
            ->color('blue')
            ->order(10)
            ->permission('group.view')
            ->collapsible(true)
            ->collapsed(false)
            ->children(function ($builder) {
                $builder->item('آیتم ۱');
                $builder->item('آیتم ۲');
            });

        $array = $group->toArray();

        $this->assertSame('full-group', $array['id']);
        $this->assertSame('گروه کامل', $array['title']);
        $this->assertSame('folder-icon', $array['icon']);
        $this->assertSame('blue', $array['color']);
        $this->assertSame(10, $array['order']);
        $this->assertSame('group.view', $array['permission']);
        $this->assertSame('group', $array['type']);
        $this->assertTrue($array['collapsible']);
        $this->assertFalse($array['collapsed']);
        $this->assertCount(2, $array['children']);
    }

    public function test_supports_fluent_interface_for_group_methods(): void
    {
        $group   = new MenuGroup('تست');
        $result1 = $group->collapsible(true);
        $result2 = $group->collapsed(true);

        $this->assertSame($group, $result1);
        $this->assertSame($group, $result2);
    }

    public function test_merges_parent_array_with_group_specific_properties(): void
    {
        $group = (new MenuGroup('گروه'))
            ->url('/group-url')
            ->collapsible(false)
            ->collapsed(true);

        $array = $group->toArray();

        $this->assertArrayHasKey('id', $array);
        $this->assertArrayHasKey('title', $array);
        $this->assertArrayHasKey('url', $array);
        $this->assertArrayHasKey('type', $array);
        $this->assertArrayHasKey('collapsible', $array);
        $this->assertArrayHasKey('collapsed', $array);
    }
}
