<?php

namespace Modules\Product\Services;

use Modules\Product\DTOs\CreateProductDTO;
use Modules\Product\DTOs\UpdateProductDTO;
use Modules\Product\Models\Product;
use Modules\Category\Models\Category;

class ProductService
{
    public function getAll()
    {
        return Product::with(['category', 'unit'])->get();
    }

    public function create(CreateProductDTO $dto): Product
    {
        return Product::create([
            'name' => $dto->name,
            'price' => $dto->price,
            'total_quantity' => $dto->total_quantity,
        ]);
    }

    public function update(Product $product, UpdateProductDTO $dto): Product
    {
        $product->update([
            'name' => $dto->name ?? $product->name,
            'price' => $dto->price ?? $product->price,
            'total_quantity' => $dto->total_quantity ?? $product->total_quantity,
        ]);

        return $product;
    }

    public function delete(Product $product): void
    {
        $product->delete();
    }
}
