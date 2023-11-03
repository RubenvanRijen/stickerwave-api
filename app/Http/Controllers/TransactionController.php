<?php

namespace App\Http\Controllers;

use App\Interfaces\TransactionInterface;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class TransactionController extends Controller implements TransactionInterface
{
    /**
     * Display a listing of transactions.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        // Retrieve a paginated list of transactions
        $transactions = Transaction::paginate(10); //pagination limit.

        return response()->json(['data' => $transactions], 200);
    }

    /**
     * Show the specified transaction by ID.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id): JsonResponse
    {
        // Retrieve the transaction by ID
        $transaction = Transaction::find($id);
        $loggedInUserId = Auth::id();
        $user = auth()->user();

        if (!$transaction) {
            return response()->json(['error' => 'Transaction not found'], 404);
        }

        if ($transaction->user_id != $loggedInUserId && $user->roles != 'admin') {
            return response()->json(['error' => 'Unauthorized request'], 403);
        }


        return response()->json(['data' => $transaction], 200);
    }

    /**
     * Retrieve transactions by a specific user.
     *
     * @param  int  $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserTransactions(): JsonResponse
    {
        // Check if the logged-in user matches the requested user
        $loggedInUserId = Auth::id();

        // Retrieve the user's transactions
        $transactions = Transaction::where('user_id', $loggedInUserId)->paginate(10);

        return response()->json(['data' => $transactions], 200);
    }
}
