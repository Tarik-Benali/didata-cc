<?php

namespace Database\Factories;

use App\Models\Widget;
use App\Models\Dashboard;
use Illuminate\Database\Eloquent\Factories\Factory;

class WidgetFactory extends Factory
{
    protected $model = Widget::class;

    public function definition()
    {
        return [
            'dashboard_id' => Dashboard::factory(),
            'type' => $this->faker->randomElement(['link', 'text']),
            'position' => $this->faker->numberBetween(1, 10),
            'config' => $this->faker->randomElement([
                ['url' => 'https://example.com', 'label' => 'Example'],
                ['text' => 'Hello World'],
            ]),
        ];
    }
}
