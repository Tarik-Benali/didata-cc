<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Dashboard;
use App\Models\DashboardVersion;
use Illuminate\Http\Request;

class VersionController extends Controller
{
    public function index(Dashboard $dashboard)
    {
        return $dashboard->versions()->orderByDesc('version_number')->get();
    }

    public function rollback(Dashboard $dashboard, $versionNumber)
    {
        $version = DashboardVersion::where('dashboard_id', $dashboard->id)
            ->where('version_number', $versionNumber)
            ->firstOrFail();

        app(\App\Services\DashboardService::class)->rollback($dashboard, $version->id);

        return $dashboard->fresh()->load('widgets');
    }
}
