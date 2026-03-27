<?php

namespace Modules\Reporting\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Reporting\Services\DashboardCacheService;

class DashboardCacheController extends Controller
{
    public function show(DashboardCacheService $service): JsonResponse
    {
        $cache = $service->latest();

        return response()->json([
            'data' => $cache?->toArray(),
        ]);
    }

    public function refresh(DashboardCacheService $service): JsonResponse
    {
        $cache = $service->refresh();

        return response()->json([
            'data' => $cache->toArray(),
        ]);
    }
}
