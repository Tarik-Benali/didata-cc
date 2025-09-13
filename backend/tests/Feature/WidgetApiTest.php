<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Dashboard;
use App\Models\Widget;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WidgetApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_widget()
    {
        $dashboard = Dashboard::factory()->create();
        $response = $this->postJson("/api/v1/dashboards/{$dashboard->id}/widgets", [
            'type' => 'text',
            'config' => ['text' => 'Hello']
        ]);
        $response->assertStatus(201)->assertJsonFragment(['type' => 'text']);
    }

    public function test_invalid_widget_config_returns_error()
    {
        $dashboard = Dashboard::factory()->create();
        $response = $this->postJson("/api/v1/dashboards/{$dashboard->id}/widgets", [
            'type' => 'link',
            'config' => ['label' => 'No URL']
        ]);
        $response->assertStatus(422);
    }

    public function test_can_update_widget()
    {
        $dashboard = Dashboard::factory()->create();
        $widget = $dashboard->widgets()->create(['type' => 'text', 'position' => 1, 'config' => ['text' => 'Old']]);

        $response = $this->putJson("/api/v1/widgets/{$widget->id}", ['config' => ['text' => 'New']]);
        $response->assertStatus(200)->assertJsonFragment(['config' => ['text' => 'New']]);
    }

    public function test_can_delete_widget()
    {
        $dashboard = Dashboard::factory()->create();
        $widget = $dashboard->widgets()->create(['type' => 'text', 'position' => 1, 'config' => ['text' => 'abc']]);

        $response = $this->deleteJson("/api/v1/widgets/{$widget->id}");
        $response->assertStatus(204);
        $this->assertDatabaseMissing('widgets', ['id' => $widget->id]);
    }
}
