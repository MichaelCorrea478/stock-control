<?php

namespace App\Repositories;

use App\Models\Wallet;

class WalletRepository
{
    public function increase(Wallet $wallet, float $value)
    {
        $wallet->balance += $value;
        return $wallet->save();
    }

    public function decrease(Wallet $wallet, float $value)
    {
        $wallet->balance -= $value;
        return $wallet->save();
    }
}
