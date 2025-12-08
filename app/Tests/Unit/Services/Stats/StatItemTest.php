<?php

declare(strict_types=1);

namespace App\Tests\Unit\Services\Stats;

use App\Services\Stats\StatItem;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\PureUnitTestBase;

final class StatItemTest extends PureUnitTestBase
{
    public function test_creates_stat_item_with_title_and_auto_generated_id(): void
    {
        $title = 'کل فروش';
        $item  = new StatItem($title);

        $this->assertSame($title, $item->toArray()['title']);
        $this->assertSame(Str::slug($title), $item->getId());
    }

    public function test_creates_stat_item_with_custom_id(): void
    {
        $item = new StatItem('کل فروش', 'custom-sales-id');
        $this->assertSame('custom-sales-id', $item->getId());
    }

    public function test_creates_stat_item_using_static_make_method(): void
    {
        $item = StatItem::make('تعداد کاربران', 'users-count');

        $this->assertInstanceOf(StatItem::class, $item);
        $this->assertSame('users-count', $item->getId());
    }

    public function test_sets_value_correctly(): void
    {
        $expectedValue = '۱۲,۳۴۵';
        $item          = StatItem::make('فروش')->value($expectedValue);

        $this->assertSame($expectedValue, $item->toArray()['value']);
    }

    public function test_sets_description_correctly(): void
    {
        $expectedDescription = 'نسبت به ماه گذشته';
        $item                = StatItem::make('فروش')->description($expectedDescription);

        $this->assertSame($expectedDescription, $item->toArray()['description']);
    }

    public function test_sets_icon_correctly(): void
    {
        $item = StatItem::make('کاربران')->icon('heroicon-o-users');
        $this->assertSame('heroicon-o-users', $item->toArray()['icon']);
    }

    public function test_sets_color_correctly(): void
    {
        $item = StatItem::make('هشدار')->color('red');
        $this->assertSame('red', $item->toArray()['color']);
    }

    public function test_sets_change_value_correctly(): void
    {
        $expectedChange = '+۱۵٪';
        $item           = StatItem::make('فروش')->change($expectedChange);

        $this->assertSame($expectedChange, $item->toArray()['change']);
    }

    public function test_sets_change_with_type_correctly(): void
    {
        $item = StatItem::make('فروش')->change('+۱۵٪', 'increase');
        $this->assertSame('+۱۵٪', $item->toArray()['change']);
        $this->assertSame('increase', $item->toArray()['change_type']);
    }

    public function test_sets_order_correctly(): void
    {
        $item = StatItem::make('آمار')->order(10);
        $this->assertSame(10, $item->getOrder());
        $this->assertSame(10, $item->toArray()['order']);
    }

    public function test_has_default_order_of_zero(): void
    {
        $item = StatItem::make('آمار');
        $this->assertSame(0, $item->getOrder());
    }

    public function test_sets_permission_correctly(): void
    {
        $item = StatItem::make('فروش')->permission('stats.sales.view');
        $this->assertSame('stats.sales.view', $item->getPermission());
    }

    public function test_active_state_true_by_default(): void
    {
        $item = StatItem::make('آمار');
        $this->assertTrue($item->isActive());
    }

    public function test_can_set_active_state_to_false(): void
    {
        $item = StatItem::make('آمار')->active(false);
        $this->assertFalse($item->isActive());
    }

    public function test_sets_active_state_using_callback_true(): void
    {
        $item = StatItem::make('آمار')->activeWhen(fn () => true);
        $this->assertTrue($item->isActive());
    }

    public function test_sets_active_state_using_callback_false(): void
    {
        $item = StatItem::make('آمار')->activeWhen(fn () => false);
        $this->assertFalse($item->isActive());
    }

    public function test_sets_custom_attributes(): void
    {
        $item       = StatItem::make('آمار')->attributes(['data-id' => '123', 'class' => 'custom-stat']);
        $attributes = $item->toArray()['attributes'];
        $this->assertSame('123', $attributes['data-id']);
        $this->assertSame('custom-stat', $attributes['class']);
    }

    public function test_converts_to_array_with_all_properties(): void
    {
        $item = StatItem::make('فروش کل', 'total-sales')
            ->value('۵۰,۰۰۰')
            ->description('در این ماه')
            ->icon('heroicon-o-currency-dollar')
            ->color('green')
            ->change('+۲۰٪', 'increase')
            ->order(5)
            ->permission('stats.sales')
            ->attributes(['data-test' => 'value']);

        $array = $item->toArray();

        $this->assertSame('total-sales', $array['id']);
        $this->assertSame('فروش کل', $array['title']);
        $this->assertSame('۵۰,۰۰۰', $array['value']);
        $this->assertSame('در این ماه', $array['description']);
        $this->assertSame('heroicon-o-currency-dollar', $array['icon']);
        $this->assertSame('green', $array['color']);
        $this->assertSame('+۲۰٪', $array['change']);
        $this->assertSame('increase', $array['change_type']);
        $this->assertSame(5, $array['order']);
        $this->assertSame('stats.sales', $array['permission']);
        $this->assertSame(['data-test' => 'value'], $array['attributes']);
        $this->assertTrue($array['active']);
    }

    public function test_has_type_stat_in_array(): void
    {
        $item  = StatItem::make('تست');
        $array = $item->toArray();
        $this->assertSame('stat', $array['type']);
    }

    #[DataProvider('specialCharacterTitlesProvider')]
    public function test_handles_special_characters_in_title(string $title): void
    {
        $item = StatItem::make($title);
        $this->assertSame($title, $item->toArray()['title']);
        $this->assertNotEmpty($item->getId());
    }

    public static function specialCharacterTitlesProvider(): array
    {
        return [
            'persian'   => ['کل فروش امسال'],
            'numbers'   => ['آمار ۱۴۰۲'],
            'special'   => ['فروش (ماهانه)'],
            'emoji'     => ['درآمد 💰'],
            'empty-ish' => ['   '],
        ];
    }

    public function test_method_chaining_works_correctly(): void
    {
        $item = StatItem::make('تست')
            ->value('۱۰۰')
            ->icon('test-icon')
            ->color('blue')
            ->order(5);

        $array = $item->toArray();
        $this->assertSame('۱۰۰', $array['value']);
        $this->assertSame('test-icon', $array['icon']);
        $this->assertSame('blue', $array['color']);
        $this->assertSame(5, $array['order']);
    }
}
