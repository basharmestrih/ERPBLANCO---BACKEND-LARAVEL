<?php
namespace Modules\Product\DTOs;


class UpdateProductDTO
{
    public function __construct(
        public ?string $name = null,
        public ?float $price = null,
        public ?int $total_quantity = null
    ) {
    }
}