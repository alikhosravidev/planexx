<?php

declare(strict_types=1);

namespace App\Services\Registry;

use App\Contracts\Registry\RegistryBuilderInterface;
use App\Contracts\Registry\RegistryItemInterface;

abstract class BaseRegistryBuilder implements RegistryBuilderInterface
{
    protected array $items = [];

    public function add(RegistryItemInterface $item): static
    {
        $this->items[] = $item;

        return $this;
    }

    public function getItems(): array
    {
        return $this->items;
    }
}
