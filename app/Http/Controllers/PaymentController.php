<?php

namespace App\Http\Controllers;

use App\Interfaces\PaymentInterface;
use App\Models\Sticker;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request as HttpRequest;
use Mollie\Laravel\Facades\Mollie;

class PaymentController extends Controller implements PaymentInterface
{
    /**
     * Return the Mollie payment page URL.
     *
     * @param int $stickerId
     * @return \Illuminate\Http\JsonResponse
     */
    public function initiatePayment($stickerId): JsonResponse
    {
        // Fetch the sticker details, including the price
        $sticker = Sticker::find($stickerId);

        if (!$sticker) {
            // Handle sticker not found error
            return response()->json(['error' => 'Sticker not found'], 404);
        }

        // Create a new payment with Mollie
        $payment = Mollie::api()->payments()->create([
            'amount' => [
                'currency' => 'EUR', // Adjust currency as needed
                'value' => number_format($sticker->price, 2), // Format price with two decimal places
            ],
            'description' => 'Purchase of Sticker #' . $sticker->id,
            'redirectUrl' => route('payment.callback', ['sticker_id' => $sticker->id]), // Callback URL
        ]);

        // Return the Mollie payment page URL
        return response()->json(['payment_url' => $payment->getCheckoutUrl()], 200);
    }
    /**
     * Handle the callback from Mollie after payment.*
     *@param \Illuminate\Http\Request $request
     *@param int $stickerId
     *@return \Illuminate\Http\JsonResponse
     */
    public function handleCallback(HttpRequest $request, mixed $stickerId): JsonResponse
    {
        // Verify the payment status with Mollie
        $paymentId = $request->input('id');
        $sticker = Sticker::find($stickerId);

        if (!$sticker) {
            // Handle sticker not found error
            return response()->json(['error' => 'Sticker not found'], 404);
        }

        try {
            $payment = Mollie::api()->payments()->get($paymentId);

            if ($payment->isPaid()) {
                // Payment was successful

                // Create a new transaction record
                $transaction = new Transaction();
                $transaction->user_id = auth()->user()->id;
                $transaction->sticker_id = $sticker->id;
                $transaction->amount = $sticker->price;
                $transaction->status = 'paid';
                $transaction->save();

                // Update the sticker availability or other logic here

                // Return a success response
                return response()->json(['message' => 'Payment successful'], 200);
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
