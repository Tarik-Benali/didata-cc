<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Dashboard;
use App\Models\Widget;
use Illuminate\Http\Request;
use App\Widgets\WidgetFactory;
use App\Widgets\WidgetValidator;
use App\Exceptions\InvalidWidgetConfigException;

class WidgetController extends Controller
{
    public function store(Request $request, Dashboard $dashboard)
    {
        $data = $request->validate([
            'type' => 'required|string',
            'config' => 'required|array',
            'position' => 'nullable|integer',
        ]);

        try {
            WidgetValidator::validate($data['type'], $request);
        } catch (InvalidWidgetConfigException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        $data['position'] = $data['position'] ?? (($dashboard->widgets()->max('position') ?? 0) + 1);

        $widgetInstance = WidgetFactory::make($data['type'], $data['config']);
        $widgetData = $widgetInstance->render();

        $widget = $dashboard->widgets()->create([
            'type' => $data['type'],
            'config' => $widgetData,
            'position' => $data['position'],
        ]);

        app(\App\Services\DashboardService::class)->storeVersion($dashboard, 'update');

        return response()->json($widget, 201);
    }

    public function update(Request $request, Widget $widget)
    {
        $data = $request->validate([
            'position' => 'nullable|integer',
            'config' => 'sometimes|array',
        ]);

        if (isset($data['config'])) {
            try {
                WidgetValidator::validate($widget->type, $request);
            } catch (InvalidWidgetConfigException $e) {
                return response()->json(['message' => $e->getMessage()], 422);
            }

            $widget->config = array_merge($widget->config ?? [], $data['config']);
        }

        if (isset($data['position'])) {
            $widget->position = $data['position'];
        }

        $widgetInstance = WidgetFactory::make($widget->type, $data['config']);
        $widget->config = $widgetInstance->render();

        $widget->save();

        app(\App\Services\DashboardService::class)->storeVersion($widget->dashboard, 'update');

        return $widget->fresh();
    }

    public function destroy(Widget $widget)
    {
        $dashboard = $widget->dashboard;
        $widget->delete();

        app(\App\Services\DashboardService::class)->storeVersion($dashboard, 'update');

        return response()->noContent();
    }
}