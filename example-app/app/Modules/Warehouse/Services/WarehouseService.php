<?php

namespace Modules\Warehouse\Services;

use Illuminate\Database\Eloquent\Collection;
use Modules\Warehouse\DTOs\CreateWarehouseDTO;
use Modules\Warehouse\Models\Warehouse;

class WarehouseService
{
    public function getAll(): Collection
    {
        return Warehouse::all();
    }

    public function create(CreateWarehouseDTO $dto): Warehouse
    {
        return Warehouse::create([
            'name' => $dto->name,
            'location' => $dto->location,
        ]);
    }
}
