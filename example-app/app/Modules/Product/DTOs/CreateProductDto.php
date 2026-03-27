<?php

namespace Modules\Product\DTOs;

class CreateProductDTO
{
    public function __construct(
        public string $name,
        public float $price,
        public int $total_quantity
    ) {
    }
}


