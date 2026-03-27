<?php

namespace Modules\StockMovement\DTOs;

class CreateStockMovementDTO
{
    public function __construct(
        public int $product_id,
        public int $warehouse_id,
        public string $type,
        public float $quantity,
        public ?string $reference_type = null,
        public ?int $reference_id = null,
        public ?string $note = null
    ) {
    }
}
