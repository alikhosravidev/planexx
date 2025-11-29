<?php

declare(strict_types=1);

namespace App\Services\Menu;

use App\Services\Registry\BaseRegistryBuilder;

class MenuBuilder extends BaseRegistryBuilder
{
    public function item(string $title, ?string $id = null): MenuItem
    {
        $item          = MenuItem::make($title, $id);
        $this->items[] = $item;

        return $item;
    }

    public function group(string $title, ?string $id = null): MenuGroup
    {
        $group         = new MenuGroup($title, $id);
        $this->items[] = $group;

        return $group;
    }

    public function divider(): static
    {
        $this->items[] = MenuItem::make('divider', 'divider-' . uniqid('d_', true))
            ->type('divider')
            ->attributes(['type' => 'divider'])
            ->order(999999);

        return $this;
    }

}
