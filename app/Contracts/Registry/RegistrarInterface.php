<?php

declare(strict_types=1);

namespace App\Contracts\Registry;

interface RegistrarInterface
{
    public function register(RegistryManagerInterface $manager): void;
}
