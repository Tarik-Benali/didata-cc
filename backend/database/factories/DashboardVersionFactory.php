<?php

namespace Database\Factories;

use App\Models\DashboardVersion;
use App\Models\Dashboard;
use Illuminate\Database\Eloquent\Factories\Factory;

class DashboardVersionFactory extends Factory
{
    protected $model = DashboardVersion::class;

    public function definition()
    {
        return [
            'dashboard_id' => Dashboard::factory(),
            'version_number' => $this->faker->numberBetween(1, 10),
            'snapshot' => ['name' => $this->faker->word()],
            'change_type' => $this->faker->randomElement(['create', 'update', 'delete', 'rollback']),
            'created_by' => 1,
        ];
    }
}
