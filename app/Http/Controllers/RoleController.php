<?php

namespace App\Http\Controllers;

use App\Interfaces\RolesInterface;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\JsonResponse;
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


    /**
     * attach a role to a user.
     *
     * @param Request $request
     * @param integer $roleId
     * @param integer $userId
     * @return void
     */
    public function attachRoleToUser(Request $request, int $roleId, int $userId): JsonResponse
    {
        $role = Role::find($roleId);

        if (!$role) {
            return response()->json(['error' => 'Role not found'], 404);
        }

        $user = User::find($userId);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $user->roles()->syncWithoutDetaching($role);

        return response()->json(['data' => $user], 200);
    }

    /**
     * detach a role of a user.
     *
     * @param Request $request
     * @param integer $roleId
     * @param integer $userId
     * @return void
     */
    public function detachRoleOfUser(Request $request, int $roleId, int $userId): JsonResponse
    {
        $role = Role::find($roleId);

        if (!$role) {
            return response()->json(['error' => 'Role not found'], 404);
        }

        $user = User::find($userId);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $user->roles()->detach($role);

        return response()->json(['data' => $user], 200);
    }
}
