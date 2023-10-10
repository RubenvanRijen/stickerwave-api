<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Interfaces\ImagesInterface;
use App\Models\Sticker;

class ImageController extends Controller implements ImagesInterface
{
    /**
     * Display the image associated with a sticker.
     *
     * @param  int  $stickerId
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($stickerId): JsonResponse
    {
        // Retrieve the sticker by its ID
        $sticker = Sticker::find($stickerId);

        if (!$sticker) {
            return response()->json(['error' => 'Sticker not found'], 404);
        }

        // Use the relationship to get the associated image
        $image = $sticker->image;

        if (!$image) {
            return response()->json(['error' => 'Image not found for this sticker'], 404);
        }

        return response()->json(['data' => $image], 200);
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
