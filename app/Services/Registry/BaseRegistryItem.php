<?php

declare(strict_types=1);

namespace App\Services\Registry;

use App\Contracts\Registry\RegistryItemInterface;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

abstract class BaseRegistryItem implements RegistryItemInterface
{
    protected string $id;
    protected string $title;
    protected int $order          = 0;
    protected bool $active        = true;
    protected ?string $permission = null;
    protected array $attributes   = [];
    protected string $type;
    protected ?Closure $activeResolver = null;

    public function __construct(string $title, ?string $id = null)
    {
        $this->title = $title;
        $this->type  = $this->getDefaultType();

        if ($id !== null) {
            $this->id = $id;
        } else {
            $slug     = Str::slug($title);
            $this->id = $slug !== '' ? $slug : $this->getIdPrefix() . Str::random(8);
        }
    }

    abstract protected function getDefaultType(): string;

    abstract protected function getIdPrefix(): string;

    public static function make(string $title, ?string $id = null): static
    {
        return new static($title, $id);
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

    public function type(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getOrder(): int
    {
        return $this->order;
    }

    public function getPermission(): ?string
    {
        return $this->permission;
    }

    public function isActive(): bool
    {
        if ($this->activeResolver instanceof Closure) {
            return (bool) ($this->activeResolver)(app(Request::class), $this);
        }

        return $this->active;
    }

    public function getType(): string
    {
        return $this->type;
    }

    protected function getBaseArray(): array
    {
        return [
            'id'         => $this->id,
            'type'       => $this->type,
            'title'      => $this->title,
            'order'      => $this->order,
            'active'     => $this->isActive(),
            'permission' => $this->permission,
            'attributes' => $this->attributes,
        ];
    }

    abstract public function toArray(): array;
}
