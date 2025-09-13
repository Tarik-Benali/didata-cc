<?php

namespace App\Widgets;

use Illuminate\Http\Request;
use App\Exceptions\InvalidWidgetConfigException;

class WidgetValidator
{
    public static function validate(string $type, Request $request): void
    {
        switch (strtolower($type)) {
            case 'link':
                $request->validate([
                    'config.url' => 'required|url',
                    'config.label' => 'required|string',
                ]);
                break;

            case 'text':
                $request->validate([
                    'config.text' => 'required|string',
                ]);
                break;

            default:
                throw new InvalidWidgetConfigException($type);
        }
    }
}