<?php

namespace Modules\Sales\DTOs;


class CreateOrderDTO
{
    /**
     * @param CreateOrderItemDTO[] $items
     */
    public function __construct(
        public int $customer_id,
        public array $items
    ) {
    }
}
