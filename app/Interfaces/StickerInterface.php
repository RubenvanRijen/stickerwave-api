<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Stickers",
 *     description="Endpoints for Stickers"
 * )
 */
interface StickerInterface
{

    /**
     * @OA\Get(
     *     path="/api/stickers",
     *     summary="List all stickers",
     *     tags={"Stickers"},
     *     @OA\Response(
     *         response=200,
     *         description="List of stickers",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="title", type="string"),
     *                 @OA\Property(property="description", type="string"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     )
     * )
     */
    public function index(): JsonResponse;


    /**
     * @OA\Post(
     *     path="/api/stickers",
     *     summary="Create a new sticker",
     *     tags={"Stickers"},
     *     security={{"jwt_token":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="title", type="string", example="Sticker Title"),
     *             @OA\Property(property="description", type="string", example="Sticker Description")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Sticker created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example="1"),
     *             @OA\Property(property="title", type="string", example="Sticker Title"),
     *             @OA\Property(property="description", type="string", example="Sticker Description"),
     *             @OA\Property(property="created_at", type="string", format="date-time"),
     *             @OA\Property(property="updated_at", type="string", format="date-time")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object", example={"title": {"The title field is required."}})
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="No permissions",
     *     ),
     * )
     */
    public function store(Request $request): JsonResponse;


    /**
     * @OA\Get(
     *     path="/api/stickers/{id}",
     *     summary="Retrieve a sticker by ID",
     *     tags={"Stickers"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the sticker to retrieve",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Sticker retrieved successfully",
     *     @OA\JsonContent(
     *         type="object",
     *         @OA\Property(property="id", type="integer"),
     *         @OA\Property(property="title", type="string"),
     *         @OA\Property(property="description", type="string"),
     *         @OA\Property(property="created_at", type="string", format="date-time"),
     *         @OA\Property(property="updated_at", type="string", format="date-time")
     *     )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Sticker not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Sticker not found")
     *         )
     *     ),
     * )
     */
    public function show(mixed $id): JsonResponse;

    /**
     * @OA\Put(
     *     path="/api/stickers/{id}",
     *     summary="Update a sticker by ID",
     *     tags={"Stickers"},
     *     security={{"jwt_token":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the sticker to update",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="title", type="string", example="Updated Title"),
     *             @OA\Property(property="description", type="string", example="Updated Description")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Sticker updated successfully",
     *     @OA\JsonContent(
     *         type="object",
     *         @OA\Property(property="id", type="integer"),
     *         @OA\Property(property="title", type="string"),
     *         @OA\Property(property="description", type="string"),
     *         @OA\Property(property="created_at", type="string", format="date-time"),
     *         @OA\Property(property="updated_at", type="string", format="date-time")
     *     )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Sticker not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Sticker not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object", example={"title": {"The title field is required."}})
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="No permissions",
     *     ),
     * )
     */
    public function update(Request $request, mixed $id): JsonResponse;


    /**
     * @OA\Delete(
     *     path="/api/stickers/{id}",
     *     summary="Delete a sticker by ID",
     *     tags={"Stickers"},
     *     security={{"jwt_token":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the sticker to delete",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Sticker deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Sticker not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Sticker not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="No permissions",
     *     ),
     * )
     */
    public function destroy(mixed $id): JsonResponse;
}
