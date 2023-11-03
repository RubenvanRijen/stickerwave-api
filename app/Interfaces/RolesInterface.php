<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Roles",
 *     description="Endpoints for Roles"
 * )
 */
interface RolesInterface
{

    /**
     * @OA\Get(
     *     path="/api/roles",
     *     summary="Get a list of all roles",
     *     tags={"Roles"},
     *     security={{"jwt_token":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of roles",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", format="int64", description="Role ID"),
     *                 @OA\Property(property="title", type="string", description="Role title"),
     *                 @OA\Property(property="description", type="string", description="Role description"),
     *             )
     *         ),
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *     ),
     * )
     */
    public function index(): JsonResponse;


    /**
     * @OA\Post(
     *     path="/api/roles",
     *     summary="Create a new role",
     *     tags={"Roles"},
     *     security={{"jwt_token":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Role data",
     *         @OA\JsonContent(
     *             @OA\Property(property="title", type="string", description="Role title"),
     *             @OA\Property(property="description", type="string", description="Role description"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Role created successfully",
     *          @OA\JsonContent(
     *              type="object",
     *                 @OA\Property(property="id", type="integer", format="int64", description="Role ID"),
     *                 @OA\Property(property="title", type="string", description="Role title"),
     *                 @OA\Property(property="description", type="string", description="Role description"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
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
     *     path="/api/roles/{id}",
     *     summary="Get a specific role by ID",
     *     tags={"Roles"},
     *     security={{"jwt_token":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Role ID",
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Role details",
     *          @OA\JsonContent(
     *              type="object",
     *                 @OA\Property(property="id", type="integer", format="int64", description="Role ID"),
     *                 @OA\Property(property="title", type="string", description="Role title"),
     *                 @OA\Property(property="description", type="string", description="Role description"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Role not found",
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="No permissions",
     *     ),
     * )
     */
    public function show(mixed $id): JsonResponse;

    /**
     * @OA\Put(
     *     path="/api/roles/{id}",
     *     summary="Update an existing role by ID",
     *     tags={"Roles"},
     *     security={{"jwt_token":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Role ID",
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Updated role data",
     *         @OA\JsonContent(
     *             @OA\Property(property="title", type="string", description="Role title"),
     *             @OA\Property(property="description", type="string", description="Role description"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Role updated successfully",
     *          @OA\JsonContent(
     *              type="object",
     *                 @OA\Property(property="id", type="integer", format="int64", description="Role ID"),
     *                 @OA\Property(property="title", type="string", description="Role title"),
     *                 @OA\Property(property="description", type="string", description="Role description"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Role not found",
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
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
     *     path="/api/roles/{id}",
     *     summary="Delete an existing role by ID",
     *     tags={"Roles"},
     *     security={{"jwt_token":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Role ID",
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Role deleted successfully",
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Role not found",
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="No permissions",
     *     ),
     * )
     */
    public function destroy(mixed $id): JsonResponse;

    /**
     * @OA\Post(
     *     path="/api/roles/{roleId}/user/attach/{userId}",
     *     summary="Attach a role to a user",
     *     description="Associates a specific role with a user.",
     *     tags={"Roles"},
     *     @OA\Parameter(
     *         name="roleId",
     *         in="path",
     *         description="ID of the role to attach",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             example="123"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="userId",
     *         in="path",
     *         description="ID of the user to whom the role will be attached",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             example="456"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Role attached successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 description="User data after attachment",
     *                 @OA\Property(
     *                     property="id",
     *                     type="integer",
     *                     description="User ID"
     *                 ),
     *                 @OA\Property(
     *                     property="name",
     *                     type="string",
     *                     description="User's name"
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     type="string",
     *                     description="User's email"
     *                 ),
     *                 @OA\Property(
     *                     property="email_verified_at",
     *                     type="string",
     *                     format="date-time",
     *                     description="Timestamp of email verification"
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Role or user not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="error",
     *                 type="string",
     *                 description="Error message"
     *             )
     *         )
     *     )
     * )
     */
    public function attachRoleToUser(Request $request, int $roleId, int $userId): JsonResponse;

    /**
     * @OA\Delete(
     *     path="/api/roles/{roleId}/user/detach/{userId}",
     *     summary="Detach a role from a user",
     *     description="Removes a specific role from a user.",
     *     tags={"Roles"},
     *     @OA\Parameter(
     *         name="roleId",
     *         in="path",
     *         description="ID of the role to detach",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             example="123"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="userId",
     *         in="path",
     *         description="ID of the user from whom the role will be detached",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             example="456"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Role detached successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 description="User data after detachment",
     *                 @OA\Property(
     *                     property="id",
     *                     type="integer",
     *                     description="User ID"
     *                 ),
     *                 @OA\Property(
     *                     property="name",
     *                     type="string",
     *                     description="User's name"
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     type="string",
     *                     description="User's email"
     *                 ),
     *                 @OA\Property(
     *                     property="email_verified_at",
     *                     type="string",
     *                     format="date-time",
     *                     description="Timestamp of email verification"
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Role or user not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="error",
     *                 type="string",
     *                 description="Error message"
     *             )
     *         )
     *     )
     * )
     */
    public function detachRoleOfUser(Request $request, int $roleId, int $userId): JsonResponse;
}
