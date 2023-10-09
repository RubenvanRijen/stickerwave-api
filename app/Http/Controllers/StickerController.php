<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StickerController extends GenericController
{


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
