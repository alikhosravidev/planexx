<?php

namespace App\View\Components;

class TableAction
{
    public function __construct(
        public string $icon,
        public string $type = 'button',
        public ?string $tooltip = null,
        public string $variant = 'default',
        public ?string $route = null,
        public ?string $url = null,
        public ?string $click = null,
        public array $clickParams = ['id'],
        public array $params = ['id'],
        public ?string $confirm = null,
        public mixed $when = null,
        public mixed $unless = null,
    ) {}

    public static function view(string $route, ?string $tooltip = 'مشاهده'): self
    {
        return new self(
            icon: 'fa-eye',
            type: 'link',
            tooltip: $tooltip,
            route: $route,
        );
    }

    public static function edit(string $route, ?string $tooltip = 'ویرایش'): self
    {
        return new self(
            icon: 'fa-pen',
            type: 'link',
            tooltip: $tooltip,
            route: $route,
        );
    }

    public static function delete(string $click = 'deleteItem', ?string $tooltip = 'حذف'): self
    {
        return new self(
            icon: 'fa-trash',
            type: 'button',
            tooltip: $tooltip,
            variant: 'danger',
            click: $click,
            confirm: 'آیا از حذف این مورد اطمینان دارید؟',
        );
    }

    public static function make(string $icon): self
    {
        return new self($icon);
    }

    public function link(string $route, array $params = ['id']): self
    {
        $this->type = 'link';
        $this->route = $route;
        $this->params = $params;
        return $this;
    }

    public function click(string $function, array $params = ['id']): self
    {
        $this->type = 'button';
        $this->click = $function;
        $this->clickParams = $params;
        return $this;
    }

    public function variant(string $variant): self
    {
        $this->variant = $variant;
        return $this;
    }

    public function tooltip(string $tooltip): self
    {
        $this->tooltip = $tooltip;
        return $this;
    }

    public function confirm(string $message): self
    {
        $this->confirm = $message;
        return $this;
    }

    public function when(mixed $condition): self
    {
        $this->when = $condition;
        return $this;
    }

    public function unless(mixed $condition): self
    {
        $this->unless = $condition;
        return $this;
    }

    public function toArray(): array
    {
        return [
            'icon' => $this->icon,
            'type' => $this->type,
            'tooltip' => $this->tooltip,
            'variant' => $this->variant,
            'route' => $this->route,
            'url' => $this->url,
            'click' => $this->click,
            'click_params' => $this->clickParams,
            'params' => $this->params,
            'confirm' => $this->confirm,
            'when' => $this->when,
            'unless' => $this->unless,
        ];
    }
}
