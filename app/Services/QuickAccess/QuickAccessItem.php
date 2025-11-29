<?php

declare(strict_types=1);

namespace App\Services\QuickAccess;

use App\Services\Registry\BaseRegistryItem;
use Closure;
use Illuminate\Http\Request;
use Throwable;

class QuickAccessItem extends BaseRegistryItem
{
    protected ?string $url              = null;
    protected ?string $route            = null;
    protected array $routeParams        = [];
    protected ?string $icon             = null;
    protected ?string $color            = null;
    protected bool $enabled             = true;
    protected ?string $target           = null;
    protected ?Closure $enabledResolver = null;

    protected function getDefaultType(): string
    {
        return 'quick-access';
    }

    protected function getIdPrefix(): string
    {
        return 'qa-';
    }

    public function url(string $url): static
    {
        $this->url = $url;

        return $this;
    }

    public function route(string $route, array $params = []): static
    {
        $this->route       = $route;
        $this->routeParams = $params;

        return $this;
    }

    public function icon(string $icon): static
    {
        $this->icon = $icon;

        return $this;
    }

    public function color(string $color): static
    {
        $this->color = $color;

        return $this;
    }

    public function target(string $target): static
    {
        $this->target = $target;

        return $this;
    }

    public function enabled(bool $enabled = true): static
    {
        $this->enabled = $enabled;

        return $this;
    }

    public function enabledWhen(callable $callback): static
    {
        $this->enabledResolver = $callback(...);

        return $this;
    }

    public function isEnabled(): bool
    {
        if ($this->enabledResolver instanceof Closure) {
            return (bool) ($this->enabledResolver)(app(Request::class), $this);
        }

        return $this->enabled;
    }

    public function getUrl(): ?string
    {
        if ($this->route) {
            try {
                return route($this->route, $this->routeParams);
            } catch (Throwable) {
                return '#';
            }
        }

        return $this->url;
    }

    public function toArray(): array
    {
        return array_merge($this->getBaseArray(), [
            'url'     => $this->getUrl(),
            'icon'    => $this->icon,
            'color'   => $this->color,
            'enabled' => $this->isEnabled(),
            'target'  => $this->target,
        ]);
    }
}
