<?php

namespace App\Http\Controllers;

use App\Interfaces\CategoriesInterface;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller implements CategoriesInterface
{
    /**
     * Display a listing of categories.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        $categories = Category::all();
        return response()->json(['data' => $categories], 200);
    }

    /**
     * Show the specified category by ID.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id): JsonResponse
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['error' => 'Category not found'], 404);
        }

        return response()->json(['data' => $category], 200);
    }

    /**
     * Store a newly created category in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        // Validate the request data (title)
        $validatedData = $request->validate([
            'title' => 'required|max:255',
        ]);

        $category = new Category;
        $category->title = $validatedData['title'];
        $category->save();

        return response()->json(['message' => 'Category created successfully'], 201);
    }

    /**
     * Update the specified category by ID.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id): JsonResponse
    {
        // Validate the request data (title)
        $validatedData = $request->validate([
            'title' => 'required|max:255',
        ]);

        $category = Category::find($id);

        if (!$category) {
            return response()->json(['error' => 'Category not found'], 404);
        }

        $category->title = $validatedData['title'];
        $category->save();

        return response()->json(['message' => 'Category updated successfully'], 200);
    }

    /**
     * Remove the specified category by ID.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['error' => 'Category not found'], 404);
        }

        $category->delete();

        return response()->json(['message' => 'Category deleted successfully'], 200);
    }
}
