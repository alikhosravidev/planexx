<?php

declare(strict_types=1);

namespace App\Services\Menu;

use App\Contracts\MenuRegistrar;
use App\Contracts\Registry\RegistrarInterface;
use App\Contracts\Registry\RegistryBuilderInterface;
use App\Services\Registry\BaseRegistryManager;
use Illuminate\Support\Collection;
use InvalidArgumentException;

class MenuManager extends BaseRegistryManager
{
    protected function getConfigKey(): string
    {
        return 'services.registry';
    }

    protected function getDefaultCachePrefix(): string
    {
        return 'menu_';
    }

    protected function createBuilder(): RegistryBuilderInterface
    {
        return new MenuBuilder();
    }

    public function extend(string $menuName, string $parentId, callable $callback): static
    {
        $this->pendingItems[$menuName . '_extend_' . $parentId][] = $callback;

        return $this;
    }

    protected function build(string $registryName): Collection
    {
        $items = parent::build($registryName);

        return $this->applyExtensions($items, $registryName);
    }

    protected function applyExtensions(Collection $items, string $menuName): Collection
    {
        $apply = function (MenuItem $item) use ($menuName, &$apply) {
            $extendKey = $menuName . '_extend_' . $item->getId();

            if (isset($this->pendingItems[$extendKey])) {
                foreach ($this->pendingItems[$extendKey] as $callback) {
                    $builder = new MenuBuilder();
                    $callback($builder);

                    foreach ($builder->getItems() as $child) {
                        $item->addChild($child);
                    }
                }
            }

            foreach ($item->getChildren() as $child) {
                if ($child instanceof MenuItem) {
                    $apply($child);
                }
            }

            return $item;
        };

        return $items->map(fn ($item) => $apply($item));
    }

    protected function sortItems(Collection $items): Collection
    {
        return $items
            ->sortBy(fn ($item) => $item->getOrder())
            ->map(function ($item) {
                if ($item->hasChildren()) {
                    $children = $this->sortItems(collect($item->getChildren()));
                    $item->children($children->all());
                }

                return $item;
            })
            ->values();
    }

    protected function filterByPermissions(Collection $items): Collection
    {
        return $items->filter(function ($item) {
            if (! $item->isActive()) {
                return false;
            }

            $permission = $item->getPermission();

            if ($permission && ! $this->userHasPermission($permission)) {
                return false;
            }

            if ($item->hasChildren()) {
                $filteredChildren = $this->filterByPermissions(collect($item->getChildren()));
                $item->children($filteredChildren->all());

                if ($item instanceof MenuGroup && ! $filteredChildren->count()) {
                    return false;
                }
            }

            return true;
        })->values();
    }

    public function registerBy(string $registrarClass): static
    {
        $registrar = app($registrarClass);

        if ($registrar instanceof RegistrarInterface) {
            $registrar->register($this);
        } elseif ($registrar instanceof MenuRegistrar) {
            $registrar->register($this);
        } else {
            throw new InvalidArgumentException("{$registrarClass} must implement " . RegistrarInterface::class . ' or ' . MenuRegistrar::class);
        }

        return $this;
    }

    public function clearCache(?string $menuName = null): void
    {
        if ($menuName) {
            parent::clearCache($menuName);

            return;
        }

        foreach ($this->getBaseMenuNames() as $name) {
            parent::clearCache($name);
        }
    }

    public function getMenuNames(): array
    {
        return $this->getRegistryNames();
    }

    protected function getBaseMenuNames(): array
    {
        $names = [];

        foreach (array_keys($this->pendingItems) as $name) {
            if (! str_contains($name, '_extend_')) {
                $names[] = $name;
            }
        }

        return array_values(array_unique($names));
    }

    public function getTransformed(string $registryName): Collection
    {
        $items = $this->get($registryName);

        return $items->map(fn ($item) => $item->toArray());
    }
}
