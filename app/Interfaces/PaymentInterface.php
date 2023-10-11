<?php

namespace App\Interfaces;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Payment",
 *     description="Endpoints for Payment"
 * )
 */
interface PaymentInterface
{
    /**
     * @OA\Get(
     *     path="/api/payment/sticker/{stickerId}",
     *     operationId="initiatePayment",
     *     tags={"Payment"},
     *     security={{"jwt_token":{}}},
     *     summary="Initiate a payment for a sticker",
     *     @OA\Parameter(
     *         name="stickerId",
     *         in="path",
     *         required=true,
     *         description="ID of the sticker to purchase",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Initiate payment successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="payment_url", type="string", example="https://mollie.com/payment-url"),
     *         )
     *     ),
     *     @OA\Response(response=404, description="Sticker not found"),
     *     @OA\Response(response=401, description="No Authorization")
     * )
     */
    public function initiatePayment($stickerId): JsonResponse;


    /**
     * @OA\Get(
     *     path="/api/payment/callback/{sticker_id}",
     *     operationId="handleCallback",
     *     tags={"Payment"},
     *     security={{"jwt_token":{}}},
     *     summary="Handle payment callback from Mollie",
     *     @OA\Parameter(
     *         name="sticker_id",
     *         in="path",
     *         required=true,
     *         description="ID of the sticker associated with the payment",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="id",
     *         in="query",
     *         required=true,
     *         description="ID of the payment from Mollie",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Payment successful",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Payment successful")
     *         )
     *     ),
     *     @OA\Response(
     *         response=202,
     *         description="Payment is pending",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Payment is pending")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Payment failed",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Payment failed")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Payment failed")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Sticker not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Sticker not found")
     *         )
     *     ),
     *     @OA\Response(response=401, description="No Authorization")
     * )
     */
    public function handleCallback(Request $request, mixed $stickerId): JsonResponse;
}
