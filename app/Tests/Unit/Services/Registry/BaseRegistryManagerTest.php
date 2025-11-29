<?php

declare(strict_types=1);

namespace App\Tests\Unit\Services\Registry;

use App\Contracts\Registry\RegistryBuilderInterface;
use App\Contracts\Registry\RegistryItemInterface;
use App\Services\Registry\BaseRegistryManager;
use Illuminate\Support\Collection;
use Tests\PureUnitTestBase;

final class BaseRegistryManagerTest extends PureUnitTestBase
{
    public function test_register_method_stores_callback_and_returns_manager(): void
    {
        $manager  = $this->createTestManager();
        $callback = fn () => null;

        $result = $manager->register('test.registry', $callback);

        $this->assertSame($manager, $result);
        $this->assertTrue($manager->has('test.registry'));
    }

    public function test_has_method_returns_true_for_registered_registry(): void
    {
        $manager = $this->createTestManager();

        $manager->register('existing.registry', fn () => null);

        $this->assertTrue($manager->has('existing.registry'));
    }

    public function test_has_method_returns_false_for_non_existent_registry(): void
    {
        $manager = $this->createTestManager();

        $this->assertFalse($manager->has('non.existent.registry'));
    }

    public function test_get_registry_names_returns_all_registered_names(): void
    {
        $manager = $this->createTestManager();

        $manager->register('registry.one', fn () => null);
        $manager->register('registry.two', fn () => null);
        $manager->register('registry.three', fn () => null);

        $names = $manager->getRegistryNames();

        $this->assertCount(3, $names);
        $this->assertContains('registry.one', $names);
        $this->assertContains('registry.two', $names);
        $this->assertContains('registry.three', $names);
    }

    public function test_get_registry_names_returns_empty_array_when_no_registrations(): void
    {
        $manager = $this->createTestManager();

        $this->assertEmpty($manager->getRegistryNames());
        $this->assertIsArray($manager->getRegistryNames());
    }

    public function test_multiple_callbacks_can_be_registered_to_same_registry(): void
    {
        $manager = $this->createTestManager();

        $manager->register('multi.registry', fn ($builder) => $builder->add($this->createMockItem('item-1')));
        $manager->register('multi.registry', fn ($builder) => $builder->add($this->createMockItem('item-2')));
        $manager->register('multi.registry', fn ($builder) => $builder->add($this->createMockItem('item-3')));

        $items = $manager->withoutCache()->get('multi.registry');

        $this->assertCount(3, $items);
    }

    public function test_get_returns_collection_of_items(): void
    {
        $manager = $this->createTestManager();

        $manager->register('test.registry', fn ($builder) => $builder->add($this->createMockItem('test')));

        $result = $manager->withoutCache()->get('test.registry');

        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(1, $result);
    }

    public function test_get_returns_empty_collection_for_non_existent_registry(): void
    {
        $manager = $this->createTestManager();

        $result = $manager->withoutCache()->get('non.existent');

        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(0, $result);
        $this->assertTrue($result->isEmpty());
    }

    public function test_items_are_sorted_by_order(): void
    {
        $manager = $this->createTestManager();

        $manager->register('sorted.registry', function ($builder) {
            $builder->add($this->createMockItem('third', 30));
            $builder->add($this->createMockItem('first', 10));
            $builder->add($this->createMockItem('second', 20));
        });

        $items = $manager->withoutCache()->get('sorted.registry');

        $this->assertSame('first', $items[0]->getId());
        $this->assertSame('second', $items[1]->getId());
        $this->assertSame('third', $items[2]->getId());
    }

    public function test_inactive_items_are_filtered_out(): void
    {
        $manager = $this->createTestManager();

        $manager->register('filtered.registry', function ($builder) {
            $builder->add($this->createMockItem('active', 1, true));
            $builder->add($this->createMockItem('inactive', 2, false));
        });

        $items = $manager->withoutCache()->get('filtered.registry');

        $this->assertCount(1, $items);
        $this->assertSame('active', $items->first()->getId());
    }

    public function test_to_array_converts_items_to_array(): void
    {
        $manager = $this->createTestManager();

        $manager->register('array.registry', fn ($builder) => $builder->add($this->createMockItem('test')));

        $result = $manager->withoutCache()->toArray('array.registry');

        $this->assertIsArray($result);
        $this->assertCount(1, $result);
        $this->assertSame('test', $result[0]['id']);
    }

    public function test_to_json_converts_items_to_json_string(): void
    {
        $manager = $this->createTestManager();

        $manager->register('json.registry', fn ($builder) => $builder->add($this->createMockItem('test')));

        $json    = $manager->withoutCache()->toJson('json.registry');
        $decoded = json_decode($json, true);

        $this->assertIsString($json);
        $this->assertNotNull($decoded);
        $this->assertIsArray($decoded);
        $this->assertSame('test', $decoded[0]['id']);
    }

    public function test_with_cache_method_enables_cache_and_is_chainable(): void
    {
        $manager = $this->createTestManager();

        $result = $manager->withCache(7200);

        $this->assertSame($manager, $result);
    }

    public function test_without_cache_method_disables_cache_and_is_chainable(): void
    {
        $manager = $this->createTestManager();

        $result = $manager->withoutCache();

        $this->assertSame($manager, $result);
    }

    private function createTestManager(): BaseRegistryManager
    {
        return new class () extends BaseRegistryManager {
            protected function getConfigKey(): string
            {
                return 'test.config';
            }

            protected function getDefaultCachePrefix(): string
            {
                return 'test_';
            }

            protected function createBuilder(): RegistryBuilderInterface
            {
                return new class () implements RegistryBuilderInterface {
                    private array $items = [];

                    public function add(RegistryItemInterface $item): static
                    {
                        $this->items[] = $item;

                        return $this;
                    }

                    public function getItems(): array
                    {
                        return $this->items;
                    }
                };
            }
        };
    }

    private function createMockItem(string $id, int $order = 0, bool $active = true): RegistryItemInterface
    {
        return new class ($id, $order, $active) implements RegistryItemInterface {
            public function __construct(
                private string $id,
                private int $order,
                private bool $active
            ) {
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
                return $this->order;
            }

            public function getPermission(): ?string
            {
                return null;
            }

            public function isActive(): bool
            {
                return $this->active;
            }

            public function getType(): string
            {
                return 'test';
            }

            public function toArray(): array
            {
                return [
                    'id'     => $this->id,
                    'order'  => $this->order,
                    'active' => $this->active,
                ];
            }
        };
    }
}
