<?php

namespace Modules\Warehouse\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Warehouse\DTOs\CreateWarehouseDTO;
use Modules\Warehouse\Services\WarehouseService;

class WarehouseController extends Controller
{
    public function index(WarehouseService $warehouseService)
    {
        return response()->json($warehouseService->getAll());
    }

    public function store(Request $request, WarehouseService $warehouseService)
    {
        $payload = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
        ]);

        $dto = new CreateWarehouseDTO(
            name: $payload['name'],
            location: $payload['location'],
        );

        $warehouse = $warehouseService->create($dto);

        return response()->json([
            'message' => 'Warehouse created successfully',
            'warehouse' => $warehouse,
        ], 201);
    }
}
