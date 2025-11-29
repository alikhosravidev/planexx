<?php

declare(strict_types=1);

namespace App\Tests\Unit\Services\Registry;

use App\Contracts\Registry\RegistryItemInterface;
use App\Services\Registry\BaseRegistryBuilder;
use Tests\PureUnitTestBase;

final class BaseRegistryBuilderTest extends PureUnitTestBase
{
    public function test_starts_with_empty_items_array(): void
    {
        $builder = $this->createTestBuilder();

        $this->assertEmpty($builder->getItems());
        $this->assertIsArray($builder->getItems());
    }

    public function test_add_method_adds_item_to_items_array(): void
    {
        $builder = $this->createTestBuilder();
        $item    = $this->createMockItem('test-1');

        $result = $builder->add($item);

        $this->assertSame($builder, $result);
        $this->assertCount(1, $builder->getItems());
        $this->assertSame($item, $builder->getItems()[0]);
    }

    public function test_add_method_is_chainable(): void
    {
        $builder = $this->createTestBuilder();
        $item1   = $this->createMockItem('test-1');
        $item2   = $this->createMockItem('test-2');

        $result = $builder->add($item1)->add($item2);

        $this->assertSame($builder, $result);
        $this->assertCount(2, $builder->getItems());
    }

    public function test_adds_multiple_items_in_order(): void
    {
        $builder = $this->createTestBuilder();
        $item1   = $this->createMockItem('first');
        $item2   = $this->createMockItem('second');
        $item3   = $this->createMockItem('third');

        $builder->add($item1);
        $builder->add($item2);
        $builder->add($item3);

        $items = $builder->getItems();
        $this->assertCount(3, $items);
        $this->assertSame($item1, $items[0]);
        $this->assertSame($item2, $items[1]);
        $this->assertSame($item3, $items[2]);
    }

    public function test_get_items_returns_array_not_reference(): void
    {
        $builder = $this->createTestBuilder();
        $item    = $this->createMockItem('test');

        $builder->add($item);
        $items1 = $builder->getItems();
        $items2 = $builder->getItems();

        $this->assertSame($items1, $items2);
        $this->assertCount(1, $items1);
    }

    public function test_accepts_different_item_implementations(): void
    {
        $builder = $this->createTestBuilder();
        $item1   = $this->createMockItem('item-1');
        $item2   = $this->createMockItem('item-2');

        $builder->add($item1);
        $builder->add($item2);

        $items = $builder->getItems();
        $this->assertContainsOnlyInstancesOf(RegistryItemInterface::class, $items);
    }

    private function createTestBuilder(): BaseRegistryBuilder
    {
        return new class () extends BaseRegistryBuilder {
        };
    }

    private function createMockItem(string $id): RegistryItemInterface
    {
        return new class ($id) implements RegistryItemInterface {
            public function __construct(private string $id)
            {
            }

            public function getId(): string
            {
                return $this->id;
            }

            public function getTitle(): string
            {
                return 'Test Title';
            }

            public function getOrder(): int
            {
                return 0;
            }

            public function getPermission(): ?string
            {
                return null;
            }

            public function isActive(): bool
            {
                return true;
            }

            public function getType(): string
            {
                return 'test';
            }

            public function toArray(): array
            {
                return ['id' => $this->id];
            }
        };
    }
}
