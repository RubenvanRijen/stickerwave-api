<?php

namespace App\Http\Controllers;

use App\Interfaces\RolesInterface;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends GenericController implements RolesInterface
{
    /**
     * The Eloquent model associated with this controller.
     *
     * @var Model
     */
    protected $model = Role::class;

    /**
     * Get validation rules for the specific model.
     *
     * @return array
     */
    protected function getValidationRulesCreate(): array
    {
        // Define the validation rules for the specific model here.
        return [
            'name' => 'required|max:255|min:3|unique:roles',
            // 'description' => 'required|max:255|min:10',
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
            'name' => 'required|unique:roles,name,' . $id . '|max:255',
        ];
    }
}
