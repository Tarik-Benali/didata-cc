<?php

namespace App\Exceptions;

use Exception;

class InvalidWidgetConfigException extends Exception
{
    public function __construct(string $type, string $message = "")
    {
        parent::__construct($message ?: "Configuration invalide pour le widget de type: $type");
    }
}
