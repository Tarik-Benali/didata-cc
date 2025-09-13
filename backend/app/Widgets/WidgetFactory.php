<?php

namespace App\Widgets;

class WidgetFactory
{
    public static function make(string $type, array $config): WidgetTypeInterface
    {
        $type = strtolower($type);
        
        return match($type) {
            'link' => new LinkWidget($config),
            'text' => new TextWidget($config),
            default => throw new \Exception("Type de widget inconnu: $type"),
        };
    }
}
