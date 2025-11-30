<?php

namespace App\View\Components;

class TableColumn
{
    public function __construct(
        public string $key,
        public string $label,
        public ?string $component = null,
        public array $options = [],
        public ?string $align = 'right',
        public ?string $width = null,
        public bool $sortable = false,
        public ?string $sortKey = null,
        public bool $nowrap = false,
        public ?\Closure $render = null,
        public ?\Closure $when = null,
    ) {}

    public static function make(string $key, string $label): self
    {
        return new self($key, $label);
    }

    public function component(string $component, array $options = []): self
    {
        $this->component = $component;
        $this->options = $options;
        return $this;
    }

    public function align(string $align): self
    {
        $this->align = $align;
        return $this;
    }

    public function width(string $width): self
    {
        $this->width = $width;
        return $this;
    }

    public function sortable(?string $sortKey = null): self
    {
        $this->sortable = true;
        $this->sortKey = $sortKey;
        return $this;
    }

    public function nowrap(): self
    {
        $this->nowrap = true;
        return $this;
    }

    public function render(\Closure $callback): self
    {
        $this->render = $callback;
        return $this;
    }

    public function toArray(): array
    {
        return [
            'key' => $this->key,
            'label' => $this->label,
            'component' => $this->component,
            'options' => $this->options,
            'align' => $this->align,
            'width' => $this->width,
            'sortable' => $this->sortable,
            'sort_key' => $this->sortKey ?? $this->key,
            'nowrap' => $this->nowrap,
            'render' => $this->render,
        ];
    }
}
