<?php

namespace Modules\Unit\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Unit\DTOs\CreateUnitDTO;
use Modules\Unit\Services\UnitService;

class UnitController extends Controller
{
    public function index(UnitService $unitService)
    {
        return response()->json($unitService->getAll());
    }

    public function store(Request $request, UnitService $unitService)
    {
        $payload = $request->validate([
            'name' => 'required|string|max:255',
            'symbol' => 'required|string|max:10',
        ]);

        $dto = new CreateUnitDTO(
            name: $payload['name'],
            symbol: $payload['symbol'],
        );

        $unit = $unitService->create($dto);

        return response()->json([
            'message' => 'Unit created successfully',
            'unit' => $unit,
        ], 201);
    }
}
