<?php

namespace Modules\StockMovement\Listeners;

use Modules\Sales\Events\OrderApproved;
use Modules\StockMovement\DTOs\CreateStockMovementDTO;
use Modules\StockMovement\Services\StockMovementService;

class CreateStockMovementListener
{
    public function __construct(
        private StockMovementService $stockMovementService
    ) {
    }

    public function handle(OrderApproved $event): void
    {
        $order = $event->order->loadMissing('items');

        foreach ($order->items as $item) {
            $dto = new CreateStockMovementDTO(
                product_id: $item->product_id,
                warehouse_id: 1,
                type: 'out',
                quantity: (float) $item->quantity,
                reference_type: 'order',
                reference_id: $order->id
            );

            $this->stockMovementService->create($dto, $event->approvedBy);
        }
    }
}
