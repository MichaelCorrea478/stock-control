<?php

namespace App\Services;

use App\Models\Transaction;

class TransactionService
{
    public function saveDepositTransaction(int $userId, float $value)
    {
        Transaction::create([
            'user_id' => $userId,
            'transaction_type_id' => 1,
            'value' => $value
        ]);
    }

    public function saveWithdrawTransaction(int $userId, float $value)
    {
        Transaction::create([
            'user_id' => $userId,
            'transaction_type_id' => 2,
            'value' => $value
        ]);
    }

    public function saveBuyStockTransaction(int $userId, $request)
    {
        Transaction::create([
            'user_id' => $userId,
            'transaction_type_id' => 3,
            'stock_symbol' => $request->stock['stock'],
            'quantity' => $request->quantity,
            'value' => $request->value
        ]);
    }


}
