<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Http\Request;
use App\Widgets\WidgetValidator;
use App\Exceptions\InvalidWidgetConfigException;

class WidgetValidatorTest extends TestCase
{
    public function test_valid_link_config()
    {
        $request = new Request(['config' => ['url' => 'https://x.com', 'label' => 'Test']]);
        WidgetValidator::validate('link', $request);
        $this->assertTrue(true); // si aucune exception -> ok
    }

    public function test_invalid_link_config_fails()
    {
        $this->expectException(\Illuminate\Validation\ValidationException::class);
        $request = new Request(['config' => ['label' => 'Missing URL']]);
        WidgetValidator::validate('link', $request);
    }

    public function test_valid_text_config()
    {
        $request = new Request(['config' => ['text' => 'Hello']]);
        WidgetValidator::validate('text', $request);
        $this->assertTrue(true);
    }

    public function test_invalid_widget_type_exception()
    {
        $this->expectException(InvalidWidgetConfigException::class);
        $request = new Request(['config' => []]);
        WidgetValidator::validate('unknown', $request);
    }
}
