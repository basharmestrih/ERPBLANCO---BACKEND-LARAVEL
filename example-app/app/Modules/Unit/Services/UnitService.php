<?php

namespace Modules\Unit\Services;

use App\Models\Unit;
use Illuminate\Database\Eloquent\Collection;
use Modules\Unit\DTOs\CreateUnitDTO;

class UnitService
{
    public function getAll(): Collection
    {
        return Unit::all();
    }

    public function create(CreateUnitDTO $dto): Unit
    {
        return Unit::create([
            'name' => $dto->name,
            'symbol' => $dto->symbol,
        ]);
    }
}
