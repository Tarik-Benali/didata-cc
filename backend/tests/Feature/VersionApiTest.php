<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Dashboard;
use App\Services\DashboardService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class VersionApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_versions()
    {
        $dashboard = Dashboard::factory()->create(['name' => 'Test']);
        app(DashboardService::class)->update($dashboard, ['name' => 'Changed']);

        $response = $this->getJson("/api/v1/dashboards/{$dashboard->id}/versions");
        $response->assertStatus(200)->assertJsonStructure([['id', 'version_number', 'change_type']]);
    }


    public function test_can_rollback_to_version()
    {
        $service = app(DashboardService::class);

        $dashboard = $service->create(['name' => 'Original']);
        $service->update($dashboard, ['name' => 'Changed']);
        $version = $dashboard->versions()->where('version_number', 1)->first();

        $response = $this->postJson("/api/v1/dashboards/{$dashboard->id}/rollback/{$version->version_number}");
        $response->assertStatus(200)
                ->assertJsonFragment(['name' => 'Original']);
    }
}
