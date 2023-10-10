<?php

namespace App\Http\Controllers;

use App\Models\Sticker;
use App\Models\Transaction;
use Illuminate\Http\Client\Request;
use Mollie\Laravel\Facades\Mollie;

class PaymentController extends Controller
{
    /**
     * Redirect the user to the Mollie payment page.
     *
     * @param int $stickerId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function initiatePayment($stickerId)
    {
        // Fetch the sticker details, including the price
        $sticker = Sticker::find($stickerId);

        if (!$sticker) {
            // Handle sticker not found error
            return redirect()->back()->with('error', 'Sticker not found');
        }

        // Create a new payment with Mollie
        $payment = Mollie::api()->payments()->create([
            'amount' => [
                'currency' => 'EUR', // Adjust currency as needed
                'value' => number_format($sticker->price, 2), // Format price with two decimal places
            ],
            'description' => 'Purchase of Sticker #' . $sticker->id,
            'redirectUrl' => route('payment.callback'), // Callback URL
        ]);

        // Redirect the user to the Mollie payment page
        return redirect($payment->getCheckoutUrl(), 303);
    }

    /**
     * Handle the callback from Mollie after payment.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleCallback(Request $request)
    {
        // Verify the payment status with Mollie
        $paymentId = $request->input('id');

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

                // Redirect to a success page or display a success message
                return redirect()->route('payment.success')->with('success', 'Payment successful');
            } elseif ($payment->isOpen()) {
                // Payment is still pending
                return redirect()->route('payment.pending')->with('info', 'Payment is pending');
            } else {
                // Payment failed
                return redirect()->route('payment.failure')->with('error', 'Payment failed');
            }
        } catch (\Exception $e) {
            // Handle any exceptions or errors
            return redirect()->route('payment.failure')->with('error', 'Payment failed');
        }
    }
}
