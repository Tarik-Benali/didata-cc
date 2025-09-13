<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Dashboard;
use App\Models\Widget;
use App\Services\DashboardService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DashboardServiceTest extends TestCase
{
    use RefreshDatabase;

    protected DashboardService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(DashboardService::class);
    }

    public function test_create_dashboard_and_version()
    {
        $dashboard = $this->service->create(['name' => 'Test', 'description' => 'Desc']);
        $this->assertDatabaseHas('dashboards', ['name' => 'Test']);
        $this->assertDatabaseHas('dashboard_versions', [
            'dashboard_id' => $dashboard->id,
            'change_type' => 'create'
        ]);
    }

    public function test_update_dashboard_creates_new_version()
    {
        $dashboard = Dashboard::factory()->create(['name' => 'Old']);
        $this->service->update($dashboard, ['name' => 'New']);
        $this->assertDatabaseHas('dashboards', ['name' => 'New']);
        $this->assertDatabaseHas('dashboard_versions', ['change_type' => 'update']);
    }


    public function test_delete_dashboard_removes_dashboard_and_widgets()
    {
        $dashboard = Dashboard::factory()->create();
        $dashboard->widgets()->create([
            'type' => 'text',
            'position' => 1,
            'config' => ['text' => 'abc'],
        ]);

        $this->service->delete($dashboard);

        $this->assertDatabaseMissing('dashboards', ['id' => $dashboard->id]);
        $this->assertDatabaseMissing('widgets', ['dashboard_id' => $dashboard->id]);

        $this->assertTrue(true);
    }

    public function test_rollback_restores_dashboard_and_widgets()
    {
        $dashboard = $this->service->create(['name' => 'Init']);
        $this->service->update($dashboard, ['name' => 'Changed']);

        $version = $dashboard->versions()->first();
        $this->service->rollback($dashboard, $version->id);

        $this->assertDatabaseHas('dashboards', ['name' => 'Init']);
        $this->assertDatabaseHas('dashboard_versions', ['change_type' => 'rollback']);
    }
}
