<?php

namespace App\Services;

use App\Models\Wallet;
use App\Services\TransactionService;

class WalletService
{
    /** @var TransactionService $transactionService */
    protected $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function makeDeposit(Wallet $wallet, float $value)
    {
        $wallet->balance += $value;

        $this->transactionService
             ->saveDepositTransaction(
                $wallet->user->id,
                $value
            );

        return ($wallet->save());
    }

    public function makeWithdraw(Wallet $wallet, float $value)
    {
        if ($wallet->balance < $value) {
            return false;
        }

        $this->transactionService
             ->saveWithdrawTransaction(
                $wallet->user->id,
                $value
            );

        $wallet->balance -= $value;
        return ($wallet->save());
    }
}
