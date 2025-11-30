<?php

declare(strict_types=1);

namespace App\Services\Distribution;

use App\Services\Registry\BaseRegistryBuilder;

class DistributionBuilder extends BaseRegistryBuilder
{
    public function segment(string $label, ?string $id = null): DistributionItem
    {
        $item          = DistributionItem::make($label, $id);
        $this->items[] = $item;

        return $item;
    }
}
