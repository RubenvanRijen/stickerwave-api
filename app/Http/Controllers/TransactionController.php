<?php

namespace App\Http\Controllers;

use App\Interfaces\TransactionInterface;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
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
        $transactions = Transaction::paginate(10); // You can adjust the pagination limit as needed

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

        if (!$transaction) {
            return response()->json(['error' => 'Transaction not found'], 404);
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

    /**
     * Retrieve a psecific transaction by a specific user.
     *
     * @param  int  $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserTransaction($id): JsonResponse
    {
        // Retrieve the transaction by ID
        $transaction = Transaction::find($id);

        // Check if the logged-in user matches the requested user
        $loggedInUserId = Auth::id();

        if ($loggedInUserId != $transaction->user_id) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return response()->json(['data' => $transaction], 200);
    }
}
