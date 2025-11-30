<?php

declare(strict_types=1);

namespace App\Services\Distribution;

use App\Services\Registry\BaseRegistryItem;

class DistributionItem extends BaseRegistryItem
{
    protected string|int|null $value = null;
    protected int $percent           = 0;
    protected ?string $color         = null;

    protected function getDefaultType(): string
    {
        return 'distribution-segment';
    }

    protected function getIdPrefix(): string
    {
        return 'dist-';
    }

    public function value(string|int $value): static
    {
        $this->value = $value;

        return $this;
    }

    public function percent(int $percent): static
    {
        $this->percent = $percent;

        return $this;
    }

    public function color(string $color): static
    {
        $this->color = $color;

        return $this;
    }

    public function toArray(): array
    {
        return array_merge($this->getBaseArray(), [
            'label'   => $this->getTitle(),
            'value'   => $this->value,
            'percent' => $this->percent,
            'color'   => $this->color,
        ]);
    }
}
