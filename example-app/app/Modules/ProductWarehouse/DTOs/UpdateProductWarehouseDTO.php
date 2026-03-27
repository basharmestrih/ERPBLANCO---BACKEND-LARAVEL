<?php
namespace Modules\ProductWarehouse\DTOs;

class UpdateProductWarehouseDTO
{
    public function __construct(
        public int $product_id,
        public int $warehouse_id,
        public int $quantity
    ) {}
}