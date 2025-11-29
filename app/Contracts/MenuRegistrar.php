<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Services\Menu\MenuManager;

interface MenuRegistrar
{
    public function register(MenuManager $menu): void;
}
