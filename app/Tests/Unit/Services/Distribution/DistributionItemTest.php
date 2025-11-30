<?php

declare(strict_types=1);

namespace App\Tests\Unit\Services\Distribution;

use App\Services\Distribution\DistributionItem;
use Illuminate\Support\Str;
use Tests\PureUnitTestBase;

final class DistributionItemTest extends PureUnitTestBase
{
    public function test_creates_distribution_item_with_title_and_auto_generated_id(): void
    {
        $title = 'بخش فروش';
        $item  = new DistributionItem($title);

        $this->assertSame($title, $item->toArray()['label']);
        $this->assertSame(Str::slug($title), $item->getId());
    }

    public function test_creates_distribution_item_with_custom_id(): void
    {
        $item = new DistributionItem('بخش فروش', 'custom-sales-id');
        $this->assertSame('custom-sales-id', $item->getId());
    }

    public function test_creates_distribution_item_using_static_make_method(): void
    {
        $item = DistributionItem::make('افزودن محصول', 'add-product');

        $this->assertInstanceOf(DistributionItem::class, $item);
        $this->assertSame('add-product', $item->getId());
    }

    public function test_sets_value_correctly(): void
    {
        $item   = DistributionItem::make('بخش تست');
        $result = $item->value('۱۰۰۰');

        $this->assertSame($item, $result);
        $this->assertSame('۱۰۰۰', $item->toArray()['value']);
    }

    public function test_sets_value_with_integer(): void
    {
        $item = DistributionItem::make('بخش تست')->value(1500);
        $this->assertSame(1500, $item->toArray()['value']);
    }

    public function test_sets_percent_correctly(): void
    {
        $item   = DistributionItem::make('بخش تست');
        $result = $item->percent(25);

        $this->assertSame($item, $result);
        $this->assertSame(25, $item->toArray()['percent']);
    }

    public function test_sets_color_correctly(): void
    {
        $item   = DistributionItem::make('بخش تست');
        $result = $item->color('blue');

        $this->assertSame($item, $result);
        $this->assertSame('blue', $item->toArray()['color']);
    }

    public function test_has_default_percent_of_zero(): void
    {
        $item = DistributionItem::make('بخش تست');
        $this->assertSame(0, $item->toArray()['percent']);
    }

    public function test_has_null_default_value(): void
    {
        $item = DistributionItem::make('بخش تست');
        $this->assertNull($item->toArray()['value']);
    }

    public function test_has_null_default_color(): void
    {
        $item = DistributionItem::make('بخش تست');
        $this->assertNull($item->toArray()['color']);
    }

    public function test_converts_to_array_with_all_properties(): void
    {
        $item = DistributionItem::make('بخش فروش', 'sales-segment')
            ->value('۵۰۰۰۰۰')
            ->percent(50)
            ->color('green')
            ->order(1);

        $array = $item->toArray();

        $this->assertSame('sales-segment', $array['id']);
        $this->assertSame('بخش فروش', $array['label']);
        $this->assertSame('۵۰۰۰۰۰', $array['value']);
        $this->assertSame(50, $array['percent']);
        $this->assertSame('green', $array['color']);
        $this->assertSame(1, $array['order']);
        $this->assertTrue($array['active']);
    }

    public function test_has_type_distribution_segment_in_array(): void
    {
        $item  = DistributionItem::make('تست');
        $array = $item->toArray();
        $this->assertSame('distribution-segment', $array['type']);
    }

    /**
     * @dataProvider specialCharacterTitlesProvider
     */
    public function test_handles_special_characters_in_title(string $title): void
    {
        $item = DistributionItem::make($title);
        $this->assertSame($title, $item->toArray()['label']);
        $this->assertNotEmpty($item->getId());
    }

    public static function specialCharacterTitlesProvider(): array
    {
        return [
            'persian'   => ['بخش فروش'],
            'numbers'   => ['گزارش ۱۴۰۲'],
            'special'   => ['بخش (ویژه)'],
            'emoji'     => ['بخش 📊'],
            'empty-ish' => ['   '],
        ];
    }

    public function test_method_chaining_works_correctly(): void
    {
        $item = DistributionItem::make('بخش تست')
            ->value('۱۰۰۰')
            ->percent(25)
            ->color('blue')
            ->order(5);

        $array = $item->toArray();
        $this->assertSame('۱۰۰۰', $array['value']);
        $this->assertSame(25, $array['percent']);
        $this->assertSame('blue', $array['color']);
        $this->assertSame(5, $array['order']);
    }
}
