<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Transactions",
 *     description="Endpoints for Transactions"
 * )
 */
interface TransactionInterface
{
    /**
     * @OA\Get(
     *     path="/api/transactions",
     *     summary="Retrieve a list of transactions",
     *     operationId="getTransactions",
     *     tags={"Transactions"},
     *     security={{"jwt_token":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="user_id", type="integer"),
     *                 @OA\Property(property="sticker_id", type="integer"),
     *                 @OA\Property(property="amount", type="number", format="decimal"),
     *                 @OA\Property(property="status", type="string", enum={"pending", "paid", "failed"}),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time"),
     *             ),
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
     * @OA\Get(
     *     path="/api/transactions/{id}",
     *     summary="Retrieve a specific transaction by ID",
     *     operationId="getTransactionById",
     *     tags={"Transactions"},
     *     security={{"jwt_token":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Transaction ID",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="user_id", type="integer"),
     *             @OA\Property(property="sticker_id", type="integer"),
     *             @OA\Property(property="amount", type="number", format="decimal"),
     *             @OA\Property(property="status", type="string", enum={"pending", "paid", "failed"}),
     *             @OA\Property(property="created_at", type="string", format="date-time"),
     *             @OA\Property(property="updated_at", type="string", format="date-time"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Transaction not found",
     *     ),
     * )
     */
    public function show($id): JsonResponse;

    /**
     * @OA\Get(
     *     path="/api/transactions/user",
     *     summary="Retrieve transactions by a specific user",
     *     operationId="getUserTransactions",
     *     tags={"Transactions"},
     *     security={{"jwt_token":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="user_id", type="integer"),
     *                 @OA\Property(property="sticker_id", type="integer"),
     *                 @OA\Property(property="amount", type="number", format="decimal"),
     *                 @OA\Property(property="status", type="string", enum={"pending", "paid", "failed"}),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time"),
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="No permissions",
     *     ),
     * )
     */
    public function getUserTransactions(): JsonResponse;

    /**
     * @OA\Get(
     *     path="/api/transactions/{id}/user",
     *     summary="Retrieve a specific transaction by a specific user",
     *     operationId="getUserTransaction",
     *     tags={"Transactions"},
     *     security={{"jwt_token":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Transaction ID",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="user_id", type="integer"),
     *             @OA\Property(property="sticker_id", type="integer"),
     *             @OA\Property(property="amount", type="number", format="decimal"),
     *             @OA\Property(property="status", type="string", enum={"pending", "paid", "failed"}),
     *             @OA\Property(property="created_at", type="string", format="date-time"),
     *             @OA\Property(property="updated_at", type="string", format="date-time"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="No permissions",
     *     ),
     * )
     */
    public function getUserTransaction($id): JsonResponse;
}
