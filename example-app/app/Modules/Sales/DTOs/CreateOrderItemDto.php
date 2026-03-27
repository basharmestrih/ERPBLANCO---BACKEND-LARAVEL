<?php


namespace Modules\Sales\DTOs;


class CreateOrderItemDTO
{
    public function __construct(
        public int $product_id,
        public int $quantity
    ) {
    }
}