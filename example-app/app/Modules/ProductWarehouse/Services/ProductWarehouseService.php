<?php

namespace Modules\ProductWarehouse\Services;

use Modules\ProductWarehouse\DTOs\UpdateProductWarehouseDTO;
use Modules\ProductWarehouse\Models\ProductWarehouse;

class ProductWarehouseService
{
    public function addOrUpdate(UpdateProductWarehouseDTO $dto)
    {
        $record = ProductWarehouse::firstOrCreate([
            'product_id' => $dto->product_id,
            'warehouse_id' => $dto->warehouse_id,
        ]);

        $record->quantity += $dto->quantity;
        $record->save();

        return $record;
    }

    public function decrease(UpdateProductWarehouseDTO $dto)
    {
        $record = ProductWarehouse::where([
            'product_id' => $dto->product_id,
            'warehouse_id' => $dto->warehouse_id,
        ])->firstOrFail();

        if ($record->quantity < $dto->quantity) {
            throw new \Exception('Not enough stock in this warehouse');
        }

        $record->quantity -= $dto->quantity;
        $record->save();

        return $record;
    }

    public function getByWarehouse(int $warehouseId)
    {
        return ProductWarehouse::with([
            'product.category.parent',
        ])
            ->where('warehouse_id', $warehouseId)
            ->get();
    }
}
