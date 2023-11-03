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
     * @OA\Post(
     *     path="/api/payment/sticker",
     *     summary="Initiate payment for stickers",
     *     description="Initiates the payment process for stickers.",
     *     tags={"Payment"},
     *     security={{"jwt_token":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="sticker_ids", type="array", @OA\Items(type="integer")),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Returns the payment URL for the sticker purchase.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="payment_url", type="string", description="URL for the payment process"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid sticker ID",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", description="Error message"),
     *         ),
     *     ),
     * )
     */
    public function initiatePayment(Request $request): JsonResponse;


    /**
     * @OA\Post(
     *     path="/api/payment/callback",
     *     security={{"jwt_token":{}}},
     *     summary="Handle Mollie payment callback",
     *     description="Handles the callback from Mollie after payment processing.",
     *     tags={"Payment"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Mollie payment callback data",
     *         @OA\JsonContent(
     *             type="object",
     *        )
     *   ),
     *  @OA\Response(
     *      response=200,
     *      description="Payment successful",
     *      @OA\JsonContent(
     *          type="object",
     *          @OA\Property(property="message", type="string", description="Success message"),
     *      ),
     * ),
     *  @OA\Response(
     *      response=202,
     *      description="Payment is pending",
     *      @OA\JsonContent(
     *          type="object",
     *          @OA\Property(property="message", type="string", description="Pending message"),
     *      ),
     *  ),
     *  @OA\Response(
     *      response=400,
     *      description="Payment failed",
     *      @OA\JsonContent(
     *          type="object",
     *          @OA\Property(property="error", type="string", description="Error message"),
     *        ),
     *     ),
     *      @OA\Response(
     *          response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *            type="object",
     *            @OA\Property(property="error", type="string", description="Error message"),
     *         ),
     *     ),
     *  )
     */
    public function handleCallback(Request $request): JsonResponse;
}
