<?php

declare(strict_types=1);

namespace App\Services\Stats;

use App\Services\Registry\BaseRegistryBuilder;

class StatBuilder extends BaseRegistryBuilder
{
    public function stat(string $title, ?string $id = null): StatItem
    {
        $item          = StatItem::make($title, $id);
        $this->items[] = $item;

        return $item;
    }

}
