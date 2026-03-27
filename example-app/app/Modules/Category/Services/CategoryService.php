<?php

namespace Modules\Category\Services;

use Modules\Category\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Modules\Category\DTOs\CreateCategoryDTO;

class CategoryService
{
    public function getAll(): Collection
    {
        return Category::with('parent')->get();
    }

    public function create(CreateCategoryDTO $dto): Category
    {
        return Category::create([
            'name' => $dto->name,
            'parent_id' => $dto->parent_id,
        ]);
    }
}
