<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Dashboard;
use Illuminate\Foundation\Testing\RefreshDatabase;


class DashboardApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_dashboards()
    {
        Dashboard::factory()->create(['name' => 'My Dashboard']);
        $response = $this->getJson('/api/v1/dashboards');
        $response->assertStatus(200)->assertJsonFragment(['name' => 'My Dashboard']);
    }

    public function test_can_create_dashboard()
    {
        $response = $this->postJson('/api/v1/dashboards', [
            'name' => 'Created',
            'description' => 'Desc'
        ]);
        $response->assertStatus(201)->assertJsonFragment(['name' => 'Created']);
    }

    public function test_can_update_dashboard()
    {
        $dashboard = Dashboard::factory()->create(['name' => 'Old']);
        $response = $this->putJson("/api/v1/dashboards/{$dashboard->id}", ['name' => 'New']);
        $response->assertStatus(200)->assertJsonFragment(['name' => 'New']);
    }

    public function test_can_delete_dashboard()
    {
        $dashboard = Dashboard::factory()->create();
        $response = $this->deleteJson("/api/v1/dashboards/{$dashboard->id}");
        $response->assertStatus(204);
        $this->assertDatabaseMissing('dashboards', ['id' => $dashboard->id]);
    }
}
