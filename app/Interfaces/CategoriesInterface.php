<?php

namespace App\Interfaces;

use App\Models\Image;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

interface CategoriesInterface
{
    /**
     * @OA\Get(
     *     path="/categories",
     *     operationId="getCategories",
     *     tags={"Categories"},
     *     summary="Get a list of categories",
     *     @OA\Response(
     *         response=200,
     *         description="List of categories",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="title", type="string")
     *             )
     *         )
     *     ),
     * )
     */
    public function index(): JsonResponse;

    /**
     * @OA\Get(
     *     path="/categories/{id}",
     *     operationId="getCategoryById",
     *     tags={"Categories"},
     *     summary="Show the specified category by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the category",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Category found", @OA\JsonContent(
     *         type="object",
     *         @OA\Property(property="id", type="integer"),
     *         @OA\Property(property="title", type="string")
     *     )),
     *     @OA\Response(response=404, description="Category not found"),
     * )
     */
    public function show($id): JsonResponse;

    /**
     * @OA\Post(
     *     path="/categories",
     *     operationId="storeCategory",
     *     tags={"Categories"},
     *     summary="Store a newly created category in the database",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title"},
     *             @OA\Property(property="title", type="string", description="Category title")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Category created successfully"),
     *     @OA\Response(response=400, description="Validation error"),
     * )
     */
    public function store(Request $request): JsonResponse;

    /**
     * @OA\Put(
     *     path="/categories/{id}",
     *     operationId="updateCategory",
     *     tags={"Categories"},
     *     summary="Update the specified category by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the category",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title"},
     *             @OA\Property(property="title", type="string", description="Category title")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Category updated successfully"),
     *     @OA\Response(response=400, description="Validation error"),
     *     @OA\Response(response=404, description="Category not found"),
     * )
     */
    public function update(Request $request, $id): JsonResponse;

    /**
     * @OA\Delete(
     *     path="/categories/{id}",
     *     operationId="deleteCategory",
     *     tags={"Categories"},
     *     summary="Remove the specified category by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the category",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Category deleted successfully"),
     *     @OA\Response(response=404, description="Category not found"),
     * )
     */
    public function destroy($id): JsonResponse;
}
