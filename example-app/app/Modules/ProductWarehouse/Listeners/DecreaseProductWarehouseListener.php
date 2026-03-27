<?php

namespace Modules\ProductWarehouse\Listeners;

use Modules\ProductWarehouse\DTOs\UpdateProductWarehouseDTO;
use Modules\ProductWarehouse\Services\ProductWarehouseService;
use Modules\Sales\Events\OrderApproved;

class DecreaseProductWarehouseListener
{
    public function __construct(
        private ProductWarehouseService $productWarehouseService
    ) {
    }

    public function handle(OrderApproved $event): void
    {
        $order = $event->order->loadMissing('items');
        $warehouseId = 1;

        foreach ($order->items as $item) {
            $dto = new UpdateProductWarehouseDTO(
                product_id: $item->product_id,
                warehouse_id: $warehouseId,
                quantity: (int) $item->quantity
            );

            $this->productWarehouseService->decrease($dto);
        }
    }
}
