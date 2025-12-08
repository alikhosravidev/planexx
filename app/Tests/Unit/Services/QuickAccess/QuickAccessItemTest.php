<?php

declare(strict_types=1);

namespace App\Tests\Unit\Services\QuickAccess;

use App\Services\QuickAccess\QuickAccessItem;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\PureUnitTestBase;

final class QuickAccessItemTest extends PureUnitTestBase
{
    public function test_creates_quick_access_item_with_title_and_auto_generated_id(): void
    {
        $title = 'ایجاد کاربر';
        $item  = new QuickAccessItem($title);

        $this->assertSame($title, $item->toArray()['title']);
        $this->assertSame(Str::slug($title), $item->getId());
    }

    public function test_creates_quick_access_item_with_custom_id(): void
    {
        $item = new QuickAccessItem('ایجاد کاربر', 'custom-create-user-id');
        $this->assertSame('custom-create-user-id', $item->getId());
    }

    public function test_creates_quick_access_item_using_static_make_method(): void
    {
        $item = QuickAccessItem::make('افزودن محصول', 'add-product');

        $this->assertInstanceOf(QuickAccessItem::class, $item);
        $this->assertSame('add-product', $item->getId());
    }

    public function test_sets_url_correctly(): void
    {
        $item   = QuickAccessItem::make('لینک خارجی');
        $result = $item->url('https://example.com');

        $this->assertSame($item, $result);
        $this->assertSame('https://example.com', $item->getUrl());
    }

    public function test_sets_icon_correctly(): void
    {
        $item = QuickAccessItem::make('کاربران')->icon('heroicon-o-users');
        $this->assertSame('heroicon-o-users', $item->toArray()['icon']);
    }

    public function test_sets_color_correctly(): void
    {
        $item = QuickAccessItem::make('هشدار')->color('red');
        $this->assertSame('red', $item->toArray()['color']);
    }

    public function test_sets_order_correctly(): void
    {
        $item = QuickAccessItem::make('آیتم')->order(10);
        $this->assertSame(10, $item->getOrder());
        $this->assertSame(10, $item->toArray()['order']);
    }

    public function test_has_default_order_of_zero(): void
    {
        $item = QuickAccessItem::make('آیتم');
        $this->assertSame(0, $item->getOrder());
    }

    public function test_sets_permission_correctly(): void
    {
        $item = QuickAccessItem::make('ایجاد کاربر')->permission('users.create');
        $this->assertSame('users.create', $item->getPermission());
    }

    public function test_sets_target_correctly(): void
    {
        $item = QuickAccessItem::make('لینک خارجی')->target('_blank');
        $this->assertSame('_blank', $item->toArray()['target']);
    }

    public function test_active_state_true_by_default(): void
    {
        $item = QuickAccessItem::make('آیتم');
        $this->assertTrue($item->isActive());
    }

    public function test_can_set_active_state_to_false(): void
    {
        $item = QuickAccessItem::make('آیتم')->active(false);
        $this->assertFalse($item->isActive());
    }

    public function test_sets_active_state_using_callback_true(): void
    {
        $item = QuickAccessItem::make('آیتم')->activeWhen(fn () => true);
        $this->assertTrue($item->isActive());
    }

    public function test_sets_active_state_using_callback_false(): void
    {
        $item = QuickAccessItem::make('آیتم')->activeWhen(fn () => false);
        $this->assertFalse($item->isActive());
    }

    public function test_sets_enabled_state_true(): void
    {
        $item = QuickAccessItem::make('آیتم')->enabled(true);
        $this->assertTrue($item->toArray()['enabled']);
    }

    public function test_sets_enabled_state_false(): void
    {
        $item = QuickAccessItem::make('آیتم')->enabled(false);
        $this->assertFalse($item->toArray()['enabled']);
    }

    public function test_enabled_state_true_by_default(): void
    {
        $item = QuickAccessItem::make('آیتم');
        $this->assertTrue($item->toArray()['enabled']);
    }

    public function test_sets_enabled_state_using_callback_true(): void
    {
        $item = QuickAccessItem::make('آیتم')->enabledWhen(fn () => true);
        $this->assertTrue($item->toArray()['enabled']);
    }

    public function test_sets_enabled_state_using_callback_false(): void
    {
        $item = QuickAccessItem::make('آیتم')->enabledWhen(fn () => false);
        $this->assertFalse($item->toArray()['enabled']);
    }

    public function test_sets_custom_attributes(): void
    {
        $item       = QuickAccessItem::make('آیتم')->attributes(['data-id' => '123', 'class' => 'custom-class']);
        $attributes = $item->toArray()['attributes'];
        $this->assertSame('123', $attributes['data-id']);
        $this->assertSame('custom-class', $attributes['class']);
    }

    public function test_returns_url_when_set_directly(): void
    {
        $item = QuickAccessItem::make('لینک')->url('/create-user');
        $this->assertSame('/create-user', $item->getUrl());
    }

    public function test_returns_null_url_when_not_set(): void
    {
        $item = QuickAccessItem::make('آیتم');
        $this->assertNull($item->getUrl());
    }

    public function test_converts_to_array_with_all_properties(): void
    {
        $item = QuickAccessItem::make('ایجاد کاربر', 'create-user')
            ->url('/users/create')
            ->icon('heroicon-o-user-plus')
            ->color('blue')
            ->order(5)
            ->permission('users.create')
            ->target('_self')
            ->enabled(true)
            ->attributes(['data-test' => 'value']);

        $array = $item->toArray();

        $this->assertSame('create-user', $array['id']);
        $this->assertSame('ایجاد کاربر', $array['title']);
        $this->assertSame('/users/create', $array['url']);
        $this->assertSame('heroicon-o-user-plus', $array['icon']);
        $this->assertSame('blue', $array['color']);
        $this->assertSame(5, $array['order']);
        $this->assertSame('users.create', $array['permission']);
        $this->assertSame('_self', $array['target']);
        $this->assertTrue($array['enabled']);
        $this->assertSame(['data-test' => 'value'], $array['attributes']);
        $this->assertTrue($array['active']);
    }

    public function test_has_type_quick_access_in_array(): void
    {
        $item  = QuickAccessItem::make('تست');
        $array = $item->toArray();
        $this->assertSame('quick-access', $array['type']);
    }

    #[DataProvider('specialCharacterTitlesProvider')]
    public function test_handles_special_characters_in_title(string $title): void
    {
        $item = QuickAccessItem::make($title);
        $this->assertSame($title, $item->toArray()['title']);
        $this->assertNotEmpty($item->getId());
    }

    public static function specialCharacterTitlesProvider(): array
    {
        return [
            'persian'   => ['ایجاد کاربر جدید'],
            'numbers'   => ['گزارش ۱۴۰۲'],
            'special'   => ['تنظیمات (پیشرفته)'],
            'emoji'     => ['افزودن 📝'],
            'empty-ish' => ['   '],
        ];
    }

    public function test_method_chaining_works_correctly(): void
    {
        $item = QuickAccessItem::make('تست')
            ->url('/test')
            ->icon('test-icon')
            ->color('green')
            ->order(5)
            ->enabled(true);

        $array = $item->toArray();
        $this->assertSame('/test', $array['url']);
        $this->assertSame('test-icon', $array['icon']);
        $this->assertSame('green', $array['color']);
        $this->assertSame(5, $array['order']);
        $this->assertTrue($array['enabled']);
    }
}
