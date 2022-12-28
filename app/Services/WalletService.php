<?php

namespace App\Services;

use App\Models\Wallet;

class WalletService
{
    public function makeDeposit(Wallet $wallet, float $value)
    {
        $wallet->balance += $value;
        return ($wallet->save());
    }
}
