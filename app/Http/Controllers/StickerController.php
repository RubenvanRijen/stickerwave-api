<?php

namespace App\Http\Controllers;

use App\Interfaces\StickerInterface;
use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use App\Models\Sticker;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StickerController extends GenericController implements StickerInterface
{

    /**
     * The Eloquent model associated with this controller.
     *
     * @var Model
     */
    protected $model = Sticker::class;


    /**
     * Return all the data of the stickers with every info
     * @return JsonResponse
     */
    public function indexDetails(): JsonResponse
    {
        $stickers = $this->model::with('image')->with('categories')->paginate(10);
        return response()->json(['data' => $stickers], 200);
    }

    /**
     * Retrieve a specific sticker with all the data.
     *
     * @param mixed $id
     * @return JsonResponse
     */
    public function showDetails(mixed $id): JsonResponse
    {
        $sticker = $this->model::with('image')->with('categories')->find($id);

        if (!$sticker) {
            // Return a JSON response with a 404 (Not Found) status if the item is not found.
            return response()->json(['message' => 'Item not found'], 404);
        }

        // Return a JSON response with the found item.
        return response()->json($sticker, 200);
    }

    /**
     * Get validation rules for the specific model.
     *
     * @return array
     */
    protected function getValidationRulesCreate(): array
    {
        // Define the validation rules for the specific model here.
        return [
            'title' => 'required|max:255|min:3',
            'description' => 'required|max:255|min:10',
            'price' => 'required|numeric|min:0.01',
        ];
    }

    /**
     * Get validation rules for the specific model for updating.
     *
     * @return array
     */
    protected function getValidationRulesUpdate(mixed $id): array
    {
        // Define the validation rules for the specific model here when updating.
        return [
            'title' => 'required|max:255|min:3',
            'description' => 'required|max:255|min:10',
            'price' => 'required|numeric|min:0.01',
        ];
    }

    /**
     * get the stickers with a certain categoryId.
     *
     * @param integer $categoryId
     * @return JsonResponse
     */
    public function getStickersByCategory(int $categoryId): JsonResponse
    {
        $stickers = Sticker::whereHas('categories', function ($query) use ($categoryId) {
            $query->where('id', $categoryId);
        })->paginate(10);

        return response()->json(['data' => $stickers], 200);
    }


    /**
     * Attach a category to a sticker.
     *
     * @param Request $request
     * @param int $stickerId
     * @return JsonResponse
     */
    public function attachCategoryToSticker(Request $request, int $stickerId): JsonResponse
    {
        $sticker = Sticker::find($stickerId);

        if (!$sticker) {
            return response()->json(['error' => 'Sticker not found'], 404);
        }

        $categories = $request->input('category_ids', []);

        foreach ($categories as $categoryId) {
            $category = Category::find($categoryId);

            if (!$category) {
                return response()->json(['error' => "Category with ID $categoryId not found"], 404);
            }

            $sticker->categories()->syncWithoutDetaching($categoryId);
        }

        return response()->json(['data' => $sticker], 200);
    }


    /**
     * detach a category from a sticker.
     *
     * @param Request $request
     * @param int $stickerId
     * @return JsonResponse
     */
    public function detachCategoryToSticker(Request $request, int $stickerId): JsonResponse
    {
        $sticker = Sticker::find($stickerId);

        if (!$sticker) {
            return response()->json(['error' => 'Sticker not found'], 404);
        }

        $categories = $request->input('category_ids', []);

        foreach ($categories as $categoryId) {
            $category = Category::find($categoryId);

            if (!$category) {
                return response()->json(['error' => "Category with ID $categoryId not found"], 404);
            }

            $sticker->categories()->detach($categoryId);
        }

        return response()->json(['data' => $sticker], 200);
    }
}
