<?php

namespace App\Widgets;

class LinkWidget implements WidgetTypeInterface
{
    protected array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function render(): array
    {
        return $this->config;
    }
}
