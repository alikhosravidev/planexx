<?php

declare(strict_types=1);

namespace App\Query\Contracts;

interface DataTransferObject
{
    public function toArray(): array;
}
