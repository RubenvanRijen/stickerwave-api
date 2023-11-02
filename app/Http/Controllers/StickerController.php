<?php

namespace App\Http\Controllers;

use App\Interfaces\StickerInterface;
use Illuminate\Database\Eloquent\Model;
use App\Models\Sticker;
use Illuminate\Http\JsonResponse;

class StickerController extends GenericController implements StickerInterface
{

    /**
     * The Eloquent model associated with this controller.
     *
     * @var Model
     */
    protected $model = Sticker::class;

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
        $stickers = $this->model->find($categoryId)->stickers;
        return response()->json($stickers);
    }
}
