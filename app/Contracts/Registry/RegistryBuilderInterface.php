<?php

declare(strict_types=1);

namespace App\Contracts\Registry;

interface RegistryBuilderInterface
{
    public function add(RegistryItemInterface $item): static;

    public function getItems(): array;
}
