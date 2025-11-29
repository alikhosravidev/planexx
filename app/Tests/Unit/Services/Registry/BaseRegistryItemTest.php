<?php

declare(strict_types=1);

namespace App\Tests\Unit\Services\Registry;

use App\Services\Registry\BaseRegistryItem;
use Illuminate\Http\Request;
use Tests\PureUnitTestBase;

final class BaseRegistryItemTest extends PureUnitTestBase
{
    public function test_generates_id_from_title_slug_when_id_not_provided(): void
    {
        $item = $this->createTestItem('تست عنوان');

        $this->assertSame('tst-aanoan', $item->getId());
    }

    public function test_uses_provided_id_when_given(): void
    {
        $item = $this->createTestItem('عنوان', 'custom-id');

        $this->assertSame('custom-id', $item->getId());
    }

    public function test_generates_random_id_when_slug_is_empty(): void
    {
        $item = $this->createTestItem('   ');

        $id = $item->getId();
        $this->assertStringStartsWith('test-', $id);
        $this->assertGreaterThan(5, strlen($id));
    }

    public function test_static_make_method_creates_instance(): void
    {
        $itemClass = $this->getTestItemClass();
        $item      = $itemClass::make('تست', 'test-id');

        $this->assertInstanceOf(BaseRegistryItem::class, $item);
        $this->assertSame('test-id', $item->getId());
        $this->assertSame('تست', $item->getTitle());
    }

    public function test_order_method_sets_and_returns_order(): void
    {
        $item = $this->createTestItem('تست');

        $result = $item->order(10);

        $this->assertSame($item, $result);
        $this->assertSame(10, $item->getOrder());
    }

    public function test_default_order_is_zero(): void
    {
        $item = $this->createTestItem('تست');

        $this->assertSame(0, $item->getOrder());
    }

    public function test_permission_method_sets_and_returns_permission(): void
    {
        $item = $this->createTestItem('تست');

        $result = $item->permission('test.permission');

        $this->assertSame($item, $result);
        $this->assertSame('test.permission', $item->getPermission());
    }

    public function test_default_permission_is_null(): void
    {
        $item = $this->createTestItem('تست');

        $this->assertNull($item->getPermission());
    }

    public function test_active_method_sets_active_state_to_true(): void
    {
        $item = $this->createTestItem('تست');

        $result = $item->active(true);

        $this->assertSame($item, $result);
        $this->assertTrue($item->isActive());
    }

    public function test_active_method_sets_active_state_to_false(): void
    {
        $item = $this->createTestItem('تست');

        $result = $item->active(false);

        $this->assertSame($item, $result);
        $this->assertFalse($item->isActive());
    }

    public function test_default_active_state_is_true(): void
    {
        $item = $this->createTestItem('تست');

        $this->assertTrue($item->isActive());
    }

    public function test_active_when_with_callback_returning_true(): void
    {
        $item = $this->createTestItem('تست');

        $result = $item->activeWhen(fn () => true);

        $this->assertSame($item, $result);
        $this->assertTrue($item->isActive());
    }

    public function test_active_when_with_callback_returning_false(): void
    {
        $item = $this->createTestItem('تست');

        $result = $item->activeWhen(fn () => false);

        $this->assertSame($item, $result);
        $this->assertFalse($item->isActive());
    }

    public function test_active_when_callback_receives_request_and_item(): void
    {
        $item           = $this->createTestItem('تست');
        $receivedParams = [];

        $item->activeWhen(function ($request, $itemParam) use (&$receivedParams) {
            $receivedParams['request'] = $request;
            $receivedParams['item']    = $itemParam;

            return true;
        });

        $item->isActive();

        $this->assertInstanceOf(Request::class, $receivedParams['request']);
        $this->assertSame($item, $receivedParams['item']);
    }

    public function test_attributes_method_sets_custom_attributes(): void
    {
        $item       = $this->createTestItem('تست');
        $attributes = ['data-id' => '123', 'class' => 'custom'];

        $result = $item->attributes($attributes);

        $this->assertSame($item, $result);
        $array = $item->toArray();
        $this->assertSame($attributes, $array['attributes']);
    }

    public function test_type_method_overrides_default_type(): void
    {
        $item = $this->createTestItem('تست');

        $result = $item->type('custom-type');

        $this->assertSame($item, $result);
        $this->assertSame('custom-type', $item->getType());
    }

    public function test_default_type_comes_from_get_default_type(): void
    {
        $item = $this->createTestItem('تست');

        $this->assertSame('test-type', $item->getType());
    }

    public function test_get_base_array_returns_common_properties(): void
    {
        $item = $this->createTestItem('عنوان تست', 'test-id')
            ->order(5)
            ->permission('test.permission')
            ->active(true)
            ->attributes(['key' => 'value']);

        $array = $item->toArray();

        $this->assertSame('test-id', $array['id']);
        $this->assertSame('test-type', $array['type']);
        $this->assertSame('عنوان تست', $array['title']);
        $this->assertSame(5, $array['order']);
        $this->assertTrue($array['active']);
        $this->assertSame('test.permission', $array['permission']);
        $this->assertSame(['key' => 'value'], $array['attributes']);
    }

    public function test_method_chaining_works_correctly(): void
    {
        $item = $this->createTestItem('تست')
            ->order(10)
            ->permission('test.perm')
            ->active(true)
            ->attributes(['test' => 'value'])
            ->type('custom');

        $this->assertSame(10, $item->getOrder());
        $this->assertSame('test.perm', $item->getPermission());
        $this->assertTrue($item->isActive());
        $this->assertSame('custom', $item->getType());
    }

    private function createTestItem(string $title, ?string $id = null): BaseRegistryItem
    {
        $class = $this->getTestItemClass();

        return new $class($title, $id);
    }

    private function getTestItemClass(): string
    {
        return new class ('') extends BaseRegistryItem {
            protected function getDefaultType(): string
            {
                return 'test-type';
            }

            protected function getIdPrefix(): string
            {
                return 'test-';
            }

            public function toArray(): array
            {
                return $this->getBaseArray();
            }
        }::class;
    }
}
