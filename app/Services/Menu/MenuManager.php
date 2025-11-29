<?php

declare(strict_types=1);

namespace App\Services\Menu;

use App\Contracts\MenuRegistrar;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class MenuManager implements MenuRegistrar
{
    protected array $pendingItems = [];
    protected bool $cacheEnabled;
    protected int $cacheTtl;
    protected string $cachePrefix;

    public function __construct()
    {
        $this->cacheEnabled = (bool) config('services.menu.cache_enabled', true);
        $this->cacheTtl     = (int) config('services.menu.cache_ttl', 3600);
        $this->cachePrefix  = (string) config('services.menu.cache_prefix', 'menu_');
    }

    public function register(string $menuName, callable $callback): static
    {
        $this->pendingItems[$menuName][] = $callback;

        return $this;
    }

    public function extend(string $menuName, string $parentId, callable $callback): static
    {
        $this->pendingItems[$menuName . '_extend_' . $parentId][] = $callback;

        return $this;
    }

    public function get(string $menuName): Collection
    {
        if ($this->cacheEnabled) {
            $cacheKey = $this->getCacheKey($menuName);
            $cached   = Cache::get($cacheKey);

            if ($cached) {
                return collect($cached);
            }
        }

        $items = $this->build($menuName);
        $items = $this->filterByPermissions($items);

        if ($this->cacheEnabled) {
            $cacheKey = $cacheKey ?? $this->getCacheKey($menuName);
            Cache::put($cacheKey, $items->all(), $this->cacheTtl);
        }

        return $items;
    }

    protected function build(string $menuName): Collection
    {
        $builder = new MenuBuilder();

        foreach ($this->pendingItems[$menuName] ?? [] as $callback) {
            $callback($builder);
        }

        $items = collect($builder->getItems());

        $items = $this->applyExtensions($items, $menuName);

        return $this->sortItems($items);
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

    protected function userHasPermission(string $permission): bool
    {
        $user = Auth::user();

        if (! $user) {
            return false;
        }

        if (method_exists($user, 'hasPermissionTo')) {
            return $user->hasPermissionTo($permission);
        }

        if (method_exists($user, 'can')) {
            return $user->can($permission);
        }

        return true;
    }

    public function toArray(string $menuName): array
    {
        return $this->get($menuName)
            ->map(fn ($item) => $item->toArray())
            ->toArray();
    }

    public function toJson(string $menuName): string
    {
        return json_encode($this->toArray($menuName), JSON_UNESCAPED_UNICODE);
    }

    public function clearCache(?string $menuName = null): void
    {
        if ($menuName) {
            Cache::forget($this->getCacheKey($menuName));

            return;
        }

        foreach ($this->getBaseMenuNames() as $name) {
            Cache::forget($this->getCacheKey($name));
        }
    }

    public function withoutCache(): static
    {
        $this->cacheEnabled = false;

        return $this;
    }

    public function withCache(int $ttl = 3600): static
    {
        $this->cacheEnabled = true;
        $this->cacheTtl     = $ttl;

        return $this;
    }

    protected function getCacheKey(string $menuName): string
    {
        $userId = Auth::id() ?? 'guest';

        return $this->cachePrefix . $menuName . '_' . $userId;
    }

    public function has(string $menuName): bool
    {
        return isset($this->pendingItems[$menuName]);
    }

    public function getMenuNames(): array
    {
        return array_keys($this->pendingItems);
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
}
