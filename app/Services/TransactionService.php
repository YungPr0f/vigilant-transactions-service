<?php

namespace App\Services;

use App\Models\Transaction;

class TransactionService
{
    public function getTransactions($pageSize = 10)
    {
        $transactions = Transaction::paginate($pageSize, ['*'], 'page_number');

        return $transactions->items();
    }

    public function saveTransaction($data)
    {
        $transaction = Transaction::create($data);

        $transaction->verdict = 'ok';

        $transaction = $this->checkAmountTreshold($transaction);

        $transaction = $this->checkRapidVelocity($transaction);

        $transaction->save();

        return $transaction;
    }

    public function checkAmountTreshold($transaction)
    {
        if ($transaction->amount > config('transaction.max_amount')) {
            $transaction->verdict = 'review';
            $transaction->reasons = array_merge($transaction->reasons ?? [], ['High amount']);
        }

        return $transaction;
    }

    public function checkRapidVelocity($transaction) {
        $timeStart = now()->copy()->subMinutes(config('transaction.throttle_mins'));

        $txnCount = Transaction::where([
                        'user_id' => $transaction->user_id,
                        'device_id' => $transaction->device_id,
                        'ip' => $transaction->ip
                    ])->where('created_at', '>=', $timeStart)->count();

        if ($txnCount > config('transaction.throttle_max')) {
            $transaction->verdict = 'review';
            $transaction->reasons = array_merge($transaction->reasons ?? [], ['Rapid velocity']);
        }

        return $transaction;
    }

    public function userFlaggedTransactions($userId, $pageSize = 10)
    {
        $transactions = Transaction::where('user_id', $userId)->where('verdict', '!=', 'ok')->paginate($pageSize, ['*'], 'page_number');

        return $transactions->items();
    }

    public function transactionMetrics()
    {
        $totalTransactionsCount = Transaction::count();
        $totalOkTransactions = Transaction::where('verdict', 'ok')->count();
        $totalReviewTransactions = Transaction::where('verdict', 'review')->count();
        $totalBlockedTransactions = Transaction::where('verdict', 'blocked')->count();
        $transactionsReasonsDistribution = array_count_values(Transaction::pluck('reasons')->collapse()->toArray());
        $transactionsUserDistribution = Transaction::select('user_id')->groupBy('user_id')
                                                    ->selectRaw('count(*) as transactions')->get()->toArray();
        $transactionsToday = Transaction::whereToday('created_at')->count();


        return [
            'transaction_today' => $transactionsToday,
            'total_transactions' => $totalTransactionsCount,
            'total_ok_transactions' => $totalOkTransactions,
            'total_review_transactions' => $totalReviewTransactions,
            'total_blocked_transactions' => $totalBlockedTransactions,
            'reasons_distribution' => $transactionsReasonsDistribution,
            'users_distribution' => $transactionsUserDistribution,
        ];
    }
}