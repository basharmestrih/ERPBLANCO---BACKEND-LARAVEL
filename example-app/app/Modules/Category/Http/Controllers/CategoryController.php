<?php

namespace Modules\Category\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Category\DTOs\CreateCategoryDTO;
use Modules\Category\Services\CategoryService;

class CategoryController extends Controller
{
    public function index(CategoryService $categoryService)
    {
        return response()->json($categoryService->getAll());
    }

    public function store(Request $request, CategoryService $categoryService)
    {
        $payload = $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        $dto = new CreateCategoryDTO(
            name: $payload['name'],
            parent_id: $payload['parent_id'] ?? null,
        );

        $category = $categoryService->create($dto);

        return response()->json([
            'message' => 'Category created successfully',
            'category' => $category,
        ], 201);
    }
}
