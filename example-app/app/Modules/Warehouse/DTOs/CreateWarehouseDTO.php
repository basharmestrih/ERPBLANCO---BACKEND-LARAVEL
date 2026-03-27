<?php

namespace Modules\Warehouse\DTOs;

class CreateWarehouseDTO
{
    public function __construct(
        public string $name,
        public string $location
    ) {
    }
}
