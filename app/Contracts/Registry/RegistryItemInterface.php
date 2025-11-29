<?php

declare(strict_types=1);

namespace App\Contracts\Registry;

use Illuminate\Contracts\Support\Arrayable;

interface RegistryItemInterface extends Arrayable
{
    public function getId(): string;

    public function getTitle(): string;

    public function getOrder(): int;

    public function getPermission(): ?string;

    public function isActive(): bool;

    public function getType(): string;
}
