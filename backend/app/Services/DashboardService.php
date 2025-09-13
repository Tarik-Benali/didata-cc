<?php
namespace App\Services;

use App\Models\Dashboard;
use App\Models\DashboardVersion;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    public function create(array $data, ?int $userId = null): Dashboard
    {
        return DB::transaction(function() use($data, $userId) {
            $dashboard = Dashboard::create($data);
            $this->storeVersion($dashboard, 'create', $userId);
            return $dashboard;
        });
    }

    public function update(Dashboard $dashboard, array $data, ?int $userId = null): Dashboard
    {
        return DB::transaction(function() use($dashboard, $data, $userId) {
            $dashboard->update($data);
            $this->storeVersion($dashboard, 'update', $userId);
            return $dashboard->fresh();
        });
    }

    public function delete(Dashboard $dashboard, ?int $userId = null): void
    {
        DB::transaction(function() use($dashboard, $userId) {
            $this->storeVersion($dashboard, 'delete', $userId);
            $dashboard->widgets()->delete();
            $dashboard->delete();
        });
    }

    public function storeVersion(Dashboard $dashboard, string $changeType, ?int $userId = null)
    {
        $last = DashboardVersion::where('dashboard_id', $dashboard->id)->max('version_number') ?? 0;

        $snapshot = [
            'dashboard' => $dashboard->toArray(),
            'widgets' => $dashboard->widgets()->get()->toArray(),
        ];

        return DashboardVersion::create([
            'dashboard_id' => $dashboard->id,
            'version_number' => $last + 1,
            'snapshot' => $snapshot,
            'change_type' => $changeType,
            'created_by' => $userId,
        ]);
    }

    public function rollback(Dashboard $dashboard, int $versionId, ?int $userId = null): Dashboard
    {
        return DB::transaction(function() use($dashboard, $versionId, $userId) {
            $version = DashboardVersion::where('dashboard_id', $dashboard->id)->where('id', $versionId)->firstOrFail();
            $snap = $version->snapshot;

            // Restore dashboard fields (exclude id & timestamps)
            $dashData = $snap['dashboard'] ?? [];
            unset($dashData['id'], $dashData['created_at'], $dashData['updated_at']);

            $dashboard->update($dashData);

            // Replace widgets: delete existing, recreate snapshot widgets
            $dashboard->widgets()->delete();
            foreach ($snap['widgets'] ?? [] as $w) {
                $data = $w;
                unset($data['id'], $data['created_at'], $data['updated_at']);
                $dashboard->widgets()->create($data);
            }

            // Store rollback as a new version
            $this->storeVersion($dashboard, 'rollback', $userId);

            return $dashboard->fresh()->load('widgets');
        });
    }
}