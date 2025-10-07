<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\TransactionService;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    protected TransactionService $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService; 
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $transactions = $this->transactionService->getTransactions($request->page_size);

        return response()->json([
            'success' => true,
            'message' => 'Transactions fetched successfully',
            'data' => $transactions,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'id' => 'sometimes|required|integer|unique:transactions,id',
            'user_id' => 'required|integer',
            'amount' => 'required|numeric|decimal:0,2',
            'currency' => 'required|string|max:5',
            'ip' => 'required|ip',
            'device_id' => 'required|integer',
            'timestamp' => 'required|date'
        ], [
            'id.unique' => 'The transaction id already exists'
        ]);

        $transaction = $this->transactionService->saveTransaction($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Transaction saved successfully',
            'data' => $transaction
        ]);
    }

    public function alerts(Request $request, $user_id)
    {
        $request->merge(['user_id' => $user_id]);
        $request->validate([
            'user_id' => 'required|integer',
        ]);

        $transactions = $this->transactionService->userFlaggedTransactions($request->user_id, $request->page_size);

        return response()->json([
            'success' => true,
            'message' => 'Flagged transactions fetched successfully',
            'data' => $transactions
        ]);
    }

    public function metrics()
    {
        $metrics = $this->transactionService->transactionMetrics();

        return response()->json([
            'success' => true,
            'message' => 'Transactions metrics retrieved successfully',
            'data' => $metrics
        ]);
    }

    public function config()
    {
        $config =  [
            'amount_threshold' => config('transaction.max_amount'),
            'throttle_max_transactions' => config('transaction.throttle_max'),
            'throttle_duration_minutes' => config('transaction.throttle_mins')
        ];

        return $config;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
