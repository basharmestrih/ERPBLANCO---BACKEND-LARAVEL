<?php

namespace Modules\StockMovement\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\StockMovement\DTOs\CreateStockMovementDTO;
use Modules\StockMovement\Models\StockMovement;
use Modules\StockMovement\Services\StockMovementService;

class StockMovementController extends Controller
{
    public function index(Request $request, StockMovementService $stockMovementService)
    {
        //$this->authorize('index', StockMovement::class);
        $filters = $request->validate([
            'type' => ['nullable', 'string'],
            'date' => ['nullable', 'date'],
            'quantity' => ['nullable', 'numeric', 'min:0.01'],
        ]);

        return response()->json($stockMovementService->getAll($filters));
    }

    public function store(Request $request, StockMovementService $stockMovementService)
    {
        //$this->authorize('store', StockMovement::class);
        $payload = $request->validate([
            'product_id'   => 'required|exists:products,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'type'         => 'required|in:in,out,adjustment',
            'quantity'     => 'required|numeric|min:0.01',
            'reference_type' => 'nullable|string|max:255|required_with:reference_id',
            'reference_id' => 'nullable|integer|min:1|required_with:reference_type',
            'note'         => 'nullable|string',
        ]);

        $dto = new CreateStockMovementDTO(
            product_id: (int) $payload['product_id'],
            warehouse_id: (int) $payload['warehouse_id'],
            type: $payload['type'],
            quantity: (float) $payload['quantity'],
            reference_type: $payload['reference_type'] ?? null,
            reference_id: array_key_exists('reference_id', $payload) ? (int) $payload['reference_id'] : null,
            note: $payload['note'] ?? null
        );

        $movement = $stockMovementService->create($dto, auth()->id());

        return response()->json($movement, 201);
    }
}
