<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Dashboard;
use App\Services\DashboardService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected DashboardService $service;

    public function __construct(DashboardService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return Dashboard::with('widgets')->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'meta' => 'nullable|array',
        ]);

        $dashboard = $this->service->create($data);

        return response()->json($dashboard->load('widgets'), 201);
    }

    public function show(Dashboard $dashboard)
    {
        return $dashboard->load('widgets');
    }

    public function update(Request $request, Dashboard $dashboard)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'meta' => 'nullable|array',
        ]);

        $this->service->update($dashboard, $data);

        return $dashboard->fresh()->load('widgets');
    }

    public function destroy(Dashboard $dashboard)
    {
        $this->service->delete($dashboard);
        return response()->noContent();
    }
}
