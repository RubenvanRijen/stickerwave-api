<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Interfaces\ImagesInterface;

class ImageController extends Controller implements ImagesInterface
{
    /**
     * Display a listing of images associated with a specific sticker.
     *
     * @param  int  $stickerId
     * @return \Illuminate\Http\JsonResponse
     */
    public function index($stickerId): JsonResponse
    {
        $images = Image::where('sticker_id', $stickerId)->get();
        return response()->json(['data' => $images], 200);
    }

    /**
     * Store a newly created image for a specific sticker in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $stickerId
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, $stickerId): JsonResponse
    {
        // Validate the request data (filename, mime, data)
        $validatedData = $request->validate([
            'filename' => 'required|max:255',
            'mime' => 'required',
            'data' => 'required',
        ]);

        // Create a new image record associated with the specified sticker
        $image = new Image;
        $image->filename = $validatedData['filename'];
        $image->mime = $validatedData['mime'];
        $image->data = $validatedData['data'];
        $image->sticker_id = $stickerId;
        $image->save();

        return response()->json(['message' => 'Image created successfully'], 201);
    }

    /**
     * Update the specified image for a specific sticker in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $stickerId
     * @param  int  $imageId
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $stickerId, $imageId): JsonResponse
    {
        // Validate the request data (filename, mime, data)
        $validatedData = $request->validate([
            'filename' => 'required|max:255',
            'mime' => 'required',
            'data' => 'required',
        ]);

        // Find the image by its ID and ensure it is associated with the specified sticker
        $image = Image::where('id', $imageId)->where('sticker_id', $stickerId)->first();

        if (!$image) {
            return response()->json(['error' => 'Image not found'], 404);
        }

        $image->filename = $validatedData['filename'];
        $image->mime = $validatedData['mime'];
        $image->data = $validatedData['data'];
        $image->save();

        return response()->json(['message' => 'Image updated successfully'], 200);
    }

    /**
     * Remove the specified image for a specific sticker from the database.
     *
     * @param  int  $stickerId
     * @param  int  $imageId
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($stickerId, $imageId): JsonResponse
    {
        // Find the image by its ID and ensure it is associated with the specified sticker
        $image = Image::where('id', $imageId)->where('sticker_id', $stickerId)->first();

        if (!$image) {
            return response()->json(['error' => 'Image not found'], 404);
        }

        $image->delete();

        return response()->json(['message' => 'Image deleted successfully'], 200);
    }
}
