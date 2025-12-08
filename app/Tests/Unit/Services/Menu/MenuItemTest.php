<?php

declare(strict_types=1);

namespace App\Tests\Unit\Services\Menu;

use App\Services\Menu\MenuBuilder;
use App\Services\Menu\MenuItem;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\PureUnitTestBase;

final class MenuItemTest extends PureUnitTestBase
{
    public function test_creates_menu_item_with_title_and_auto_generated_id(): void
    {
        $title = 'داشبورد';
        $item  = new MenuItem($title);

        $this->assertSame($title, $item->toArray()['title']);
        $this->assertSame(Str::slug($title), $item->getId());
    }

    public function test_creates_menu_item_with_custom_id(): void
    {
        $item = new MenuItem('داشبورد', 'custom-dashboard-id');
        $this->assertSame('custom-dashboard-id', $item->getId());
    }

    public function test_creates_menu_item_using_static_make_method(): void
    {
        $item = MenuItem::make('تنظیمات', 'settings');

        $this->assertInstanceOf(MenuItem::class, $item);
        $this->assertSame('settings', $item->getId());
    }

    public function test_sets_url_correctly(): void
    {
        $item   = MenuItem::make('لینک خارجی');
        $result = $item->url('https://example.com');

        $this->assertSame($item, $result);
        $this->assertSame('https://example.com', $item->getUrl());
    }

    public function test_sets_icon_correctly(): void
    {
        $item = MenuItem::make('داشبورد')->icon('heroicon-o-home');
        $this->assertSame('heroicon-o-home', $item->toArray()['icon']);
    }

    public function test_sets_color_correctly(): void
    {
        $item = MenuItem::make('هشدار')->color('red');
        $this->assertSame('red', $item->toArray()['color']);
    }

    public function test_sets_badge_without_color(): void
    {
        $item  = MenuItem::make('پیام‌ها')->badge('5');
        $array = $item->toArray();
        $this->assertSame('5', $array['badge']);
        $this->assertNull($array['badge_color']);
    }

    public function test_sets_badge_with_color(): void
    {
        $item  = MenuItem::make('پیام‌ها')->badge('جدید', 'green');
        $array = $item->toArray();
        $this->assertSame('جدید', $array['badge']);
        $this->assertSame('green', $array['badge_color']);
    }

    public function test_sets_order_correctly(): void
    {
        $item = MenuItem::make('آیتم')->order(10);
        $this->assertSame(10, $item->getOrder());
        $this->assertSame(10, $item->toArray()['order']);
    }

    public function test_has_default_order_of_zero(): void
    {
        $item = MenuItem::make('آیتم');
        $this->assertSame(0, $item->getOrder());
    }

    public function test_sets_permission_correctly(): void
    {
        $item = MenuItem::make('کاربران')->permission('users.view');
        $this->assertSame('users.view', $item->getPermission());
    }

    public function test_sets_target_correctly(): void
    {
        $item = MenuItem::make('لینک خارجی')->target('_blank');
        $this->assertSame('_blank', $item->toArray()['target']);
    }

    public function test_active_state_true_by_default(): void
    {
        $item = MenuItem::make('آیتم');
        $this->assertTrue($item->isActive());
    }

    public function test_can_set_active_state_to_false(): void
    {
        $item = MenuItem::make('آیتم')->active(false);
        $this->assertFalse($item->isActive());
    }

    public function test_sets_active_state_using_callback_true(): void
    {
        $item = MenuItem::make('آیتم')->activeWhen(fn () => true);
        $this->assertTrue($item->isActive());
    }

    public function test_sets_active_state_using_callback_false(): void
    {
        $item = MenuItem::make('آیتم')->activeWhen(fn () => false);
        $this->assertFalse($item->isActive());
    }

    public function test_sets_custom_attributes(): void
    {
        $item       = MenuItem::make('آیتم')->attributes(['data-id' => '123', 'class' => 'custom-class']);
        $attributes = $item->toArray()['attributes'];
        $this->assertSame('123', $attributes['data-id']);
        $this->assertSame('custom-class', $attributes['class']);
    }

    public function test_adds_children_using_array(): void
    {
        $child1 = MenuItem::make('زیرمنو ۱');
        $child2 = MenuItem::make('زیرمنو ۲');
        $item   = MenuItem::make('منوی اصلی')->children([$child1, $child2]);

        $this->assertTrue($item->hasChildren());
        $this->assertCount(2, $item->getChildren());
    }

    public function test_adds_children_using_callback(): void
    {
        $item = MenuItem::make('منوی اصلی')->children(function (MenuBuilder $builder) {
            $builder->item('زیرمنو ۱');
            $builder->item('زیرمنو ۲');
            $builder->item('زیرمنو ۳');
        });

        $this->assertTrue($item->hasChildren());
        $this->assertCount(3, $item->getChildren());
    }

    public function test_adds_single_child(): void
    {
        $parent = MenuItem::make('والد');
        $child  = MenuItem::make('فرزند');
        $result = $parent->addChild($child);

        $this->assertSame($parent, $result);
        $this->assertTrue($parent->hasChildren());
        $this->assertCount(1, $parent->getChildren());
    }

    public function test_has_children_false_when_empty(): void
    {
        $item = MenuItem::make('آیتم بدون فرزند');
        $this->assertFalse($item->hasChildren());
        $this->assertEmpty($item->getChildren());
    }

    public function test_returns_url_when_set_directly(): void
    {
        $item = MenuItem::make('لینک')->url('/dashboard');
        $this->assertSame('/dashboard', $item->getUrl());
    }

    public function test_returns_null_url_when_not_set(): void
    {
        $item = MenuItem::make('آیتم');
        $this->assertNull($item->getUrl());
    }

    public function test_converts_to_array_with_all_properties(): void
    {
        $item = MenuItem::make('تست کامل', 'full-test')
            ->url('/test')
            ->icon('test-icon')
            ->color('blue')
            ->badge('10', 'red')
            ->order(5)
            ->permission('test.permission')
            ->target('_self')
            ->attributes(['data-test' => 'value']);

        $array = $item->toArray();

        $this->assertSame('full-test', $array['id']);
        $this->assertSame('تست کامل', $array['title']);
        $this->assertSame('/test', $array['url']);
        $this->assertSame('test-icon', $array['icon']);
        $this->assertSame('blue', $array['color']);
        $this->assertSame('10', $array['badge']);
        $this->assertSame('red', $array['badge_color']);
        $this->assertSame(5, $array['order']);
        $this->assertSame('test.permission', $array['permission']);
        $this->assertSame('_self', $array['target']);
        $this->assertSame(['data-test' => 'value'], $array['attributes']);
        $this->assertTrue($array['active']);
        $this->assertIsArray($array['children']);
    }

    public function test_converts_children_to_array_recursively(): void
    {
        $item = MenuItem::make('والد')->children(function (MenuBuilder $builder) {
            $builder->item('فرزند ۱', 'child-1')->order(1);
            $builder->item('فرزند ۲', 'child-2')->order(2);
        });

        $array = $item->toArray();

        $this->assertCount(2, $array['children']);
        $this->assertSame('child-1', $array['children'][0]['id']);
        $this->assertSame('child-2', $array['children'][1]['id']);
    }

    #[DataProvider('specialCharacterTitlesProvider')]
    public function test_handles_special_characters_in_title(string $title): void
    {
        $item = MenuItem::make($title);
        $this->assertSame($title, $item->toArray()['title']);
        $this->assertNotEmpty($item->getId());
    }

    public static function specialCharacterTitlesProvider(): array
    {
        return [
            'persian'   => ['مدیریت کاربران'],
            'numbers'   => ['گزارش ۱۳۹۹'],
            'special'   => ['تنظیمات (پیشرفته)'],
            'emoji'     => ['پیام‌ها 📧'],
            'empty-ish' => ['   '],
        ];
    }
}
