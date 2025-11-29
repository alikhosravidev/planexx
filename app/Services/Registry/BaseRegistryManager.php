<?php

declare(strict_types=1);

namespace App\Services\Registry;

use App\Contracts\Registry\RegistrarInterface;
use App\Contracts\Registry\RegistryBuilderInterface;
use App\Contracts\Registry\RegistryManagerInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use InvalidArgumentException;
use Throwable;

abstract class BaseRegistryManager implements RegistryManagerInterface
{
    protected array $pendingItems = [];
    protected bool $cacheEnabled;
    protected int $cacheTtl;
    protected string $cachePrefix;

    public function __construct()
    {
        $configKey = $this->getConfigKey();

        $this->cacheEnabled = (bool) $this->getConfig("{$configKey}.cache_enabled", true);
        $this->cacheTtl     = (int) $this->getConfig("{$configKey}.cache_ttl", 3600);
        $this->cachePrefix  = (string) $this->getConfig("{$configKey}.cache_prefix", $this->getDefaultCachePrefix());
    }

    protected function getConfig(string $key, mixed $default = null): mixed
    {
        try {
            return config($key, $default);
        } catch (Throwable) {
            return $default;
        }
    }

    abstract protected function getConfigKey(): string;

    abstract protected function getDefaultCachePrefix(): string;

    abstract protected function createBuilder(): RegistryBuilderInterface;

    public function register(string $registryName, callable $callback): static
    {
        $this->pendingItems[$registryName][] = $callback;

        return $this;
    }

    public function get(string $registryName): Collection
    {
        if ($this->cacheEnabled) {
            $cacheKey = $this->getCacheKey($registryName);
            $cached   = Cache::get($cacheKey);

            if ($cached) {
                return collect($cached);
            }
        }

        $items = $this->build($registryName);
        $items = $this->filterByPermissions($items);
        $items = $this->applyCustomFilters($items, $registryName);

        if ($this->cacheEnabled) {
            $cacheKey = $cacheKey ?? $this->getCacheKey($registryName);
            Cache::put($cacheKey, $items->all(), $this->cacheTtl);
        }

        return $items;
    }

    protected function build(string $registryName): Collection
    {
        $builder = $this->createBuilder();

        foreach ($this->pendingItems[$registryName] ?? [] as $callback) {
            $callback($builder);
        }

        $items = collect($builder->getItems());

        return $this->sortItems($items);
    }

    protected function sortItems(Collection $items): Collection
    {
        return $items
            ->sortBy(fn ($item) => $item->getOrder())
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

            return true;
        })->values();
    }

    protected function applyCustomFilters(Collection $items, string $registryName): Collection
    {
        return $items;
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

    public function toArray(string $registryName): array
    {
        return $this->get($registryName)
            ->map(fn ($item) => $item->toArray())
            ->toArray();
    }

    public function toJson(string $registryName): string
    {
        return json_encode($this->toArray($registryName), JSON_UNESCAPED_UNICODE);
    }

    public function registerBy(string $registrarClass): static
    {
        $registrar = app($registrarClass);

        if (! $registrar instanceof RegistrarInterface) {
            throw new InvalidArgumentException("{$registrarClass} must implement " . RegistrarInterface::class);
        }

        $registrar->register($this);

        return $this;
    }

    public function clearCache(?string $registryName = null): void
    {
        if ($registryName) {
            Cache::forget($this->getCacheKey($registryName));

            return;
        }

        foreach ($this->getRegistryNames() as $name) {
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

    protected function getCacheKey(string $registryName): string
    {
        $userId = Auth::id() ?? 'guest';

        return $this->cachePrefix . $registryName . '_' . $userId;
    }

    public function has(string $registryName): bool
    {
        return isset($this->pendingItems[$registryName]);
    }

    public function getRegistryNames(): array
    {
        return array_keys($this->pendingItems);
    }
}
