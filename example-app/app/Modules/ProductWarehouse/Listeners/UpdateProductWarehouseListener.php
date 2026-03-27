<?php

namespace Modules\ProductWarehouse\Listeners;

use Modules\ProductWarehouse\DTOs\UpdateProductWarehouseDTO;
use Modules\ProductWarehouse\Services\ProductWarehouseService;
use Modules\StockMovement\Events\StockMovementInRecorded;

class UpdateProductWarehouseListener
{
    public function __construct(
        private ProductWarehouseService $productWarehouseService
    ) {
    }

    public function handle(StockMovementInRecorded $event): void
    {
        $movement = $event->stockMovement;

        $dto = new UpdateProductWarehouseDTO(
            product_id: $movement->product_id,
            warehouse_id: $movement->warehouse_id,
            quantity: (int) $movement->quantity
        );

        $this->productWarehouseService->addOrUpdate($dto);
    }
}
