<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Widgets\WidgetFactory;
use App\Widgets\LinkWidget;
use App\Widgets\TextWidget;

class WidgetFactoryTest extends TestCase
{
    public function test_make_link_widget()
    {
        $widget = WidgetFactory::make('link', ['url' => 'https://x.com', 'label' => 'X']);
        $this->assertInstanceOf(LinkWidget::class, $widget);
    }

    public function test_make_text_widget()
    {
        $widget = WidgetFactory::make('text', ['text' => 'Hello']);
        $this->assertInstanceOf(TextWidget::class, $widget);
    }

    public function test_invalid_widget_type_throws_exception()
    {
        $this->expectException(\Exception::class);
        WidgetFactory::make('invalid', []);
    }
}
