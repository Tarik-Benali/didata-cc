<?php

namespace App\Widgets;

class TextWidget implements WidgetTypeInterface
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
