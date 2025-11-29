<?php

declare(strict_types=1);

namespace App\Services\QuickAccess;

use App\Services\Registry\BaseRegistryBuilder;

class QuickAccessBuilder extends BaseRegistryBuilder
{
    public function item(string $title, ?string $id = null): QuickAccessItem
    {
        $item          = QuickAccessItem::make($title, $id);
        $this->items[] = $item;

        return $item;
    }

}
