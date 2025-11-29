<?php

declare(strict_types=1);

namespace App\Services\Menu;

class MenuGroup extends MenuItem
{
    protected string $type      = 'group';
    protected bool $collapsible = true;
    protected bool $collapsed   = false;

    public function collapsible(bool $collapsible = true): static
    {
        $this->collapsible = $collapsible;

        return $this;
    }

    public function collapsed(bool $collapsed = true): static
    {
        $this->collapsed = $collapsed;

        return $this;
    }

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'collapsible' => $this->collapsible,
            'collapsed'   => $this->collapsed,
        ]);
    }
}
