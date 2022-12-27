<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getUserInformation()
    {
        $user = auth()->user();
        $wallet = $user->wallet;
        $stocks = $user->stocks;

        return response()->json([
            'wallet' => $wallet,
            'stocks' => $stocks
        ]);
    }
}
