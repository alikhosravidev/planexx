<?php

declare(strict_types=1);

namespace App\Services\Menu;

use App\Services\Registry\BaseRegistryItem;
use Illuminate\Contracts\Support\Arrayable;
use Throwable;

class MenuItem extends BaseRegistryItem
{
    protected ?string $url        = null;
    protected ?string $route      = null;
    protected array $routeParams  = [];
    protected ?string $icon       = null;
    protected ?string $color      = null;
    protected ?string $badge      = null;
    protected ?string $badgeColor = null;
    protected array $children     = [];
    protected ?string $target     = null;

    protected function getDefaultType(): string
    {
        return 'item';
    }

    protected function getIdPrefix(): string
    {
        return 'item-';
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

    public function badge(string $badge, ?string $color = null): static
    {
        $this->badge      = $badge;
        $this->badgeColor = $color;

        return $this;
    }

    public function target(string $target): static
    {
        $this->target = $target;

        return $this;
    }

    public function children(array|callable $children): static
    {
        if (is_callable($children)) {
            $builder = new MenuBuilder();
            $children($builder);
            $this->children = $builder->getItems();
        } else {
            $this->children = $children;
        }

        return $this;
    }

    public function addChild(MenuItem $item): static
    {
        $this->children[] = $item;

        return $this;
    }


    public function getChildren(): array
    {
        return $this->children;
    }

    public function hasChildren(): bool
    {
        return !empty($this->children);
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
            'url'         => $this->getUrl(),
            'icon'        => $this->icon,
            'color'       => $this->color,
            'badge'       => $this->badge,
            'badge_color' => $this->badgeColor,
            'target'      => $this->target,
            'children'    => collect($this->children)
                ->map(fn ($child) => $child instanceof Arrayable ? $child->toArray() : $child)
                ->toArray(),
        ]);
    }
}
