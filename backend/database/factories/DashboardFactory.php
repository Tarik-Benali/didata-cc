<?php

namespace Database\Factories;

use App\Models\Dashboard;
use Illuminate\Database\Eloquent\Factories\Factory;

class DashboardFactory extends Factory
{
    protected $model = Dashboard::class;

    public function definition()
    {
        return [
            'name' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
        ];
    }
}
