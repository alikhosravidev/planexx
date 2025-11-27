<?php

declare(strict_types=1);

namespace App\Contracts;

interface DataTransferObject
{
    public function toArray(): array;
}
