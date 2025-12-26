<?php

declare(strict_types=1);

namespace App\Services\Stats;

use App\Services\Registry\BaseRegistryItem;
use Closure;

class StatItem extends BaseRegistryItem
{
    protected string|int|float|Closure|null $value = null;
    protected ?string $description                 = null;
    protected ?string $icon                        = null;
    protected ?string $color                       = null;
    protected ?string $change                      = null;
    protected ?string $changeType                  = null;
    protected array $payload                       = [];

    protected function getDefaultType(): string
    {
        return 'stat';
    }

    protected function getIdPrefix(): string
    {
        return 'stat-';
    }

    public function value(string|int|float $value): static
    {
        $this->value = $value;

        return $this;
    }

    public function description(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function icon(string $icon): static
    {
        $this->icon = $icon;

        return $this;
    }

    public function color(string $color): static
    {
        $this->color = $color;

        return $this;
    }

    public function payload(array $payload): static
    {
        $this->payload = $payload;

        return $this;
    }

    public function change(string $change, string $changeType = 'neutral'): static
    {
        $this->change     = $change;
        $this->changeType = $changeType;

        return $this;
    }


    public function getValue(): string|int|float|null
    {
        if ($this->value instanceof Closure) {
            return ($this->value)();
        }

        return $this->value;
    }

    public function toArray(): array
    {
        return array_merge($this->getBaseArray(), [
            'value'       => $this->getValue(),
            'description' => $this->description,
            'icon'        => $this->icon,
            'color'       => $this->color,
            'change'      => $this->change,
            'change_type' => $this->changeType,
            'payload'     => $this->payload,
        ]);
    }
}
