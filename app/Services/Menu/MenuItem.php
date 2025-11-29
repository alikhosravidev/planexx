<?php

declare(strict_types=1);

namespace App\Services\Menu;

use Closure;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Throwable;

class MenuItem implements Arrayable
{
    protected string $id;
    protected string $title;
    protected ?string $url             = null;
    protected ?string $route           = null;
    protected array $routeParams       = [];
    protected ?string $icon            = null;
    protected ?string $color           = null;
    protected ?string $badge           = null;
    protected ?string $badgeColor      = null;
    protected int $order               = 0;
    protected bool $active             = true;
    protected ?string $permission      = null;
    protected array $children          = [];
    protected ?string $target          = null;
    protected array $attributes        = [];
    protected string $type             = 'item';
    protected ?Closure $activeResolver = null;

    public function __construct(string $title, ?string $id = null)
    {
        $this->title = $title;

        if ($id !== null) {
            $this->id = $id;
        } else {
            $slug     = Str::slug($title);
            $this->id = $slug !== '' ? $slug : 'item-' . Str::random(8);
        }
    }

    public static function make(string $title, ?string $id = null): static
    {
        return new static($title, $id);
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

    public function order(int $order): static
    {
        $this->order = $order;

        return $this;
    }

    public function permission(string $permission): static
    {
        $this->permission = $permission;

        return $this;
    }

    public function target(string $target): static
    {
        $this->target = $target;

        return $this;
    }

    public function active(bool $active = true): static
    {
        $this->active = $active;

        return $this;
    }

    public function activeWhen(callable $callback): static
    {
        $this->activeResolver = $callback(...);

        return $this;
    }

    public function attributes(array $attributes): static
    {
        $this->attributes = $attributes;

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

    public function type(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getOrder(): int
    {
        return $this->order;
    }

    public function getPermission(): ?string
    {
        return $this->permission;
    }

    public function getChildren(): array
    {
        return $this->children;
    }

    public function hasChildren(): bool
    {
        return !empty($this->children);
    }

    public function isActive(): bool
    {
        if ($this->activeResolver instanceof Closure) {
            return (bool) ($this->activeResolver)(app(Request::class), $this);
        }

        return $this->active;
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
        return [
            'id'          => $this->id,
            'type'        => $this->type,
            'title'       => $this->title,
            'url'         => $this->getUrl(),
            'icon'        => $this->icon,
            'color'       => $this->color,
            'badge'       => $this->badge,
            'badge_color' => $this->badgeColor,
            'order'       => $this->order,
            'active'      => $this->isActive(),
            'permission'  => $this->permission,
            'target'      => $this->target,
            'attributes'  => $this->attributes,
            'children'    => collect($this->children)
                ->map(fn ($child) => $child instanceof Arrayable ? $child->toArray() : $child)
                ->toArray(),
        ];
    }
}
