<?php

namespace Modules\StockMovement\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;
use Modules\Product\Models\Product;
use Modules\StockMovement\DTOs\CreateStockMovementDTO;
use Modules\StockMovement\Events\StockMovementInRecorded;
use Modules\StockMovement\Models\StockMovement;

class StockMovementService
{
    public function getAll(array $filters = []): Collection
    {
        return StockMovement::with(['product', 'warehouse', 'user'])
            ->when(! empty($filters['type']), function (Builder $query) use ($filters) {
                $type = strtolower(trim((string) $filters['type']));

                if (in_array($type, ['in', 'out'], true)) {
                    $query->where('type', $type);
                }
            })
            ->when(! empty($filters['date']), function (Builder $query) use ($filters) {
                $query->whereDate('created_at', $filters['date']);
            })
            // find items highter than or equal to the specified quantity
            ->when(! empty($filters['quantity']), function (Builder $query) use ($filters) {
                $query->where('quantity', '>=', (float) $filters['quantity']);
            })
            ->latest()
            ->get();
    }
    public function create(CreateStockMovementDTO $dto): StockMovement
    {
        return DB::transaction(function () use ($dto) {
            $product = Product::lockForUpdate()->findOrFail($dto->product_id);

            if ($dto->type === 'out') {
                if ($product->total_quantity < $dto->quantity) {
                    throw new \Exception("Insufficient stock for {$product->name}");
                }

                $product->decrement('total_quantity', $dto->quantity);
            }

            if ($dto->type === 'in') {
                $product->increment('total_quantity', $dto->quantity);
            }

            if ($dto->type === 'adjustment') {
                $product->increment('total_quantity', $dto->quantity);
            }

            $movement = StockMovement::create([
                'product_id' => $dto->product_id,
                'warehouse_id' => $dto->warehouse_id,
                'type' => $dto->type,
                'quantity' => $dto->quantity,
                'reference_type' => $dto->reference_type,
                'reference_id' => $dto->reference_id,
                'note' => $dto->note,
                'created_by' => auth()->id(),
            ]);

            if (in_array($dto->type, ['in', 'adjustment'], true)) {
                StockMovementInRecorded::dispatch($movement);
            }

            return $movement;
        });
    }

}
