<?php

namespace App\Repositories;

use App\Models\Stock;
use App\Models\User;

class StockRepository
{
    public function saveBoughtStock(User $user, $request)
    {
        $existingStock = Stock::where('user_id', $user->id)
                            ->where('symbol', $request->stock['stock'])
                            ->first();

        if (!empty($existingStock)) {
            $existingStock->quantity += $request->quantity;
            $existingStock->save();
        } else {
            $user->stocks()->create([
                'logo' => $request->stock['logo'],
                'symbol' => $request->stock['stock'],
                'sector' => $request->stock['sector'],
                'quantity' => $request->quantity
            ]);
        }
    }
}
