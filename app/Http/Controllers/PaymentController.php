<?php

namespace App\Http\Controllers;

use App\Interfaces\PaymentInterface;
use App\Models\Sticker;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request as HttpRequest;
use Mollie\Laravel\Facades\Mollie;
use Illuminate\Http\Request;

class PaymentController extends Controller implements PaymentInterface
{
    /**
     * create the payment url.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function initiatePayment(Request $request): JsonResponse
    {
        // Fetch sticker IDs from the request
        $stickerIds = $request->input('sticker_ids');

        // Fetch sticker details, including the price, for all sticker IDs
        $stickers = Sticker::find($stickerIds);

        // Validate sticker existence and calculate total price
        if (count($stickers) !== count($stickerIds)) {
            return response()->json(['error' => 'Invalid sticker ID'], 400);
        }

        $totalPrice = 0;
        foreach ($stickers as $sticker) {
            $totalPrice += $sticker->price;
        }

        // Create a new payment with Mollie
        $payment = Mollie::api()->payments()->create([
            'amount' => [
                'currency' => 'EUR',
                'value' => number_format($totalPrice, 2),
            ],
            'description' => 'Purchase of Stickers',
            'redirectUrl' => route('payment.callback'), // Callback URL
            'metadata' => [
                'sticker_ids' => $stickerIds, // Include sticker IDs in metadata
            ],
        ]);

        // Return the Mollie payment page URL
        return response()->json(['payment_url' => $payment->getCheckoutUrl()], 200);
    }
    /**
     * Handle the callback from Mollie after payment.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handleCallback(HttpRequest $request): JsonResponse
    {
        try {
            $paymentId = $request->input('id');
            $payment = Mollie::api()->payments()->get($paymentId);

            if ($payment->isPaid()) {
                $metadata = $payment->metadata ?? [];

                if (isset($metadata->sticker_ids) && is_array($metadata->sticker_ids)) {
                    foreach ($metadata->sticker_ids as $stickerId) {
                        $sticker = Sticker::find($stickerId);

                        if ($sticker) {
                            $transaction = new Transaction();
                            $transaction->user_id = auth()->user()->id;
                            $transaction->sticker_id = $sticker->id;
                            $transaction->amount = $sticker->price;
                            $transaction->status = 'paid';
                            $transaction->save();
                        }
                    }

                    return response()->json(['message' => 'Payment successful'], 200);
                } else {
                    return response()->json(['error' => 'Invalid sticker IDs in metadata'], 400);
                }
            } elseif ($payment->isOpen()) {
                // Payment is still pending
                return response()->json(['message' => 'Payment is pending'], 202);
            } else {
                // Payment failed
                return response()->json(['error' => 'Payment failed'], 400);
            }
        } catch (\Exception $e) {
            // Handle any exceptions or errors
            return response()->json(['error' => 'Payment failed'], 500);
        }
    }
}
