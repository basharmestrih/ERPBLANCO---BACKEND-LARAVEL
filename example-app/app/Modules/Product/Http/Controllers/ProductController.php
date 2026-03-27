<?php

namespace Modules\Product\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Product\DTOs\CreateProductDTO;
use Modules\Product\DTOs\UpdateProductDTO;
use Modules\Product\Models\Product;
use Modules\Product\Services\ProductService;

class ProductController extends Controller
{
    // LIST
    public function index(ProductService $productService)
    {
        //$this->authorize('index', Product::class);

        return response()->json($productService->getAll());
    }

    // CREATE
    public function store(Request $request, ProductService $productService)
    {
        $this->authorize('store', Product::class);
        $payload = $request->validate([
            'name'           => 'required|string|max:255',
            'price'          => 'required|numeric|min:0',
            'total_quantity' => 'required|integer|min:0',
        ]);

        $dto = new CreateProductDTO(
            name: $payload['name'],
            price: (float) $payload['price'],
            total_quantity: (int) $payload['total_quantity']
        );

        $product = $productService->create($dto);

        return response()->json([
            'message' => 'Product created successfully',
            'product' => $product,
        ], 201);
    }

    // UPDATE
    public function update(Request $request, Product $product, ProductService $productService)
    {
        $this->authorize('update', $product);
        $payload = $request->validate([
            'name'           => 'sometimes|string|max:255',
            'price'          => 'sometimes|numeric|min:0',
            'total_quantity' => 'sometimes|integer|min:0',
        ]);

        $dto = new UpdateProductDTO(
            name: $payload['name'] ?? null,
            price: array_key_exists('price', $payload) ? (float) $payload['price'] : null,
            total_quantity: array_key_exists('total_quantity', $payload) ? (int) $payload['total_quantity'] : null
        );

        $product = $productService->update($product, $dto);

        return response()->json([
            'message' => 'Product updated successfully',
            'product' => $product,
        ]);
    }

    // DELETE
    public function destroy(Product $product, ProductService $productService)
    {
        $this->authorize('destroy', $product);
        $productService->delete($product);

        return response()->json([
            'message' => 'Product deleted successfully',
        ]);
    }
}
