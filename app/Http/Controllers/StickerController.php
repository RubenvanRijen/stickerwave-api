<?php

namespace App\Http\Controllers;

use App\Interfaces\StickerInterface;
use Illuminate\Http\Request;
use App\Models\Sticker;

class StickerController extends GenericController
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
    protected function getValidationRules(): array
    {
        // Define the validation rules for the specific model here.
        return [
            'title' => 'required|max:255|min:3',
            'description' => 'required|max:255|min:10',
        ];
    }
}
