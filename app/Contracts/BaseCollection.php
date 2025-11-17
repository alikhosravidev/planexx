<?php

declare(strict_types=1);

namespace App\Contracts;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;

abstract class BaseCollection extends Collection
{
    protected string $expectedClass;

    private bool $isConvertingToArray = false;

    public function __construct(array $items = [])
    {
        $this->setExpectedClass();
        parent::__construct(
            $this->validateItems($items)
        );
    }

    public static function empty(): self
    {
        return new static();
    }

    public function offsetSet($key, $value): void
    {
        $this->validateItem($value);
        parent::offsetSet($key, $value);
    }

    public function add($value): static
    {
        $this->validateItem($value);

        return parent::add($value);
    }

    public function put($key, $value): static
    {
        $this->validateItem($value);

        return parent::put($key, $value);
    }

    public function push(...$values): static
    {
        $this->validateItems($values);

        return parent::push(...$values);
    }

    public function combine($values): static
    {
        $this->validateItems($values);

        return parent::combine($values);
    }

    public function union($items): static
    {
        $this->validateItems($items);

        return parent::union($items);
    }

    public function merge($items): static
    {
        $this->validateItems($items);

        return parent::merge($items);
    }

    public function mergeRecursive($items): static
    {
        $this->validateItems($items);

        return parent::mergeRecursive($items);
    }

    public function zip($items): static
    {
        $this->validateItems($items);

        return parent::zip($items);
    }

    public function replace($items): static
    {
        $this->validateItems($items);

        return parent::replace($items);
    }

    public function replaceRecursive($items): static
    {
        $this->validateItems($items);

        return parent::replaceRecursive($items);
    }

    public function pluck($value, $key = null)
    {
        return collect($this->items)->pluck($value, $key);
    }

    public function map(callable $callback): static
    {
        return new static(array_map($callback, $this->items));
    }

    public function filter(?callable $callback = null): static
    {
        return new static(array_filter($this->items, $callback));
    }

    public function each(callable $callback): static
    {
        foreach ($this->items as $key => $item) {
            $callback($item, $key);
        }

        return $this;
    }

    public function first(?callable $callback = null, mixed $default = null)
    {
        return collect($this->items)->first($callback, $default);
    }

    public function last(?callable $callback = null, mixed $default = null)
    {
        return collect($this->items)->last($callback, $default);
    }

    public function toArray(): array
    {
        $this->isConvertingToArray = true;

        $result = array_map(static function ($item) {
            if ($item instanceof Arrayable) {
                return $item->toArray();
            }

            return $item;
        }, $this->items);

        $this->isConvertingToArray = false;

        return $result;
    }

    abstract protected function setExpectedClass(): void;

    protected function validateItems(array|self|Arrayable $items): array|self
    {
        if (is_a($items, static::class)) {
            return $items;
        }

        if (is_a($items, Arrayable::class)) {
            $items = $items->toArray();
        }
        $items = array_filter($items);

        if (empty($items)) {
            return [];
        }

        return array_map([$this, 'validateItem'], $items);
    }

    protected function validateItem($item)
    {
        if ($this->isConvertingToArray && \is_array($item)) {
            return $item;
        }

        if (!is_a($item, $this->expectedClass)) {
            throw new \InvalidArgumentException(
                "Each item must be an instance of {$this->expectedClass}."
            );
        }

        return $item;
    }
}
