<?php

namespace App\Interfaces;

use App\Models\Image;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

interface ImagesInterface
{
    /**
     * @OA\Get(
     *     path="/stickers/{stickerId}/images",
     *     operationId="getImages",
     *     tags={"Images"},
     *     summary="Get a list of images associated with a specific sticker",
     *     @OA\Parameter(
     *         name="stickerId",
     *         in="path",
     *         required=true,
     *         description="ID of the sticker",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of images",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="filename", type="string"),
     *                 @OA\Property(property="mime", type="string"),
     *                 @OA\Property(property="data", type="string"),
     *                 @OA\Property(property="sticker_id", type="integer")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=404, description="Image not found")
     * )
     */
    public function index($stickerId): JsonResponse;

    /**
     * @OA\Post(
     *     path="/stickers/{stickerId}/images",
     *     operationId="storeImage",
     *     tags={"Images"},
     *     summary="Store a newly created image for a specific sticker in the database",
     *     @OA\Parameter(
     *         name="stickerId",
     *         in="path",
     *         required=true,
     *         description="ID of the sticker",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"filename", "mime", "data"},
     *             @OA\Property(property="filename", type="string", description="Image filename"),
     *             @OA\Property(property="mime", type="string", description="MIME type of the image"),
     *             @OA\Property(property="data", type="string", description="Image data (base64 encoded)")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Image created successfully"),
     *     @OA\Response(response=400, description="Validation error"),
     * )
     */
    public function store(Request $request, $stickerId): JsonResponse;

    /**
     * @OA\Put(
     *     path="/stickers/{stickerId}/images/{imageId}",
     *     operationId="updateImage",
     *     tags={"Images"},
     *     summary="Update the specified image for a specific sticker in the database",
     *     @OA\Parameter(
     *         name="stickerId",
     *         in="path",
     *         required=true,
     *         description="ID of the sticker",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="imageId",
     *         in="path",
     *         required=true,
     *         description="ID of the image",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"filename", "mime", "data"},
     *             @OA\Property(property="filename", type="string", description="Image filename"),
     *             @OA\Property(property="mime", type="string", description="MIME type of the image"),
     *             @OA\Property(property="data", type="string", description="Image data (base64 encoded)")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Image updated successfully"),
     *     @OA\Response(response=400, description="Validation error"),
     *     @OA\Response(response=404, description="Image not found"),
     * )
     */
    public function update(Request $request, $stickerId, $imageId): JsonResponse;

    /**
     * @OA\Delete(
     *     path="/stickers/{stickerId}/images/{imageId}",
     *     operationId="deleteImage",
     *     tags={"Images"},
     *     summary="Remove the specified image for a specific sticker from the database",
     *     @OA\Parameter(
     *         name="stickerId",
     *         in="path",
     *         required=true,
     *         description="ID of the sticker",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="imageId",
     *         in="path",
     *         required=true,
     *         description="ID of the image",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Image deleted successfully"),
     *     @OA\Response(response=404, description="Image not found"),
     * )
     */
    public function destroy($stickerId, $imageId): JsonResponse;
}
